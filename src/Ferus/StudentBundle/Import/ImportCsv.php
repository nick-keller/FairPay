<?php


namespace Ferus\StudentBundle\Import;

use Doctrine\ORM\EntityManager;
use Ferus\StudentBundle\Entity\Student;

class ImportCsv 
{
    /**
     * @var EntityManager
     */
    private $em;

    private $success = 0;
    private $error = 0;

    function __construct($em)
    {
        $this->em = $em;
    }

    public function import($csv)
    {
        $csv = preg_replace('#[\n\r]+#', '#', $csv);

        $lines = explode('#', $csv);

        foreach($lines as $line){
            $info = explode(';', $line);
            $student = new Student();
            $student->setId($info[0]);
            $student->setLastName($info[1]);
            $student->setFirstName($info[2]);
            $student->setIsContributor(false);

            if($this->em->getRepository('FerusStudentBundle:Student')->isIdAvailable($student->getId())){
                $this->em->persist($student);
                $this->em->flush();

                $this->success++;
            }
            else
                $this->error++;
        }

        return $this->success . ' étudiants créés et '.$this->error.' erreur.';
    }
} 