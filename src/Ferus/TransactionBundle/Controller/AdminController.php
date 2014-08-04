<?php

namespace Ferus\TransactionBundle\Controller;

use Ferus\TransactionBundle\Entity\Transaction;
use Ferus\TransactionBundle\Form\TransactionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;

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
    public function newAction(Request $request)
    {
        $transaction = new Transaction;
        $form = $this->createForm(new TransactionType, $transaction);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
        }

        return array(
            'form' => $form->createView(),
        );
    }

} 