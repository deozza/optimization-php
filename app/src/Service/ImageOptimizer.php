<?php

namespace App\Service;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageOptimizer
{
    private string $targetDirectory;
    
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->targetDirectory = $parameterBag->get('kernel.project_dir') . '/public/img';
    }
    
    public function resize(string $filename, int $width, int $height): string
    {
        return $filename;
    }
    
    public function getResponsiveImagePath(string $filename, string $size = 'medium'): string
    {
        return $filename;
    }
}