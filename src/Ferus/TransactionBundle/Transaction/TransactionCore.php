<?php

namespace Ferus\TransactionBundle\Transaction;


use Doctrine\ORM\EntityManager;
use Ferus\TransactionBundle\Entity\Transaction;
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
} 