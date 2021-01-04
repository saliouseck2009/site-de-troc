<?php

namespace App\Repository;

use App\Entity\ListBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ListBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListBook[]    findAll()
 * @method ListBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListBook::class);
    }

    // /**
    //  * @return ListBook[] Returns an array of ListBook objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ListBook
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
