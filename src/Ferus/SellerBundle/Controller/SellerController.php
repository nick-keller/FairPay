<?php


namespace Ferus\SellerBundle\Controller;

use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;
use Doctrine\ORM\EntityManager;
use Ferus\SellerBundle\Entity\Product;
use Ferus\SellerBundle\Entity\ProductsSelection;
use Ferus\SellerBundle\Entity\Seller;
use Ferus\SellerBundle\Entity\Store;
use Ferus\SellerBundle\Form\ProductsSelectionType;
use Ferus\SellerBundle\Form\SellType;
use Ferus\TransactionBundle\Entity\Transaction;
use Ferus\UserBundle\Entity\User;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;

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

        if($user->getAccount()->getSeller()->getCashRegisterExpiresAt() < new \DateTime)
            return array(
                'seller' => $user->getAccount()->getSeller(),
                'expired' => true,
            );

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

    /**
     * @Template
     * @Secure(roles="ROLE_SELLER")
     */
    public function dashboardAction(Request $request)
    {
        $seller = $this->getUser()->getAccount()->getSeller();

        if(preg_match('/^((15|30) min|[1-3] hours?)$/', $request->query->get('add_time', ''))){
            if($seller->getCashRegisterExpiresAt() > new \DateTime)
                $newDate = $seller->getCashRegisterExpiresAt()->modify('+'.$request->query->get('add_time'));
            else
                $newDate = new \DateTime('+'.$request->query->get('add_time'));

            $seller->setCashRegisterExpiresAt($newDate);

            $this->em->persist($seller);
            $this->em->flush();
        }

        return array(
            'seller' => $seller,
        );
    }

    /**
     * @Template
     * @Secure(roles="ROLE_SELLER")
     */
    public function newStoreAction(Request $request)
    {
        $store = new Store($request->query->get('name'));
        $store->setSeller($this->getUser()->getAccount()->getSeller());

        $this->em->persist($store);
        $this->em->flush();

        return array(
            'store' => $store,
        );
    }

    /**
     * @Template
     */
    public function storeAction(Store $store, Request $request)
    {
        $selection = new ProductsSelection;
        $selection->setSeller($store->getSeller()->getAccount());
        $form = $this->createForm(new ProductsSelectionType, $selection);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $transactionCore = $this->get('ferus_transaction.transaction_core');
                $transactionCore->cashProductsSelection($selection);

                $this->flash->success('Paiement effectué. Il reste '.$selection->getClient()->getBalance().' € sur le compte de '.$selection->getClient()->getOwner().'.');

                return $this->redirect($this->generateUrl('seller_store', array('id'=>$store->getId())));
            }
        }

        return array(
            'store' => $store,
            'form' => $form->createView(),
        );
    }

    /**
     * @Secure(roles="ROLE_SELLER")
     */
    public function removeStoreAction(Store $store)
    {
        if($store->getSeller()->getId() != $this->getUser()->getAccount()->getSeller()->getId())
            $this->createAccessDeniedException();

        $this->em->remove($store);
        $this->em->flush();

        return new Response('OK');
    }

    /**
     * @Template
     * @Secure(roles="ROLE_SELLER")
     */
    public function newProductAction(Store $store)
    {
        if($store->getSeller()->getId() != $this->getUser()->getAccount()->getSeller()->getId())
            $this->createAccessDeniedException();

        $product = new Product;
        $product->setStore($store);

        $this->em->persist($product);
        $this->em->flush();

        return array(
            'product' => $product,
        );
    }

    /**
     * @Secure(roles="ROLE_SELLER")
     */
    public function removeProductAction(Product $product)
    {
        if($product->getStore()->getSeller()->getId() != $this->getUser()->getAccount()->getSeller()->getId())
            $this->createAccessDeniedException();

        $this->em->remove($product);
        $this->em->flush();

        return new Response('OK');
    }

    /**
     * @Secure(roles="ROLE_SELLER")
     */
    public function editProductAction(Product $product, Request $request)
    {
        if($product->getStore()->getSeller()->getId() != $this->getUser()->getAccount()->getSeller()->getId())
            $this->createAccessDeniedException();

        $product->setName($request->query->get('name', null));
        $product->setPrice($request->query->get('price', null));

        $err = $this->get('validator')->validate($product);

        if(!count($err)){
            $this->em->persist($product);
            $this->em->flush();

            return new Response('OK');
        }

        return new Response('Error', 401);
    }
} 