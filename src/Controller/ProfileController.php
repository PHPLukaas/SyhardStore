<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/profil', name: 'profil_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'compte' => 'Votre compte Syhard',
            'firstname' => $this->getUser()->getFirstname(),
            'lastname' => $this->getUser()->getLastname(),
            'email' => $this->getUser()->getEmail(),
            'adresse' => $this->getUser()->getAddress(),
            'ville' => $this->getUser()->getCity(),
            'code_postal' => $this->getUser()->getZipcode(),
        ]);
    }

    #[Route('/commandes', name: 'orders')]
    public function orders(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Commandes de l\'utilisateur ',
        ]);
    }
}
