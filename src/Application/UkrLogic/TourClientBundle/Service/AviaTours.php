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
    public function loadTours(FilterOption $options, $page = 1)
    {
        $request = simplexml_load_file(dirname(__FILE__) . '/../Resources/config/request.xml', 'SimpleXMLElement', LIBXML_NOCDATA);

        $request->TourSearchRequest->dataOffset = ($page - 1) * intval($request->TourSearchRequest->dataLimit);
        $request->TourSearchRequest->addChild('cityId', array_search(true, $options->get('cities', ['668' => true])) ? : '668');
        $request->TourSearchRequest->addChild('countryId', array_search(true, $options->get('countries', ['12' => true])) ? : '12');
        $request->TourSearchRequest->addChild('durationFrom', $options->get('days_from', 5));
        $request->TourSearchRequest->addChild('durationTill', $options->get('days_to', 15));
        $request->TourSearchRequest->addChild('departureFrom', $options->get('date_from', (new \DateTime('+1 day'))->format('Y-m-d')));
        $request->TourSearchRequest->addChild('departureTill', $options->get('date_to', (new \DateTime('+1 week'))->format('Y-m-d')));
        $request->TourSearchRequest->addChild('priceFrom', $options->get('price_from', 500));
        $request->TourSearchRequest->addChild('priceTill', $options->get('price_to', 5000));
        $request->TourSearchRequest->addChild('allocRate', $options->get('hotel_rate', 3));
        $request->TourSearchRequest->addChild('adults', $options->get('adults', 1));
        $request->TourSearchRequest->addChild('children', $options->get('children', 0));
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