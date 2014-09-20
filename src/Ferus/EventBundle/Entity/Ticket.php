<?php

namespace Ferus\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 */
class Ticket
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $maxTickets;

    /**
     * @var string
     */
    private $price;

    /**
     * @var string
     */
    private $priceContributor;

    /**
     * @var boolean
     */
    private $forceCheck;

    /**
     * @var Event
     */
    private $event;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $payments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->payments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return$this->name;
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
     * Set name
     *
     * @param string $name
     * @return Ticket
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
     * Set maxTickets
     *
     * @param integer $maxTickets
     * @return Ticket
     */
    public function setMaxTickets($maxTickets)
    {
        $this->maxTickets = $maxTickets;

        return $this;
    }

    /**
     * Get maxTickets
     *
     * @return integer 
     */
    public function getMaxTickets()
    {
        return $this->maxTickets;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set priceContributor
     *
     * @param string $priceContributor
     * @return Ticket
     */
    public function setPriceContributor($priceContributor)
    {
        $this->priceContributor = $priceContributor;

        return $this;
    }

    /**
     * Get priceContributor
     *
     * @return string 
     */
    public function getPriceContributor()
    {
        return $this->priceContributor;
    }

    /**
     * @param boolean $forceCheck
     */
    public function setForceCheck($forceCheck)
    {
        $this->forceCheck = $forceCheck;
    }

    /**
     * @return boolean
     */
    public function getForceCheck()
    {
        return $this->forceCheck;
    }

    /**
     * @param \Ferus\EventBundle\Entity\Event $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return \Ferus\EventBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Add payments
     *
     * @param \Ferus\EventBundle\Entity\Payment $payments
     * @return Ticket
     */
    public function addPayment(\Ferus\EventBundle\Entity\Payment $payments)
    {
        $this->payments[] = $payments;

        return $this;
    }

    /**
     * Remove payments
     *
     * @param \Ferus\EventBundle\Entity\Payment $payments
     */
    public function removePayment(\Ferus\EventBundle\Entity\Payment $payments)
    {
        $this->payments->removeElement($payments);
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPayments()
    {
        return $this->payments;
    }
}
