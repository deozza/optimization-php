<?php

namespace App\Controller;

use App\Repository\GalaxyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class CarouselController extends AbstractController
{
    #[Route('/carousel', name: 'app_carousel')]
    public function index(GalaxyRepository $galaxyRepository, CacheInterface $cache): Response
    {
        $carousel = $cache->get('carousel_data', function (ItemInterface $item) use ($galaxyRepository) {
            $item->expiresAfter(3600);
            return $galaxyRepository->findAllWithModelsAndFiles();
        });
        
        $initialItems = array_slice($carousel, 0, 3);
        
        $response = $this->render('carousel/index.html.twig', [
            'carousel' => $initialItems,
            'hasMoreItems' => count($carousel) > 3
        ]);
        
        $response->setPublic();
        $response->setMaxAge(300);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        
        return $response;
    }

    #[Route('/api/carousel/load-more', name: 'api_carousel_load_more')]
    public function loadMore(Request $request, GalaxyRepository $galaxyRepository, CacheInterface $cache): JsonResponse
    {
        $offset = $request->query->getInt('offset', 0);
        $limit = $request->query->getInt('limit', 3);
        
        $carousel = $cache->get('carousel_data', function (ItemInterface $item) use ($galaxyRepository) {
            $item->expiresAfter(3600);
            return $galaxyRepository->findAllWithModelsAndFiles();
        });
        
        $items = array_slice($carousel, $offset, $limit);
        $hasMoreItems = (count($carousel) > ($offset + $limit));
        
        $html = '';
        foreach ($items as $item) {
            $html .= $this->renderView('carousel/_item.html.twig', [
                'item' => $item
            ]);
        }
        
        return new JsonResponse([
            'html' => $html,
            'hasMoreItems' => $hasMoreItems,
            'nextOffset' => $offset + $limit
        ]);
    }
}
