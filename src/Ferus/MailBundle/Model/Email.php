<?php


namespace Ferus\MailBundle\Model;


class Email 
{
    private $header;
    private $body;

    function __construct($header, $body)
    {
        $this->body   = $body;
        $this->header = $header;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * @return mixed
     */
    public function getHeader()
    {
        return $this->header;
    }
} 