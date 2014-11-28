<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 11/26/14
 * Time: 3:17 PM
 */

namespace Application\UkrLogic\AkkordTourBundle\Service;


use Application\UkrLogic\TourBundle\Entity\BusTour;
use Application\UkrLogic\TourBundle\Entity\Country;
use Application\UkrLogic\TourBundle\Service\GatewayAbstract;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Guzzle\Service\Client;

/**
 * Class BusTours
 * @package Application\UkrLogic\AkkordTourBundle\Service
 */
class BusTours extends GatewayAbstract
{
    /**
     * @var Client
     */
    private $curl;

    /**
     * @var EntityRepository
     */
    private $countryRepository;

    /**
     * @var EntityRepository
     */
    private $tourRepository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param Client $curl
     * @param EntityRepository $countryRepository
     * @param EntityRepository $tourRepository
     */
    function __construct(Client $curl, EntityRepository $countryRepository, EntityRepository $tourRepository, EntityManager $entityManager)
    {
        $this->curl = $curl;
        $this->countryRepository = $countryRepository;
        $this->tourRepository = $tourRepository;
        $this->entityManager = $entityManager;
    }

    public function loadTours()
    {
        /** @var \SimpleXMLElement $response */
        $response = $this->curl->getCommand('get_tours')->execute();
        $xml = $this->repairXML($response);
        $this->convertTours($xml);
        $this->entityManager->flush();
    }

    /**
     * @param \SimpleXMLElement $xml
     */
    public function convertTours(\SimpleXMLElement $xml)
    {
        foreach ($xml as $xmlTour) {
            if ((string)$xmlTour->dates === '') {
                continue;
            }

            $tourId = (int)$xmlTour->tour_id;

            if ($this->tourRepository->findOneBy(['tourId' => $tourId])) {
                continue;
            }

            $tour = new BusTour();
            $tour->setTourId($tourId);
            $tour->setGateway($this->getAlias());
            $tour->setName((string)$xmlTour->name);
            $tour->setDays((int)preg_replace('/[^0-9]*/', '', (string)$xmlTour->days));
            $tour->setDates((string)$xmlTour->dates);
            $tour->setPriceUah((float)$xmlTour->price);
            $tour->setPriceUsd((float)$xmlTour->price_usd);
            $tour->setPriceEur((float)$xmlTour->price_eur);
            $tour->setCurrency((string)$xmlTour->tour_currency);

            foreach ($xmlTour->countries as $country) {
                $tour->addCounty($this->getCountry((string)$country->country));
            }

            $this->entityManager->persist($tour);
        }
    }

    /**
     * @param string $countryName
     * @return Country
     */
    private function getCountry($countryName)
    {
        $country = $this->countryRepository->findOneBy(['nameRu' => $countryName]);

        if (!$country) {
            $country = new Country();
            $country->setNameRu($countryName);
            $country->setSearch(false);

            $this->entityManager->persist($country);
            $this->entityManager->flush();
        }

        return $country;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return true;
    }


}