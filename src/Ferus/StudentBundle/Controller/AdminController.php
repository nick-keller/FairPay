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
    public function indexAction(Request $request)
    {
        $students = $this->paginator->paginate(
            $this->em
                ->getRepository('FerusStudentBundle:Student')
                ->querySearch($request->query->get('search', null)),
            $request->query->get('page', 1),
            50
        );

        return array(
            'students' => $students,
            'search' => $request->query->get('search', '')
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

    /**
     * @Template
     */
    public function editAction(Student $student, Request $request)
    {
        $form = $this->createForm(new StudentType, $student);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->em->persist($student);
                $this->em->flush();

                $flash = $this->get('braincrafted_bootstrap.flash');
                $flash->success('Eleve mis à jour.');

                return $this->redirect($this->generateUrl('student_admin_edit', ['id' => $student->getId()]));
            }
        }

        return array(
            'student' => $student,
            'form' => $form->createView(),
        );
    }

    /**
     * @Template
     */
    public function removeAction(Student $student, Request $request)
    {
        if($request->isMethod('POST')){
            $this->em->remove($student);
            $this->em->flush();

            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Eleve supprimé.');

            return $this->redirect($this->generateUrl('student_admin_index'));
        }

        return array(
            'student' => $student,
        );
    }

    /**
     * @Template
     */
    public function contributorsAction()
    {
        return array();
    }

    /**
     * @Template
     */
    public function searchContributorsAction(Request $request)
    {
        $students = $this->em
            ->getRepository('FerusStudentBundle:Student')
            ->search($request->request->get('search', null));

        return array(
            'students' => $students,
        );
    }

    public function saveContributorAction(Student $student, $value)
    {
        $student->setIsContributor($value);
        $this->em->persist($student);
        $this->em->flush();

        return new Response('Ok', 200);
    }
} 