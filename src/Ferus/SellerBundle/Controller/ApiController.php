<?php
namespace Ferus\SellerBundle\Controller;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Ferus\SellerBundle\Entity\Api\Cash;
use Ferus\SellerBundle\Entity\Api\Deposit;
use Ferus\SellerBundle\Form\CashType;
use Ferus\SellerBundle\Form\DepositType;
use Ferus\TransactionBundle\Entity\Transaction;
use Ferus\TransactionBundle\Form\TransactionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\Get;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\Post;

class ApiController extends Controller
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Effectue une transaction du compte client (identifié par <i>client_id</i>) vers le compte marchand (identifié par <i>api_key</i>).
     *
     * @ApiDoc(
     *      section="Marchands",
     *      requirements={
     *          {
     *              "name"="api_key",
     *              "dataType"="string",
     *              "requirement"="api_\d+",
     *              "description"="Votre clef privée"
     *          },
     *          {
     *              "name"="client_id",
     *              "dataType"="integer",
     *              "description"="Numéros figurant sur la carte étudiante"
     *          },
     *          {
     *              "name"="amount",
     *              "dataType"="float",
     *              "description"="Montant de la transaction"
     *          },
     *          {
     *              "name"="cause",
     *              "dataType"="string",
     *              "description"="Motif de la transaction"
     *          }
     *      },
     *      description="Encaisser un paiement"
     * )
     * @Post()
     */
    public function cashAction(Request $request)
    {
        $cash = new Cash;
        $form = $this->createForm(new CashType, $cash);
        $form->handleRequest($request);

        if($form->isValid()){
            $transaction = $this->get('ferus_transaction.transaction_core');
            $transaction->cash($cash);

            throw new HttpException(200, 'Transaction effectuée.');
        }

        return $form;
    }

    /**
     * Effectue un dépôt d'argent sur le compte d'un étudiant (client_id).
     *
     * L'argent est prélevé sur le compte marchand (api_key) et transféré sur le compte de l'étudiant.
     * C'est donc au marchand de vérifier qu'il récupère bien le cash, car dans le cas contraire il aura perdu de l'argent.
     *
     * Cette opération permet donc aux étudiants d'alimenter leur compte chez tous les marchands,
     * et permet aux marchands de faire sortir du cash sans passer par le BDE.
     *
     * @ApiDoc(
     *      section="Marchands",
     *      requirements={
     *          {
     *              "name"="api_key",
     *              "dataType"="string",
     *              "requirement"="api_\d+",
     *              "description"="Votre clef privée"
     *          },
     *          {
     *              "name"="client_id",
     *              "dataType"="integer",
     *              "description"="Numéros figurant sur la carte étudiante"
     *          },
     *          {
     *              "name"="amount",
     *              "dataType"="float",
     *              "description"="Montant de la transaction"
     *          }
     *      },
     *      description="Effectuer un dépôt chez le marchand"
     * )
     * @Post()
     */
    public function depositAction(Request $request)
    {
        $deposit = new Deposit;
        $form = $this->createForm(new DepositType, $deposit);
        $form->handleRequest($request);

        if($form->isValid()){
            $transaction = $this->get('ferus_transaction.transaction_core');
            $transaction->sellerDeposit($deposit);

            throw new HttpException(200, 'Dépôt effectuée.');
        }

        return $form;
    }

    /**
     *
     * @ApiDoc(
     *      section="Marchands",
     *      requirements={
     *          {
     *              "name"="api_key",
     *              "dataType"="string",
     *              "requirement"="api_\d+",
     *              "description"="Votre clef privée"
     *          }
     *      },
     *      description="Obtenir le solde"
     * )
     */
    public function getBalanceAction(Request $request)
    {
        if(!$request->query->has('api_key'))
            throw new HttpException(400, 'Clef privée non valide.');

        try{
            $account = $this->em->getRepository('FerusAccountBundle:Account')
                ->findOneBySellerApiKey($request->query->get('api_key'));
        }
        catch(NoResultException $e){
            throw new HttpException(400, 'Clef privée non valide.');
        }

        return array(
            'code' => 200,
            'balance' => $account->getBalance(),
        );
    }
} 