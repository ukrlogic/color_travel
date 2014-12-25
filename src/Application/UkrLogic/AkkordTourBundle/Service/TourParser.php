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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Guzzle\Service\Client;

/**
 * Class TourParser
 * @package Application\UkrLogic\AkkordTourBundle\Service
 */
class TourParser
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
     * @var Client
     */
    private $tourLoader;

    /**
     * @param Client $curl
     * @param EntityRepository $countryRepository
     * @param EntityRepository $tourRepository
     * @param EntityManager $entityManager
     * @param Client $tourLoader
     */
    function __construct(
        Client $curl,
        EntityRepository $countryRepository,
        EntityRepository $tourRepository,
        EntityManager $entityManager,
        Client $tourLoader
    )
    {
        $this->curl = $curl;
        $this->countryRepository = $countryRepository;
        $this->tourRepository = $tourRepository;
        $this->entityManager = $entityManager;
        $this->tourLoader = $tourLoader;
    }

    public function loadTours()
    {
        /** @var \SimpleXMLElement $response */
        $response = $this->curl->getCommand('get_tours')->execute();
        $xml = simplexml_load_string($response->asXML(), "SimpleXMLElement", LIBXML_NOCDATA);
        $this->convertTours($xml);
    }

    /**
     * @param \SimpleXMLElement $xml
     */
    public function convertTours(\SimpleXMLElement $xml)
    {
        foreach ($xml as $xmlTour) {
            try {
                if ((string)$xmlTour->dates === '') {
                    continue;
                }

                $tourId = (int)$xmlTour->tour_id;

                if ($this->tourRepository->findOneBy(['tourId' => $tourId])) {
                    continue;
                }

                $dates = $this->getDatesArray($xmlTour->dates);

                foreach ($dates as $date) {
                    $tour = new BusTour();
                    $tour->setTourId($tourId);
                    $tour->setGateway('akkord_tour_bus');
                    $tour->setName((string)$xmlTour->name);
                    $tour->setDays((int)preg_replace('/[^0-9]*/', '', (string)$xmlTour->days));

                    $date = new \DateTime($date);
                    $tour->setDateFrom($date);
                    $tour->setDateTo($date);
                    $tour->setDates($date->format('Y-m-d'));

                    $tour->setPriceUah((float)$xmlTour->price);
                    $tour->setPriceUsd((float)$xmlTour->price_usd);
                    $tour->setPriceEur((float)$xmlTour->price_eur);
                    $tour->setCurrency((string)$xmlTour->tour_currency);


                    foreach ($xmlTour->countries as $country) {
                        $tour->addCountry($this->getCountry((string)$country->country));
                    }

                    $tourInfo = $this->getTour($tourId)->tour;

                    $tour->setRoute($tourInfo->route);
                    $tour->setDescription($tourInfo->description);

                    $this->entityManager->persist($tour);
                }
                /**
                 * исправить на запросы
                 */
                $this->entityManager->flush();
            } catch (\Exception $e) {
                var_dump($e->getMessage());
            }
        }
    }

    private function getTour($id)
    {
        $resp = $this->tourLoader->getCommand('get_tour', ['id' => $id])->execute();
        $xml = simplexml_load_string($resp->asXML(), "SimpleXMLElement", LIBXML_NOCDATA);
        return json_decode(json_encode((array)$xml, true));
    }

    /**
     * @param string $countryName
     * @return Country
     * @throws \Exception
     */
    private function getCountry($countryName)
    {
        $country = $this->countryRepository->findOneBy(['name' => $countryName]);

        if (!$country) {
            throw new \Exception(sprintf("Country '%s' not found", $countryName));
        }

        return $country;
    }

    /**
     * @param \SimpleXMLElement $xmlDates
     * @return array
     */
    private function getDatesArray(\SimpleXMLElement $xmlDates)
    {
        return explode(';', (string) $xmlDates);
    }

}