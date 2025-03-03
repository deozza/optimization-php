<?php

namespace App\Controller;

use App\Repository\DirectusFilesRepository;
use App\Repository\GalaxyRepository;
use App\Repository\ModelesFilesRepository;
use App\Repository\ModelesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CarouselController extends AbstractController
{
    #[Route('/carousel', name: 'app_carousel')]
    public function index(
        GalaxyRepository $galaxyRepository,
        ModelesRepository $modelesRepository,
        ModelesFilesRepository $modelesFilesRepository,
        DirectusFilesRepository $directusFilesRepository
    ): Response {
        // Récupérer toutes les galaxies avec les modèles associés en une seule requête
        $galaxies = $galaxyRepository->findAll();

        // Collecter tous les modèles et leurs fichiers associés à l'avance pour éviter les requêtes répétées
        $modelIds = array_map(fn($galaxy) => $galaxy->getModele(), $galaxies);
        $modeles = $modelesRepository->findBy(['id' => $modelIds]);

        // Collecter tous les fichiers liés aux modèles en une seule requête
        $modeleIds = array_map(fn($modele) => $modele->getId(), $modeles);
        $modelesFiles = $modelesFilesRepository->findBy(['modeles_id' => $modeleIds]);

        // Récupérer les fichiers Directus pour tous les fichiers liés
        $directusFileIds = array_map(fn($modelesFile) => $modelesFile->getDirectusFilesId(), $modelesFiles);
        $directusFiles = $directusFilesRepository->findBy(['id' => $directusFileIds]);

        // Organiser les données pour le carousel
        $carousel = [];
        foreach ($galaxies as $galaxy) {
            $carouselItem = [
                'title' => $galaxy->getTitle(),
                'description' => $galaxy->getDescription(),
                'files' => [],
            ];

            // Trouver le modèle correspondant à la galaxie
            $modele = $modeles[$galaxy->getModele()];

            // Filtrer les fichiers associés à ce modèle
            $modelFiles = array_filter($modelesFiles, fn($modelesFile) => $modelesFile->getModelesId() === $modele->getId());
            foreach ($modelFiles as $modelesFile) {
                $file = $directusFiles[$modelesFile->getDirectusFilesId()];
                $carouselItem['files'][] = $file;
            }

            $carousel[] = $carouselItem;
        }

        return $this->render('carousel/index.html.twig', [
            'carousel' => $carousel
        ]);
    }
}
