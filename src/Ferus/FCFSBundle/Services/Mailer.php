<?php


namespace Ferus\FCFSBundle\Services;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service()
 */
class Mailer
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @InjectParams({
     *     "entityManager" = @Inject("doctrine.orm.entity_manager"),
     *     "mailer" = @Inject("swiftmailer.mailer.aws"),
     *     "twig" = @Inject("twig")
     * })
     * @param EntityManager $entityManager
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(EntityManager $entityManager, \Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->em     = $entityManager;
        $this->mailer = $mailer;
        $this->twig   = $twig;
    }

    public function sendRegistrationEmail($event_id)
    {
        $event = $this->em->getRepository("FerusFCFSBundle:Event")->find($event_id);

        if (null === $event) {
            echo "Event non trouvé\n";
            return false;
        }

        $students = $this->em->getRepository("FerusStudentBundle:Student")->findAll();

        echo "envoie en cours\n";

        foreach($students as $student)
        {
            $token = chr(97+rand(0, 25)).rand(0, 9).chr(97+rand(0, 25)).$student->getId().chr(97+rand(0, 25)).$student->getHash().chr(97+rand(0, 25)).$event->getId().chr(97+rand(0, 25));

            echo $student->getFirstName().' '.$student->getLastName().' '.$token."\n";

            $message = \Swift_Message::newInstance()
                ->setSubject("Inscription à l'événement ".$event->getName())
                ->setFrom(array("bde@edu.esiee.fr" => "BDE"))
                ->setTo($student->getEmail())
                ->setContentType('text/html')
                ->setBody($this->twig->render('FerusFCFSBundle:Email:registration.html.twig', array(
                    'student' => $student,
                    'event'   => $event,
                    'token'   => $token
                )))
            ;

            $response = $this->mailer->send($message);
            if (!$response)
            {
                echo "Une erreur est survenue pour ".$student->getId()." ".$student->getFirstName()." ".$student->getLastName()."\n";
            }

            // On est limité à 5 mails par seconde
            usleep(200000);
        }

        return true;
    }

    public function sendWarnEmail($event_id)
    {
        $event = $this->em->getRepository("FerusFCFSBundle:Event")->find($event_id);

        if (null === $event) {
            echo "Event non trouvé\n";
            return false;
        }

        $students = $this->em->getRepository("FerusStudentBundle:Student")->findAll();

        echo "envoie en cours\n";

        foreach($students as $student)
        {
            echo $student->getFirstName().' '.$student->getLastName()."\n";

            $message = \Swift_Message::newInstance()
                ->setSubject("Ouverture prochaine des inscriptions pour l'événement ".$event->getName())
                ->setFrom(array("bde@edu.esiee.fr" => "BDE"))
                ->setTo($student->getEmail())
                ->setContentType('text/html')
                ->setBody($this->twig->render('FerusFCFSBundle:Email:warn.html.twig', array(
                    'student' => $student,
                    'event'   => $event
                )))
            ;

            $response = $this->mailer->send($message);
            if (!$response)
            {
                echo "Une erreur est survenue pour ".$student->getId()." ".$student->getFirstName()." ".$student->getLastName()."\n";
            }

            // On est limité à 5 mails par seconde
            usleep(200000);
        }

        return true;
    }
} 