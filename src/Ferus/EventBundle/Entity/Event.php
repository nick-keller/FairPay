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
     * @Assert\NotBlank
     */
    private $date;

    /**
     * @var integer
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(0)
     */
    private $maxTickets;

    /**
     * @var boolean
     * @Assert\NotBlank
     */
    private $askForCars;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $carRequests;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(0)
     */
    private $price;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(0)
     */
    private $priceNonContributor;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(0)
     */
    private $deposit;

    /**
     * @var boolean
     * @Assert\NotBlank
     */
    private $depositByCheck;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $options;
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $extraFields;

    /**
     * @var array
     */
    private $removedThings;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $participations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->carRequests = new ArrayCollection();
        $this->options = new ArrayCollection();
        $this->extraFields = new ArrayCollection();
        $this->askForCars = true;
        $this->deposit = 0;
        $this->depositByCheck = true;
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


    /**
     * Set price
     *
     * @param string $price
     * @return Event
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
     * Set priceNonContributor
     *
     * @param string $priceNonContributor
     * @return Event
     */
    public function setPriceNonContributor($priceNonContributor)
    {
        $this->priceNonContributor = $priceNonContributor;

        return $this;
    }

    /**
     * Get priceNonContributor
     *
     * @return string 
     */
    public function getPriceNonContributor()
    {
        return $this->priceNonContributor;
    }

    /**
     * Set deposit
     *
     * @param string $deposit
     * @return Event
     */
    public function setDeposit($deposit)
    {
        $this->deposit = $deposit;

        return $this;
    }

    /**
     * Get deposit
     *
     * @return string 
     */
    public function getDeposit()
    {
        return $this->deposit;
    }

    /**
     * Set depositByCheck
     *
     * @param boolean $depositByCheck
     * @return Event
     */
    public function setDepositByCheck($depositByCheck)
    {
        $this->depositByCheck = $depositByCheck;

        return $this;
    }

    /**
     * Get depositByCheck
     *
     * @return boolean 
     */
    public function getDepositByCheck()
    {
        return $this->depositByCheck;
    }


    /**
     * Add options
     *
     * @param \Ferus\EventBundle\Entity\EventOption $options
     * @return Event
     */
    public function addOption(\Ferus\EventBundle\Entity\EventOption $options)
    {
        $options->setEvent($this);
        $this->options[] = $options;

        return $this;
    }

    /**
     * Remove options
     *
     * @param \Ferus\EventBundle\Entity\EventOption $options
     */
    public function removeOption(\Ferus\EventBundle\Entity\EventOption $options)
    {
        $this->removedThings[] = $options;
        $this->options->removeElement($options);
    }

    /**
     * Get options
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOptions()
    {
        return $this->options;
    }


    /**
     * Add extraFields
     *
     * @param \Ferus\EventBundle\Entity\ExtraField $extraFields
     * @return Event
     */
    public function addExtraField(\Ferus\EventBundle\Entity\ExtraField $extraFields)
    {
        $extraFields->setEvent($this);
        $this->extraFields[] = $extraFields;

        return $this;
    }

    /**
     * Remove extraFields
     *
     * @param \Ferus\EventBundle\Entity\ExtraField $extraFields
     */
    public function removeExtraField(\Ferus\EventBundle\Entity\ExtraField $extraFields)
    {
        $this->removedThings[] = $extraFields;
        $this->extraFields->removeElement($extraFields);
    }

    /**
     * Get extraFields
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExtraFields()
    {
        return $this->extraFields;
    }

    /**
     * @return array
     */
    public function getRemovedThings()
    {
        return $this->removedThings;
    }


    /**
     * Add participations
     *
     * @param \Ferus\EventBundle\Entity\Participation $participations
     * @return Event
     */
    public function addParticipation(\Ferus\EventBundle\Entity\Participation $participations)
    {
        $this->participations[] = $participations;

        return $this;
    }

    /**
     * Remove participations
     *
     * @param \Ferus\EventBundle\Entity\Participation $participations
     */
    public function removeParticipation(\Ferus\EventBundle\Entity\Participation $participations)
    {
        $this->participations->removeElement($participations);
    }

    /**
     * Get participations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParticipations()
    {
        return $this->participations;
    }
}
