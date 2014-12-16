<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 15.12.14
 * Time: 13:15
 */

namespace Application\UkrLogic\AkkordTourBundle\Service;


use Application\UkrLogic\TourBundle\Repository\BusTourRepository;
use Application\UkrLogic\TourBundle\Service\RepositoryInterface;
use Application\UkrLogic\TourBundle\Service\Tour;
use Symfony\Component\Form\Form;

/**
 * Class TourRepository
 * @package Application\UkrLogic\AkkordTourBundle\Service
 */
class TourRepository implements RepositoryInterface
{
    /**
     * @var BusTourRepository
     */
    private $entityRepository;

    /**
     * @param BusTourRepository $entityRepository
     */
    function __construct (BusTourRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * @param Form $form
     * @param integer $limit
     * @return Tour[]
     */
    public function find (Form $form, $limit)
    {
        if ($form->get('type')->getData() !== 'bus') {
            return [];
        }

        $tours = [];
        $entities = $this->entityRepository->filterBy($form, $limit);

        foreach ($entities as $entity) {
            $tours[] = new Tour('bus', $entity);
        }

        return $tours;
    }

}