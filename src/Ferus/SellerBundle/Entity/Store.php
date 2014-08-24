<?php

namespace Ferus\SellerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Store
 */
class Store
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
     * @var Seller
     */
    private $seller;

    function __construct($name = null)
    {
        $this->name = $name;
    }

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

    /**
     * Set name
     *
     * @param string $name
     * @return Store
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
     * @param \Ferus\SellerBundle\Entity\Seller $seller
     */
    public function setSeller(Seller $seller = null)
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
}
