<?php

namespace Ferus\StudentBundle\Controller;


use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;
use Doctrine\ORM\EntityManager;
use Ferus\StudentBundle\Entity\CsvImport;
use Ferus\StudentBundle\Entity\Student;
use Ferus\StudentBundle\Form\CsvImportType;
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
     * @var FlashMessage
     */
    private $flash;

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
        $csv = new CsvImport;
        $form = $this->createForm(new StudentType, $student);
        $csvImport = $this->createForm(new CsvImportType($this->get('router')), $csv);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){

                // We check that there is no softdeleted student for this id
                $this->em->getFilters()->disable('softdeleteable');
                $deleted = $this->em->getRepository('FerusStudentBundle:Student')
                    ->findSoftDeleted($student);

                if($deleted !== null){
                    $deleted->setFirstName($student->getFirstName());
                    $deleted->setLastName($student->getLastName());
                    $deleted->setIsContributor($student->getIsContributor());
                    $deleted->setDeletedAt(null);
                    $student = $deleted;
                }

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
            'csvImport' => $csvImport->createView(),
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

                $this->flash->success('Eleve mis à jour.');

                return $this->redirect($this->generateUrl('student_admin_edit', ['id' => $student->getId()]));
            }
        }

        return array(
            'student' => $student,
            'form' => $form->createView(),
        );
    }

    public function importAction(Request $request)
    {
        $csv = new CsvImport;
        $form = $this->createForm(new CsvImportType($this->get('router')), $csv);
        $form->handleRequest($request);

        if($form->isValid()){
            $this->flash->info($this->get('import_csv')->import($csv->getCsv()));
            return $this->redirect($this->generateUrl('student_admin_add'));
        }
        else{
            $this->flash->error('Erreur dans le formulaire.');
            return $this->redirect($this->generateUrl('student_admin_add'));
        }
    }

    /**
     * @Template
     */
    public function removeAction(Student $student, Request $request)
    {
        if($request->isMethod('POST') && ($student->getAccount() === null || $student->getAccount()->getDeletedAt() !== null)){

            $this->em->getFilters()->disable('softdeleteable');

            if($this->em->getRepository('FerusAccountBundle:Account')->findSoftDeleted($student) !== null){
                $this->em->remove($student);
                $this->em->flush();
            }
            else{
                $this->em->getRepository('FerusStudentBundle:Student')->remove($student);
            }

            $this->flash->success('Eleve supprimé.');

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