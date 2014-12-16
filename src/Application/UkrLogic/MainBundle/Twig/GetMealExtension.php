<?php

namespace Application\UkrLogic\MainBundle\Twig;

use Application\UkrLogic\TourBundle\Entity\Meal;
use Doctrine\ORM\EntityRepository;

/**
 * Class GetMealExtension
 * @package Application\UkrLogic\MainBundle\Twig
 */
class GetMealExtension extends \Twig_Extension
{
    /**
     * @var Meal[]
     */
    private $meals = [];

    /**
     * @param EntityRepository $repo
     */
    public function __construct (EntityRepository $repo)
    {
        /** @var Meal $meal */
        foreach ($repo->findAll() as $meal) {
            $this->meals[$meal->getId()] = $meal;
        }
    }

    /**
     * @return array
     */
    public function getFunctions ()
    {
        return [
            new \Twig_SimpleFunction('getAllMealTypes', [$this, 'getAllMealTypes']),
            new \Twig_SimpleFunction('getMeal', [$this, 'getMeal']),
        ];
    }

    /**
     * @return \Application\UkrLogic\TourBundle\Entity\Country[]
     */
    public function getAllMealTypes ()
    {
        return $this->meals;
    }

    /**
     * @param integer $id
     * @return Meal
     * @throws \Exception
     */
    public function getMeal ($id)
    {
        if (! array_key_exists($id, $this->meals)) {
            throw new \Exception(sprintf("Meal type with id '%d' not found", $id));
        }

        return $this->meals[$id];
    }

    /**
     * @return string
     */
    public function getName ()
    {
        return 'get_meals_extension';
    }
}