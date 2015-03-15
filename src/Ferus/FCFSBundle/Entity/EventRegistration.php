<?php

namespace Ferus\FCFSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventRegistration
 */
class EventRegistration
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return EventRegistration
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
     * @var \Ferus\FCFSBundle\Entity\Event
     */
    private $event;

    /**
     * @var \Ferus\StudentBundle\Entity\Student
     */
    private $student;


    /**
     * Set event
     *
     * @param \Ferus\FCFSBundle\Entity\Event $event
     * @return EventRegistration
     */
    public function setEvent(\Ferus\FCFSBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \Ferus\FCFSBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set student
     *
     * @param \Ferus\StudentBundle\Entity\Student $student
     * @return EventRegistration
     */
    public function setStudent(\Ferus\StudentBundle\Entity\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \Ferus\StudentBundle\Entity\Student 
     */
    public function getStudent()
    {
        return $this->student;
    }
}
