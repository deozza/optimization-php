<?php

namespace App\Twig;

use App\Service\ImageOptimizer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $imageOptimizer;
    
    public function __construct(ImageOptimizer $imageOptimizer)
    {
        $this->imageOptimizer = $imageOptimizer;
    }
    
    public function getFunctions(): array
    {
        return [
            new TwigFunction('responsive_image', [$this, 'getResponsiveImage']),
        ];
    }
    
    public function getFilters(): array
    {
        return [
            new TwigFilter('truncate', [$this, 'truncateText']),
        ];
    }
    
    public function getResponsiveImage(string $filename, string $size = 'medium'): string
    {
        return $this->imageOptimizer->getResponsiveImagePath($filename, $size);
    }
    
    public function truncateText(string $text, int $length = 100, string $suffix = '...'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        
        return mb_substr($text, 0, $length) . $suffix;
    }
}