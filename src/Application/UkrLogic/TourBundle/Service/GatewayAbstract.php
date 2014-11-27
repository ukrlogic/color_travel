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
     * @var Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @return Tour[]
     */
    public abstract function getTours();

    /**
     * @return boolean
     */
    public abstract function isActive();

    /**
     * @return Request
     */
    protected final function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public final function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

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

}