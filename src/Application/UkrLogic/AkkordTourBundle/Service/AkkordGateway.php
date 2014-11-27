<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 11/26/14
 * Time: 3:17 PM
 */

namespace Application\UkrLogic\AkkordTourBundle\Service;


use Application\UkrLogic\TourBundle\Entity\BusTour;
use Application\UkrLogic\TourBundle\Service\GatewayAbstract;
use Application\UkrLogic\TourBundle\Service\Tour;
use Guzzle\Service\Client;

class AkkordGateway extends GatewayAbstract
{
    /**
     * @var Client
     */
    private $curl;

    /**
     * @var RequestAdapter
     */
    private $adapter;

    function __construct(Client $curl, RequestAdapter $adapter)
    {
        $this->curl = $curl;
        $this->adapter = $adapter;
    }

    /**
     * @return BusTour[]
     */
    public function getTours()
    {
        /** @var \SimpleXMLElement $response */
        $response = $this->curl->getCommand('get_tours')->execute();

        $xml = $this->repairXML($response);

        return $this->adapter->loadXML($xml)->getTours();

    }

    /**
     * @param \SimpleXMLElement $xml
     * @return \SimpleXMLElement
     */
    private function repairXML(\SimpleXMLElement $xml)
    {
        $xml->saveXML('/tmp/response.xml');

        return simplexml_load_file('/tmp/response.xml', 'SimpleXMLElement', LIBXML_NOCDATA);
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return true;
    }


}