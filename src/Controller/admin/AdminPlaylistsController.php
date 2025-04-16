<?php

namespace App\Controller\admin;

use App\Controller\Form\PlaylistType;
use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Gère les routes de la page d'administration des playlists
 *
 * 
 * 
 */
class AdminPlaylistsController extends AbstractController {
    const PAGES_PLAYLISTS = "admin/admin.playlists.html.twig";
    /**
     * $var FormationRepository
     */
    private $formationRepository;
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;
    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     * Création du constructeur
     * @param FormationRepository $formationRepository
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
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
     * @Route("/admin/playlists", name="admin.playlists")
     * @return Response
     */
    #[Route("/admin/playlists", name:"admin.playlists")]
     public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGES_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,            
        ]);
    }
    
    /**
     * Suppression d'une playlist
     * @Route("/admin/playlist/suppr/{id}", name="admin.playlist.suppr")
     * @param Playlist $playlist
     * @return Response
     */
    #[Route("/admin/playlist/suppr/{id}", name:"admin.playlist.suppr")]
     public function suppr(Playlist $playlist): Response{
        $this -> playlistRepository -> remove($playlist , true);
        return  $this -> redirectToRoute('admin.playlists');
    }
    
    /**
     * Edition d'une playlist
     * @Route("/admin/playlist/edit/{id}", name="admin.playlist.edit")
     * @param Request $request
     * @returns Response
     * 
     */
    #[Route("/admin/playlist/edit/{id}", name:"admin.playlist.edit")]
     public function edit(Playlist $id, Request $request): Response{
        $formPlaylist = $this->createForm(PlaylistType::class, $id);
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->playlistRepository->add($id, true);
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render("admin/admin.playlist.edit.html.twig", [
            'playlist' => $id,
            'formplaylist' => $formPlaylist->createView()
        ])
        ;
    }

    /**
     * Ajout d'une playlist
     * @Route("/admin/playlist/ajout", name="admin.playlist.ajout")
     * @param Request $request
     * @return Response
     */
    #[Route("/admin/playlist/ajout", name:"admin.playlist.ajout")]
    public function ajout(Request $request): Response{
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute('admin.playlists');
        }
        return $this -> render("admin/admin.playlist.ajout.html.twig", [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
    /**
     * Trie les playlists par nom ou par nombre de formations
     * 
     * @Route("/admin/playlists/tri/{champ}/{ordre}", name="admin.playlists.sort")
     * @param string $champ  Le champ à trier
     * @param string $ordre  L'ordre de tri
     * @return Response
     */
    #[Route("/admin/playlists/tri/{champ}/{ordre}", name:"admin.playlists.sort")]
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
        return $this->render(self::PAGES_PLAYLISTS, [

            'playlists' => $playlists,
            'categories' => $categories,
        ]);
    }

    /**
     * Récupère les enregistrements selon $champ $valeur
     * Et selon le $champ et la $valeur si autre $table
     * @Route("/admin/playlists/recherche/{champ}/{table}", name="admin.playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route("/admin/playlists/recherche/{champ}/{table}", name:"admin.playlists.findallcontain")]
    public function findAllContain($champ, Request $request, $table= ""): Response{
        $valeur = $request->get("recherche");
        if ($table != ""){
            $playlists = $this->playlistRepository->findByContainValueTable($champ,$valeur,$table);
        }else{
            $playlists = $this->playlistRepository->findByContainValue($champ,$valeur);
        }
            $categories = $this->categorieRepository->findAll();
            return $this->render(self::PAGES_PLAYLISTS, [
               'playlists' => $playlists,
               'categories' => $categories,
               'valeur' => $valeur,
               'table' => $table,
            ]);
    }
     /**
     * @Route("/playlist/edit/{id}", name="admin.playlists.showone")
     * @param type $id
     * @return Response
     */
    #[Route("/playlist/edit/{id}", name:"admin.playlists.showone")]
    public function showOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("admin.playlist.edit.html.twig", [
            'playlist' => $playlist,
            'playlistformations' => $playlistFormations,
        ]);
    }
}