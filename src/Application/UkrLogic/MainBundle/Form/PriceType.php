<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 02.12.14
 * Time: 0:31
 */

namespace Application\UkrLogic\MainBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PriceType
 * @package Application\UkrLogic\MainBundle\Form
 */
class PriceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price_from', 'text', ['attr' => ['class' => 'price_from'], 'required' => false])
            ->add('price_to', 'text', ['attr' => ['class' => 'price_to'], 'required' => false]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'price_form_type';
    }

} 