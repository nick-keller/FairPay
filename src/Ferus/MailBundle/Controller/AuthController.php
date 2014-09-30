<?php

namespace Ferus\MailBundle\Controller;

use Ferus\MailBundle\Entity\Auth;
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

    /**
     * @Template
     */
    public function addAction(\Ferus\MailBundle\Entity\Template $template, Request $request)
    {
        $auth = new Auth();
        $auth->setTemplate($template);

        $form = $this->get('ferus_mail.auth_fields_form_factory')->createFromTemplate($template);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->get('ferus_mail.auth_manager')->sendAuth($auth, $form->getData());
            }
        }

        return array(
            'template' => $template,
            'form' => $form->createView(),
        );
    }
} 