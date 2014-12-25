<?php
namespace Ferus\StudentBundle\Controller;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\Get;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
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
     * Récupérer un étudiant.
     * Si le résultat de la requête n'est pas unique (aucun résultat ou plusieurs) une erreur est retournée.
     *
     * Il est possible d'utiliser le nom ET le prénom en les séparant par des espaces.
     * Il est possible de n'indiquer qu'une partie de la chaine. Exemple : 'nico k'
     *
     * @ApiDoc(
     *      section="Etudiants",
     *      requirements={
     *          {
     *              "name"="query",
     *              "dataType"="string|integer",
     *              "description"="Id, nom ou prénom de l'étudiant"
     *          }
     *      },
     *      description="Récupérer un étudiant"
     * )
     */
    public function getStudentAction($query){

        $students = $this->em->getRepository('FerusStudentBundle:Student')->search($query);

        if(count($students) == 0)
            $students = $this->em->getRepository('FerusSellerBundle:Seller')->search($query);

        if(count($students) == 0)
            throw new HttpException(404, 'Aucun résultat pour cette requête.');

        if(count($students) != 1)
            throw new HttpException(400, 'Cette requête ne correspond pas à un résultat unique.');

        return $students[0];
    }

    /**
     * Récupérer la photo d'un étudiant.
     * La requête peut se faire sur l'email de l'étudiant ou sur son ID.
     * Lorsque faite sur l'ID, rajouter ".jpg" à la fin permet un gain de performance.
     *
     * @Get("/students/photo/by-{type}/{typeId}", requirements={"type" = "email|id", "typeId" = "^[a-z\.-]+@edu\.esiee\.fr$|\d{7}"}, defaults={"_format" = "jpg"})
     * @ApiDoc(
     *      section="Etudiants",
     *      requirements={
     *          {
     *              "name"="type",
     *              "dataType"="email|id",
     *              "description"="Requête sur l'id ou sur l'email"
     *          },
     *          {
     *              "name"="typeId",
     *              "dataType"="^[a-z\.-]+@edu\.esiee\.fr$|\d{7}",
     *              "description"="Email ou ID de l'étudiant"
     *          }
     *      },
     *      description="Récupérer la photo d'un étudiant"
     * )
     */
    public function getStudentPhotoAction($type, $typeId)
    {
        $header_exception = array('Content-Type' => 'text/html');

        if ($type === "id")
            $student = $this->em->getRepository('FerusStudentBundle:Student')->find($typeId);
        else
            $student = $this->em->getRepository('FerusStudentBundle:Student')->findOneByEmail($typeId);

        if (null === $student)
            throw new HttpException(404, 'Aucun résultat pour cette requête.', null, $header_exception);

        $file_path = 'api/students/photo/by-id/'.$student->getId().'.jpg';
        if (!file_exists($file_path))
            throw new HttpException(404, 'La photo de cet étudiant n\'est pas disponible.', null, $header_exception);

        $response = new Response();
        $response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'max-age=31536000');
        $response->headers->set('Content-type', 'image/jpeg');
        $response->headers->set('Content-Disposition', 'inline; filename="'.$student->getEmail().'.jpg"');
        $response->headers->set('Content-length', filesize($file_path));

        // On envoie le header AVANT d'envoyer le contenu
        $response->sendHeaders();

        return $response->setContent(readfile($file_path));
    }

    /**
     * Rechercher des étudiants.
     *
     * Il est possible d'utiliser le nom ET le prénom en les séparant par des espaces.
     * Il est possible de n'indiquer qu'une partie de la chaine. Exemple : 'nico k'
     *
     * @ApiDoc(
     *      section="Etudiants",
     *      requirements={
     *          {
     *              "name"="query",
     *              "dataType"="string|integer",
     *              "description"="Id, nom ou prénom de l'étudiant"
     *          }
     *      },
     *      description="Rechercher des étudiants"
     * )
     * @Get()
     */
    public function searchStudentsAction($query){

        $students = $this->em->getRepository('FerusStudentBundle:Student')->search($query);
        return $students;
    }

    /**
     * Récupérer tous les étudiants
     *
     * @ApiDoc(
     *      section="Etudiants",
     *      requirements={
     *          {
     *              "name"="page",
     *              "dataType"="integer",
     *              "description"="Numéro de page"
     *          }
     *      }
     * )
     */
    public function getStudentsAction(Request $request)
    {
        $students = $this->paginator->paginate(
            $this->em->getRepository('FerusStudentBundle:Student')->queryAll(),
            $request->query->get('page', 1),
            21
        );

        $result = array(
            'students' => $students->getItems(),
            'current_page' => intval($students->getCurrentPageNumber()),
            'items_per_page' => $students->getItemNumberPerPage(),
            'total_count' => $students->getTotalItemCount(),
            'next_page' => $this->generateUrl($students->getRoute(), [$students->getPaginatorOptions()['pageParameterName'] => $students->getCurrentPageNumber()+1], true),
            'prev_page' => $this->generateUrl($students->getRoute(), [$students->getPaginatorOptions()['pageParameterName'] => $students->getCurrentPageNumber()-1], true),
        );

        if($request->query->get('page', 1) == 1)
            $result['prev_page'] = false;

        if($result['current_page'] * $result['items_per_page'] >= $result['total_count'])
            $result['next_page'] = false;

        return $result;
    }
} 