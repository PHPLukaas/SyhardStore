<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur principal de l'application.
 */
class MainController extends AbstractController
{
    /**
     * Affiche la page d'accueil.
     *
     * @param CategoriesRepository $categoriesRepository Le référentiel (repository) des catégories
     * @param ProductsRepository   $productsRepository   Le référentiel (repository) des produits
     *
     * @return Response La réponse HTTP
     *
     * @Route("/", name="app_main")
     */
    #[Route('/', name: 'app_main')]
    public function index(CategoriesRepository $categoriesRepository, ProductsRepository $productsRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'categories' => $categoriesRepository->findBy([],
                ['categoryOrder' => 'ASC']),
            'lastProducts' => $productsRepository->findLatest(),
        ]);
    }
}
