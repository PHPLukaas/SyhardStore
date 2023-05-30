<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur des produits.
 */
#[Route('/produits', name: 'produits_')]
class ProductsController extends AbstractController
{
    /**
     * Affiche la liste des produits.
     *
     * @param ProductsRepository $productsRepository Le repository des produits.
     * @return Response La réponse HTTP contenant la liste des produits.
     */
    #[Route('/', name: 'index')]
    public function index(ProductsRepository $productsRepository): Response
    {
        $produits = $productsRepository->findAll();
        return $this->render('products/index.html.twig' , compact('produits'));
    }

    /**
     * Affiche les détails d'un produit.
     *
     * @param Products $products Le produit à afficher.
     * @return Response La réponse HTTP contenant les détails du produit.
     */
    #[Route('/{slug} ', name: 'detail')]
    public function detail(Products $products): Response
    {
        return $this->render('products/details.html.twig', compact('products'));
    }
}
