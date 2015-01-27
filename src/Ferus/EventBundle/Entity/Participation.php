<?php

namespace Ferus\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Participation
 * @ExclusionPolicy("all")
 */
class Participation
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $studentId;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var array
     * @Expose
     */
    private $fields;

    /**
     * @var array
     * @Expose
     */
    private $options;

    /**
     * @var \Ferus\EventBundle\Entity\Event
     */
    private $event;

    /**
     * @var string
     * @Expose
     */
    private $comments;

    /**
     * @var string
     * @Expose
     */
    private $paymentMethod;

    /**
     * @var string
     */
    private $paymentAmount;

    /**
     * @var string
     * @Expose
     */
    private $depositMethod;

    /**
     * @var string
     */
    private $depositAmount;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \Ferus\UserBundle\Entity\User
     */
    private $representative;

    /**
     * @var boolean
     */
    private $expired;

    public function __construct(Event $event)
    {
        $this->event = $event;
        $this->expired = false;
    }

    public function __toString()
    {
        return $this->lastName . ' ' . $this->firstName;
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
     * Set studentId
     *
     * @param integer $studentId
     * @return Participation
     */
    public function setStudentId($studentId)
    {
        $this->studentId = $studentId;

        return $this;
    }

    /**
     * Get studentId
     *
     * @return integer 
     */
    public function getStudentId()
    {
        return $this->studentId;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Participation
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Participation
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Participation
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
     * Set fields
     *
     * @param array $fields
     * @return Participation
     */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Get fields
     *
     * @return array 
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set options
     *
     * @param array $options
     * @return Participation
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get options
     *
     * @return array 
     */
    public function getOptions()
    {
        return $this->options;
    }


    /**
     * Set event
     *
     * @param \Ferus\EventBundle\Entity\Event $event
     * @return Participation
     */
    public function setEvent(\Ferus\EventBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \Ferus\EventBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }


    /**
     * Set comments
     *
     * @param string $comments
     * @return Participation
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string 
     */
    public function getComments()
    {
        return $this->comments;
    }


    /**
     * Set paymentMethod
     *
     * @param string $paymentMethod
     * @return Participation
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethod
     *
     * @return string 
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set paymentAmount
     *
     * @param string $paymentAmount
     * @return Participation
     */
    public function setPaymentAmount($paymentAmount)
    {
        $this->paymentAmount = $paymentAmount;

        return $this;
    }

    /**
     * Get paymentAmount
     *
     * @return string 
     */
    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }

    /**
     * Set depositMethod
     *
     * @param string $depositMethod
     * @return Participation
     */
    public function setDepositMethod($depositMethod)
    {
        $this->depositMethod = $depositMethod;

        return $this;
    }

    /**
     * Get depositMethod
     *
     * @return string 
     */
    public function getDepositMethod()
    {
        return $this->depositMethod;
    }

    /**
     * Set depositAmount
     *
     * @param string $depositAmount
     * @return Participation
     */
    public function setDepositAmount($depositAmount)
    {
        $this->depositAmount = $depositAmount;

        return $this;
    }

    /**
     * Get depositAmount
     *
     * @return string 
     */
    public function getDepositAmount()
    {
        return $this->depositAmount;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Participation
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set representative
     *
     * @param \Ferus\UserBundle\Entity\User $representative
     * @return Participation
     */
    public function setRepresentative(\Ferus\UserBundle\Entity\User $representative = null)
    {
        $this->representative = $representative;

        return $this;
    }

    /**
     * Get representative
     *
     * @return \Ferus\UserBundle\Entity\User 
     */
    public function getRepresentative()
    {
        return $this->representative;
    }


    /**
     * Set expired
     *
     * @param boolean $expired
     * @return Participation
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;

        return $this;
    }

    /**
     * Get expired
     *
     * @return boolean 
     */
    public function getExpired()
    {
        return $this->expired;
    }
}
