<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conversation>
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    /** @return Conversation[] */
    public function findForUser(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.participants', 'p')
            ->where('p.id = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findBetweenUsersForListing(User $a, User $b, int $listingId): ?Conversation
    {
        return $this->createQueryBuilder('c')
            ->join('c.participants', 'p1')
            ->join('c.participants', 'p2')
            ->join('c.listing', 'l')
            ->where('p1.id = :aId')
            ->andWhere('p2.id = :bId')
            ->andWhere('l.id = :listingId')
            ->setParameter('aId', $a->getId())
            ->setParameter('bId', $b->getId())
            ->setParameter('listingId', $listingId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
