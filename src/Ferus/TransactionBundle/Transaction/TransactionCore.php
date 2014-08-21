<?php

namespace Ferus\TransactionBundle\Transaction;


use Doctrine\ORM\EntityManager;
use Ferus\SellerBundle\Entity\Api\Cash;
use Ferus\TransactionBundle\Entity\Deposit;
use Ferus\TransactionBundle\Entity\Transaction;
use Ferus\TransactionBundle\Entity\Withdrawal;
use Ferus\TransactionBundle\Transaction\Exception\InsufficientBalanceException;

class TransactionCore
{
    /**
     * @var EntityManager
     */
    private $em;

    function __construct($em)
    {
        $this->em = $em;
    }

    public function execute(Transaction $transaction)
    {
        if($transaction->getIssuer()->getBalance() < $transaction->getAmount())
            throw new InsufficientBalanceException;

        $issuer = $transaction->getIssuer();
        $receiver = $transaction->getReceiver();

        $issuer->setBalance($issuer->getBalance() - $transaction->getAmount());
        $receiver->setBalance($receiver->getBalance() + $transaction->getAmount());

        $this->em->persist($transaction);
        $this->em->flush();
    }

    public function deposit(Deposit $deposit)
    {
        $transaction = new Transaction;
        $transaction->setReceiver($deposit->getAccount());
        $transaction->setAmount($deposit->getAmount());
        $transaction->setCause($deposit->getCause());

        $receiver = $deposit->getAccount();
        $receiver->setBalance($receiver->getBalance() + $deposit->getAmount());

        $this->em->persist($transaction);
        $this->em->flush();
    }

    public function withdrawal(Withdrawal $withdrawal)
    {
        $transaction = new Transaction;
        $transaction->setIssuer($withdrawal->getAccount());
        $transaction->setAmount($withdrawal->getAmount());
        $transaction->setCause($withdrawal->getCause());

        $issuer = $withdrawal->getAccount();
        $issuer->setBalance($issuer->getBalance() - $withdrawal->getAmount());

        if($issuer->getBalance() < 0)
            throw new InsufficientBalanceException;

        $this->em->persist($transaction);
        $this->em->flush();
    }

    public function cash(Cash $cash)
    {
        $transaction = new Transaction;
        $transaction->setIssuer($cash->client_id->getAccount());
        $transaction->setReceiver($cash->api_key->getAccount());
        $transaction->setAmount($cash->amount);
        $transaction->setCause($cash->cause);

        $this->execute($transaction);
    }

    public function sellerDeposit(\Ferus\SellerBundle\Entity\Api\Deposit $deposit)
    {
        $transaction = new Transaction;
        $transaction->setIssuer($deposit->api_key->getAccount());
        $transaction->setReceiver($deposit->client_id->getAccount());
        $transaction->setAmount($deposit->amount);
        $transaction->setCause($deposit->cause);

        $this->execute($transaction);
    }
} 