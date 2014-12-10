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
use Symfony\Component\Validator\Constraints\DateTime;

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

    public function filterBy(FilterOption $options, $page = 1, $limit = 15)
    {
        try {
            $qb = $this->createQueryBuilder('t');

            if (is_array($options->get('countries')) && $cid = array_search(true, $options->get('countries'))) {
                $country = $this->getCountryRepository()->find($cid);
                if (null !== $country) {
                    $qb->where(':country MEMBER OF t.countries')->setParameter('country', $country);
                }
            }

            $daysFrom = $options->get('days_from');
            $daysTo = $options->get('days_to');
            if ($daysFrom && $daysTo) {
                $qb->where('t.days BETWEEN :days_from AND :days_to')
                    ->setParameter('days_from', $daysFrom ? : 6)
                    ->setParameter('days_to', $daysTo ? : 15);
            }

            $priceFrom = $options->get('price_from');
            $priceTo = $options->get('price_to');

            if ($priceFrom && $priceTo) {
                $qb->where('t.price_uah BETWEEN :price_from AND :price_to')
                    ->setParameter('price_from', $priceFrom ? : 100)
                    ->setParameter('price_to', $priceTo ? : 3500);
            }

            $dateFrom = $options->get('date_from');
            $dateTo = $options->get('date_to');

            if ($dateFrom && $dateTo) {
                $qb->where('t.dateFrom >= :date_from AND t.dateTo <= :date_to')
                    ->setParameter('date_from', $dateFrom)
                    ->setParameter('date_to', $dateTo);
            }

            $query = $qb->setFirstResult(($page - 1) * $limit)->setMaxResults($limit)->getQuery();

            return $query->getResult();
        } catch (\Exception $ex) {
            return [];
        }
    }
} 