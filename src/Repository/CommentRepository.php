<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE =2;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }
    public function getCommentPaginator(Conference $conference, int $offset) : Paginator{
        //Querybuilder doet altijd dit voor de repository waar je in zit: select * from comments (dus omdat je in deze repository zit)
        $query = $this->createQueryBuilder('c')
            //where conference = hier eerst als placeholder
        ->andWhere('c.conference = :conference')
            //hier bind je de parameter door de $conference die meegegeven is als argument aan deze function
            ->setParameter('conference', $conference)
            //nu hebben we select * from comments where conference = $conference je wil een sortereing hebben, dus orderBy
            ->orderBy('c.createdAt', 'DESC')

            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            //begin bij (eerst bij 0 dan bij 2 etc
            ->setFirstResult($offset)
            ->getQuery();
        return new Paginator($query);

    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
