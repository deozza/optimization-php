<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class WebpConvertisor extends AbstractExtension
{
    public function getFilters():array
    {
        return [
            new TwigFilter('webp', [$this, 'webpConvertissorTwig']),
        ];
    }

    public function webpConvertissorTwig(string $filename): string
    {
        $wrongName = pathinfo($filename, PATHINFO_FILENAME);
        return $wrongName . '.webp';
    }
}