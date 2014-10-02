<?php

namespace Ferus\MailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Response
 */
class Response
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $receivedAt;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \Ferus\MailBundle\Entity\Auth
     */
    private $auth;

    /**
     * @var \Ferus\MailBundle\Entity\Authority
     */
    private $from;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set receivedAt
     *
     * @param \DateTime $receivedAt
     * @return Response
     */
    public function setReceivedAt($receivedAt)
    {
        $this->receivedAt = $receivedAt;

        return $this;
    }

    /**
     * Get receivedAt
     *
     * @return \DateTime 
     */
    public function getReceivedAt()
    {
        return $this->receivedAt;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Response
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * Set auth
     *
     * @param \Ferus\MailBundle\Entity\Auth $auth
     * @return Response
     */
    public function setAuth(\Ferus\MailBundle\Entity\Auth $auth = null)
    {
        $this->auth = $auth;

        return $this;
    }

    /**
     * Get auth
     *
     * @return \Ferus\MailBundle\Entity\Auth 
     */
    public function getAuth()
    {
        return $this->auth;
    }


    /**
     * Set from
     *
     * @param \Ferus\MailBundle\Entity\Authority $from
     * @return Response
     */
    public function setFrom(\Ferus\MailBundle\Entity\Authority $from = null)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get from
     *
     * @return \Ferus\MailBundle\Entity\Authority 
     */
    public function getFrom()
    {
        return $this->from;
    }
    /**
     * @var string
     */
    private $message;


    /**
     * Set message
     *
     * @param string $message
     * @return Response
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }
}
