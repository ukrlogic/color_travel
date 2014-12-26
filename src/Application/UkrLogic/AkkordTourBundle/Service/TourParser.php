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
     * @var Country[]
     */
    private $countries = [];

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
        $sth1 = $this->entityManager->getConnection()->prepare('
            INSERT INTO `bus_tour`
               (`tour_id`, `gateway`, `name`, `days`, `date_from`, `date_to`, `dates`, `price_uah`, `price_usd`, `price_eur`, `currency`, `route`, `description`)
            VALUES
               (:tour_id, :gateway, :name, :days, :date_from, :date_to, :dates, :price_uah, :price_usd, :price_eur, :currency, :route, :description)

        ');

        $sth2 = $this->entityManager->getConnection()->prepare('
            INSERT INTO bustour_country (`bustour_id`, `country_id`) VALUES (:bustour_id, :country_id)
        ');

        foreach ($xml as $xmlTour) {
            try {
                if ((string)$xmlTour->dates === '') {
                    continue;
                }

                $tourId = (int)$xmlTour->tour_id;
                $dates = $this->getDatesArray($xmlTour->dates);
                $tourInfo = $this->getTour($tourId)->tour;

                foreach ($dates as $date) {
                    $date = new \DateTime($date);
                    $date = $date->format('Y-m-d');

                    $sth1->execute([
                        'tour_id' => $tourId,
                        'gateway' => 'akkord_tour_bus',
                        'name' => (string)$xmlTour->name,
                        'days' => (int)preg_replace('/[^0-9]*/', '', (string)$xmlTour->days),
                        'date_from' => $date,
                        'date_to' => $date,
                        'dates' => $date,
                        'price_uah' => (float)$xmlTour->price,
                        'price_usd' => (float)$xmlTour->price_usd,
                        'price_eur' => (float)$xmlTour->price_eur,
                        'currency' => (string)$xmlTour->tour_currency,
                        'route' => $tourInfo->route,
                        'description' => $tourInfo->description
                    ]);

                    $id = $this->entityManager->getConnection()->lastInsertId();

                    foreach ($xmlTour->countries as $country) {
                        $sth2->execute([
                            'bustour_id' => $id,
                            'country_id' => $this->getCountryId((string)$country->country),
                        ]);
                    }
                }
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
    private function getCountryId($countryName)
    {
        if (! array_key_exists($countryName, $this->countries)) {
            $country = $this->countryRepository->findOneBy(['name' => $countryName]);

            if (!$country) {
                throw new \Exception(sprintf("Country '%s' not found", $countryName));
            }

            $this->countries[$countryName] = $country;
        }


        return $this->countries[$countryName]->getId();
    }

    /**
     * @param \SimpleXMLElement $xmlDates
     * @return array
     */
    private function getDatesArray(\SimpleXMLElement $xmlDates)
    {
        return explode(';', (string)$xmlDates);
    }

}