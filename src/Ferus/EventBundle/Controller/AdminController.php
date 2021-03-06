<?php

namespace Ferus\EventBundle\Controller;

use Ferus\EventBundle\Entity\Event;
use Ferus\EventBundle\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;

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

                if(count($event->getRemovedThings()))
                    foreach($event->getRemovedThings() as $thing)
                        $this->em->remove($thing);

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
        if($request->isMethod('POST')){

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

        return array(
            'event' => $event,
        );
    }

    public function participantsCsvAction(Event $event)
    {
        $response = new Response();
        $response->setContent($this->get('ferus.csv_generator')->generate($event));
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('charset', 'UTF-8');
        $filename = $event;
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'.csv"');
        return $response;
    }

    /**
     * @Template
     */
    public function detailsAction(Event $event, $email)
    {
        $history = $this->em->getRepository('FerusEventBundle:Participation')->findBy(array(
            'event' =>$event,
            'email' => $email,
        ));

        return array(
            'event' => $event,
            'history' => $history,
        );
    }

    public function removeFromAction(Event $event, $email)
    {
        $this->em->getRepository('FerusEventBundle:Payment')->removeFrom($event, $email);
        $this->flash->success("Tous les paiements de $email ont étés supprimés.");

        return $this->redirect($this->generateUrl('event_admin_participants', array('id'=>$event->getId())));
    }

    /**
     * @Template
     */
    public function moneyAction(Event $event)
    {
        $stats = $this->em->getRepository('FerusEventBundle:Payment')->findEventStats($event);
        $statsTickets = $this->em->getRepository('FerusEventBundle:Payment')->findEventStatsTickets($event);

        return array(
            'stats' => $stats,
            'statsTickets' => $statsTickets,
        );
    }

    /**
     * @Template
     */
    public function carRequestAction(Event $event, Request $request)
    {
        return array(
            'requests' => $this->em->getRepository('FerusEventBundle:CarRequest')->findBy(array('event'=>$event)),
        );
    }

    public function carRequestCsvAction(Event $event)
    {
        $response = new Response();
        $response->setContent($this->get('ferus.csv_generator')->generateParking($event));
        $response->headers->set('Content-Type', 'text/csv');
        $filename = $event.'_parking';
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'.csv"');
        return $response;
    }
}
