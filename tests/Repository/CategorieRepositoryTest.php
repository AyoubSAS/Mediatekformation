<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 *Tests d'intégration sur le CategorieRepository
 *
 * 
 */
class CategorieRepositoryTest extends KernelTestCase{
    
    /**
     * Test d'intégration sur les Catégorie
     */
    public function recupRepository(): CategorieRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);
        return $repository;
    }
    
    /**
     * Récupérer le nombre d'enregistrements contenus dans la table Catégorie
     */
    public function testNbCategories(){
        $repository = $this->recupRepository();
        $nbCategories = $repository->count([]);
        $this->assertEquals(9, $nbCategories);
    }
    
    /**
     * Création d'une instance de Catégorie avec les champs
     * @return Categorie
     */
    public function newCategorie(): Categorie{
        $categorie = (new Categorie())
                ->setName("CATEGORIE TEST");
        return $categorie;
    }
    
    /**
     * Tester la fonction d'ajout d'une catégorie
     */
    public function testAddCategorie(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $nbCategories = $repository->count([]);
        $repository->add($categorie, true);
        $this->assertEquals($nbCategories + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    /**
     * Tester la fonction de suppression d'une catégorie
     */
    public function testRemoveCategorie(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $nbCategories = $repository->count([]);
        $repository->remove($categorie, true);
        $this->assertEquals($nbCategories - 1, $repository->count([]), "erreur lors de la suppression");
    }
    
    /**
     * Tester la fonction de récupération des catégories des formations d'une playlist
     */
    public function testFindAllForOnePlaylist(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $categories = $repository->findAllForOnePlaylist(3);
        $nbCategories = count($categories);
        $this->assertEquals(2, $nbCategories);
        $this->assertEquals("POO",$categories[0]->getName());
    }
    
    /**
     * Tester la fonction de tri d'un champ dans un ordre défini
     */
    public function testFindAllOrderBy(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $categories = $repository->findAllOrderBy("name", "ASC");
        $nbCategories = count($categories);
        $this->assertEquals(10, $nbCategories);
        $this->assertEquals("Android", $categories[0]->getName());
    }
    
    
}