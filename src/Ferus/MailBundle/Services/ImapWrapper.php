<?php


namespace Ferus\MailBundle\Services;


use Ferus\MailBundle\Model\Email;
use Ferus\MailBundle\Services\Exception\ConnexionFailedException;
use Ferus\MailBundle\Services\Exception\NoResultException;

class ImapWrapper
{
    private $mailbox;
    private $port;
    private $username;
    private $password;

    private $mbox = null;
    private $info;

    function __construct($mailbox, $password, $port, $username)
    {
        $this->mailbox  = $mailbox;
        $this->password = $password;
        $this->port     = $port;
        $this->username = $username;
    }

    /**
     * Connect to the mailbox
     * @throws Exception\ConnexionFailedException
     */
    public function connect()
    {
        if($this->mbox !== null) return;

        $this->mbox = imap_open('{'.$this->mailbox.':'.$this->port.'/pop3}INBOX', $this->username, $this->password, OP_READONLY);
        if(false === $this->mbox)
            throw new ConnexionFailedException;

        $this->info = imap_check($this->mbox);
    }

    /**
     * Query the mailbox
     * @param $query
     * @return array the list of all messages UIDs
     * @throws Exception\NoResultException when no result are found or query is wrong
     */
    public function search($query)
    {
        $this->connect();

        $result = imap_search($this->mbox, $query, SE_UID);

        if(false === $result)
            throw new NoResultException;

        return $result;
    }

    public function getMessage($uid)
    {
        return new Email(
            imap_headerinfo($this->mbox, imap_msgno($this->mbox, $uid)),
            imap_fetchbody($this->mbox, $uid, 0, FT_UID|FT_PEEK)
        );
    }
} 