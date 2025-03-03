<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class WebpExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('to_webp', [$this, 'convertToWebp']),
        ];
    }

    public function convertToWebp(string $filename): string
    {
        return preg_replace('/\.(png|jpg|JPG|jpeg)$/', '.webp', $filename);
    }
} 