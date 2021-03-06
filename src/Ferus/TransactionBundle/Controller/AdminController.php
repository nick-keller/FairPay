<?php

namespace Ferus\TransactionBundle\Controller;

use Ferus\TransactionBundle\Entity\Deposit;
use Ferus\TransactionBundle\Entity\Transaction;
use Ferus\TransactionBundle\Entity\Withdrawal;
use Ferus\TransactionBundle\Form\DepositType;
use Ferus\TransactionBundle\Form\TransactionType;
use Ferus\TransactionBundle\Form\WithdrawalType;
use Ferus\TransactionBundle\Transaction\Exception\InsufficientBalanceException;
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
        $this->em->getFilters()->disable('softdeleteable');
        $transactions = $this->em->getRepository('FerusTransactionBundle:Transaction')->findLast(50);

        return array(
            'transactions' => $transactions,
        );
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

            if($form->isValid()){
                $transactionCore = $this->get('ferus_transaction.transaction_core');
                $transactionCore->execute($transaction);

                $this->flash->success('Transaction effectuée.');

                return $this->redirect($this->generateUrl('transaction_admin_index'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Template
     */
    public function depositAction(Request $request)
    {
        $deposit = new Deposit;

        if($request->query->has('account'))
            $deposit->setAccount(
                $this->em->getRepository('FerusAccountBundle:Account')
                    ->findOneById($request->query->get('account'))
            );

        $form = $this->createForm(new DepositType, $deposit);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){

                $transactionCore = $this->get('ferus_transaction.transaction_core');
                $transactionCore->deposit($deposit);

                $this->flash->success('Dépot effectuée.');

                return $this->redirect($this->generateUrl('transaction_admin_index'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Template
     */
    public function withdrawalAction(Request $request)
    {
        $withdrawal = new Withdrawal;

        if($request->query->has('account'))
            $withdrawal->setAccount(
                $this->em->getRepository('FerusAccountBundle:Account')
                    ->findOneById($request->query->get('account'))
            );

        $form = $this->createForm(new WithdrawalType, $withdrawal);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){

                $transactionCore = $this->get('ferus_transaction.transaction_core');
                $transactionCore->withdrawal($withdrawal);

                $this->flash->success('Retrait effectuée.');

                return $this->redirect($this->generateUrl('transaction_admin_index'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

} 