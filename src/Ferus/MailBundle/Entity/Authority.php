<?php

namespace Ferus\MailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Authority
 */
class Authority
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $okMessage;

    /**
     * @var array
     */
    private $noMessage;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->okMessage = array('ok', 'oui');
        $this->noMessage = array('non');
    }

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
     * Set email
     *
     * @param string $email
     * @return Authority
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Authority
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set okMessage
     *
     * @param array $okMessage
     * @return Authority
     */
    public function setOkMessage($okMessage)
    {
        $this->okMessage = $okMessage;

        return $this;
    }

    /**
     * Get okMessage
     *
     * @return array 
     */
    public function getOkMessage()
    {
        return $this->okMessage;
    }

    /**
     * Set noMessage
     *
     * @param array $noMessage
     * @return Authority
     */
    public function setNoMessage($noMessage)
    {
        $this->noMessage = $noMessage;

        return $this;
    }

    /**
     * Get noMessage
     *
     * @return array 
     */
    public function getNoMessage()
    {
        return $this->noMessage;
    }
}
