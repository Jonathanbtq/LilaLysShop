<?php

namespace App\Repository;

use App\Entity\TempUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TempUser>
 *
 * @method TempUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method TempUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method TempUser[]    findAll()
 * @method TempUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TempUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TempUser::class);
    }

//    /**
//     * @return TempUser[] Returns an array of TempUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TempUser
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
