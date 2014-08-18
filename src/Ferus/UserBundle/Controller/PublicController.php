<?php

namespace Ferus\UserBundle\Controller;

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

} 