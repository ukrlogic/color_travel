<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 15.12.14
 * Time: 12:47
 */

namespace Application\UkrLogic\TourBundle\Service;


use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

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
     * @var string
     */
    private $defaultTourType;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param TourRepositoryContainer $repositoryContainer
     * @param string $defaultTourType
     * @param RequestStack $request
     */
    function __construct(TourRepositoryContainer $repositoryContainer, $defaultTourType, RequestStack $request)
    {
        $this->repositoryContainer = $repositoryContainer;
        $this->defaultTourType = $defaultTourType;
        $this->request = $request->getCurrentRequest();
    }

    /**
     * @param Form $form
     * @param integer $limit
     * @return array
     */
    public function find(Form $form, $limit)
    {
        return $this->repositoryContainer->getRepository($form->get('type')->getData() ?: $this->defaultTourType)->find($form, $limit);
    }

    /**
     * @param FormBuilderInterface $builder
     * @throws \Exception
     */
    public function modify(FormBuilderInterface $builder)
    {
        $type = $this->request->get('tour_form') ? $this->request->get('tour_form')['type'] : $this->defaultTourType;
//        $this->repositoryContainer->getRepository($type)->modify($builder);
    }
}