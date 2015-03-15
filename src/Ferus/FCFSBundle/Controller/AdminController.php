<?php

namespace Ferus\FCFSBundle\Controller;

use Ferus\FCFSBundle\Entity\Event;
use Ferus\FCFSBundle\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
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
     * @var FlashMessage
     */
    private $flash;

    /**
     * @Template
     */
    public function indexAction()
    {
        return array(
            'events' => $this->em->getRepository('FerusFCFSBundle:Event')->findAll(),
        );
    }

    /**
     * @Template
     */
    public function addAction(Request $request)
    {
        $event = new Event;

        return $this->handleForm($event, $request);
    }

    private function handleForm(Event $event, Request $request)
    {
        $form = $this->createForm(new EventType, $event);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->em->persist($event);
                $this->em->flush();
                $this->flash->success('Evenement enregistré');

                return $this->redirect($this->generateUrl('fcfs_admin_index'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    public function downloadAction(Event $event)
    {
        $data = $this->em->getRepository('FerusFCFSBundle:EventRegistration')->findData($event);

        $csv = implode(';', array('id', 'Nom', 'Email', 'Date'))."\n".implode("\n", array_map(function($t){
                return $t['id'].';'.$t['name'].';'.$t['email'].';'.$t['createdAt']->format('d/m/Y H:i');
            }, $data));

        $response = new Response();
        $response->setContent($csv);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('charset', 'UTF-8');
        $filename = $event;
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'.csv"');
        return $response;
    }
}
