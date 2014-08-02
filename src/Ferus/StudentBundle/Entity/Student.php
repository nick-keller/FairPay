<?php

namespace Ferus\StudentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ferus\AccountBundle\Entity\Account;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Student
 *
 * @UniqueEntity("id")
 */
class Student
{
    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @var boolean
     * @Assert\NotBlank()
     */
    private $isContributor;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $login;

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

    public function generateLogin()
    {
        $this->login =
            substr($this->cleanStr($this->lastName), 0, 7) .
            substr($this->cleanStr($this->firstName), 0, 1);
    }

    public function generateEmail()
    {
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
