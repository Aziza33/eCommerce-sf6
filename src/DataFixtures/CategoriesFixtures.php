<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    private $counter = 1;
    public function load(ObjectManager $manager): void
    {
        //Créer un nouvel objet Categorie et Nourrir l'objet Categorie
        $parent = $this->createCategory('Informatique', null, $manager);
    
        $this->createCategory('Ordinateurs portables', $parent, $manager);
        $this->createCategory('Ecrans', $parent, $manager);
        $this->createCategory('Souris', $parent, $manager);

        //créer un nouvel objet Categorie et Nourrir l'objet Categorie
        $parent = $this->createCategory('Mode',null, $manager);

        $this->createCategory('Homme', $parent, $manager);
        $this->createCategory('Femme', $parent, $manager);
        $this->createCategory('Enfant', $parent, $manager);

         //Pusher dans la base de données
         $manager->flush();
       
    }

    public function createCategory(string $name, Categories $parent = null, ObjectManager $manager)
    {
        $category = new Categories();
        $category->setName($name);
        $category->setParent($parent);
        $category->setCategoryOrder($this->counter);
        $manager->persist($category);

        //Pour créer et stocker la référence dans la base de données
        $this->addReference('cat-'.$this->counter, $category);
        $this->counter++;
        //faire un return de la category de façon à récupérer si c'est un parent:
        return $category;
    }
}

