<?php

namespace App\Repository;

use App\Entity\Galaxy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function findAllWithModelsAndFiles()
    {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.modele', 'm') 
            ->leftJoin('m.modelesFiles', 'mf') 
            ->leftJoin('mf.directusFiles', 'df')
            ->addSelect('m, mf, df') 
            ->getQuery()
            ->getResult();
    }
}