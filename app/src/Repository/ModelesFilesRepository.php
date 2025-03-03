<?php

namespace App\Repository;

use App\Entity\ModelesFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModelesFiles>
 */
class ModelesFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModelesFiles::class);
    }

    /**
     * 
     * @param array 
     * @return ModelesFiles[] 
     */
    public function findFilesForModeles(array $modelesIds): array
    {
        if (empty($modelesIds)) {
            return [];
        }

        return $this->createQueryBuilder('mf')
            ->select('mf')
            ->leftJoin('mf.directus_file', 'df')
            ->where('mf.modeles_id IN (:modelesIds)')
            ->setParameter('modelesIds', $modelesIds)
            ->getQuery()
            ->enableResultCache(3600)
            ->getResult();
    }
}