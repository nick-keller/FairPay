<?php

namespace Ferus\StudentBundle\Controller;


use Doctrine\ORM\EntityManager;
use Ferus\StudentBundle\Entity\Student;
use Ferus\StudentBundle\Form\StudentType;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
     * @Template
     */
    public function indexAction()
    {
        $students = $this->paginator->paginate(
            $this->em->getRepository('FerusStudentBundle:Student')->queryAll(),
            $this->get('request')->query->get('page', 1),
            50
        );

        return array(
            'students' => $students,
        );
    }

    /**
     * @Template
     */
    public function addAction(Request $request)
    {
        $student = new Student;
        $form = $this->createForm(new StudentType, $student);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->em->persist($student);
                $this->em->flush();

                return new Response('Ok', 201);
            }
            else{
                return new Response('Invalid form', 400);
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }
} 