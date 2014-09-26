<?php

namespace Ferus\MailBundle\Controller;

use Ferus\MailBundle\Entity\Authority;
use Ferus\MailBundle\Form\AuthorityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;
use JMS\SecurityExtraBundle\Annotation\Secure;

class AuthController  extends Controller
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
    public function indexAction()
    {
        $templates = $this->em->getRepository('FerusMailBundle:Template')->findAll();

        return array(
            'templates' => $templates,
        );
    }
} 