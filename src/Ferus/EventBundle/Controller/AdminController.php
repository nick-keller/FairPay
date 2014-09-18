<?php

namespace Ferus\EventBundle\Controller;

use Ferus\EventBundle\Entity\Event;
use Ferus\EventBundle\Entity\Ticket;
use Ferus\EventBundle\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;

class AdminController extends Controller
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var FlashMessage
     */
    private $flash;

    /**
     * @Template
     */
    public function indexAction(Request $request)
    {
        $events = $this->paginator->paginate(
            $this->em->getRepository('FerusEventBundle:Event')->queryAll(),
            $request->query->get('page', 1),
            20
        );

        return array(
            'events' => $events,
        );
    }

    /**
     * @Template
     */
    public function addAction(Request $request)
    {
        $event = new Event;
        $ticket = new Ticket();
        $ticket->setName('Place');
        $event->addTicket($ticket);
        $form = $this->createForm(new EventType, $event);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->em->persist($event);
                $this->em->flush();

                $this->flash->success('Evenement créé.');
                return $this->redirect($this->generateUrl('event_admin_index'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Template
     */
    public function editAction(Event $event, Request $request)
    {
        $form = $this->createForm(new EventType, $event);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->em->persist($event);

                if(count($event->getRemovedTickets()))
                    foreach($event->getRemovedTickets() as $ticket)
                        $this->em->remove($ticket);

                $this->em->flush();

                $this->flash->success('Evenement mis à jour.');
                return $this->redirect($this->generateUrl('event_admin_index'));
            }
        }

        return array(
            'event' => $event,
            'form' => $form->createView(),
        );
    }
}
