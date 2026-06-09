<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Listing;
use App\Enum\ListingStatus;
use App\Enum\Theme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Listing>
 */
class ListingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Listing::class);
    }

    /** @return Listing[] */
    public function findActiveByTheme(Theme $theme, int $limit = 20): array
    {
        return $this->createQueryBuilder('l')
            ->join('l.category', 'c')
            ->where('c.themeType = :theme')
            ->andWhere('l.status = :status')
            ->setParameter('theme', $theme)
            ->setParameter('status', ListingStatus::Active)
            ->orderBy('l.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /** @return Listing[] */
    public function findActiveByCategory(Category $category, int $limit = 40): array
    {
        return $this->findBy(
            ['category' => $category, 'status' => ListingStatus::Active],
            ['createdAt' => 'DESC'],
            $limit
        );
    }

    public function createActiveByThemeQueryBuilder(Theme $theme): QueryBuilder
    {
        return $this->createQueryBuilder('l')
            ->join('l.category', 'c')
            ->where('c.themeType = :theme')
            ->andWhere('l.status = :status')
            ->setParameter('theme', $theme)
            ->setParameter('status', ListingStatus::Active)
            ->orderBy('l.createdAt', 'DESC');
    }
}
