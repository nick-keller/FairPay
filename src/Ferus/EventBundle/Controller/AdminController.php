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
use JMS\SecurityExtraBundle\Annotation\Secure;

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
     * @Secure(roles="ROLE_EVENT_ADMIN")
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
     * @Secure(roles="ROLE_EVENT_ADMIN")
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

    /**
     * @Template
     */
    public function removeAction(Event $event, Request $request)
    {
        if($request->isMethod('POST') && !count($event->getPayments())){

            $this->em->remove($event);
            $this->em->flush();

            $this->flash->success('Evénement supprimé.');

            return $this->redirect($this->generateUrl('event_admin_index'));
        }

        return array(
            'event' => $event,
        );
    }

    /**
     * @Template
     */
    public function participantsAction(Event $event)
    {
        $participants = $this->em->getRepository('FerusEventBundle:Payment')->findFromEvent($event);

        return array(
            'event' => $event,
            'participants' => $participants,
        );
    }

    /**
     * @Template
     */
    public function detailsAction(Event $event, $email)
    {
        $payments = $this->em->getRepository('FerusEventBundle:Payment')->findBy(array(
            'event' =>$event,
            'email' => $email,
        ));

        return array(
            'event' => $event,
            'payments' => $payments,
        );
    }
}