<?php

namespace App\Repository;

use App\Entity\GameMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameMessage[]    findAll()
 * @method GameMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameMessage::class);
    }

    public function findByGameId($id)
    {
        return $this->createQueryBuilder('q')
            ->innerJoin('q.game', 't')
            ->andWhere('t = :idgame')
            ->setParameter('idgame', $id)
            ->orderBy('q.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return GameMessage[] Returns an array of GameMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GameMessage
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
