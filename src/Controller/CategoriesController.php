<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('categories/index.html.twig');
    }

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
