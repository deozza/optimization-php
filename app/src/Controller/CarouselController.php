<?php

namespace App\Controller;

use App\Repository\GalaxyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
        
        $response = $this->render('carousel/index.html.twig', [
            'carousel' => $carousel
        ]);
        
        $response->setPublic();
        $response->setMaxAge(300);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        
        return $response;
    }
}
