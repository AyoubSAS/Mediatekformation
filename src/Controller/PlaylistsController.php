<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use const PLAYLISTSPATH;

define("PLAYLISTSPATH", "pages/playlists.html.twig");

/**
 * Gère les routes de la page des playlists
 *
 * 
 */

class PlaylistsController extends AbstractController {
    
    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;    
    
    /**
     * Création du constructeur
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRepository
     */
    
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }
    
    /**
     * Création de la route vers la page des playlists
     * @Route("/playlists", name="playlists")
     * @return Response
     */
    #[Route("/playlists", name:"playlists")]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }

    /**
     * Tri les enregistrements selon le $champ "name" et l'ordre
     * Ou selon le $champ "nbformations" et l'ordre
     * @Route("/playlists/tri/{champ}/{ordre}", name="playlists.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    #[Route("/playlists/tri/{champ}/{ordre}", name:"playlists.sort")]
    public function sort($champ, $ordre): Response{
        switch($champ){
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nbformations":
                $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(PLAYLISTSPATH, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }         
    
    /**
     * Récupère les enregistrements selon le $champ et la $valeur
     * Et selon le $champ et la $valeur si autre $table
     * @Route("/playlists/recherche/{champ}/{table}", name="playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route("/playlists/recherche/{champ}/{table}", name:"playlists.findallcontain")]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        if ($table != ""){
            $playlists = $this->playlistRepository->findByContainValueTable($champ,$valeur,$table);
        }else{
            $playlists = $this->playlistRepository->findByContainValue($champ,$valeur);
        }
            $categories = $this->categorieRepository->findAll();
            return $this->render(PLAYLISTSPATH, [
               'playlists' => $playlists,
               'categories' => $categories,            
               'valeur' => $valeur,
               'table' => $table
            ]);  
    }  
    
    /**
     * Récupère les enregistrements des playlists individuelles
     * @Route("/playlists/playlist/{id}", name="playlists.showone")
     * @param type $id
     * @return Response
     */
    #[Route("/playlists/playlist/{id}", name:"playlists.showone")]
    public function showOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/playlist.html.twig", [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);        
    }       
    
}