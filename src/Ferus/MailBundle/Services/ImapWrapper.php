<?php


namespace Ferus\MailBundle\Services;


use Ferus\MailBundle\Services\Exception\ConnexionFailedException;

class ImapWrapper
{
    private $mailbox;
    private $port;
    private $username;
    private $password;

    private $mbox;
    private $info;

    function __construct($mailbox, $password, $port, $username)
    {
        $this->mailbox  = $mailbox;
        $this->password = $password;
        $this->port     = $port;
        $this->username = $username;
    }

    public function connect()
    {
        $this->mbox = imap_open('{'.$this->mailbox.':'.$this->port.'/pop3}', $this->username, $this->password);
        if(false === $this->mbox)
            throw new ConnexionFailedException;

        $this->info = imap_check($this->mbox);
    }
} 