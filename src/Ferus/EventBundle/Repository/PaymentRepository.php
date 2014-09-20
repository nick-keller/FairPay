<?php

namespace Ferus\EventBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Ferus\EventBundle\Entity\Event;
use Doctrine\ORM\Query;

/**
 * PaymentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PaymentRepository extends EntityRepository
{
    public function findFromEvent(Event $event)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.studentId, CONCAT(p.firstName, CONCAT(\' \', p.lastName)) name, p.email')
            ->where('p.event = :event')
            ->setParameter('event', $event)
            ->groupBy('p.email')
        ;

        foreach($event->getTickets() as $ticket){
            $qb->addSelect("SUM(IF(IDENTITY(p.ticket) = {$ticket->getId()}, 1, 0)) as ticket_{$ticket->getId()}");
        }

        return $qb
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY)
        ;
    }
}