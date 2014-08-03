<?php

namespace Ferus\AccountBundle\Controller;

use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;
use Ferus\AccountBundle\Entity\Account;
use Ferus\AccountBundle\Form\AccountType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
    public function indexAction()
    {
        return array();
    }

    /**
     * @Template
     */
    public function addAction(Request $request)
    {
        $account = new Account;
        $form = $this->createForm(new AccountType, $account);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->em->persist($account);
                $this->em->flush();

                $this->flash->success('Compte créé.');
                return $this->redirect($this->generateUrl('account_admin_index'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
