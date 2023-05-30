<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Service pour la gestion du panier d'achats.
 */
class PanierService
{
    private SessionInterface $session;

    /**
     * Constructeur de la classe PanierService.
     *
     * @param SessionInterface $session L'objet de gestion de session.
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Ajoute un produit au panier.
     *
     * @param Produit $produit Le produit à ajouter.
     */
    public function ajouterProduit(Produit $produit)
    {
        $panier = $this->session->get('panier', []);
        $panier[] = $produit;
        $this->session->set('panier', $panier);
    }

    /**
     * Supprime un produit du panier.
     *
     * @param Produit $produit Le produit à supprimer.
     */
    public function supprimerProduit(Produit $produit)
    {
        $panier = $this->session->get('panier', []);
        $index = array_search($produit, $panier);
        if ($index !== false) {
            unset($panier[$index]);
            $this->session->set('panier', $panier);
        }
    }

    /**
     * Récupère le contenu du panier.
     *
     * @return array Le tableau des produits dans le panier.
     */
    public function getPanier()
    {
        return $this->session->get('panier', []);
    }
}
?>

