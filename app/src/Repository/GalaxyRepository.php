<?php

namespace App\Repository;

use App\Entity\Galaxy;
use App\Entity\DirectusFiles;
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

    /**
     * Récupère toutes les galaxies avec leurs modèles et fichiers associés en une seule requête
     * @return array
     */
    public function findAllWithModelsAndFiles(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $sql = '
            SELECT 
                g.id AS galaxy_id, 
                g.title AS galaxy_title, 
                g.description AS galaxy_description,
                g.sort AS galaxy_sort,
                df.id AS file_id,
                df.storage AS file_storage,
                df.filename_disk AS file_filename_disk
            FROM galaxy g
            LEFT JOIN modeles m ON g.modele = m.id
            LEFT JOIN modeles_files mf ON m.id = mf.modeles_id
            LEFT JOIN directus_files df ON mf.directus_files_id = df.id
            ORDER BY g.sort ASC, mf.id ASC
        ';
        
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $results = $resultSet->fetchAllAssociative();
        
        $carousel = [];
        $galaxyMap = [];
        
        foreach ($results as $row) {
            $galaxyId = $row['galaxy_id'];
            
            if (!isset($galaxyMap[$galaxyId])) {
                $galaxyMap[$galaxyId] = count($carousel);
                $carousel[] = [
                    'title' => $row['galaxy_title'],
                    'description' => $row['galaxy_description'],
                    'files' => []
                ];
            }
            
            if ($row['file_id']) {
                $fileExists = false;
                
                foreach ($carousel[$galaxyMap[$galaxyId]]['files'] as $existingFile) {
                    if ($existingFile->getId() === $row['file_id']) {
                        $fileExists = true;
                        break;
                    }
                }
                
                if (!$fileExists) {
                    $file = new DirectusFiles();
                    $this->setDirectusFileId($file, $row['file_id']);
                    $file->setStorage($row['file_storage']);
                    $file->filename_disk = $row['file_filename_disk'];
                    
                    $carousel[$galaxyMap[$galaxyId]]['files'][] = $file;
                }
            }
        }
        
        return $carousel;
    }
    
    private function setDirectusFileId(DirectusFiles $file, string $id): void
    {
        $reflection = new \ReflectionClass(DirectusFiles::class);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($file, $id);
    }
}
