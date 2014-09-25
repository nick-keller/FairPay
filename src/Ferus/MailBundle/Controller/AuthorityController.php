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

class AuthorityController  extends Controller
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
        $authorities = $this->em->getRepository('FerusMailBundle:Authority')->findAll();

        return array(
            'authorities' => $authorities,
        );
    }

    /**
     * @Template
     */
    public function addAction(Request $request)
    {
        $authority = new Authority;
        $form = $this->createForm(new AuthorityType, $authority);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->em->persist($authority);
                $this->em->flush();

                $this->flash->success('Autorité créé.');
                return $this->redirect($this->generateUrl('authority_admin_index'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Template
     */
    public function editAction(Authority $authority, Request $request)
    {
        $form = $this->createForm(new AuthorityType, $authority);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->em->persist($authority);
                $this->em->flush();

                $this->flash->success('Autorité modifiée.');
                return $this->redirect($this->generateUrl('authority_admin_index'));
            }
        }

        return array(
            'authority' => $authority,
            'form' => $form->createView(),
        );
    }

    /**
     * @Template
     */
    public function removeAction(Authority $authority, Request $request)
    {
        if($request->isMethod('POST')){

            $this->em->remove($authority);
            $this->em->flush();

            $this->flash->success('Autorité supprimée.');

            return $this->redirect($this->generateUrl('authority_admin_index'));
        }

        return array(
            'authority' => $authority,
        );
    }
} 