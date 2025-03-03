<?php
namespace App\Controller;

use App\Repository\GalaxyRepository;
use App\Repository\ModelesFilesRepository;
use App\Repository\ModelesRepository;
use App\Repository\DirectusFilesRepository;
use App\Service\ImageOptimizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class CarouselController extends AbstractController
{
    #[Route('/carousel', name: 'app_carousel')]
    public function index(
        GalaxyRepository $galaxyRepository,
        ModelesFilesRepository $modelesFilesRepository,
        ModelesRepository $modelesRepository,
        DirectusFilesRepository $directusFilesRepository,
        CacheInterface $cache,
        ImageOptimizer $imageOptimizer,
        Request $request
    ): Response {
        $page = $request->query->getInt('page', 1);
        $limit = 6; 
        
        $cacheKey = 'carousel_data_page_' . $page;
        
        $carousel = $cache->get($cacheKey, function (ItemInterface $item) use (
            $galaxyRepository, 
            $modelesFilesRepository, 
            $modelesRepository, 
            $directusFilesRepository,
            $page,
            $limit
        ) {
            $item->expiresAfter(3600);

            $galaxiesWithModels = $galaxyRepository->findAllWithModelsPaginated($page, $limit);

            $modelesIds = array_filter(array_map(fn($galaxy) => $galaxy->getModele(), iterator_to_array($galaxiesWithModels)));

            if (empty($modelesIds)) {
                return [];
            }

            $modeles = $modelesRepository->findByIdsIndexed($modelesIds);

            $modelesFiles = $modelesFilesRepository->findFilesForModeles($modelesIds);

            $modelesFilesMap = [];
            $fileIds = [];
            
            foreach ($modelesFiles as $modelesFile) {
                $modeleId = $modelesFile->getModelesId();
                $fileId = $modelesFile->getDirectusFilesId();

                if ($fileId && $modeleId) {
                    if (!isset($modelesFilesMap[$modeleId])) {
                        $modelesFilesMap[$modeleId] = [];
                    }
                    $modelesFilesMap[$modeleId][] = $fileId;
                    $fileIds[] = $fileId;
                }
            }

            $files = empty($fileIds) ? [] : $directusFilesRepository->findByIdsIndexed($fileIds);

            $carousel = [];
            foreach ($galaxiesWithModels as $galaxy) {
                $modeleId = $galaxy->getModele();
                $filesList = [];

                if ($modeleId && isset($modelesFilesMap[$modeleId])) {
                    foreach ($modelesFilesMap[$modeleId] as $fileId) {
                        if (isset($files[$fileId])) {
                            $filesList[] = $files[$fileId];
                        }
                    }
                }

                $carousel[] = [
                    'title' => $galaxy->getTitle(),
                    'description' => $galaxy->getDescription(),
                    'files' => $filesList,
                    'model' => $modeleId ? ($modeles[$modeleId] ?? null) : null
                ];
            }

            return $carousel;
        });

        $totalItems = $galaxyRepository->countPublished();
        
        $response = $this->render('carousel/index.html.twig', [
            'carousel' => $carousel,
            'currentPage' => $page,
            'totalPages' => ceil($totalItems / $limit)
        ]);

        $response->setSharedMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        $response->headers->set('Link', '</styles/app.css>; rel=preload; as=style');
        
        return $response;
    }
}