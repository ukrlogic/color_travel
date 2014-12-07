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

class TourType extends AbstractType
{
    private $countryForm;
    private $cityForm;

    function __construct(AbstractType $countryForm, AbstractType $cityForm)
    {
        $this->countryForm = $countryForm;
        $this->cityForm = $cityForm;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cities', $this->cityForm, ['required' => false])
            ->add('countries', $this->countryForm)
            ->add('date_from', 'date', [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'date_from'
                ],
                'required' => false,
            ])
            ->add('date_to', 'date', [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'date_to'
                ],
                'required' => false,
            ])
            ->add('days_from', 'integer', ['attr' => ['class' => 'days_from'], 'required' => false])
            ->add('days_to', 'integer', ['attr' => ['class' => 'days_to'], 'required' => false])
            ->add('price_from', 'text', ['attr' => ['class' => 'price_from'], 'required' => false])
            ->add('price_to', 'text', ['attr' => ['class' => 'price_to'], 'required' => false])
            ->add('hotel_rate', 'text', ['attr' => ['class' => 'hotel_rate'], 'required' => false])
            ->add('adult_count', 'choice', [
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'attr' => [
                    'class' => 'selectordie'
                ],
                'required' => false,
                'data' => '1',
            ])
            ->add('child_count', 'choice', [
                'choices' => [
                    '0' => '0',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'attr' => [
                    'class' => 'selectordie'
                ],
                'required' => false,
                'data' => '0',
            ])
            ->add('is_avia', 'checkbox', ['attr' => ['class' => 'is_avia'], 'required' => false])
            ->add('page', 'hidden', ['empty_data' => 1, 'attr' => ['class' => 'page'], 'required' => false])
            ->add('submit', 'submit', ['label' => 'Поиск']);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tour_form';
    }

} 