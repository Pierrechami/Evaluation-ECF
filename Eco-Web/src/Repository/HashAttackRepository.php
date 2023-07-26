<?php

namespace App\Repository;

use App\Entity\HashAttack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HashAttack>
 *
 * @method HashAttack|null find($id, $lockMode = null, $lockVersion = null)
 * @method HashAttack|null findOneBy(array $criteria, array $orderBy = null)
 * @method HashAttack[]    findAll()
 * @method HashAttack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HashAttackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HashAttack::class);
    }

//    /**
//     * @return HashAttack[] Returns an array of HashAttack objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HashAttack
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
