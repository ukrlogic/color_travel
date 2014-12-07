<?php

namespace Application\UkrLogic\MainBundle\Twig;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class GetCountriesExtension extends \Twig_Extension
{
    /**
     * @var EntityRepository
     */
    private $countryRepo;

    /**
     * @param EntityRepository $repo
     */
    public function setCountryRepo(EntityRepository $repo)
    {
        $this->countryRepo = $repo;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getCountries', [$this, 'getCountries']),
        ];
    }

    public function getCountries()
    {
        return $this->countryRepo->findAll();
    }

    public function getName()
    {
        return 'get_countries_extension';
    }
}