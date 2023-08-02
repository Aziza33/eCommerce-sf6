<?php

namespace App\Security\Voter;

use App\Entity\Products;
// rajouté suite à message intelephense quel est le bon entre ligne 7 et ligne 8
use \Symfony\Bundle\SecurityBundle\Security;
//as SecurityBundleSecurity;
//use Symfony\Bundle\SecurityBundle\SecurityBundle; provoque une erreur au niveau de isGranted contrairement à celui du dessus

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

//Le security ci-dessous est deprecated mais il figure dans le tuto
//use Symfony\Component\Security\Core\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class ProductsVoter extends Voter

{
    const EDIT = 'PRODUCT_EDIT';
    const DELETE = 'PRODUCT_DELETE';

    private $security;  
    public function __construct(Security $security) 

    
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $product): bool
    {
        if(!in_array($attribute, [self::EDIT, self::DELETE])){
            return false;
        }
        if(!$product instanceof Products){
            return false;
        }
        return true;

        // On aurait pu écrire :
        // return in_array($attribute, [self::EDIT, self::DELETE]) && $product instanceof Products;
    }


    protected function voteOnAttribute($attribute, $product, TokenInterface $token): bool
    {
        // On récupère l'utilisateur à partir du token
        $user = $token->getUser();

        if(!$user instanceof UserInterface) return false;
        
        // On vérifie si l'utilisateur est admin
        if($this->security->isGranted('ROLE_ADMIN')) return true;

        // On vérifie les permissions
        switch($attribute){
            case self::EDIT:
                // On vérifie si l'utilisateur peut éditer
                return $this->canEdit();
                break;
            case self::DELETE:
                // On vérifie si l'utilisaeur peut supprimer
                return $this->canDelete();
                break;
        }
    }

    private function canEdit(){
        return $this->security->isGranted('ROLE_PRODUCT_ADMIN');
    }
    private function canDelete(){
        return $this->security->isGranted('ROLE_PRODUCT_ADMIN');
    }

}