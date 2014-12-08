<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 03.12.14
 * Time: 0:08
 */

namespace Application\UkrLogic\TourClientBundle\Service;


use Application\UkrLogic\TourBundle\Service\FilterOption;

/**
 * Class AviaTours
 * @package Application\UkrLogic\TourClientBundle\Service
 */
class AviaTours
{
    public function loadTours(FilterOption $options, $page = 1, $limit = 15)
    {
        $request = simplexml_load_file(dirname(__FILE__) . '/../Resources/config/request.xml', 'SimpleXMLElement', LIBXML_NOCDATA);

        $request->TourSearchRequest->dataLimit = $limit;
        $request->TourSearchRequest->dataOffset = ($page - 1) * $limit;

        $request->TourSearchRequest->addChild('cityId', array_search(true, $options->get('cities')));
        $request->TourSearchRequest->addChild('countryId', array_search(true, $options->get('countries')));

        if (null !== $options->get('days_from')) {
            $request->TourSearchRequest->addChild('durationFrom', $options->get('days_from'));
        }

        if (null !==  $options->get('days_to')) {
            $request->TourSearchRequest->addChild('durationTill', $options->get('days_to'));
        }

        if ($options->get('date_to') instanceof \DateTime) {
            $request->TourSearchRequest->addChild('departureFrom', $options->get('date_from')->format('Y-m-d'));
        }

        if ($options->get('date_to') instanceof \DateTime) {
            $request->TourSearchRequest->addChild('departureTill', $options->get('date_to')->format('Y-m-d'));
        }

        if (null !== $options->get('price_from')) {
            $request->TourSearchRequest->addChild('priceFrom', $options->get('price_from'));
        }

        if (null !== $options->get('price_to')) {
            $request->TourSearchRequest->addChild('priceTill', $options->get('price_to'));
        }

        if (null !== $options->get('hotel_rate')) {
            $request->TourSearchRequest->addChild('allocRate', intval($options->get('hotel_rate')));
        }

        if (null !== $options->get('adult_count')) {
            $request->TourSearchRequest->addChild('adults', $options->get('adult_count'));
        }


        if (null !== $options->get('child_count')) {
            $request->TourSearchRequest->addChild('children', $options->get('child_count'));
        }

        $ch = curl_init();

        $data = array('request' => $request->asXML());

        curl_setopt($ch, CURLOPT_URL, "http://tourclient.ru/f/exml/58658/tours_export");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        return simplexml_load_string(str_replace('&', 'and', $response), "SimpleXMLElement", LIBXML_NOCDATA);
    }

}