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
    private $mailer1;

    /**
     * @var \Swift_Mailer
     */
    private $mailer2;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @InjectParams({
     *     "entityManager" = @Inject("doctrine.orm.entity_manager"),
     *     "mailer1" = @Inject("swiftmailer.mailer.aws1"),
     *     "mailer2" = @Inject("swiftmailer.mailer.aws2"),
     *     "twig" = @Inject("twig")
     * })
     * @param EntityManager $entityManager
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(EntityManager $entityManager, \Swift_Mailer $mailer1, \Swift_Mailer $mailer2, \Twig_Environment $twig)
    {
        $this->em      = $entityManager;
        $this->mailer1 = $mailer1;
        $this->mailer2 = $mailer2;
        $this->twig    = $twig;
        $this->mailer1->registerPlugin(new \Swift_Plugins_ThrottlerPlugin( 300, \Swift_Plugins_ThrottlerPlugin::MESSAGES_PER_MINUTE ));
        $this->mailer2->registerPlugin(new \Swift_Plugins_ThrottlerPlugin( 300, \Swift_Plugins_ThrottlerPlugin::MESSAGES_PER_MINUTE ));
    }

    public function sendRegistrationEmail($event_id)
    {
        $event = $this->em->getRepository("FerusFCFSBundle:Event")->find($event_id);

        if (null === $event) {
            echo "Event non trouvé\n";
            return false;
        }

        $students = $this->em->getRepository("FerusStudentBundle:Student")->findAll();
        $i = 0;

        echo "envoie en cours\n";

        foreach($students as $student)
        {
            $token = chr(97+rand(0, 25)).rand(0, 9).chr(97+rand(0, 25)).$student->getId().chr(97+rand(0, 25)).$student->getHash().chr(97+rand(0, 25)).$event->getId().chr(97+rand(0, 25));

            echo $i." ".$student->getFirstName().' '.$student->getLastName().' '.$token."\n";

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

            if ($i%2 == 0)
                $response = $this->mailer1->send($message);
            else
                $response = $this->mailer2->send($message);

            if (!$response)
            {
                echo "Une erreur est survenue pour ".$student->getId()." ".$student->getFirstName()." ".$student->getLastName()."\n";
            }

            // On est limité à 5 mails par seconde
            //usleep(100000);
            $i++;
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
        $i = 0;

        echo "envoie en cours\n";

        foreach($students as $student)
        {
            echo $i." ".$student->getFirstName().' '.$student->getLastName()."\n";

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

            if ($i%2 == 0)
                $response = $this->mailer1->send($message);
            else
                $response = $this->mailer2->send($message);

            if (!$response)
            {
                echo "Une erreur est survenue pour ".$student->getId()." ".$student->getFirstName()." ".$student->getLastName()."\n";
            }

            // On est limité à 5 mails par seconde
            //usleep(100000);
            $i++;
        }

        return true;
    }
} 