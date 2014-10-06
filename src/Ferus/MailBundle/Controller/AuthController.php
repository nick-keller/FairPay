<?php

namespace Ferus\MailBundle\Controller;

use Ferus\MailBundle\Entity\Auth;
use Ferus\MailBundle\Entity\Authority;
use Ferus\MailBundle\Entity\Response;
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
    public function indexAction(Request $request)
    {
        $templates = $this->em->getRepository('FerusMailBundle:Template')->findAll();

        $auths = $this->paginator->paginate(
            $this->em->getRepository('FerusMailBundle:Auth')->queryAll(),
            $request->query->get('page', 1),
            20
        );

//        $this->get('imap_wrapper')->getMessageUid(null);

        return array(
            'templates' => $templates,
            'auths' => $auths,
        );
    }

    public function fetchMailsAction()
    {
        $stats = $this->get('ferus_mail.auth_manager')->fetchMails();

        $this->flash->success($stats." nouveaux mails détectés.");
        return $this->redirect($this->generateUrl('auth_admin_index'));
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
                $this->flash->success('Demande envoyée !');

                return $this->redirect($this->generateUrl('auth_admin_index'));
            }
        }

        return array(
            'template' => $template,
            'form' => $form->createView(),
        );
    }

    /**
     * @Template
     */
    public function showAction(Auth $auth)
    {
        return array(
            'auth' => $auth,
        );
    }

    public function validateAction(Response $response)
    {
        $this->get('ferus_mail.auth_manager')->validateResponse($response);

        return $this->redirect($this->generateUrl('auth_admin_show', array('id'=>$response->getAuth()->getId())));
    }

    public function denyAction(Response $response)
    {
        $this->get('ferus_mail.auth_manager')->denyResponse($response);

        return $this->redirect($this->generateUrl('auth_admin_show', array('id'=>$response->getAuth()->getId())));
    }
} 