<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur principal pour l'administration.
 */
#[Route('/admin', name: 'admin_')]
class MainController extends AbstractController
{
    /**
     * Affiche la page d'accueil de l'administration.
     *
     * @return Response La réponse HTTP contenant la page d'accueil.
     */
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}