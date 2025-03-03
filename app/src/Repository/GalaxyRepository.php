<?php

namespace App\Repository;

use App\Entity\Galaxy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Galaxy>
 */
class GalaxyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Galaxy::class);
    }
    
    /**
     * @return Galaxy[]
     */
    public function findAllWithModels(): array
    {
        return $this->createQueryBuilder('g')
            ->select('g')  
            ->where('g.status = :status')  
            ->setParameter('status', 'published')
            ->orderBy('g.sort', 'ASC')
            ->getQuery()
            ->enableResultCache(3600)  
            ->getResult();
    }
    
    /**
     * @return array<int, Galaxy>
     */
    public function findAllWithModelsPaginated(int $page = 1, int $limit = 6): array
    {
        $query = $this->createQueryBuilder('g')
            ->select('g')
            ->where('g.status = :status')
            ->setParameter('status', 'published')
            ->orderBy('g.sort', 'ASC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->enableResultCache(3600, "galaxy_paginated_{$page}_{$limit}");
            
        $paginator = new Paginator($query);
        
        return iterator_to_array($paginator);
    }
    
    /**
     * Returns the total count of published galaxies
     */
    public function countPublished(): int
    {
        return $this->createQueryBuilder('g')
            ->select('COUNT(g.id)')
            ->where('g.status = :status')
            ->setParameter('status', 'published')
            ->getQuery()
            ->enableResultCache(3600, 'count_published_galaxies')
            ->getSingleScalarResult();
    }
}