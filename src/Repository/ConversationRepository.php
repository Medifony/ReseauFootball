<?php

namespace App\Repository;

use App\Entity\Conversation;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    // /**
    //  * @return Conversation[] Returns an array of Conversation objects
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
    public function findOneBySomeField($value): ?Conversation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findConversationByParticipants(int $otherUserId, int $myId){
        $qb = $this->createQueryBuilder('c');
        $qb->select($qb->expr()->count('p.conversations'))
           ->innerJoin('c.participants', 'p')
           ->where(
               $qb->expr()->orX(
                    $qb->expr()->eq('p.users', ':me'),
                    $qb->expr()->eq('p.users', ':otherUser')
               )
           )
           ->groupBy('p.conversations')
           ->having(
               $qb->expr()->eq(
                    $qb->expr()->count('p.conversations'),
                    2
               )
           )
            ->setParameters([
                'me' => $myId,
                'otherUser' => $otherUserId
            ])
        ;

        return $qb->getQuery()->getResult();
    }

    public function findConversationsByUser(int $userId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->
            select('otherUser.pseudo', 'otherUser.filename', 'c.id as conversationId', 'lm.contenu', 'lm.contDate')
            ->innerJoin('c.participants', 'p', Join::WITH, $qb->expr()->neq('p.users', ':user'))
            ->innerJoin('c.participants', 'me', Join::WITH, $qb->expr()->eq('me.users', ':user'))
            ->leftJoin('c.dernierMess', 'lm')
            ->innerJoin('me.users', 'meUser')
            ->innerJoin('p.users', 'otherUser')
            ->where('meUser.id = :user')
            ->setParameter('user', $userId)
            ->orderBy('lm.contDate', 'DESC')
        ;

        return $qb->getQuery()->getResult();
    }

    public function checkIfUserIsParticipant(int $conversationId, int $userId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->innerJoin('c.participants', 'p')
            ->where('c.id = :conversationId')
            ->andWhere(
                $qb->expr()->eq('p.users', ':userId')
            )
            ->setParameters([
                'conversationId' => $conversationId,
                'userId' => $userId
            ])
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    /*public function checkIfConversationsByUserExist($slugUser, $slugMe){
        return $this->createQueryBuilder('c')
                    ->join('c.participants', 'p')
                    ->select('c')
                    ->where('c.participants.users.slug = \'' . $slugUser . '\'')
                    ->andWhere('c.participants.users.slug = \'' . $slugMe . '\'')
                    ->getQuery()
                    ->getResult();
    }*/
}
