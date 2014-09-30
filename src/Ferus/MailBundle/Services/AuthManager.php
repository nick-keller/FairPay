<?php


namespace Ferus\MailBundle\Services;


use Ferus\MailBundle\Entity\Auth;

class AuthManager
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    function __construct($mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendAuth(Auth $auth, $fields)
    {
        $template = $auth->getTemplate();

        $message = \Swift_Message::newInstance()
            ->setSubject($this->convertString($template->getSubject(), $fields))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
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
} 