<?php

namespace Ferus\FCFSBundle\Controller;

use Ferus\FCFSBundle\Entity\EventRegistration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublicController extends Controller
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
    public function registerAction(Request $request, $token)
    {
        $data = preg_replace('#^.+[a-z]([0-9]+)[a-z]$#', '$1', $token);

        $this->em->getConnection()->beginTransaction();

        $event = $this->em->getRepository('FerusFCFSBundle:Event')->findOneById($data);
        $student = $this->em->getRepository('FerusStudentBundle:Student')->findFromToken($token);

        if($event == null || $student == null)
            throw new NotFoundHttpException;

        $registered = $this->em->getRepository('FerusFCFSBundle:EventRegistration')->findOneBy(array('event'=>$event, 'student'=>$student)) !== null;
        $left = $event->getMaxPeople() - count($event->getRegistrations());

        if($request->isMethod('POST') && $left > 0 && !$registered){
            $r = new EventRegistration();
            $r->setEvent($event);
            $r->setStudent($student);

            $this->em->persist($r);
            $this->em->flush();
            $this->em->getConnection()->commit();

            return $this->redirect($this->generateUrl('fcfs_register', array('token' =>$token)));
        }

        $this->em->getConnection()->commit();

        return array(
            'event' => $event,
            'student' => $student,
            'left' => $left,
            'registered' => $registered,
        );
    }
}
