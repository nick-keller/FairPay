<?php

namespace Ferus\MailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Template
 */
class Template
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
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $firstWaveAuth;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $firstWaveCC;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $secondWaveAuth;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $secondWaveCC;

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subject = '[Autorisation] ';
        $this->firstWaveAuth = new \Doctrine\Common\Collections\ArrayCollection();
        $this->firstWaveCC = new \Doctrine\Common\Collections\ArrayCollection();
        $this->secondWaveAuth = new \Doctrine\Common\Collections\ArrayCollection();
        $this->secondWaveCC = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Template
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
     * Set text
     *
     * @param string $text
     * @return Template
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Add firstWaveAuth
     *
     * @param \Ferus\MailBundle\Entity\Authority $firstWaveAuth
     * @return Template
     */
    public function addFirstWaveAuth(\Ferus\MailBundle\Entity\Authority $firstWaveAuth)
    {
        $this->firstWaveAuth[] = $firstWaveAuth;

        return $this;
    }

    /**
     * Remove firstWaveAuth
     *
     * @param \Ferus\MailBundle\Entity\Authority $firstWaveAuth
     */
    public function removeFirstWaveAuth(\Ferus\MailBundle\Entity\Authority $firstWaveAuth)
    {
        $this->firstWaveAuth->removeElement($firstWaveAuth);
    }

    /**
     * Get firstWaveAuth
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFirstWaveAuth()
    {
        return $this->firstWaveAuth;
    }

    /**
     * Add firstWaveCC
     *
     * @param \Ferus\MailBundle\Entity\Authority $firstWaveCC
     * @return Template
     */
    public function addFirstWaveCC(\Ferus\MailBundle\Entity\Authority $firstWaveCC)
    {
        $this->firstWaveCC[] = $firstWaveCC;

        return $this;
    }

    /**
     * Remove firstWaveCC
     *
     * @param \Ferus\MailBundle\Entity\Authority $firstWaveCC
     */
    public function removeFirstWaveCC(\Ferus\MailBundle\Entity\Authority $firstWaveCC)
    {
        $this->firstWaveCC->removeElement($firstWaveCC);
    }

    /**
     * Get firstWaveCC
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFirstWaveCC()
    {
        return $this->firstWaveCC;
    }

    /**
     * Add secondWaveAuth
     *
     * @param \Ferus\MailBundle\Entity\Authority $secondWaveAuth
     * @return Template
     */
    public function addSecondWaveAuth(\Ferus\MailBundle\Entity\Authority $secondWaveAuth)
    {
        $this->secondWaveAuth[] = $secondWaveAuth;

        return $this;
    }

    /**
     * Remove secondWaveAuth
     *
     * @param \Ferus\MailBundle\Entity\Authority $secondWaveAuth
     */
    public function removeSecondWaveAuth(\Ferus\MailBundle\Entity\Authority $secondWaveAuth)
    {
        $this->secondWaveAuth->removeElement($secondWaveAuth);
    }

    /**
     * Get secondWaveAuth
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSecondWaveAuth()
    {
        return $this->secondWaveAuth;
    }

    /**
     * Add secondWaveCC
     *
     * @param \Ferus\MailBundle\Entity\Authority $secondWaveCC
     * @return Template
     */
    public function addSecondWaveCC(\Ferus\MailBundle\Entity\Authority $secondWaveCC)
    {
        $this->secondWaveCC[] = $secondWaveCC;

        return $this;
    }

    /**
     * Remove secondWaveCC
     *
     * @param \Ferus\MailBundle\Entity\Authority $secondWaveCC
     */
    public function removeSecondWaveCC(\Ferus\MailBundle\Entity\Authority $secondWaveCC)
    {
        $this->secondWaveCC->removeElement($secondWaveCC);
    }

    /**
     * Get secondWaveCC
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSecondWaveCC()
    {
        return $this->secondWaveCC;
    }


    /**
     * Set subject
     *
     * @param string $subject
     * @return Template
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
