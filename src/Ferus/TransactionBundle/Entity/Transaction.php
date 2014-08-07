<?php

namespace Ferus\TransactionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ferus\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Ferus\AccountBundle\Entity\Account;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Transaction
 */
class Transaction
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     */
    private $amount;

    /**
     * @var \DateTime
     */
    private $completedAt;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $cause;
    
    /**
     * @var Account
     * @Assert\NotBlank()
     */
    private $issuer;

    /**
     * @var Account
     * @Assert\NotBlank()
     */
    private $receiver;

    /**
     * @var User
     */
    private $representative;


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
     * Set amount
     *
     * @param float $amount
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set completedAt
     *
     * @param \DateTime $completedAt
     * @return Transaction
     */
    public function setCompletedAt($completedAt)
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    /**
     * Get completedAt
     *
     * @return \DateTime 
     */
    public function getCompletedAt()
    {
        return $this->completedAt;
    }

    /**
     * Set cause
     *
     * @param string $cause
     * @return Transaction
     */
    public function setCause($cause)
    {
        $this->cause = $cause;

        return $this;
    }

    /**
     * Get cause
     *
     * @return string 
     */
    public function getCause()
    {
        return $this->cause;
    }


    /**
     * Set issuer
     *
     * @param Account $issuer
     * @return Transaction
     */
    public function setIssuer(Account $issuer = null)
    {
        $this->issuer = $issuer;

        return $this;
    }

    /**
     * Get issuer
     *
     * @return Account 
     */
    public function getIssuer()
    {
        return $this->issuer;
    }

    /**
     * Set receiver
     *
     * @param Account $receiver
     * @return Transaction
     */
    public function setReceiver(Account $receiver = null)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return Account
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    public function getType()
    {
        if($this->issuer == null) return 'Dépot';
        if($this->receiver == null) return 'Retrait';

        return 'Transaction';
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if($this->issuer instanceof Account && $this->receiver instanceof Account){

            if ($this->issuer->getId() == $this->receiver->getId()) {
                $context->addViolationAt(
                    'receiver',
                    'Le récepteur doit être différent de l\'émetteur',
                    array(),
                    null
                );
            }

            if($this->issuer->getBalance() < $this->getAmount()){
                $context->addViolationAt(
                    'amount',
                    'Le solde de '.$this->issuer->getOwner().' est insufisant',
                    array(),
                    null
                );
            }
        }
    }


    /**
     * Set representative
     *
     * @param User $representative
     * @return Transaction
     */
    public function setRepresentative(User $representative = null)
    {
        $this->representative = $representative;

        return $this;
    }

    /**
     * Get representative
     *
     * @return User
     */
    public function getRepresentative()
    {
        return $this->representative;
    }
}
