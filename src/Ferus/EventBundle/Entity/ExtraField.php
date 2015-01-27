<?php

namespace Ferus\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ExtraField
 */
class ExtraField
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
     * @var boolean
     * @Assert\NotBlank
     */
    private $mandatory;

    /**
     * @var \Ferus\EventBundle\Entity\Event
     */
    private $event;

    public function __toString()
    {
        return $this->name;
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
     * @return ExtraField
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
     * Set mandatory
     *
     * @param boolean $mandatory
     * @return ExtraField
     */
    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;

        return $this;
    }

    /**
     * Get mandatory
     *
     * @return boolean 
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }


    /**
     * Set event
     *
     * @param \Ferus\EventBundle\Entity\Event $event
     * @return ExtraField
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
}
