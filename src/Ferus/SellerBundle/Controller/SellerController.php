<?php


namespace Ferus\SellerBundle\Controller;

use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;
use Doctrine\ORM\EntityManager;
use Ferus\SellerBundle\Entity\Product;
use Ferus\SellerBundle\Entity\Seller;
use Ferus\SellerBundle\Entity\Store;
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

    public function removeStoreAction(Store $store)
    {
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

    public function removeProductAction(Product $product)
    {
        $this->em->remove($product);
        $this->em->flush();

        return new Response('OK');
    }
} 