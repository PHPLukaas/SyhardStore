<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur gérant les catégories.
 *
 * @Route("/categories", name="categories_")
 */
#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
    /**
     * Affiche la page d'index des catégories.
     *
     * @return Response La réponse HTTP
     *
     * @Route("/", name="index")
     */
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('categories/index.html.twig');
    }

    /**
     * Affiche la liste des produits d'une catégorie.
     *
     * @param Categories          $categories          L'entité de la catégorie
     * @param ProductsRepository $productsRepository Le référentiel (repository) des produits
     * @param Request             $request             La requête HTTP
     *
     * @return Response La réponse HTTP
     *
     * @Route("/{slug}", name="list")
     */
    #[Route('/{slug} ', name: 'list')]

    public function list(Categories $categories, ProductsRepository $productsRepository, Request $request): Response
    {
        //On va chercher le numéro de la page dans l'URL
        $page = $request->query->getInt('page', 1);

        //On va chercher la liste des produits de la catégorie
        $products = $productsRepository->findProductsPaginated($page, $categories->getSlug(), 4);

        return $this->render('categories/list.html.twig', compact('categories', 'products'));

        //syntaxe alternative
//        return $this->render('categories/list.html.twig', [
//            'categories' => $categories,
//            'products' => $products,
//        ]);
    }
}
