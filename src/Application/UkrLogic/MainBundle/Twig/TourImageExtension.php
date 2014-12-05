<?php

namespace Application\UkrLogic\MainBundle\Twig;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class TourImageExtension extends \Twig_Extension
{
    private $imagesDir;

    /**
     * @param string $appDir
     */
    public function setImagesDir($appDir)
    {
        $this->imagesDir = $appDir;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getTourImages', [$this, 'getImages']),
        ];
    }

    public function getImages($id, $path)
    {
        $finder = new Finder();

        $images = [];

        try {
            $dir = sprintf("%s/%s/%d", $this->imagesDir, $path, $id);
            $large = file_exists($dir . '/large');
            if ($large) {
                $dir .= '/large';
            }
            /** @var SplFileInfo $file */
            foreach ($finder->files()->in($dir) as $file) {
                $images[] = sprintf("images/%s/%d/%s", $path, $id, ($large ? 'large/' : '') . $file->getRelativePathname());
            }
        } catch (\Exception $e) {
            $images[] = "images/noimagew.png";
        }

        return $images;
    }

    public function getName()
    {
        return 'tour_image_extension';
    }
}