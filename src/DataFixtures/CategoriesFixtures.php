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


        $parent = $this->createCategory('Informatique', null, 1, 'fas fa-computer-mouse', manager: $manager);

        $this->createCategory('Ordinateur portables',$parent, 0,'fas fa-laptop', $manager);
        $this->createCategory('Ecran', $parent,0, 'fas fa-desktop' ,$manager);
        $this->createCategory('Souris', $parent,0, 'fas fa-computer-mouse' ,$manager);

        $parent = $this->createCategory('Mode', null,2, 'fas fa-shirt', manager: $manager);

        $this->createCategory('Homme', $parent,0, 'fas fa-person', $manager);
        $this->createCategory('Femme', $parent,0, 'fas fa-person-dress', $manager);
        $this->createCategory('Enfant', $parent,0, 'fas fa-child', $manager);
        $manager->flush();
    }

    public function createCategory(string $name, Categories $parent = null, int $categoryOrder, string $categoryIcon, ObjectManager $manager, )
    {
        $category = new Categories();
        $category->setName($name);
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        $category->setParent($parent);
        $category->setCategoryOrder($categoryOrder);
        $category->setIcon($categoryIcon);
        $manager->persist($category);

        $this->addReference('cat-' . $this->counter, $category);
        $this->counter++;

        return $category;
    }
}
