<?php

namespace App\Repository;

use App\Entity\DemandeAmi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemandeAmi|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeAmi|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeAmi[]    findAll()
 * @method DemandeAmi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeAmiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeAmi::class);
    }

    // /**
    //  * @return DemandeAmi[] Returns an array of DemandeAmi objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DemandeAmi
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findIfAmis($slugOne, $slugTwo){
        return $this->createQueryBuilder('d')
                    ->join('d.userAjout', 'userA')
                    ->join('d.userRec', 'userR')
                    ->select('d')
                    ->where('(userR.slug = \'' . $slugOne . '\' AND ' . 'userA.slug = \'' . $slugTwo . '\') 
                    OR (userA.slug = \'' . $slugOne . '\' AND ' . 'userR.slug = \'' . $slugTwo . '\')')
                    ->getQuery()
                    ->getResult();
    }

    public function findListeAmis($unSlug){
        return $this->createQueryBuilder('d')
                    ->join('d.userAjout', 'userA')
                    ->join('d.userRec', 'userR')
                    ->select('d')
                    ->where('d.amiStatut = true')
                    ->andWhere('userR.slug = \'' . $unSlug . '\' OR ' . 'userA.slug = \'' . $unSlug . '\'')
                    ->orderBy('d.envoiDate', 'desc')
                    ->getQuery()
                    ->getResult();
    }

    public function findTotal($slug){
        return $this->createQueryBuilder('d')
        ->join('d.userAjout', 'userA')
        ->join('d.userRec', 'userR')
        ->select('count(d)')
        ->where('d.amiStatut = true')
        ->andWhere('userR.slug = \'' . $slug . '\' OR ' . 'userA.slug = \'' . $slug . '\'')
        ->getQuery()
        ->getSingleScalarResult();
    }

    /*public function findListeAmis($unSlug){
        return $this->createQueryBuilder('d')
                    ->join('d.userAjout', 'userA')
                    ->join('d.userRec', 'userR')
                    ->select('d')
                    ->where('userA.slug = \'' . $unSlug . '\'')
                    ->orWhere('userR.slug = \'' . $unSlug . '\'')
                    ->andWhere('d.amiStatut = ' . '0')
                    ->orderBy('d.envoiDate', 'desc')
                    ->getQuery()
                    ->getResult();
    }*/
}
