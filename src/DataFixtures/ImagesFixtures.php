<?php

namespace App\DataFixtures;

use App\Entity\Images;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker ;

class ImagesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($prod = 1; $prod <= 100; $prod++) {
            $image = new Images();
            $image->setName($faker->imageUrl(640,480, null, true));
            $product = $this->getReference('prod-'.rand(1,10));
            $image->setProducts($product);
            $manager->persist($image);
        }
        $manager->flush();
    }
    public function getDependencies():array
    {
        return [
            ProductsFixtures::class
        ];
    }
}
