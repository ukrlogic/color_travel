<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 11/25/14
 * Time: 2:22 PM
 */

namespace Application\UkrLogic\TourBundle\Service;


use Application\UkrLogic\TourBundle\Entity\BusTour;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TourRepository
 * @package Application\UkrLogic\TourBundle\Service
 */
class TourRepository
{
    /**
     * @var GatewayContainer
     */
    private $gatewayContainer;

    private $entityManager;

    /**
     * @param GatewayContainer $gatewayContainer
     */
    public function __construct(GatewayContainer $gatewayContainer, EntityManager $entityManager)
    {
        $this->gatewayContainer = $gatewayContainer;
        $this->entityManager = $entityManager;
    }

    /**
     * @return BusTour[]
     * @throws \Exception
     */
    public function getTours()
    {
        $tours = [];

        /**
         * @var string $alias
         * @var GatewayAbstract $gateway
         */
        foreach ($this->gatewayContainer as $alias => $gateway) {
            if ($gateway->isActive()) {
                foreach ($gateway->getTours() as $tour) {
                    $this->insertBusTour($tour);
                }
            }
        }

        return $tours;
    }

    private function insertBusTour(BusTour $tour)
    {
        $meta = $this->entityManager->getClassMetadata('ApplicationUkrLogicTourBundle:BusTour');
        $query = '';
        foreach ($meta->getColumnNames(array_keys((array) new \ArrayObject($tour))) as $field) {
            $query .= sprintf('`%1$s`=:%1$s, ', $field);
        }

        $query = sprintf('INSERT INTO `%1$s` SET %2$s ON DUBLICATE KEY UPDATE %2$s', $meta->getTableName(), $query);

        var_dump($query); die;
    }
} 