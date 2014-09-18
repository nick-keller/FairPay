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
     * @var Event
     */
    private $event;

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
}
