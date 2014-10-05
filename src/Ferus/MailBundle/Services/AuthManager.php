<?php


namespace Ferus\MailBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Ferus\MailBundle\Entity\Auth;
use Ferus\MailBundle\Entity\Response;
use Symfony\Component\Templating\EngineInterface;

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

    /**
     * @var EngineInterface
     */
    protected $templating;

    function __construct($em, $mailer, $imap, $templating)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->imap = $imap;
        $this->templating = $templating;
    }

    public function sendAuth(Auth $auth, $fields)
    {
        $template = $auth->getTemplate();
        $auth->setCustomFields($fields);
        $auth->setStatus('waiting');

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
            ->setBody(
                $this->templating->render('FerusMailBundle:Auth:template_before.txt.twig') .
                $this->convertString($template->getText(), $fields) .
                $this->templating->render('FerusMailBundle:Auth:template_after.txt.twig')
            )
        ;

        $auth->setMessageUid($message->getId());
        $this->mailer->send($message);

        $this->em->persist($auth);
        $this->em->flush();
    }

    public function sendSecondWave(Auth $auth)
    {
        $template = $auth->getTemplate();

        $sendTo = array();
        $sendCC = array();
        foreach($template->getSecondWaveAuth() as $mail)
            $sendTo[$mail->getEmail()] = $mail->getName();
        foreach($template->getSecondWaveCC() as $mail)
            $sendCC[$mail->getEmail()] = $mail->getName();

        $message = \Swift_Message::newInstance()
            ->setSubject($this->convertString($template->getSubject(), $auth->getCustomFields()))
            ->setFrom(array('bde@edu.esiee.fr' => 'BDE ESIEE Paris'))
            ->setTo($sendTo)
            ->setCc($sendCC)
            ->setBody(
                $this->templating->render('FerusMailBundle:Auth:template_before_bis.txt.twig', array('authorities'=>$template->getFirstWaveAuth())) .
                $this->convertString($template->getText(), $auth->getCustomFields()) .
                $this->templating->render('FerusMailBundle:Auth:template_after.txt.twig')
            )
        ;

        $auth->setMessageUid($message->getId());
        $this->mailer->send($message);

        $this->em->persist($auth);
        $this->em->flush();
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
        $sendSecondWave = false;
        if($template->getFirstWaveAuth()->contains($from)){
            $total = 0;
            foreach($responses as $r)
                if($template->getFirstWaveAuth()->contains($r->getFrom()))
                    $total++;

            $auth->setFirstWaveStatus($total * 100 / $template->getFirstWaveAuth()->count());
            if($auth->getFirstWaveStatus() == 100)
                $sendSecondWave = true;
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

            if($sendSecondWave)
                $this->sendSecondWave($auth);
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
        $lastChecked = $this->em->getRepository('FerusMailBundle:Variable')->get('last_checked_mails', null, null, new \DateTime('-7 days'));
        $last = $lastChecked->getDate();//->modify('-1 day');
        $date = new \DateTime( "-7 days" );
        $date = $date > $last ? $date : $last;
        $lastChecked->setDate(new \DateTime());
        $this->em->persist($lastChecked);
        $this->em->flush();
        $date = $date->format("d M Y");
        $uids = $this->imap->search('SINCE "'.$date.'"');
        $messages = array();

        foreach($uids as $uid)
            $messages[] = $this->imap->getMessage($uid);

        $tot = 0;
        foreach($messages as $message){
            try{
                if(!property_exists($message->getHeader(), 'in_reply_to')) continue;

                $auth = $this->em->getRepository('FerusMailBundle:Auth')->findOneBy(array(
                    'messageUid' => str_replace(array('<', '>'), '', $message->getHeader()->in_reply_to)
                ));

                if($auth == null) continue;

                if($this->em->getRepository('FerusMailBundle:Response')->responseExist($message->getHeader()->message_id)) continue;

                $response = new Response();
                $response->setStatus('unknown');
                $response->setAuth($auth);
                $response->setFrom($this->em->getRepository('FerusMailBundle:Authority')->findOneByEmail($message->getHeader()->fromaddress));
                $response->setReceivedAt(new \DateTime($message->getHeader()->date));
                $response->setMessage($message->getBody());
                $response->setMessageUid($message->getHeader()->message_id);

                $this->em->persist($response);
                $this->em->flush();
                $tot++;

                $firstWord = strtolower(preg_replace('#^[^a-zA-Z]*([a-zA-Z]+).+$#s', '$1', $message->getBody()));
                if(in_array($firstWord, $response->getFrom()->getOkMessage())){
                    $this->validateResponse($response);
                }
                else if(in_array($firstWord, $response->getFrom()->getNoMessage())){
                    $this->denyResponse($response);
                }
                else{
                    $auth->setStatus('manual');
                    $this->em->persist($auth);
                    $this->em->flush();
                }
            }
            catch(NoResultException $e){}
        }

        return $tot;
    }
} 