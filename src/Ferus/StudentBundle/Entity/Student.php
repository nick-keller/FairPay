<?php

namespace Ferus\StudentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ferus\AccountBundle\Entity\Account;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * Student
 *
 * @UniqueEntity("id")
 * @ExclusionPolicy("all")
 */
class Student
{
    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     * @Expose
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Expose
     */
    private $firstName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Expose
     */
    private $lastName;

    /**
     * @var boolean
     * @Assert\NotBlank()
     * @Expose
     */
    private $isContributor;

    /**
     * @var string
     * @Expose
     */
    private $email;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     * @Expose
     */
    private $class;

    /**
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * @var Account
     */
    private $account;


    public function __construct($firstName = null, $lastName = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->isContributor = false;
    }

    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getHash()
    {
        return md5($this->email . $this->id . $this->login . $this->class);
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function getBarcode()
    {
        return $this->id;
    }

    /**
     * @param string $firstName
     * @return Student
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     * @return Student
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param boolean $isContributor
     * @return Student
     */
    public function setIsContributor($isContributor)
    {
        $this->isContributor = $isContributor;

        return $this;
    }

    /**
     * @return boolean 
     */
    public function getIsContributor()
    {
        return $this->isContributor;
    }

    /**
     * @param string $email
     * @return Student
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
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
     * @param \Ferus\AccountBundle\Entity\Account $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return \Ferus\AccountBundle\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @VirtualProperty
     * @return bool
     */
    public function hasFairpay()
    {
        return $this->account !== null;
    }

    public function generateLogin()
    {
        if($this->login != null) return;

        $this->login =
            substr($this->cleanStr($this->lastName), 0, 7) .
            substr($this->cleanStr($this->firstName), 0, 1);
    }

    public function generateEmail()
    {
        if($this->email != null) return;

        $this->email =
            $this->cleanStr($this->firstName) . '.' .
            $this->cleanStr($this->lastName) . '@edu.esiee.fr';
    }

    private function cleanStr($str)
    {

        $chars = array(
            'a' => '/à|á|ã|â|ä/',
            'e' => '/è|é|ê|ë/',
            'i' => '/ì|í|î|ï/',
            'o' => '/ò|ó|õ|ô|ö|ø/',
            'u' => '/ù|ú|û|ü/',
            'c' => '/ç/',
            'n' => '/ñ/',
            'y' => '/ý|y|ÿ/',
            ''  => '/[ \']/'
        );

        return preg_replace($chars, array_keys($chars), strtolower($str));
    }
}
