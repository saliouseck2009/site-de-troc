<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
      * @return Book[] Returns an array of Book objects
    */
    public function findTenBook()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.year', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Book[] Returns an array of Book objects
     */
    public function findTenMostRecentBook()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.year', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }



     /**
      * @return Book[] Returns an array of Book objects
      */
    public function searchBytitle($isbn)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.isbn LIKE :isbn')
            ->setParameter('title', '%'.$isbn.'%')
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * @return Book[] Returns an array of Book objects
     */
    public function searchTitle($title)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.title LIKE :title')
            ->setParameter('title', '%'.$title.'%')
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * @return Book[] Returns an array of Book objects
     */
    public function searchByAuthor($author)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.author LIKE :author')
            ->setParameter('author', '%'.$author.'%')
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * @return Book[] Returns an array of Book objects
     */
    public function searchByYear($year)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.year LIKE :year')
            ->setParameter('year', '%'.$year.'%')
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * @return Book[] Returns an array of Book objects
     */
    public function searchByPointValue($point)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.point_value LIKE :point_value')
            ->setParameter('year', '%'.$point.'%')
            ->getQuery()
            ->execute()
            ;
    }

     /**
      * @return Book[] Returns an array of Book objects
      */

    public function findOrderdBook($value)
    {
        return $this->createQueryBuilder('b')
            ->join()
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }





    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
