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
        $galaxies = $galaxyRepository->findAll();
        $carousel = [];

        $modeles = $modelesRepository->findAll();
        $modelesFiles = $modelesFilesRepository->findAll();
        $directusFiles = $directusFilesRepository->findAll();

        $modelesMap = [];
        foreach ($modeles as $modele) {
            $modelesMap[$modele->getId()] = $modele;
        }

        $modelesFilesMap = [];
        foreach ($modelesFiles as $modelesFile) {
            $modelesFilesMap[$modelesFile->getModelesId()][] = $modelesFile;
        }

        $directusFilesMap = [];
        foreach ($directusFiles as $file) {
            $directusFilesMap[$file->getId()] = $file;
        }

        foreach ($galaxies as $galaxy) {
            $carouselItem = [
                'title' => $galaxy->getTitle(),
                'description' => $galaxy->getDescription(),
            ];

            $modele = $modelesMap[$galaxy->getModele()];
            $files = [];

            if (isset($modelesFilesMap[$modele->getId()])) {
                foreach ($modelesFilesMap[$modele->getId()] as $modelesFile) {
                    $files[] = $directusFilesMap[$modelesFile->getDirectusFilesId()];
                }
            }

            $carouselItem['files'] = $files;
            $carousel[] = $carouselItem;
        }

        return $this->render('carousel/index.html.twig', [
            'carousel' => $carousel
        ]);
    }
}
