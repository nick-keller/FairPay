<?php

namespace Ferus\SellerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ferus\AccountBundle\Entity\Account;

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
     */
    private $name;

    /**
     * @var string
     */
    private $apiKey;

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
}
