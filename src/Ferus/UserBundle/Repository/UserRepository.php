<?php

namespace Ferus\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Ferus\AccountBundle\Entity\Account;
use Ferus\SellerBundle\Entity\Seller;
use Ferus\StudentBundle\Entity\Student;

/**
 * AccountRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function querySearch($query)
    {
        $qb = $this->createQueryBuilder('u');

        $query = trim($query);
        $words = explode(' ', $query);

        foreach($words as $key => $word){
            $qb
                ->andWhere("u.username LIKE :query$key")
                ->setParameter("query$key", "%$word%");
        }

        return $qb->getQuery();
    }

    public function queryAdmins()
    {

        return $this->createQueryBuilder('u');

    }
}