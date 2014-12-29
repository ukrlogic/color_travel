<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 03.12.14
 * Time: 0:08
 */

namespace Application\UkrLogic\TourClientBundle\Service;

use Application\UkrLogic\TourBundle\Entity\Meal;
use Application\UkrLogic\TourBundle\Service\RepositoryInterface;
use Application\UkrLogic\TourBundle\Service\Tour;
use Doctrine\ORM\EntityRepository;
use Guzzle\Http\Message\Response;
use Guzzle\Service\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Session;


/**
 * Class TourRepository
 * @package Application\UkrLogic\TourClientBundle\Service
 */
class TourRepository implements RepositoryInterface
{
    const STATIC_EXCHANGE_RATE = 15;
    /**
     * @var Session
     */
    private $session;

    /**
     * @var Client
     */
    private $curl;

    /**
     * @var EntityRepository
     */
    private $hotelRepository;

    /**
     * @var EntityRepository;
     */
    private $countryRepository;


    /**
     * @param Session $session
     * @param Client $curl
     * @param EntityRepository $hotelRepository
     * @param EntityRepository $countryRepository
     */
    function __construct(Session $session, Client $curl, EntityRepository $hotelRepository, EntityRepository $countryRepository)
    {
        $this->session = $session;
        $this->curl = $curl;
        $this->hotelRepository = $hotelRepository;
        $this->countryRepository = $countryRepository;
    }

    /**
     * @param Form $form
     * @param integer $limit
     * @return Tour[]
     */
    public function find(Form $form, $limit)
    {
        if ($form->get('type')->getData() !== 'avia') {
            return [];
        }

        $tours = [];
        $lastSearch = [];
        $search = $this->loadTours($form, $limit);

        for ($i = 0; $i < count($search->Tours->Tour); $i++) {
            $tours[] = new Tour('avia', $search->Tours->Tour[$i]);
            $lastSearch[(string)$search->Tours->Tour[$i]->id] = $search->Tours->Tour[$i];
        }

        $this->session->set('lastSearch', json_decode(json_encode($lastSearch), true));

        return $tours;
    }

    /**
     * @param Form $form
     * @param integer $limit
     * @return \SimpleXMLElement
     */
    protected function loadTours(Form $form, $limit)
    {
        $request = simplexml_load_file(dirname(__FILE__) . '/../Resources/request.xml', 'SimpleXMLElement', LIBXML_NOCDATA);

        /* Filter by city and country */
        $request->TourSearchRequest->addChild('cityId', $form->get('city')->getData() ?: '668');
        $country = $form->get('country')->getData() ? $form->get('country')->getData()->getId() : '12';
        $countryEntity = $this->countryRepository->find($country);

        if (trim($countryEntity->getTravelType()) !== 'avia') {
            $country = '12';
        }

        $request->TourSearchRequest->addChild('countryId', $country);

        /* Filter by duration */
        $request->TourSearchRequest->addChild('durationFrom', $form->get('days_from')->getData() ?: 5);
        $request->TourSearchRequest->addChild('durationTill', $form->get('days_to')->getData() ?: 15);

        /* Filter by date */
        $departureFrom = $form->get('date_from')->getData() ?: new \DateTime();
        $departureTill = $form->get('date_to')->getData() ?: new \DateTime('+ 1 month');

        $request->TourSearchRequest->addChild('departureFrom', $departureFrom->format('Y-m-d'));
        $request->TourSearchRequest->addChild('departureTill', $departureTill->format('Y-m-d'));

        /* Filter by price */
        $priceFrom = $form->get('price_from')->getData();
        $priceTill = $form->get('price_to')->getData();

        if ($priceFrom && $priceTill) {
            $request->TourSearchRequest->addChild('priceFrom', round($priceFrom / $this->getExchangeRate()));
            $request->TourSearchRequest->addChild('priceTill', round($priceTill / $this->getExchangeRate()));
        }

        /* Filter by hotel */
        $hotel = $form->get('hotel')->getData() ? $this->hotelRepository->findOneBy(['name' => $form->get('hotel')->getData()]) : null;

        if ($hotel) {
            $request->TourSearchRequest->addChild('allocationIds')->addChild('id', $hotel->getId());
        }

        /* Filter by hotel rate */
        $allocRate = $form->get('hotel_rate')->getData();

        if ($allocRate) {
            $request->TourSearchRequest->addChild('allocRate', intval($allocRate));
        }

        /* Filter by adults and child count */
        $request->TourSearchRequest->addChild('adults', $form->get('adult_count')->getData());
        $request->TourSearchRequest->addChild('children', $form->get('child_count')->getData());

        /* Filter by meal type */
        $meals = $form->get('meal')->getData();

        if (count($meals) > 0) {
            $request->TourSearchRequest->addChild('mealIds');

            /** @var Meal $meal */
            foreach ($meals as $meal) {
                $request->TourSearchRequest->mealIds->addChild('id', $meal->getId());
            }
        }

        /* Filter's pages */
        $request->TourSearchRequest->dataLimit = $limit;
        $request->TourSearchRequest->dataOffset = ($form->get('page')->getData() - 1) * $limit;

        $response = $this->curl->post('http://tourclient.ru/f/exml/58658/tours_export', [], ['request' => $request->asXML()])->send()->getBody(true);

        return simplexml_load_string(str_replace('&', 'and', $response), "SimpleXMLElement", LIBXML_NOCDATA);
    }

    public function getExchangeRate()
    {
        if ($exchangeRate = $this->session->get('exchange_rate')) {
            return $exchangeRate;
        }

        $response = $this->curl->get('http://resources.finance.ua/ru/public/currency-cash.json')->send();
        $body = $response->getBody(true);

        $data = json_decode($body, true);

        if (!array_key_exists('organizations', $data)) {
            return self::STATIC_EXCHANGE_RATE;
        }

        if (!count($data['organizations'])) {
            return self::STATIC_EXCHANGE_RATE;
        }

        $organization = reset($data['organizations']);

        if (!array_key_exists('currencies', $organization)) {
            return self::STATIC_EXCHANGE_RATE;
        }

        if (!array_key_exists('USD', $organization['currencies'])) {
            return self::STATIC_EXCHANGE_RATE;
        }

        if (!array_key_exists('ask', $organization['currencies']['USD'])
            || !array_key_exists('bid', $organization['currencies']['USD'])
        ) {
            return self::STATIC_EXCHANGE_RATE;
        }

        $exchangeRate = array_sum($organization['currencies']['USD']) / count($organization['currencies']['USD']);
        $this->session->set('exchangeRate', $exchangeRate);

        return $exchangeRate;
    }

    /**
     * Adds specified fields to form builder
     *
     * @param FormBuilder $form
     */
    public function modify(FormBuilder $form)
    {

    }

}