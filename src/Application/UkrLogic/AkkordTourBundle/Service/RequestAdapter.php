<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 11/26/14
 * Time: 3:30 PM
 */

namespace Application\UkrLogic\AkkordTourBundle\Service;


use Application\UkrLogic\TourBundle\Entity\BusTour;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestAdapter
 * @package Application\UkrLogic\AkkordTourBundle\Service
 */
class RequestAdapter
{
    /**
     * @var \SimpleXMLElement
     */
    private $xml;

    /**
     * @var EntityRepository
     */
    private $countryRepository;

    /**
     * @param EntityRepository $countryRepository
     */
    function __construct(EntityRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @return $this
     */
    public function loadXML(\SimpleXMLElement $xml)
    {
        $this->xml = $xml;

        return $this;
    }


    public function getTours()
    {
        /** @var \SimpleXMLElement $xmlTour */
        foreach ($this->xml as $xmlTour) {
            var_dump($xmlTour);

            $tour = new BusTour();
            $tour->setTourId((int) $xmlTour->tour_id);
            $tour->setName((string) $xmlTour->name);
            $tour->setDays($xmlTour->days);
            $tour->setDays((int) preg_replace('/[^0-9]*/', '', (string) $xmlTour->days));
            $tour->setPrice((float) $xmlTour->price);
            $tour->setCurrency((string) $xmlTour->tour_currency);
            var_dump($tour);die;
        }

    }

} 