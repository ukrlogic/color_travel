<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 30.11.14
 * Time: 13:09
 */

namespace Application\UkrLogic\MainBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BusTourFilterForm extends AbstractType
{
    private $cForm;

    function __construct(AbstractType $cForm)
    {
        $this->cForm = $cForm;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('countries', $this->cForm)
            ->add('date', new DateType())
            ->add('days', new DaysType())
            ->add('price', new PriceType())
            ->add('submit', 'submit', ['label' => 'Поиск']);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'bus_tour_filter';
    }

} 