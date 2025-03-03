<?php

namespace App\Repository;

use App\Entity\DirectusFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DirectusFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DirectusFiles::class);
    }
    
    /**
     * @param array $fileIds
     * @return array
     */
    public function findByIdsIndexed(array $fileIds): array
    {
        if (empty($fileIds)) {
            return [];
        }
        
        $files = $this->createQueryBuilder('df')
            ->where('df.id IN (:fileIds)')
            ->setParameter('fileIds', $fileIds)
            ->getQuery()
            ->enableResultCache(3600)
            ->getResult();
            
        $indexedFiles = [];
        foreach ($files as $file) {
            $indexedFiles[$file->getId()] = $file;
        }
        
        return $indexedFiles;
    }
}