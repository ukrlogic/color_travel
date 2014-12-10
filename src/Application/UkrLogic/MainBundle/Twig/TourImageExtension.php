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

            if (! file_exists($dir)) {
                @mkdir($dir);
            }

            /** @var SplFileInfo $file */
            foreach ($finder->files()->in($dir) as $file) {
                if (! $file->getRelativePath() || $file->getRelativePath() === 'originals') {
                    $images[] = sprintf("images/%s/%d/%s", $path, $id, $file->getRelativePathname());
                }
            }
        } catch (\Exception $e) {
            $images[] = "images/noimagew.png?".$e->getMessage();
        }

        return $images;
    }

    public function getName()
    {
        return 'tour_image_extension';
    }
}