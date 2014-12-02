<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 02.12.14
 * Time: 14:41
 */

namespace Application\UkrLogic\TourBundle\Repository;


use Application\UkrLogic\TourBundle\Service\FilterOption;
use Doctrine\ORM\EntityRepository;

class BusTourRepository extends EntityRepository
{
    /**
     * @var EntityRepository
     */
    private $countryRepository;

    /**
     * @return EntityRepository
     */
    public function getCountryRepository()
    {
        return $this->countryRepository;
    }

    /**
     * @param EntityRepository $cRepo
     */
    public function setCountryRepository(EntityRepository $cRepo)
    {
        $this->countryRepository = $cRepo;
    }

    public function filterBy(FilterOption $options)
    {
        $qb = $this->createQueryBuilder('t');

        if (is_array($options->get('countries')) && $code = array_search(true, $options->get('countries'))) {
            $country = $this->getCountryRepository()->findOneBy(['alpha2' => $code]);
            if (null !== $country) {
                $qb->where(':country MEMBER OF t.countries')->setParameter('country', $country);
            }
        }

        if ($daysFrom = $options->get('days_from') && $daysTo = $options->get('days_to')) {
            $qb->where('t.days BETWEEN :days_from AND :days_to')
                ->setParameter('days_from', $daysFrom)
                ->setParameter('days_to', $daysTo);
        }

        if ($priceFrom = $options->get('price_from') && $priceTo = $options->get('price_to')) {
            $qb->where('t.price_uah BETWEEN :price_from AND :price_to')
                ->setParameter('price_from', $priceFrom)
                ->setParameter('price_to', $priceTo);
        }

        if ($dateFrom = $options->get('date_from') && $dateTo = $options->get('date_to')) {
            $qb->where('t.dateFrom >= :date_from AND t.dateTo <= :date_to')
                ->setParameter('date_from', $dateFrom)
                ->setParameter('date_to', $dateTo);
        }

        $query = $qb->getQuery();

        var_dump($query->getSQL());

        return $query->getResult();
    }
} 