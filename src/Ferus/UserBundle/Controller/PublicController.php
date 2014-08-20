<?php

namespace Ferus\UserBundle\Controller;

use Ferus\TransactionBundle\Entity\Transaction;
use Ferus\UserBundle\Form\TransferType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;

class PublicController extends Controller
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
        $graphData = $this->em->getRepository('FerusTransactionBundle:Transaction')->getGraphData($this->getUser()->getAccount());
        $graphData->computeData($this->getUser()->getAccount()->getBalance());

        return array(
            'graphData' => $graphData,
        );
    }

    /**
     * @Template
     */
    public function statementAction(Request $request)
    {
        $this->em->getFilters()->disable('softdeleteable');

        $transactions = $this->paginator->paginate(
            $this->em
                ->getRepository('FerusTransactionBundle:Transaction')
                ->queryOfAccount($this->getUser()->getAccount()),
            $request->query->get('page', 1),
            50
        );

        return array(
            'transactions' => $transactions,
            'account' => $this->getUser()->getAccount(),
        );
    }

    /**
     * @Template
     */
    public function transferAction(Request $request)
    {
        $transaction = new Transaction;
        $transaction->setIssuer($this->getUser()->getAccount());
        $form = $this->createForm(new TransferType, $transaction);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $transactionCore = $this->get('ferus_transaction.transaction_core');
                $transactionCore->execute($transaction);

                $this->flash->success('Virement effectuée.');

                return $this->redirect($this->generateUrl('user_statement'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }
} 