<?php
namespace Ferus\StudentBundle\Controller;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\Get;

class ApiController extends Controller
{
    /**
     * @var EntityManager
     */
    private $em;

    public function getStudentAction($query){

        $students = $this->em->getRepository('FerusStudentBundle:Student')->search($query);

        if(count($students) == 0){
            return array(
                'error' => array(
                    'message' => 'Aucun résultat pour cette requête.',
                    'code' => 404
                )
            );
        }

        if(count($students) != 1){
            return array(
                'error' => array(
                    'message' => 'Cette requête ne correspond pas à un résultat unique.',
                    'code' => 400
                )
            );
        }

        return $students[0];
    }

    /**
     * @Get()
     */
    public function searchStudentsAction($query){

        $students = $this->em->getRepository('FerusStudentBundle:Student')->search($query);
        return $students;
    }

    public function getStudentsAction()
    {
        $students = $this->em->getRepository('FerusStudentBundle:Student')->findAll();
        return $students;
    }
} 