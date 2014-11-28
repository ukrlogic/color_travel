<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 11/25/14
 * Time: 2:22 PM
 */

namespace Application\UkrLogic\TourBundle\Service;


/**
 * Class TourParser
 * @package Application\UkrLogic\TourBundle\Service
 */
class TourParser
{
    /**
     * @var GatewayContainer
     */
    private $gatewayContainer;

    /**
     * @param GatewayContainer $gatewayContainer
     */
    public function __construct(GatewayContainer $gatewayContainer)
    {
        $this->gatewayContainer = $gatewayContainer;
    }

    public function parse()
    {
        try {
            /**
             * @var string $alias
             * @var GatewayAbstract $gateway
             */
            foreach ($this->gatewayContainer as $alias => $gateway) if ($gateway->isActive()) {
                $gateway->setAlias($alias)->loadTours();
            }
        } catch (\Exception $e) {
            //написать норм обработку ошибок парсера
            var_dump($e->getMessage());
            die;
        }
    }
}