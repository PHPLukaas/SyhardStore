<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Products>
 *
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * Repository de l'entité Products.
 *
 * @extends ServiceEntityRepository<Products>
 */
class ProductsRepository extends ServiceEntityRepository
{
    /**
     * Construit une nouvelle instance du repository.
     *
     * @param ManagerRegistry $registry Le registre du gestionnaire d'entités.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    /**
     * Enregistre l'entité Products.
     *
     * @param Products $entity L'entité Products à enregistrer.
     * @param bool $flush Indique s'il faut exécuter l'opération de flush.
     *
     * @return void
     */
    public function save(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprime l'entité Products.
     *
     * @param Products $entity L'entité Products à supprimer.
     * @param bool $flush Indique s'il faut exécuter l'opération de flush.
     *
     * @return void
     */
    public function remove(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Recherche les produits de manière paginée en fonction de la page, du slug et de la limite.
     *
     * @param int $page Le numéro de la page.
     * @param string $slug Le slug de la catégorie.
     * @param int $limit La limite de produits par page.
     *
     * @return array Un tableau contenant les données des produits paginés.
     */
    public function findProductsPaginated(int $page, string $slug, int $limit = 6): array
    {
        $limit = abs($limit);
        $result = [];
        $query = $this->getEntityManager()->createQueryBuilder()
        ->select('c', 'p')
        ->from('App\Entity\Products', 'p')
        ->join('p.categories', 'c')
        ->where ("c.slug = '$slug'")
        ->setMaxResults($limit)
        ->setFirstResult(($page * $limit) - $limit);

        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();

        //on verifie les données
        if (empty($data)) {
            return $result;
        }

        //on calcule le nombre de pages
        $pages = ceil($paginator->count() / $limit);

        //on remplie le tableau
        $result['data'] = $data;
        $result['pages'] = $pages;
        $result['page'] = $page;
        $result['limit'] = $limit;



        return $result;
    }

//    /**
//     * @return Products[] Returns an array of Products objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Products
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * Récupère les derniers produits ajoutés.
     *
     * @return Products[] Un tableau contenant les derniers produits ajoutés.
     */
    public function findLatest(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }

}
