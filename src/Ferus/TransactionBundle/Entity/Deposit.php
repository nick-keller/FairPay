<?php


namespace Ferus\TransactionBundle\Entity;

use Ferus\AccountBundle\Entity\Account;
use Symfony\Component\Validator\Constraints as Assert;

class Deposit 
{
    /**
     * @var Account
     * @Assert\NotBlank()
     */
    private $account;

    /**
     * @var float
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(0)
     */
    private $amount;

    /**
     * @param Account|null $account
     */
    public function setAccount(Account $account = null)
    {
        $this->account = $account;
    }

    /**
     * @return Account
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
} 