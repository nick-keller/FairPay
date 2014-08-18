<?php

namespace Ferus\AccountBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ferus\SellerBundle\Entity\Seller;
use Ferus\StudentBundle\Entity\Student;
use Ferus\TransactionBundle\Entity\Transaction;
use Ferus\UserBundle\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Account
 * @UniqueEntity(
 *      fields="student",
 *      message="Cet étudiant a déjà un compte.",
 *      errorPath="owner"
 * )
 * @UniqueEntity(
 *      fields="seller",
 *      message="Ce marchand a déjà un compte.",
 *      errorPath="owner"
 * )
 */
class Account
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     * @Assert\GreaterThanOrEqual(0)
     */
    private $balance;

    /**
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * @var Student
     */
    private $student;

    /**
     * @var Seller
     */
    private $seller;

    /**
     * @var User
     */
    private $user;
    
    /**
     * @var ArrayCollection
     */
    private $issuedTransactions;

    /**
     * @var ArrayCollection
     */
    private $receivedTransactions;


    public function __construct(Student $student = null)
    {
        $this->student = $student;
        $this->balance = 0;
        
        $this->issuedTransactions = new ArrayCollection;
        $this->receivedTransactions = new ArrayCollection;
    }

    public function __toString()
    {
        return $this->getOwner()->__toString();
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
     * Set balance
     *
     * @param float $balance
     * @return Account
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param \DateTime $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param \Ferus\StudentBundle\Entity\Student $student
     */
    public function setStudent($student)
    {
        $this->student = $student;
    }

    /**
     * @return \Ferus\StudentBundle\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * @param \Ferus\SellerBundle\Entity\Seller $seller
     */
    public function setSeller($seller)
    {
        $this->seller = $seller;
    }

    /**
     * @return \Ferus\SellerBundle\Entity\Seller
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * @param Student|Seller $owner
     */
    public function setOwner($owner)
    {
        if($owner instanceof Student){
            $this->student = $owner;
            $this->seller = null;
        }

        if($owner instanceof Seller){
            $this->seller = $owner;
            $this->student = null;
        }
    }

    /**
     * @return Seller|Student|null
     */
    public function getOwner()
    {
        if($this->student !== null)
            return $this->student;
        else if($this->seller !== null)
            return $this->seller;

        return null;
    }

    /**
     * @param \Ferus\UserBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Ferus\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Add issuedTransactions
     *
     * @param Transaction $issuedTransactions
     * @return Account
     */
    public function addIssuedTransaction(Transaction $issuedTransactions)
    {
        $this->issuedTransactions[] = $issuedTransactions;

        return $this;
    }

    /**
     * Remove issuedTransactions
     *
     * @param Transaction $issuedTransactions
     */
    public function removeIssuedTransaction(Transaction $issuedTransactions)
    {
        $this->issuedTransactions->removeElement($issuedTransactions);
    }

    /**
     * Get issuedTransactions
     *
     * @return ArrayCollection 
     */
    public function getIssuedTransactions()
    {
        return $this->issuedTransactions;
    }

    /**
     * Add receivedTransactions
     *
     * @param Transaction $receivedTransactions
     * @return Account
     */
    public function addReceivedTransaction(Transaction $receivedTransactions)
    {
        $this->receivedTransactions[] = $receivedTransactions;

        return $this;
    }

    /**
     * Remove receivedTransactions
     *
     * @param Transaction $receivedTransactions
     */
    public function removeReceivedTransaction(Transaction $receivedTransactions)
    {
        $this->receivedTransactions->removeElement($receivedTransactions);
    }

    /**
     * Get receivedTransactions
     *
     * @return ArrayCollection 
     */
    public function getReceivedTransactions()
    {
        return $this->receivedTransactions;
    }
}
