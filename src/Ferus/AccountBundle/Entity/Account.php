<?php

namespace Ferus\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ferus\StudentBundle\Entity\Student;
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


    public function __construct(Student $student = null)
    {
        $this->student = $student;
        $this->balance = 0;
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
}
