<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 11/26/14
 * Time: 2:05 PM
 */

namespace Application\UkrLogic\TourBundle\Service;


/**
 * Class GatewayContainer
 * @package Application\UkrLogic\TourBundle\Service
 */
use Symfony\Component\HttpFoundation\Request;
use Traversable;

/**
 * Class GatewayContainer
 * @package Application\UkrLogic\TourBundle\Service
 */
class GatewayContainer implements \IteratorAggregate
{
    /**
     * @var GatewayAbstract[]
     */
    private $gateways = [];

    /**
     * @param GatewayAbstract $gateway
     * @param string $alias
     * @return $this
     * @throws \Exception
     */
    public function add(GatewayAbstract $gateway, $alias)
    {
        if ($this->has($alias)) {
            throw new \Exception(sprintf("Gateway '%s' is already exists", $alias));
        }

        $this->gateways[$alias] = $gateway;

        return $this;
    }

    /**
     * @param string $alias
     * @return GatewayAbstract
     * @throws \Exception
     */
    public function get($alias)
    {
        if ($this->has($alias)) {
            return $this->gateways[$alias];
        }

        throw new \Exception(sprintf("Gateway '%s' not found", $alias));
    }

    /**
     * @param string $alias
     * @return bool
     */
    public function has($alias)
    {
        return array_key_exists($alias, $this->gateways);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->gateways);
    }


} 