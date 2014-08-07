<?php
namespace Ferus\SellerBundle\Controller;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Ferus\SellerBundle\Entity\Api\Cash;
use Ferus\SellerBundle\Form\CashType;
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
     * Effectu une transaction du compte client (identifié par <i>client_id</i>) vers le compte marchand (identifié par <i>api_key</i>).
     *
     * @ApiDoc(
     *      section="Paiement",
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
     * Effectu un dépot d'argent sur le compte d'un étudiant (client_id).
     *
     * L'argent est prélevé sur le compte marchand (api_key) et transféré sur le compte de l'étudiant.
     * C'est donc au marchand de vérifier qu'il récupère bien le cash, car dans le cas contraire il aura perdu de l'argent.
     *
     * Cette opération permet donc au étudiants d'alimenter leur compte chez tout les marchands,
     * et permet aux marchand de faire sortir du cash sans passer par le BDE.
     *
     * @ApiDoc(
     *      section="Paiement",
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
     *      description="Encaisser un paiement"
     * )
     * @Post()
     */
    public function depositAction(Request $request)
    {

    }
} 