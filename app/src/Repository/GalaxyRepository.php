<?php

namespace App\Repository;

use App\Entity\Galaxy;
use App\Entity\DirectusFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository personnalisé pour l'entité Galaxy.
 */
class GalaxyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Galaxy::class);
    }

    /**
     * Retourne toutes les galaxies avec leurs modèles et fichiers associés via une requête SQL native.
     *
     * @return array
     */
    public function fetchGalaxiesWithFiles(): array
    {
        $connection = $this->getEntityManager()->getConnection();

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

        $stmt = $connection->prepare($sql);
        $rows = $stmt->executeQuery()->fetchAllAssociative();

        $galaxiesData = [];
        $indexMapping = [];

        foreach ($rows as $row) {
            $id = $row['galaxy_id'];

            if (!isset($indexMapping[$id])) {
                $indexMapping[$id] = count($galaxiesData);
                $galaxiesData[] = [
                    'title'       => $row['galaxy_title'],
                    'description' => $row['galaxy_description'],
                    'files'       => []
                ];
            }

            if (!empty($row['file_id'])) {
                $exists = false;
                foreach ($galaxiesData[$indexMapping[$id]]['files'] as $existingFile) {
                    if ($existingFile->getId() === $row['file_id']) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $file = new DirectusFiles();
                    $this->assignFileId($file, $row['file_id']);
                    $file->setStorage($row['file_storage']);
                    $file->filename_disk = $row['file_filename_disk'];
                    $galaxiesData[$indexMapping[$id]]['files'][] = $file;
                }
            }
        }

        return $galaxiesData;
    }

    /**
     * Affecte l'identifiant à un objet DirectusFiles via la réflexion.
     *
     * @param DirectusFiles $file L'objet à modifier
     * @param string $id L'identifiant à affecter
     */
    private function assignFileId(DirectusFiles $file, string $id): void
    {
        $refClass = new \ReflectionClass(DirectusFiles::class);
        $property = $refClass->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($file, $id);
    }
}
