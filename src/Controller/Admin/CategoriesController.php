<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use App\Repository\CategoriesRepository;
use App\Repository\ImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour les catégories d'administration.
 */
#[Route('/admin/categories', name: 'admin_categories_')]
class CategoriesController extends AbstractController
{
    /**
     * Affiche la liste des catégories d'administration.
     *
     * @param CategoriesRepository $categoriesRepository Le repository des catégories.
     * @return Response La réponse HTTP contenant la vue des catégories.
     */
    #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        // Récupère la liste des catégories en utilisant le repository des catégories
        $categories = $categoriesRepository->findBy([], ['categoryOrder' => 'asc']);

        // Renvoie la vue des catégories en passant les catégories récupérées comme variable
        return $this->render('admin/categories/index.html.twig', compact('categories'));
    }
}