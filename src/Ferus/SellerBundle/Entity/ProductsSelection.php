<?php


namespace Ferus\SellerBundle\Entity;


use Ferus\AccountBundle\Entity\Account;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

class ProductsSelection
{
    /**
     * @var Account
     * @Assert\NotBlank()
     */
    private $client;

    /**
     * @var Account
     */
    private $seller;

    /**
     * @var float
     * @Assert\NotBlank(message="Le total ne peut pas être nul.")
     * @Assert\GreaterThan(value=0, message="Le total ne peut pas être nul.")
     */
    private $amount;

    /**
     * @var string
     * @Assert\NotBlank(message="Vous devez sélectionner au moins un article.")
     */
    private $cause;

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $cause
     */
    public function setCause($cause)
    {
        $this->cause = $cause;
    }

    /**
     * @return string
     */
    public function getCause()
    {
        return $this->cause;
    }

    /**
     * @param \Ferus\AccountBundle\Entity\Account $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return \Ferus\AccountBundle\Entity\Account
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $seller
     */
    public function setSeller($seller)
    {
        $this->seller = $seller;
    }

    /**
     * @return mixed
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if($this->client instanceof Account){

            if($this->client->getBalance() < $this->getAmount()){
                $context->addViolationAt(
                    'amount',
                    'Le solde de '.$this->client->getOwner().' est insufisant.',
                    array(),
                    null
                );
            }
        }
    }
} 