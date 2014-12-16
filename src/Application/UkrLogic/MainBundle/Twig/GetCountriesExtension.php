<?php

namespace Application\UkrLogic\MainBundle\Twig;

use Application\UkrLogic\TourBundle\Entity\Country;
use Doctrine\ORM\EntityRepository;

/**
 * Class GetCountriesExtension
 * @package Application\UkrLogic\MainBundle\Twig
 */
class GetCountriesExtension extends \Twig_Extension
{
    /**
     * @var Country[]
     */
    private $countries = [];

    /**
     * @param EntityRepository $repo
     */
    public function __construct (EntityRepository $repo)
    {
        /** @var Country $country */
        foreach ($repo->findAll() as $country) {
            $this->countries[$country->getId()] = $country;
        }
    }

    /**
     * @return array
     */
    public function getFunctions ()
    {
        return [
            new \Twig_SimpleFunction('getCountries', [$this, 'getCountries']),
            new \Twig_SimpleFunction('getCountry', [$this, 'getCountry']),
        ];
    }

    /**
     * @return \Application\UkrLogic\TourBundle\Entity\Country[]
     */
    public function getCountries ()
    {
        return $this->countries;
    }

    /**
     * @param integer $id
     * @return Country
     * @throws \Exception
     */
    public function getCountry ($id)
    {
        if (! array_key_exists($id, $this->countries)) {
            throw new \Exception(sprintf("Country with id '%d' not found", $id));
        }

        return $this->countries[$id];
    }

    /**
     * @return string
     */
    public function getName ()
    {
        return 'get_countries_extension';
    }
}