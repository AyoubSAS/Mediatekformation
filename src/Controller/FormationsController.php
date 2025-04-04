<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des formations
 *
 * 
 */
class FormationsController extends AbstractController {
    const PAGES_FORMATIONS = "pages/formations.html.twig";
    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * Création du constructeur
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    /**
     * Création de la route vers la page des formations
     * @Route("/formations", name="formations")
     * @return Response
     */
    #[Route("/formations", name:"formations")]
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGES_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Tri les enregistrements selon le $champ et l'ordre
     * Et sur le $champ et l'ordre si autre $table
     * @Route("/formations/tri/{champ}/{ordre}/{table}", name="formations.sort")
     * @param string $champ Le champ de tri (ex : 'title', 'publishedAt')
     * @param string $ordre L'ordre de tri (ex : 'ASC', 'DESC')
     * @param type $table
     * @return Response
     */
    #[Route("/formations/tri/{champ}/{ordre}/{table}", name:"formations.sort")]
    public function sort($champ, $ordre, $table=""): Response{
        if ($table != ""){
            $formations = $this->formationRepository->findAllByTable($champ, $ordre, $table);
        }else{
            $formations = $this->formationRepository->findAllOrderBy($champ, $ordre);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGES_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }     
    
    /**
     * Récupère les enregistrements selon le $champ et la $valeur
     * Et selon le $champ et la $valeur si autre $table
     * @Route("/formations/recherche/{champ}/{table}", name="formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route("/formations/recherche/{champ}/{table}", name:"formations.findallcontain")]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        if ($table != ""){
            $formations = $this->formationRepository->findByContainValueTable($champ, $valeur, $table);
        }else{
            $formations = $this->formationRepository->findByContainValue($champ, $valeur);
        }
        $categories = $this->categorieRepository->findAll();
        
        return $this->render(self::PAGES_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  
    
    /**
     * Récupère les enregistrements des formations individuelles
     * @Route("/formations/formation/{id}", name="formations.showone")
     * @param type $id
     * @return Response
     */
    #[Route("/formations/formation/{id}", name:"formations.showone")]
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/formation.html.twig", [
            'formation' => $formation
        ]);        
    }   
    
}
