<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function ajouterProduit(Produit $produit)
    {
        $panier = $this->session->get('panier', []);
        $panier[] = $produit;
        $this->session->set('panier', $panier);
    }
    public function supprimerProduit(Produit $produit)
    {
        $panier = $this->session->get('panier', []);
        $index = array_search($produit, $panier);
        if ($index !== false) {
            unset($panier[$index]);
            $this->session->set('panier', $panier);
        }
    }

    public function getPanier()
    {
        return $this->session->get('panier', []);
    }
}
?>

