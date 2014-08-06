<?php

namespace Ferus\SellerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ferus\AccountBundle\Entity\Account;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Seller
 */
class Seller
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * @var Account
     */
    private $account;

    public function __toString()
    {
        return $this->name;
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

    public function getBarCode()
    {
        return 'S'.$this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Seller
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
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     * @return Seller
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string 
     */
    public function getApiKey()
    {
        return $this->apiKey;
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

    public function generateApiKey()
    {
        $this->apiKey = uniqid('api_');
    }
}
