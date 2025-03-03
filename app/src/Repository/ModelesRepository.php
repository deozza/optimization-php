<?php

namespace App\Repository;

use App\Entity\Modeles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ModelesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Modeles::class);
    }
    
    /**
     * @param array $modelIds
     * @return array
     */
    public function findByIdsWithFiles(array $modelIds): array
    {
        if (empty($modelIds)) {
            return [];
        }
        
        return $this->createQueryBuilder('m')
            ->select('m', 'mf', 'df')
            ->leftJoin('m.files', 'mf')
            ->leftJoin('mf.directus_file', 'df')
            ->where('m.id IN (:modelIds)')
            ->setParameter('modelIds', $modelIds)
            ->getQuery()
            ->enableResultCache(3600)
            ->getResult();
    }
    
    /**
     * @param array $modelIds
     * @return array
     */
    public function findByIdsIndexed(array $modelIds): array
    {
        if (empty($modelIds)) {
            return [];
        }
        
        $models = $this->createQueryBuilder('m')
            ->where('m.id IN (:modelIds)')
            ->setParameter('modelIds', $modelIds)
            ->getQuery()
            ->enableResultCache(3600)
            ->getResult();
            
        $indexedModels = [];
        foreach ($models as $model) {
            $indexedModels[$model->getId()] = $model;
        }
        
        return $indexedModels;
    }
}