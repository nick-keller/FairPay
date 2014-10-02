<?php

namespace Ferus\MailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Auth
 */
class Auth
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var array
     */
    private $customFields;

    /**
     * @var \Ferus\MailBundle\Entity\Template
     */
    private $template;

    /**
     * @var integer
     */
    private $firstWaveStatus = 0;

    /**
     * @var integer
     */
    private $secondWaveStatus = 0;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $responses;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->responses = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Auth
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
     * Set customFields
     *
     * @param array $customFields
     * @return Auth
     */
    public function setCustomFields($customFields)
    {
        $this->customFields = $customFields;

        return $this;
    }

    /**
     * Get customFields
     *
     * @return array 
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }


    /**
     * Set template
     *
     * @param \Ferus\MailBundle\Entity\Template $template
     * @return Auth
     */
    public function setTemplate(\Ferus\MailBundle\Entity\Template $template = null)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return \Ferus\MailBundle\Entity\Template 
     */
    public function getTemplate()
    {
        return $this->template;
    }


    /**
     * Set firstWaveStatus
     *
     * @param integer $firstWaveStatus
     * @return Auth
     */
    public function setFirstWaveStatus($firstWaveStatus)
    {
        $this->firstWaveStatus = $firstWaveStatus;

        return $this;
    }

    /**
     * Get firstWaveStatus
     *
     * @return integer 
     */
    public function getFirstWaveStatus()
    {
        return $this->firstWaveStatus;
    }

    /**
     * Set secondWaveStatus
     *
     * @param integer $secondWaveStatus
     * @return Auth
     */
    public function setSecondWaveStatus($secondWaveStatus)
    {
        $this->secondWaveStatus = $secondWaveStatus;

        return $this;
    }

    /**
     * Get secondWaveStatus
     *
     * @return integer 
     */
    public function getSecondWaveStatus()
    {
        return $this->secondWaveStatus;
    }

    /**
     * Add reponses
     *
     * @param \Ferus\MailBundle\Entity\Response $reponses
     * @return Auth
     */
    public function addResponse(\Ferus\MailBundle\Entity\Response $responses)
    {
        $this->responses[] = $responses;

        return $this;
    }

    /**
     * Remove reponses
     *
     * @param \Ferus\MailBundle\Entity\Response $reponses
     */
    public function removeResponse(\Ferus\MailBundle\Entity\Response $responses)
    {
        $this->responses->removeElement($responses);
    }

    /**
     * Get reponses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResponses()
    {
        return $this->responses;
    }
    /**
     * @var string
     */
    private $status;


    /**
     * Set status
     *
     * @param string $status
     * @return Auth
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
}
