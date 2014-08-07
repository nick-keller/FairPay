<?php


namespace Ferus\SellerBundle\Entity\Api;

use Ferus\SellerBundle\Entity\Seller;
use Ferus\StudentBundle\Entity\Student;
use Symfony\Component\Validator\Constraints as Assert;

class Deposit
{
    /**
     * @Assert\NotBlank()
     * @var Seller
     */
    public $api_key;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="Ferus\StudentBundle\Entity\Student", message="Cette valeur doit être l'id d'un étudiant.")
     * @var Student
     */
    public $client_id;

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     * @var float
     */
    public $amount;

    public $cause = 'Dépot en cash chez le marchand';
} 