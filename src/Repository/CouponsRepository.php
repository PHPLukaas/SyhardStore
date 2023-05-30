<?php

namespace App\Repository;

use App\Entity\Coupons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Coupons>
 *
 * @method Coupons|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coupons|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coupons[]    findAll()
 * @method Coupons[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * Repository de l'entité Coupons.
 *
 * @extends ServiceEntityRepository<Coupons>
 */
class CouponsRepository extends ServiceEntityRepository
{
    /**
     * Construit une nouvelle instance du repository.
     *
     * @param ManagerRegistry $registry Le registre du gestionnaire d'entités.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coupons::class);
    }

    /**
     * Enregistre l'entité Coupons.
     *
     * @param Coupons $entity L'entité Coupons à enregistrer.
     * @param bool $flush Indique s'il faut exécuter l'opération de flush.
     *
     * @return void
     */
    public function save(Coupons $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprime l'entité Coupons.
     *
     * @param Coupons $entity L'entité Coupons à supprimer.
     * @param bool $flush Indique s'il faut exécuter l'opération de flush.
     *
     * @return void
     */
    public function remove(Coupons $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Coupons[] Returns an array of Coupons objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Coupons
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
