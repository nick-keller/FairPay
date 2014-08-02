<?php

namespace Ferus\StudentBundle\Controller;


use Doctrine\ORM\EntityManager;
use Ferus\StudentBundle\Entity\Student;
use Ferus\StudentBundle\Form\StudentType;
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
     * @Template
     */
    public function indexAction()
    {
        return array();
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