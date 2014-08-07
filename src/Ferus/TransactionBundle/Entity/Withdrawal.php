<?php


namespace Ferus\TransactionBundle\Entity;


use Ferus\AccountBundle\Entity\Account;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Withdrawal
{
    /**
     * @var Account
     * @Assert\NotBlank()
     */
    private $account;

    /**
     * @var float
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     */
    private $amount;

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
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if($this->account instanceof Account){

            if($this->account->getBalance() < $this->getAmount()){
                $context->addViolationAt(
                    'amount',
                    'Le solde de '.$this->account->getOwner().' est insufisant',
                    array(),
                    null
                );
            }
        }
    }
} 