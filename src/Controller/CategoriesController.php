<?php

namespace App\Controller;

use App\Entity\Categories;
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

    public function list(Categories $categories): Response
    {
        //On vas chercher la liste des produits de la catégorie
        $products = $categories->getProducts();

        return $this->render('categories/list.html.twig', compact('categories', 'products'));

        //syntaxe alternative
//        return $this->render('categories/list.html.twig', [
//            'categories' => $categories,
//            'products' => $products,
//        ]);
    }
}
