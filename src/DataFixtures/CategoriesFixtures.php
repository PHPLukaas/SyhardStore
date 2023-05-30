<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    /**
     * Compteur de création de références.
     *
     * @var int
     */
    private $counter = 1;

    /**
     * CategoriesFixtures constructeur.
     *
     * @param SluggerInterface $slugger Le service slugger pour générer des slugs.
     */
    public function __construct(private SluggerInterface $slugger)
    {

    }

    /**
     * Charge les données des catégories.
     *
     * @param ObjectManager $manager L'instance du gestionnaire d'objets.
     *
     * @return void
     */
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

    /**
     * Crée une catégorie.
     *
     * @param string         $name           Le nom de la catégorie.
     * @param Categories|null $parent         La catégorie parente.
     * @param int            $categoryOrder  L'ordre de la catégorie.
     * @param string         $categoryIcon   L'icône de la catégorie.
     * @param ObjectManager  $manager        L'instance du gestionnaire d'objets.
     *
     * @return Categories La catégorie créée.
     */
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
