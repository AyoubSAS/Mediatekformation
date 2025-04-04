<?php
namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur de l'accueil
 *
 * @author 
 */
class AccueilController extends AbstractController{
      
    /**
     * @var FormationRepository
     */
    private $repository;
    
    /**
     * Création du constructeur
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository) {
        $this->repository = $repository;
    }   
    
    /**
     * Création de la route vers la page d'accueil
     * @Route("/", name="accueil")
     * @return Response
     */
    #[Route("/", name:"accueil")]
    public function index(): Response{
        $formations = $this->repository->findAllLasted(2);
        return $this->render("pages/accueil.html.twig", [
            'formations' => $formations,
        ]); 
    }
    
    /**
     * Création de la route vers les conditions générales d'utilisation
     * @Route("/cgu", name="cgu")
     * @return Response
     */
    #[Route("/cgu", name:"cgu")]
    public function cgu(): Response{
        return $this->render("pages/cgu.html.twig"); 
    }
}