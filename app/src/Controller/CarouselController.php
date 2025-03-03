<?php

namespace App\Controller;

use App\Repository\DirectusFilesRepository;
use App\Repository\GalaxyRepository;
use App\Repository\ModelesFilesRepository;
use App\Repository\ModelesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class CarouselController extends AbstractController
{
    #[Route('/carousel/{page}', name: 'app_carousel', defaults: ['page' => 1])]
    public function index(
        int $page,
        GalaxyRepository $galaxyRepository,
        ModelesRepository $modelesRepository,
        ModelesFilesRepository $modelesFilesRepository,
        DirectusFilesRepository $directusFilesRepository,
        CacheInterface $cache
    ): Response {
        $itemsPerPage = 5;
        $cacheKey = "carousel_data_page_{$page}";

        $data = $cache->get($cacheKey, function (ItemInterface $item) use (
            $page,
            $itemsPerPage,
            $galaxyRepository,
            $modelesRepository,
            $modelesFilesRepository,
            $directusFilesRepository
        ) {
            $item->expiresAfter(3600);

            $offset = ($page - 1) * $itemsPerPage;
            $galaxies = $galaxyRepository->findBy([], ['sort' => 'ASC'], $itemsPerPage, $offset);
            $totalGalaxies = $galaxyRepository->count([]);

            $modeleIds = [];
            foreach ($galaxies as $galaxy) {
                $modeleIds[] = $galaxy->getModele();
            }

            $modeles = $modelesRepository->findBy(['id' => $modeleIds]);
            $modelesById = [];
            foreach ($modeles as $modele) {
                $modelesById[$modele->getId()] = $modele;
            }

            $modelesFiles = $modelesFilesRepository->findBy(['modeles_id' => $modeleIds]);

            $filesByModeleId = [];
            $allFileIds = [];
            foreach ($modelesFiles as $modelesFile) {
                $modeleId = $modelesFile->getModelesId();
                if (!isset($filesByModeleId[$modeleId])) {
                    $filesByModeleId[$modeleId] = [];
                }
                $filesByModeleId[$modeleId][] = $modelesFile;
                $allFileIds[] = $modelesFile->getDirectusFilesId();
            }

            if (!empty($allFileIds)) {
                $files = $directusFilesRepository->findBy(['id' => $allFileIds]);
                $filesById = [];
                foreach ($files as $file) {
                    $filesById[$file->getId()] = $file;
                }
            } else {
                $filesById = [];
            }

            $carousel = [];
            foreach ($galaxies as $galaxy) {
                $modeleId = $galaxy->getModele();
                $carouselFiles = [];

                if (isset($filesByModeleId[$modeleId])) {
                    foreach ($filesByModeleId[$modeleId] as $modelesFile) {
                        $fileId = $modelesFile->getDirectusFilesId();
                        if (isset($filesById[$fileId])) {
                            $carouselFiles[] = $filesById[$fileId];
                        }
                    }
                }

                $carouselItem = [
                    'title' => $galaxy->getTitle(),
                    'description' => $galaxy->getDescription(),
                    'files' => $carouselFiles
                ];

                $carousel[] = $carouselItem;
            }

            return [
                'carousel' => $carousel,
                'totalPages' => ceil($totalGalaxies / $itemsPerPage)
            ];
        });

        $response = $this->render('carousel/index.html.twig', [
            'carousel' => $data['carousel'],
            'currentPage' => $page,
            'totalPages' => $data['totalPages']
        ]);

        $response->setPublic();
        $response->setMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }
}
