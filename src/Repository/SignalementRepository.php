<?php

namespace App\Repository;

use App\Entity\Signalement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Signalement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Signalement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Signalement[]    findAll()
 * @method Signalement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignalementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Signalement::class);
    }

    public function findSignalementsSujets(){
        return $this->createQueryBuilder('s')
                    ->select('s as signalement, count(s) as total')
                    ->where('s.etat = 0')
                    ->andWhere('s.reponses IS NULL')
                    ->groupBy('s.sujets')
                    ->orderBy('total', 'desc')
                    ->getQuery()
                    ->getResult();
    }

    public function findSignalementsReponses(){
        return $this->createQueryBuilder('s')
                    ->select('s as signalement, count(s) as total')
                    ->where('s.etat = 0')
                    ->andWhere('s.sujets IS NULL')
                    ->groupBy('s.reponses')
                    ->orderBy('total', 'desc')
                    ->getQuery()
                    ->getResult();
    }

    // /**
    //  * @return Signalement[] Returns an array of Signalement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Signalement
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
