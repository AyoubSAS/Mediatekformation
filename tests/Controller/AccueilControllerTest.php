<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;



/**
 * Tester l'accès à la page d'accueil
 */
class AccueilControllerTest extends WebTestCase {
    
    public function testAccesPage()
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', '/');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatuscode());
    }
}