<?php

namespace App\DataFixtures;

use App\Entity\Images;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ImagesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //On utilise le factory pour créer un Faker\Generator
        $faker = Faker\Factory::create('fr_FR');
        for ($img = 1; $img <= 50; $img++) {
            $image = new Images();
            $image->setName($faker->image('public/assets/uploads', 640, 480, null, false));
            $product = $this->getReference('prod-' . rand(1, 10));
            $image->setProducts($product);
            $manager->persist($image);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductsFixtures::class
        ];
    }
}
