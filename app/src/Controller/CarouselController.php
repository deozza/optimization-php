<?php

namespace App\Controller;

use App\Repository\GalaxyRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CarouselController extends AbstractController
{
    #[Route('/carousel', name: 'app_carousel')]
    public function index(GalaxyRepository $galaxyRepository): Response
    {
        $carousel = $galaxyRepository->fetchGalaxiesWithFiles();

        return $this->render('carousel/index.html.twig', [
            'carousel' => $carousel
        ]);
    }
}