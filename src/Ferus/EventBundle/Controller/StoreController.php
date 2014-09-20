<?php

namespace Ferus\EventBundle\Controller;

use Ferus\EventBundle\Entity\Event;
use Ferus\EventBundle\Entity\Payment;
use Ferus\EventBundle\Entity\Ticket;
use Ferus\EventBundle\Form\EventType;
use Ferus\EventBundle\Form\PaymentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;

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
        $payment = new Payment;
        $payment->setEvent($event);
        $form = $this->createForm(new PaymentType(), $payment);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->em->persist($payment);
                $this->em->flush();

                $this->flash->success(sprintf('%s %s a payé sa %s pour l\'evènement %s',
                    $payment->getFirstName(),
                    $payment->getLastName(),
                    $payment->getTicket(),
                    $payment->getEvent()
                ));

                return $this->redirect($this->generateUrl('event_store_index', array('id'=>$event->getId())));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
