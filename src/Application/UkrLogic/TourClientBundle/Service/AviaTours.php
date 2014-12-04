<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 03.12.14
 * Time: 0:08
 */

namespace Application\UkrLogic\TourClientBundle\Service;


use Application\UkrLogic\TourBundle\Service\GatewayAbstract;
use Guzzle\Service\Client;

/**
 * Class AviaTours
 * @package Application\UkrLogic\TourClientBundle\Service
 */
class AviaTours extends GatewayAbstract
{
    /**
     * @var Client
     */
    private $curl;

    /**
     * @param Client $curl
     */
    function __construct(Client $curl)
    {
        $this->curl = $curl;
    }

    /**
     *
     */
    public function loadTours()
    {
        $repeat = true;
        $offset = 0;
        $request = simplexml_load_file(dirname(__FILE__) . '/../Resources/config/request.xml', 'SimpleXMLElement', LIBXML_NOCDATA);
        $request->addChild('departureFrom', (new \DateTime())->format('Y-m-d'));

        while ($repeat) {
            $ch = curl_init();
            $request->TourSearchRequest->dataOffset = $offset;

//            var_dump($request);die;
            $data = array('request' => $request->asXML());

            curl_setopt($ch, CURLOPT_URL, "http://tourclient.ru/f/exml/58658/tours_export");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response=curl_exec($ch);
            curl_close($ch);

            $response = str_replace('&', 'and', $response);
            $response = new \SimpleXMLElement($response);

            var_dump($response);die;
        }
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return false;
    }

} 