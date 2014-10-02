<?php


namespace Ferus\MailBundle\Services;

use Doctrine\ORM\EntityManager;
use Ferus\MailBundle\Entity\Auth;
use Ferus\MailBundle\Entity\Response;

class AuthManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var ImapWrapper
     */
    private $imap;

    function __construct($em, $mailer, $imap)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->imap = $imap;
    }

    public function sendAuth(Auth $auth, $fields)
    {
        $template = $auth->getTemplate();
        $auth->setCustomFields($fields);

        $this->em->persist($auth);
        $this->em->flush();

        $sendTo = array();
        $sendCC = array();
        foreach($template->getFirstWaveAuth() as $mail)
            $sendTo[$mail->getEmail()] = $mail->getName();
        foreach($template->getFirstWaveCC() as $mail)
            $sendCC[$mail->getEmail()] = $mail->getName();

        $message = \Swift_Message::newInstance()
            ->setSubject($this->convertString($template->getSubject(), $fields))
            ->setFrom(array('bde@edu.esiee.fr' => 'BDE ESIEE Paris'))
            ->setTo($sendTo)
            ->setCc($sendCC)
            ->setBody($this->convertString($template->getText(), $fields))
        ;

        $this->mailer->send($message);
    }

    private function convertString($string, $fields)
    {
        $string = preg_replace_callback('#{{(.+)}}#U', function($m) use($fields){
            $slug = preg_replace('#[^a-z0-9_]#', '', strtolower(str_replace(' ', '_', trim($m[1]))));

            return $fields[$slug];
        }, $string);

        return $string;
    }

    public function validateResponse(Response $response)
    {
        $auth = $response->getAuth();
        $template = $auth->getTemplate();
        $from = $response->getFrom();

        $response->setStatus('ok');
        $this->em->persist($response);
        $this->em->flush();

        $responses = $auth->getResponses();
        if($template->getFirstWaveAuth()->contains($from)){
            $total = 0;
            foreach($responses as $r)
                if($template->getFirstWaveAuth()->contains($r->getFrom()))
                    $total++;

            $auth->setFirstWaveStatus($total * 100 / $template->getFirstWaveAuth()->count());
        }
        else if($template->getSecondWaveAuth()->contains($from)){
            $total = 0;
            foreach($responses as $r)
                if($template->getSecondWaveAuth()->contains($r->getFrom()))
                    $total++;

            $auth->setSecondWaveStatus($total * 100 / $template->getSecondWaveAuth()->count());
        }

        if($auth->getStatus() != 'no'){
            $auth->setStatus('waiting');
            foreach($responses as $r)
                if($r->getStatus() == 'unknown'){
                    $auth->setStatus('manual');
                    break;
                }
        }

        $this->em->persist($auth);
        $this->em->flush();
    }

    public function denyResponse(Response $response)
    {
        $auth = $response->getAuth();
        $template = $auth->getTemplate();
        $from = $response->getFrom();

        $response->setStatus('no');

        if($template->getFirstWaveAuth()->contains($from) || $template->getSecondWaveAuth()->contains($from))
            $auth->setStatus('no');

        $this->em->persist($response);
        $this->em->persist($auth);
        $this->em->flush();
    }

    public function fetchMails()
    {
        $date = date ( "d M Y", strToTime ( "-7 days" ) );
        $uids = $this->imap->search('SINCE "'.$date.'"');
        $messages = array();

        foreach($uids as $uid)
            $messages[] = $this->imap->getMessage($uid);

        return $messages;
    }
} 