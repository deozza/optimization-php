<?php

namespace App\Controller;

use App\Repository\GalaxyRepository;
use App\Repository\ModelesRepository;
use App\Repository\ModelesFilesRepository;
use App\Repository\DirectusFilesRepository;
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
    ): Response
    {
        // Temporarily revert to the original implementation until we fix the memory issue
        $galaxies = $galaxyRepository->findAll();
        $carousel = [];

        foreach($galaxies as $galaxy) {
            $carouselItem = [
                'title' => $galaxy->getTitle(),
                'description' => $galaxy->getDescription(),
                'files' => []
            ];
            
            $modele = $modelesRepository->find($galaxy->getModele());
            
            if ($modele) {
                $modelesFiles = $modelesFilesRepository->findBy([
                    'modeles_id' => $modele->getId()
                ]);
                
                foreach($modelesFiles as $modelesFile) {
                    $file = $directusFilesRepository->find($modelesFile->getDirectusFilesId());
                    if ($file) {
                        $carouselItem['files'][] = $file;
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