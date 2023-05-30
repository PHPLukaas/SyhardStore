<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Products;
use App\Entity\Produit;
use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier', name: 'panier_')]
class PanierController extends AbstractController
{
    /**
     * @Route("/ajouter-au-panier/{id}", name="ajouter_au_panier")
     */
    public function ajouterAuPanier(Products $produit, PanierService $panierService): Response
    {
        $panierService->ajouterProduit($produit);

        // Redirigez vers la page du produit ou une autre page de votre choix
        return $this->redirectToRoute('produits_', ['id' => $produit->getId()]);
    }
    /**
     * @Route("/supprimer-du-panier/{id}", name="supprimer_du_panier")
     */
    public function supprimerDuPanier(Products $produit, PanierService $panierService): Response
    {
        $panierService->supprimerProduit($produit);

        // Redirigez vers la page du panier ou une autre page de votre choix
        return $this->redirectToRoute('produits_');
    }
}
?>