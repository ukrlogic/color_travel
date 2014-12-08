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
        $cityId = null;
        $countryId = null;

        if (is_array($options->get('cities'))) {
            $cityId = array_search(true, $options->get('cities')) ? : '668';
        }

        if (is_array($options->get('countries'))) {
            $countryId = array_search(true, $options->get('countries')) ? : '668';
        }

        $request->TourSearchRequest->addChild('cityId', $cityId ? : '668');
        $request->TourSearchRequest->addChild('countryId', $countryId ? : '12');

        if (null !== $options->get('days_from')) {
            $request->TourSearchRequest->addChild('durationFrom', $options->get('days_from'));
        }

        if (null !== $options->get('days_from')) {
            $request->TourSearchRequest->addChild('durationFrom', $options->get('days_from'));
        }

        if (null !== $options->get('date_from')) {
            $request->TourSearchRequest->addChild('durationTill', $options->get('date_from'));
        }

        if (null !== $options->get('date_to')) {
            $request->TourSearchRequest->addChild('departureFrom', $options->get('date_to'));
        }

        if (null !== $options->get('days_from')) {
            $request->TourSearchRequest->addChild('departureTill', $options->get('days_from'));
        }

        if (null !== $options->get('price_from')) {
            $request->TourSearchRequest->addChild('priceFrom', $options->get('price_from'));
        }

        if (null !== $options->get('price_to')) {
            $request->TourSearchRequest->addChild('priceTill', $options->get('price_to'));
        }

        if (null !== $options->get('hotel_rate')) {
            $request->TourSearchRequest->addChild('allocRate', $options->get('hotel_rate'));
        }

        if (null !== $options->get('adults')) {
            $request->TourSearchRequest->addChild('adults', $options->get('adults'));
        }


        if (null !== $options->get('children')) {
            $request->TourSearchRequest->addChild('children', $options->get('children'));
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