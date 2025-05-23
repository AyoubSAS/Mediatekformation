<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tests fonctionnels sur le FormationsController
 *
 * @author 
 */
class FormationsControllerTest extends WebTestCase {
    
    private const FORMATIONSPATH = '/formations';
    /**
     * Tester l'acces de la page des formations
    */
    public function testAccesPage() {
        $client = static::createClient();
        $client->request('GET', self::FORMATIONSPATH);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        
    }

    /**
     * Tester le filtrage des catégories selon la valeur recherchée
     */
    public function testFiltreCategories()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/recherche/id/categories'); 
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Java'
        ]);
        //vérifie le nombre de lignes obtenues
        $this->assertCount(7, $crawler->filter('h5'));
        // vérifie si la formation correspond à la recherche
         $this->assertSelectorTextContains('h5', 'Java');
    }
    
    /**
     * Tester le tri ascendant selon le nom des playlists
     */
    public function testPlaylistsTriAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/name/ASC/playlist');
        $this->assertSelectorTextContains('th', 'formation');
        $this->assertCount(5, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Android win cours');
    }
    
    /**
     * Tester le tri ascendant selon le titre des formations
     */
    public function testFormationsTriAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/title/ASC');
        $this->assertSelectorTextContains('th', 'formation');
        $this->assertCount(5, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }
    
    /**
     * Tester le tri ascendant selon la date de publication
     */
     public function testTriDate()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'formations/tri/publishedAt/ASC');
        $this->assertSelectorTextContains('th', 'formation');
        $this->assertCount(5, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Cours UML (1 à 7 / 33) : introduction');
    }
    
    /**
     * Tester le filtrage des formations selon la valeur recherchée
     */
     public function testFiltreFormations()
    {
        $client = static::createClient();
        $client->request('GET', '/formations'); 
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'UML'
        ]);
        //vérifie le nombre de lignes obtenues
        $this->assertCount(10, $crawler->filter('h5'));
        // vérifie si la formation correspond à la recherche
         $this->assertSelectorTextContains('h5', 'UML');
    }
    
    /**
     * Tester le filtrage des playlists selon la valeur recherchée
     */
     public function testFiltrePlaylists()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/recherche/name/playlist'); 
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Eclipse'
        ]);
        //vérifie le nombre de lignes obtenues
        $this->assertCount(9, $crawler->filter('h5'));
        // vérifie si la formation correspond à la recherche
         $this->assertSelectorTextContains('h5', 'Eclipse');
    }
    
    
    /**
     * Tester le lien qui redirige l'utilisateur vers la page de détail de la formation
     */
    public function testLinkFormations() {
        $client = static::createClient();
        $client->request('GET','/formations');
        $client->clickLink("ecran de formation");
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/formation/1', $uri);
    }
}