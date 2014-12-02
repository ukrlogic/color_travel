<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 02.12.14
 * Time: 0:34
 */

namespace Application\UkrLogic\MainBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DaysType
 * @package Application\UkrLogic\MainBundle\Form
 */
class DaysType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('days_from', 'integer', ['attr' => ['class' => 'days_from']])
            ->add('days_to', 'integer', ['attr' => ['class' => 'days_to']]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'days_form_type';
    }

} 