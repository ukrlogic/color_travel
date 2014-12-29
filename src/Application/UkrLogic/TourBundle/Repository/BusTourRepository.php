<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 02.12.14
 * Time: 14:41
 */

namespace Application\UkrLogic\TourBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Symfony\Component\Form\Form;

/**
 * Class BusTourRepository
 * @package Application\UkrLogic\TourBundle\Repository
 */
class BusTourRepository extends EntityRepository
{
    /**
     * @var EntityRepository
     */
    protected $countryRepository;

    /**
     * @return EntityRepository
     */
    public function getCountryRepository ()
    {
        return $this->countryRepository;
    }

    /**
     * @param EntityRepository $cRepo
     */
    public function setCountryRepository (EntityRepository $cRepo)
    {
        $this->countryRepository = $cRepo;
    }

    /**
     * @param Form $form
     * @param int $limit
     * @return array
     */
    public function filterBy (Form $form, $limit)
    {
        $qb = $this->createQueryBuilder('t')->where('1=1');
        
        /* Filter by country */
        $country = $form->get('country')->getData();
        $countryEntity = $country ? $this->getEntityManager()->getRepository('ApplicationUkrLogicTourBundle:Country')->find($country) : null;

        if ($countryEntity && $countryEntity->getTravelType() === 'bus') {
            $qb->andWhere(':country MEMBER OF t.countries')->setParameter('country', $country);
        }

        /* Filter by duration */
        $daysFrom = $form->get('days_from')->getData();
        $daysTo = $form->get('days_to')->getData();

        if ($daysFrom && $daysTo) {
            $qb->andWhere('t.days BETWEEN :days_from AND :days_to')
                ->setParameter('days_from', $daysFrom)
                ->setParameter('days_to', $daysTo);
        }

        /* Filter by price */
        $priceFrom = $form->get('price_from')->getData();
        $priceTo = $form->get('price_to')->getData();

        if ($priceFrom && $priceTo) {
            $qb->andWhere('t.price_usd BETWEEN :price_from AND :price_to')
                ->setParameter('price_from', $priceFrom)
                ->setParameter('price_to', $priceTo);
        }

        /* Filter by date */
        $dateFrom = $form->get('date_from')->getData();
        $dateTo = $form->get('date_to')->getData();

        if ($dateFrom && $dateTo) {
            $qb->andWhere('t.dateFrom >= :date_from AND t.dateTo <= :date_to')
                ->setParameter('date_from', $dateFrom)
                ->setParameter('date_to', $dateTo);
        }

        $query = $qb->setFirstResult(($form->get('page')->getData() - 1) * $limit)->setMaxResults($limit)->getQuery();

        return $query->getResult();
    }

}