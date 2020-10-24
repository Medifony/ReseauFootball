<?php

namespace App\Repository;

use App\Entity\Rencontre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rencontre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rencontre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rencontre[]    findAll()
 * @method Rencontre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RencontreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rencontre::class);
    }

    // /**
    //  * @return Rencontre[] Returns an array of Rencontre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rencontre
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findMatchsToday($slugChamp, $today, $tomorrow){
        return $this->createQueryBuilder('r')
                    ->join('r.championnats', 'champ')
                    ->select('r')
                    ->where('champ.slug = \'' . $slugChamp . '\'' )
                    ->andWhere('r.matchDate >= \'' . $today . '\' AND ' . 'r.matchDate <= \'' . $tomorrow . '\'')
                    ->orderBy('r.matchDate', 'asc')
                    ->getQuery()
                    ->getResult();
    }

    public function findMatchEquipe($slug){
        return $this->createQueryBuilder('r')
                    ->join('r.clubDom', 'clubD')
                    ->join('r.clubExt', 'clubE')
                    ->select('r')
                    ->where('clubD.slug = \'' . $slug . '\' OR clubE.slug = \'' . $slug . '\'')
                    ->getQuery()
                    ->getResult();
    }
}
