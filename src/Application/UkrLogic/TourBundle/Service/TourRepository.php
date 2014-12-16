<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 15.12.14
 * Time: 12:47
 */

namespace Application\UkrLogic\TourBundle\Service;


use Symfony\Component\Form\Form;

/**
 * Class TourRepository
 * @package Application\UkrLogic\TourBundle\Service
 */
class TourRepository
{
    /**
     * @var TourRepositoryContainer
     */
    private $repositoryContainer;

    /**
     * @param TourRepositoryContainer $repositoryContainer
     */
    function __construct(TourRepositoryContainer $repositoryContainer)
    {
        $this->repositoryContainer = $repositoryContainer;
    }

    /**
     * @param Form $form
     * @param integer $limit
     * @return array
     */
    public function find(Form $form, $limit)
    {
        $tours = [];

        /** @var RepositoryInterface $repository */
        foreach ($this->repositoryContainer as $repository) {
            $tours = array_merge($tours, $repository->find($form, $limit));
        }

        return $tours;
    }
}