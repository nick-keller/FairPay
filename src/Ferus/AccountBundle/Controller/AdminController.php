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
    public function indexAction(Request $request)
    {
        if($request->query->has('search'))
        {
            $search = $request->query->get('search');

            // if search is an id we go directly to the view page
            if(preg_match('/^[0-9]+$/', $search))
                return $this->redirect($this->generateUrl('account_admin_view', array('student' => $search)));

            $query = $this->em
                ->getRepository('FerusAccountBundle:Account')
                ->querySearch($search);
        }
        else {
            $query = $this->em
                ->getRepository('FerusAccountBundle:Account')
                ->queryAll();
        }

        $accounts = $this->paginator->paginate(
            $query,
            $request->query->get('page', 1),
            50
        );

        return array(
            'accounts' => $accounts,
            'search' => $request->query->get('search', null),
        );
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

    /**
     * @Template
     */
    public function viewAction(Account $account, Request $request)
    {
        $transactions = $this->paginator->paginate(
            $this->em
                ->getRepository('FerusTransactionBundle:Transaction')
                ->queryOfAccount($account),
            $request->query->get('page', 1),
            50
        );

        return array(
            'account' => $account,
            'transactions' => $transactions,
        );
    }
}
