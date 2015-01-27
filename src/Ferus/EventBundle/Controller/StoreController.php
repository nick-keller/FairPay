<?php

namespace Ferus\EventBundle\Controller;

use Ferus\EventBundle\Entity\CarRequest;
use Ferus\EventBundle\Entity\Event;
use Ferus\EventBundle\Entity\Participation;
use Ferus\EventBundle\Form\CarRequestType;
use Ferus\EventBundle\Form\EventType;
use Ferus\EventBundle\Form\ParticipationType;
use Ferus\TransactionBundle\Entity\Withdrawal;
use Ferus\TransactionBundle\Transaction\Exception\InsufficientBalanceException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
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
    public function indexAction(Event $event, Request $request)
    {
        $participation = new Participation($event);
        $form = $this->createForm(new ParticipationType(), $participation);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $old = $this->em->getRepository('FerusEventBundle:Participation')->findOneFromEvent($event, $participation->getEmail());

                if($old != null){
                    $old->setExpired(true);
                    $this->em->persist($old);
                }

                $this->em->persist($participation);
                $this->em->flush();
                $this->flash->success('Bien reçus !');
            }
        }

        return array(
            'form' => $form->createView(),
            'externalStudents' => $this->em->getRepository('FerusEventBundle:Participation')->findExternals($event)
        );
    }

    public function participantAction(Event $event, $email)
    {
        $p = $this->em->getRepository('FerusEventBundle:Participation')->findOneFromEvent($event, $email);

        $response = new Response();
        $response->setContent($this->get('serializer')->serialize($p, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Template
     */
    public function registerPlateAction(Event $event, Request $request)
    {
        $carRequest = new CarRequest();
        $carRequest->setEvent($event);
        $carRequest->setEmail($request->query->get('email'));
        $form = $this->createForm(new CarRequestType(), $carRequest);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->em->persist($carRequest);
                $this->em->flush();

                $this->flash->success('Votre demande à bien été envoyée !');
                return $this->redirect($this->generateUrl('event_store_register_plate', array('id'=>$event->getId())));
            }
        }

        return array(
            'form' => $form,
        );
    }
}
