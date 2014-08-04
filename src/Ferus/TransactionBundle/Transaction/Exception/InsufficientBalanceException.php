<?php


namespace Ferus\TransactionBundle\Transaction\Exception;


class InsufficientBalanceException extends \Exception
{
    public function __construct()
    {
        parent::__construct('La transaction n\'a pas pue être effectuée, solde insiffisant.', 500);
    }
} 