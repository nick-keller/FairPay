<?php

namespace Ferus\SellerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * @var ArrayCollection
     */
    private $products;

    function __construct($name = null)
    {
        $this->name = $name;
        $this->products = new ArrayCollection;
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

    /**
     * Add products
     *
     * @param \Ferus\SellerBundle\Entity\Product $products
     * @return Store
     */
    public function addProduct(\Ferus\SellerBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \Ferus\SellerBundle\Entity\Product $products
     */
    public function removeProduct(\Ferus\SellerBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }
}
