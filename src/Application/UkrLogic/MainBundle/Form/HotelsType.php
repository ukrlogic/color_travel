<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 07.12.14
 * Time: 4:09
 */

namespace Application\UkrLogic\MainBundle\Form;


use Application\UkrLogic\TourBundle\Entity\Hotel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class HotelsType extends AbstractType
{
    /**
     * @var Hotel[]
     */
    private $hotels;

    function __construct(array $hotels)
    {
        $this->hotels = $hotels;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->hotels as $hotel) {
            $builder->add($hotel->getId(), 'checkbox', ['label' => $hotel->getName() . ' ' . $hotel->getCatname()]);
        }
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'hotel_type';
    }

} 