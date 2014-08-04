<?php

namespace Ferus\TransactionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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
     * @var string
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(0)
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
     * @param string $amount
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
     * @return string 
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

    public function prePersist()
    {
        $this->completedAt = new \DateTime;
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
        }
    }
}
