<?php

namespace Ferus\UserBundle\Controller;

use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;
use Ferus\UserBundle\Entity\User;
use Ferus\UserBundle\Form\UserType;
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
            'search' => $request->query->get('search', null),
        );
    }

    /**
     * @Template
     */
    public function editAction(User $user, Request $request)
    {
        $form = $this->createForm(new UserType, $user);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->em->persist($user);
                $this->em->flush();

                $this->flash->success('Administrateur modifié.');
                return $this->redirect($this->generateUrl('user_admin_index'));
            }
        }

        return array(
            'form' => $form->createView(),
            'user' => $user,
        );
    }
}
