<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    private $counter = 1;

    public function __construct(private SluggerInterface $slugger)
    {

    }

    public function load(ObjectManager $manager): void
    {


        $parent = $this->createCategory('Informatique', null, 1, manager: $manager,);

        $this->createCategory('Ordinateur portables', $parent, 0, $manager);
        $this->createCategory('Ecran', $parent,0, $manager);
        $this->createCategory('Souris', $parent,0, $manager);

        $parent = $this->createCategory('Mode', null,2, manager: $manager);

        $this->createCategory('Homme', $parent,0, $manager);
        $this->createCategory('Femme', $parent,0, $manager);
        $this->createCategory('Enfant', $parent,0, $manager);
        $manager->flush();
    }

    public function createCategory(string $name, Categories $parent = null, int $categoryOrder, ObjectManager $manager, )
    {
        $category = new Categories();
        $category->setName($name);
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        $category->setParent($parent);
        $category->setCategoryOrder($categoryOrder);
        $manager->persist($category);

        $this->addReference('cat-' . $this->counter, $category);
        $this->counter++;

        return $category;
    }
}
