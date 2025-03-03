<?php

namespace App\Repository;

use App\Entity\Galaxy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Galaxy>
 */
class GalaxyRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Galaxy::class);
        $this->entityManager = $entityManager;
    }

    /**
     * Find all galaxies with their related models and files
     * This prevents the N+1 query problem by using a single optimized query
     *
     * @return array Processed data ready for template rendering
     */
    public function findAllWithModelsAndFiles(): array
    {
        // Add index to these tables if not already present:
        // ALTER TABLE modeles_files ADD INDEX (modeles_id);
        // ALTER TABLE modeles_files ADD INDEX (directus_files_id);
        
        $qb = $this->createQueryBuilder('g')
            ->select('g.id, g.title, g.description, df.filename_disk, df.width, df.height')
            ->join('App\Entity\Modeles', 'm', 'WITH', 'g.modele = m.id')
            ->join('App\Entity\ModelesFiles', 'mf', 'WITH', 'mf.modeles_id = m.id')
            ->join('App\Entity\DirectusFiles', 'df', 'WITH', 'mf.directus_files_id = df.id')
            ->orderBy('g.id', 'ASC')
            ->addOrderBy('df.id', 'ASC');
        
        $results = $qb->getQuery()->getArrayResult();
        return $this->processQueryResults($results);
    }

    /**
     * Process query results into the format expected by the template
     */
    private function processQueryResults(array $results): array
    {
        $carousel = [];
        $currentGalaxyId = null;
        $index = -1;
        
        foreach ($results as $row) {
            // If we've reached a new galaxy
            if ($currentGalaxyId !== $row['galaxy_id']) {
                $currentGalaxyId = $row['galaxy_id'];
                $index++;
                $carousel[$index] = [
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'files' => []
                ];
            }
            
            // Add file to the current galaxy
            $carousel[$index]['files'][] = [
                'filename_disk' => $row['filename_disk'],
                'type' => $row['type'],
                'width' => $row['width'],
                'height' => $row['height']
            ];
        }
        
        return $carousel;
    }
}