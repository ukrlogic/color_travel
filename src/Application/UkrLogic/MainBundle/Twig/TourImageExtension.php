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

    public function getImages($id, $gateway)
    {
        $finder = new Finder();

        $images = [];

        try {
            /** @var SplFileInfo $file */
            foreach ($finder->files()->in(sprintf("%s/%s/%d", $this->imagesDir, $gateway, $id)) as $file) {
                $images[] = sprintf("images/%d/%s", $id, $file->getRelativePathname());
            }
        } catch (\Exception $e) {
            //folder does not exists, possibly write log or notify administrator
        }

        return $images;
    }

    public function getName()
    {
        return 'tour_image_extension';
    }
}