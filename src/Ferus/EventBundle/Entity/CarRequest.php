<?php

namespace Ferus\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * CarRequest
 * @UniqueEntity(fields={"email", "event"}, message="Vous avez déjà fait une demande.", errorPath="form")
 */
class CarRequest
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $brand;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $model;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $color;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $plate;

    /**
     * @var \Ferus\EventBundle\Entity\Event
     */
    private $event;


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
     * Set email
     *
     * @param string $email
     * @return CarRequest
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
     * Set brand
     *
     * @param string $brand
     * @return CarRequest
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string 
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set model
     *
     * @param string $model
     * @return CarRequest
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string 
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return CarRequest
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set plate
     *
     * @param string $plate
     * @return CarRequest
     */
    public function setPlate($plate)
    {
        $this->plate = $plate;

        return $this;
    }

    /**
     * Get plate
     *
     * @return string 
     */
    public function getPlate()
    {
        return $this->plate;
    }


    /**
     * Set event
     *
     * @param \Ferus\EventBundle\Entity\Event $event
     * @return CarRequest
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
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        foreach($this->event->getPayments() as $payment){
            if($payment->getEmail() == $this->email) return;
        }

        $context->buildViolation('Vous devez vous inscrire à lévénement dans un premier temps. Utilisez ensuite votre mail ESIEE ou l\'adresse d\'inscription.')
            ->atPath('form')
            ->addViolation();
    }
}
