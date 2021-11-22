<?php

namespace App\Repository;

use App\Entity\TodoTask;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TodoTask|null find($id, $lockMode = null, $lockVersion = null)
 * @method TodoTask|null findOneBy(array $criteria, array $orderBy = null)
 * @method TodoTask[]    findAll()
 * @method TodoTask[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoTaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodoTask::class);
    }

    // /**
    //  * @return TodoTask[] Returns an array of TodoTask objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TodoTask
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
