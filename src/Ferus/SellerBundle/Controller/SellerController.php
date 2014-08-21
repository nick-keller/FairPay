<?php


namespace Ferus\SellerBundle\Controller;

use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;
use Doctrine\ORM\EntityManager;
use Ferus\SellerBundle\Form\SellType;
use Ferus\TransactionBundle\Entity\Transaction;
use Ferus\UserBundle\Entity\User;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SellerController extends Controller
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
    public function cashRegisterAction(User $user, Request $request)
    {
        if($user->getAccount()->getSeller() == null) return $this->createNotFoundException();

        $transaction = new Transaction;
        $transaction->setReceiver($user->getAccount());
        $transaction->setCause('Stand '.$user->getAccount()->getSeller());

        $form = $this->createForm(new SellType, $transaction);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
        }

        return array(
            'seller' => $user->getAccount()->getSeller(),
            'form' => $form->createView(),
        );
    }
} 