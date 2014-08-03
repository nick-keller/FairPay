<?php

namespace Ferus\AccountBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ferus\StudentBundle\Entity\Student;
use Ferus\TransactionBundle\Entity\Transaction;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Account
 * @UniqueEntity(
 *      fields="student",
 *      message="Cet étudiant a déjà un compte."
 * )
 */
class Account
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     * @Assert\GreaterThanOrEqual(0)
     */
    private $balance;

    /**
     * @var Student
     */
    private $student;
    
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
     * @param string $balance
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
     * @return string 
     */
    public function getBalance()
    {
        return $this->balance;
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
