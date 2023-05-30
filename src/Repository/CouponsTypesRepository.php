<?php

namespace App\Repository;

use App\Entity\CouponsTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CouponsTypes>
 *
 * @method CouponsTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method CouponsTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method CouponsTypes[]    findAll()
 * @method CouponsTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * Repository de l'entité CouponsTypes.
 *
 * @extends ServiceEntityRepository<CouponsTypes>
 */
class CouponsTypesRepository extends ServiceEntityRepository
{
    /**
     * Construit une nouvelle instance du repository.
     *
     * @param ManagerRegistry $registry Le registre du gestionnaire d'entités.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CouponsTypes::class);
    }

    /**
     * Enregistre l'entité CouponsTypes.
     *
     * @param CouponsTypes $entity L'entité CouponsTypes à enregistrer.
     * @param bool $flush Indique s'il faut exécuter l'opération de flush.
     *
     * @return void
     */
    public function save(CouponsTypes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprime l'entité CouponsTypes.
     *
     * @param CouponsTypes $entity L'entité CouponsTypes à supprimer.
     * @param bool $flush Indique s'il faut exécuter l'opération de flush.
     *
     * @return void
     */
    public function remove(CouponsTypes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CouponsTypes[] Returns an array of CouponsTypes objects
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

//    public function findOneBySomeField($value): ?CouponsTypes
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
