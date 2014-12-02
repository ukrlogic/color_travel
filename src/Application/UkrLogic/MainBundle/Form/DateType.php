<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 02.12.14
 * Time: 0:28
 */

namespace Application\UkrLogic\MainBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DateType
 * @package Application\UkrLogic\MainBundle\Form
 */
class DateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_from', 'date', [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'date_from'
                ]
            ])
            ->add('date_to', 'date', [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'date_to'
                ]
            ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'date_form_type';
    }

} 