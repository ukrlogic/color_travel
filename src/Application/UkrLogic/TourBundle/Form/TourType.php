<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 30.11.14
 * Time: 13:09
 */

namespace Application\UkrLogic\TourBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TourType
 * @package Application\UkrLogic\TourBundle\Form
 */
class TourType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', 'choice', [
                'required'    => false,
                'multiple'    => false,
                'expanded'    => true,
                'empty_value' => false,
                'choices'     => [
                    '1918' => 'Харьков',
                    '668'  => 'Киев',
                    '1919' => 'Днепропетровск',
                    '1413' => 'Одесса',
                ],
            ])
            ->add('country', 'entity', [
                'class'         => 'Application\UkrLogic\TourBundle\Entity\Country',
                'multiple'      => false,
                'expanded'      => true,
                'required'      => false,
                'empty_value'   => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')->where('c.active = 1')->orderBy('c.name', 'ASC');
                },
            ])
            ->add('date_from', 'date', [
                'widget'   => 'single_text',
                'attr'     => [
                    'class' => 'date_from'
                ],
                'required' => false,
            ])
            ->add('date_to', 'date', [
                'widget'   => 'single_text',
                'attr'     => [
                    'class' => 'date_to'
                ],
                'required' => false,
            ])
            ->add('days_from', 'integer', ['attr' => ['class' => 'days_from'], 'required' => false])
            ->add('days_to', 'integer', ['attr' => ['class' => 'days_to'], 'required' => false])
            ->add('price_from', 'text', ['attr' => ['class' => 'price_from'], 'required' => false])
            ->add('price_to', 'text', ['attr' => ['class' => 'price_to'], 'required' => false])
            ->add('hotel_rate', 'text', ['attr' => ['class' => 'hotel_rate'], 'required' => false])
            ->add('meal', 'entity', [
                'class'         => 'Application\UkrLogic\TourBundle\Entity\Meal',
                'multiple'      => true,
                'expanded'      => true,
                'required'      => false,
                'empty_value'   => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')->where('e.active = 1')->orderBy('e.name', 'ASC');
                },
            ])
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
                'required'    => false,
                'data'        => '1',
                'empty_value' => null,
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
                'required'    => false,
                'data'        => '0',
                'empty_value' => null,
            ])
            ->add('type', 'hidden', ['data' => 'bus', 'attr' => ['class' => 'travel_type']])
            ->add('page', 'hidden', ['data' => 1, 'attr' => ['class' => 'page']])
            ->add('submit', 'submit', ['label' => 'Поиск']);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName ()
    {
        return 'tour_form';
    }

} 