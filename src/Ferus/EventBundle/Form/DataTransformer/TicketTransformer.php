<?php

namespace Ferus\EventBundle\Form\DataTransformer;

use Doctrine\ORM\NoResultException;
use Ferus\EventBundle\Entity\Ticket;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class TicketTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (Ticket) to a string (id).
     *
     * @param  Ticket|null $item
     * @return string
     */
    public function transform($item)
    {
        if($item === null || $item->getId() === null)
            return '';

        return $item->getId();
    }

    /**
     * Transforms a string (id) to an object (Ticket).
     *
     * @param  string $id
     * @return Ticket|null
     * @throws TransformationFailedException if object (Ticket) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) return null;

        try{
            $ticket = $this->om
                ->getRepository('FerusEventBundle:Ticket')
                ->findOneById($id);
        }
        catch(NoResultException $e){
            throw new TransformationFailedException('Ce tarif n\'existe pas.');
        }

        return $ticket;
    }
} 