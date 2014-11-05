<?php

namespace Ferus\EventBundle\Controller;

use Ferus\EventBundle\Entity\CarRequest;
use Ferus\EventBundle\Entity\Event;
use Ferus\EventBundle\Entity\Payment;
use Ferus\EventBundle\Entity\Ticket;
use Ferus\EventBundle\Form\CarRequestType;
use Ferus\EventBundle\Form\EventType;
use Ferus\EventBundle\Form\PaymentType;
use Ferus\TransactionBundle\Entity\Withdrawal;
use Ferus\TransactionBundle\Transaction\Exception\InsufficientBalanceException;
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
                if($payment->getMethod() == 'fairpay'){
                    $withdrawal = new Withdrawal;
                    $withdrawal->setAmount($payment->getAmount());
                    $withdrawal->setAccount($this->em->getRepository('FerusAccountBundle:Account')->findOneByBarcode($payment->getStudentId()));
                    $withdrawal->setCause($payment->getTicket() .' '. $payment->getEvent());

                    try{
                        $this->get('ferus_transaction.transaction_core')->withdrawal($withdrawal);
                    }
                    catch(InsufficientBalanceException $e){
                        $this->flash->error('Solde insufisant sur le compte FairPay.');
                        return $this->redirect($this->generateUrl('event_store_index', array('id'=>$event->getId())));
                    }
                }

                $this->em->persist($payment);
                $this->em->flush();

                $message = \Swift_Message::newInstance()
                    ->setSubject("[{$payment->getEvent()}] Validation de paiement : {$payment->getTicket()}")
                    ->setFrom(array('bde@edu.esiee.fr' => 'BDE ESIEE Paris'))
                    ->setTo(array($payment->getEmail() => $payment->getFullName()))
                    ->setBody(
                        $this->renderView(
                            'FerusEventBundle:Email:registerSuccess.txt.twig',
                            array(
                                'payment' => $payment,
                                'carRequest' => $event->getAskForCars(),
                            )
                        )
                    )
                ;
                $this->get('mailer')->send($message);

                $this->flash->success(sprintf('%s a payé : %s pour l\'evènement %s',
                    $payment->getFullName(),
                    $payment->getTicket(),
                    $payment->getEvent()
                ));

                return $this->redirect($this->generateUrl('event_store_index', array('id'=>$event->getId())));
            }
        }

        return array(
            'form' => $form->createView(),
            'externals' => $this->em->getRepository('FerusEventBundle:Payment')->findExterals($event),
            'booked' => $this->em->getRepository('FerusEventBundle:Payment')->count($event),
        );
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
