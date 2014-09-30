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
}
