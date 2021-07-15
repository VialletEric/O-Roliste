<?php

namespace App\Repository;

use App\Entity\MessageUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MessageUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageUser[]    findAll()
 * @method MessageUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageUser::class);
    }

    public function findByConvId($id)
    {
        return $this->createQueryBuilder('message')
            ->innerJoin('message.conversation', 't')
            ->andWhere('t.id = :idconv')
            ->setParameter('idconv', $id)
            ->orderBy('message.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return MessageUser[] Returns an array of MessageUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MessageUser
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
