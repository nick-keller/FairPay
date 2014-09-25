<?php

namespace Ferus\MailBundle\Controller;

use Ferus\MailBundle\Entity\Template;
use Ferus\MailBundle\Form\TemplateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template as TemplateAnnotation;
use JMS\SecurityExtraBundle\Annotation\Secure;

class TemplateController  extends Controller
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
     * @TemplateAnnotation
     */
    public function indexAction()
    {
        $templates = $this->em->getRepository('FerusMailBundle:Template')->findAll();

        return array(
            'templates' => $templates,
        );
    }

    /**
     * @TemplateAnnotation
     */
    public function addAction(Request $request)
    {
        $template = new Template;
        $form = $this->createForm(new TemplateType, $template);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->em->persist($template);
                $this->em->flush();

                $this->flash->success('Template créé.');
                return $this->redirect($this->generateUrl('template_admin_index'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @TemplateAnnotation
     */
    public function editAction(Template $template, Request $request)
    {
        $form = $this->createForm(new TemplateType, $template);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->em->persist($template);
                $this->em->flush();

                $this->flash->success('Template modifiée.');
                return $this->redirect($this->generateUrl('template_admin_index'));
            }
        }

        return array(
            'template' => $template,
            'form' => $form->createView(),
        );
    }

    /**
     * @TemplateAnnotation
     */
    public function removeAction(Template $template, Request $request)
    {
        if($request->isMethod('POST')){

            $this->em->remove($template);
            $this->em->flush();

            $this->flash->success('Template supprimée.');

            return $this->redirect($this->generateUrl('template_admin_index'));
        }

        return array(
            'template' => $template,
        );
    }
} 