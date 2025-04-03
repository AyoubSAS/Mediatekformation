<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    private $categorieRepository;

    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/categories', name: 'categories')]
    public function index(): Response
    {
        $categories = $this->categorieRepository->findAllWithFormationCount();

        return $this->render('pages/categories.html.twig', [
            'categories' => $categories,
        ]);
    }
}
