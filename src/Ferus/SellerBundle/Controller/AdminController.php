<?php


namespace Ferus\SellerBundle\Controller;

use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;
use Doctrine\ORM\EntityManager;
use Ferus\SellerBundle\Entity\Seller;
use Ferus\SellerBundle\Form\SellerType;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $sellers = $this->paginator->paginate(
            $this->em
                ->getRepository('FerusSellerBundle:Seller')
                ->queryAll(),
            $request->query->get('page', 1),
            50
        );

        return array(
            'sellers' => $sellers,
        );
    }

    /**
     * @Template
     */
    public function addAction(Request $request)
    {
        $seller = new Seller();
        $form = $this->createForm(new SellerType, $seller);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){

                // We check that there is no softdeleted student for this id
                $this->em->getFilters()->disable('softdeleteable');
                $deleted = $this->em->getRepository('FerusSellerBundle:Seller')
                    ->findSoftDeleted($seller);

                if($deleted !== null){
                    $deleted->setEmail($seller->getEmail());
                    $deleted->setDeletedAt(null);
                    $seller = $deleted;
                }

                $this->em->persist($seller);
                $this->em->flush();

                $this->flash->success('Marchand créé.');
                return $this->redirect($this->generateUrl('seller_admin_index'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Template
     */
    public function editAction(Seller $seller, Request $request)
    {
        $form = $this->createForm(new SellerType, $seller);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->em->persist($seller);
                $this->em->flush();

                $this->flash->success('Marchand mis à jour.');

                return $this->redirect($this->generateUrl('seller_admin_edit', ['id' => $seller->getId()]));
            }
        }

        return array(
            'seller' => $seller,
            'form' => $form->createView(),
        );
    }

    /**
     * @Template
     */
    public function removeAction(Seller $seller, Request $request)
    {
        if($request->isMethod('POST') && ($seller->getAccount() === null || $seller->getAccount()->getDeletedAt() !== null)){

            $this->em->getFilters()->disable('softdeleteable');

            if($this->em->getRepository('FerusAccountBundle:Account')->findSoftDeleted($seller) !== null){
                $this->em->remove($seller);
                $this->em->flush();
            }
            else{
                $this->em->getRepository('FerusSellerBundle:Seller')->remove($seller);
            }

            $this->flash->success('Marchand supprimé.');

            return $this->redirect($this->generateUrl('seller_admin_index'));
        }

        return array(
            'student' => $seller,
        );
    }

    /**
     * @Template
     */
    public function storesAction()
    {
        return array(
            'stores' => $this->em->getRepository('FerusSellerBundle:Store')->findAll(),
        );
    }
} 