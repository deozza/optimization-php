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
    public function index(GalaxyRepository $galaxyRepository, ModelesRepository $modelesRepository, ModelesFilesRepository $modelesFilesRepository, DirectusFilesRepository $directusFilesRepository): Response
    {
        $galaxies = $galaxyRepository->findCarousel();
        $carousel = [];

        foreach($galaxies as $galaxy) {
            $carouselItem = [
                'title' => $galaxy->getTitle(),
                'description' => $galaxy->getDescription(),
                'files' => []
            ];

            foreach($galaxy->getModele()->getFiles() as $modelesFile) {
                $carouselItem['files'][] = $modelesFile->getFile();
            }

            $carousel[] = $carouselItem;
        }

        file_put_contents('carousel-snapshot-2.txt', json_encode($carousel, JSON_PRETTY_PRINT));
        
        return $this->render('carousel/index.html.twig', [
            'carousel' => $carousel
        ]);
    }
}
