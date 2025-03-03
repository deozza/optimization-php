<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IpxController extends AbstractController
{
    #[Route('/ipx/{path}', name: 'ipx', requirements: ['path' => '.+'])]
    public function ipx(string $path): Response
    {
        $ipxUrl = $_ENV['IPX_URL'] ?? 'http://localhost:8889';
        $url = $ipxUrl . '/' . $path;

        return $this->redirect($url);
    }
}
