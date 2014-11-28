<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 11/26/14
 * Time: 1:43 PM
 */

namespace Application\UkrLogic\TourBundle\Service;


use Symfony\Component\HttpFoundation\Request;

/**
 * Class GatewayAbstract
 * @package Application\UkrLogic\MainBundle\Service
 */
abstract class GatewayAbstract
{
    /**
     * @var string
     */
    protected $alias;

    /**
     * @return array
     */
    public abstract function loadTours();

    /**
     * @return boolean
     */
    public abstract function isActive();

    /**
     * @return string
     */
    protected final function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     * @return $this
     */
    public final function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @return \SimpleXMLElement
     */
    protected final function repairXML(\SimpleXMLElement $xml)
    {
        $xml->saveXML('/tmp/response.xml');

        return simplexml_load_file('/tmp/response.xml', 'SimpleXMLElement', LIBXML_NOCDATA);
    }
}