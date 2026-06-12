<?php

namespace App\Repository;

use App\Entity\Category;
use App\Enum\Theme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /** @return Category[] */
    public function findByTheme(Theme $theme): array
    {
        return $this->findBy(['themeType' => $theme], ['name' => 'ASC']);
    }
}
