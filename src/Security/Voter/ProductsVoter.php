<?php

namespace App\Security\Voter;

use App\Entity\Products;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Token;

/**
 * Voter pour les produits.
 */
class ProductsVoter extends Voter
{
    const EDIT = 'PRODUCT_EDIT';
    const DELETE = 'PRODUCT_DELETE';

    private $security;

    /**
     * Construit une nouvelle instance du voter.
     *
     * @param Security $security L'objet Security.
     */
    public function __construct(Security $security)
    {

        $this->security = $security;

    }

    /**
     * Indique si ce voter prend en charge l'attribut et la classe fournis.
     *
     * @param string $attribute L'attribut à vérifier.
     * @param mixed $product L'objet produit à vérifier.
     *
     * @return bool True si l'attribut et la classe sont pris en charge, sinon false.
     */
    protected function supports(string $attribute , $product): bool
    {
        if(!in_array($attribute ,[self::EDIT , self::DELETE])){
            return false ;

        }
        if(!$product instanceof Products){
            return false;

        }
        return true;

        //return in_array($attribute ,[self::EDIT , self::DELETE]) && $product instanceof Products;



    }

    /**
     * Vérifie si l'utilisateur courant a le droit d'accéder à l'attribut et à l'objet fournis.
     *
     * @param string $attribute L'attribut à vérifier.
     * @param mixed $product L'objet produit à vérifier.
     * @param TokenInterface $token Le token d'authentification.
     *
     * @return bool True si l'utilisateur a accès, sinon false.
     */
    protected function voteOnAttribute($attribute , $product, TokenInterface $token): bool
    {
        //On recup le user grace aux token
        $user = $token->getUser();

        if(!$user instanceof UserInterface) return false ;


        // On vérif si il est admin
        if($this->security->isGranted('ROLE_ADMIN')) return true ;

        //On vérifie les permissions
        switch ($attribute){
            case   self::EDIT:
                return $this->canEdit();
                // On vérif si le user peut edit
                break;

            case self::DELETE:
                return $this->canDelete();
                //on vérifi si le user peut sup
                break;


        }
        return false ;


    }

    /**
     * Vérifie si l'utilisateur courant a le droit de modifier un produit.
     *
     * @return bool True si l'utilisateur a le droit, sinon false.
     */
    private function canEdit(){
        return $this->security->isGranted('ROLE_PRODUCT_ADMIN');
    }

    /**
     * Vérifie si l'utilisateur courant a le droit de supprimer un produit.
     *
     * @return bool True si l'utilisateur a le droit, sinon false.
     */
    private function canDelete(){
        return $this->security->isGranted('ROLE_PRODUCT_ADMIN');
    }

}