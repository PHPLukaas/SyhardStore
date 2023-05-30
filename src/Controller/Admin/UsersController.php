<?php

namespace App\Controller\Admin;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Contrôleur gérant les utilisateurs dans l'administration.
 *
 * @Route("/admin/utilisateurs", name="admin_users_")
 */
#[Route('/admin/utilisateurs', name: 'admin_users_')]
class UsersController extends AbstractController
{
    /**
     * Affiche la liste des utilisateurs.
     *
     * @param UsersRepository $usersRepository Le référentiel (repository) des utilisateurs
     *
     * @return Response La réponse HTTP
     *
     * @Route("/", name="index")
     */
    #[Route('/', name: 'index')]
    public function index(UsersRepository $usersRepository): Response
    {
        $users = $usersRepository->findBy([], ['firstname' => 'asc'], 10);

        return $this->render('admin/users/index.html.twig', compact('users'));
    }
}