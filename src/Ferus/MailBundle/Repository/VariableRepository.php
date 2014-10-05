<?php

namespace Ferus\MailBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Ferus\MailBundle\Entity\Variable;

/**
 * VariableRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VariableRepository extends EntityRepository
{
    public function get($name, $string, $number, $date)
    {
        $result = $this->createQueryBuilder('v')
            ->where('v.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();

        if(count($result) == 1)
            return $result[0];

        $var = new Variable();
        $var->setName($name);
        $var->setString($string);
        $var->setNumber($number);
        $var->setDate($date);

        $this->getEntityManager()->persist($var);
        $this->getEntityManager()->flush();

        return $var;
    }
}
