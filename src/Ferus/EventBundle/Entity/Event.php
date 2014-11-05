<?php

namespace Ferus\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Event
 */
class Event
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var \Datetime
     */
    private $date;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Assert\Count(
     *      min = "1"
     * )
     */
    private $tickets;

    /**
     * @var array
     */
    private $removedTickets;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $payments;

    /**
     * @var integer
     */
    private $maxTickets;

    /**
     * @var boolean
     */
    private $askForCars;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $carRequests;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->carRequests = new ArrayCollection();
        $this->removedTickets = array();
        $this->askForCars = true;
    }

    public function __toString()
    {
        return $this->getName();
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
     * @return Event
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
     * @param \Datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \Datetime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Add tickets
     *
     * @param Ticket $tickets
     * @return Event
     */
    public function addTicket(Ticket $tickets)
    {
        $tickets->setEvent($this);
        $this->tickets[] = $tickets;

        return $this;
    }

    /**
     * Remove tickets
     *
     * @param Ticket $tickets
     */
    public function removeTicket(Ticket $tickets)
    {
        $this->removedTickets[] = $tickets;
        $this->tickets->removeElement($tickets);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * @return array
     */
    public function getRemovedTickets()
    {
        return $this->removedTickets;
    }


    /**
     * Add payments
     *
     * @param \Ferus\EventBundle\Entity\Payment $payments
     * @return Event
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


    /**
     * Set maxTickets
     *
     * @param integer $maxTickets
     * @return Event
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
     * Set askForCars
     *
     * @param boolean $askForCars
     * @return Event
     */
    public function setAskForCars($askForCars)
    {
        $this->askForCars = $askForCars;

        return $this;
    }

    /**
     * Get askForCars
     *
     * @return boolean 
     */
    public function getAskForCars()
    {
        return $this->askForCars;
    }


    /**
     * Add carRequests
     *
     * @param \Ferus\EventBundle\Entity\CarRequest $carRequests
     * @return Event
     */
    public function addCarRequest(\Ferus\EventBundle\Entity\CarRequest $carRequests)
    {
        $this->carRequests[] = $carRequests;

        return $this;
    }

    /**
     * Remove carRequests
     *
     * @param \Ferus\EventBundle\Entity\CarRequest $carRequests
     */
    public function removeCarRequest(\Ferus\EventBundle\Entity\CarRequest $carRequests)
    {
        $this->carRequests->removeElement($carRequests);
    }

    /**
     * Get carRequests
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCarRequests()
    {
        return $this->carRequests;
    }
}
