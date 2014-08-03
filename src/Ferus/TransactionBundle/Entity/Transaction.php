<?php

namespace Ferus\TransactionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ferus\AccountBundle\Entity\Account;

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
     */
    private $amount;

    /**
     * @var \DateTime
     */
    private $completedAt;

    /**
     * @var string
     */
    private $cause;
    
    /**
     * @var Account
     */
    private $issuer;

    /**
     * @var Account
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
}
