<?php

namespace Ferus\UserBundle\Controller;

use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;
use Ferus\UserBundle\Entity\User;
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
//            if(preg_match('/^S?[0-9]+$/', $search))
//                return $this->redirect($this->generateUrl('account_admin_view', array(
//                    'id' => $this->em->getRepository('FerusAccountBundle:Account')
//                            ->findOneByBarcode($search)
//                            ->getId()
//                )));

            $query = $this->em
                ->getRepository('FerusUserBundle:User')
                ->querySearch($search);
        }
        else {
            $query = $this->em
                ->getRepository('FerusUserBundle:User')
                ->queryAdmins();
        }

        $users = $this->paginator->paginate(
            $query,
            $request->query->get('page', 1),
            50
        );

        return array(
            'users' => $users,
        );
    }
}
