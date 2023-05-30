<?php

namespace App\Repository;

use App\Entity\OrdersDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrdersDetails>
 *
 * @method OrdersDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrdersDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrdersDetails[]    findAll()
 * @method OrdersDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersDetailsRepository extends ServiceEntityRepository
{
    /**
     * Construit une nouvelle instance du repository.
     *
     * @param ManagerRegistry $registry Le registre du gestionnaire d'entités.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrdersDetails::class);
    }

    /**
     * Enregistre l'entité OrdersDetails.
     *
     * @param OrdersDetails $entity L'entité OrdersDetails à enregistrer.
     * @param bool $flush Indique s'il faut exécuter l'opération de flush.
     *
     * @return void
     */
    public function save(OrdersDetails $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprime l'entité OrdersDetails.
     *
     * @param OrdersDetails $entity L'entité OrdersDetails à supprimer.
     * @param bool $flush Indique s'il faut exécuter l'opération de flush.
     *
     * @return void
     */
    public function remove(OrdersDetails $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return OrdersDetails[] Returns an array of OrdersDetails objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OrdersDetails
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
