<?php

namespace App\Controller;

use App\Repository\DirectusFilesRepository;
use App\Repository\GalaxyRepository;
use App\Repository\ModelesFilesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CarouselController extends AbstractController
{
    #[Route('/carousel', name: 'app_carousel')]
    public function index(
        GalaxyRepository $galaxyRepository, 
        ModelesFilesRepository $modelesFilesRepository, 
        DirectusFilesRepository $directusFilesRepository
    ): Response {
        $galaxies = $galaxyRepository->findAll();
        
        $modelesIds = array_map(fn($galaxy) => $galaxy->getModele(), $galaxies);
        $modelesFiles = $modelesFilesRepository->findBy(['modeles_id' => $modelesIds]);
        $directusFileIds = array_map(fn($modelesFile) => $modelesFile->getDirectusFilesId(), $modelesFiles);
        $directusFiles = $directusFilesRepository->findBy(['id' => $directusFileIds]);

        $carousel = [];
        foreach ($galaxies as $galaxy) {
            $carouselItem = [
                'title' => $galaxy->getTitle(),
                'description' => $galaxy->getDescription(),
                'files' => []
            ];

            foreach ($modelesFiles as $modelesFile) {
                if ($modelesFile->getModelesId() === $galaxy->getModele()) {
                    foreach ($directusFiles as $file) {
                        if ($file->getId() === $modelesFile->getDirectusFilesId()) {
                            $carouselItem['files'][] = $file;
                        }
                    }
                }
            }

            $carousel[] = $carouselItem;
        }

        return $this->render('carousel/index.html.twig', [
            'carousel' => $carousel
        ]);
    }
}
