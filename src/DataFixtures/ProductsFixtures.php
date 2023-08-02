<?php

namespace App\DataFixtures;
//namespace App\DataFixtures\DatetimeImmutable;

use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
//j'ai rajouté cette ligne pour pouvoir utiliser DateTimeImmutable
use DateTimeImmutable;
//use Monolog\DateTimeImmutable;
use Faker;

class ProductsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //On utilise le factory pour créer un Faker\Generator
        $faker = Faker\Factory::create('fr_FR');

        for ($prod = 1; $prod <= 10; $prod++) {
            $product = new Products();
            $product->setName($faker->text(15));
            $product->setDescription($faker->text());
            $product->setPrice($faker->numberBetween(900, 150000));
            $product->setStock($faker->numberBetween(0, 10));
            // $product->setCategories($category);
            $product->setCreatedAt(new DateTimeImmutable());

            //on va chercher une référence de catégorie de manière aléatoire
            $category = $this->getReference('cat-' . rand(1, 8));
            $product->setCategories($category);

            $this->setReference('prod-' . $prod, $product);

            $manager->persist($product);
        }
        $manager->flush();
    }
}
