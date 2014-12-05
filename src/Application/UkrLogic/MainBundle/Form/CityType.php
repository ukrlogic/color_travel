<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 04.12.14
 * Time: 18:58
 */

namespace Application\UkrLogic\MainBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('1919', 'checkbox', ['label' => 'Днепропетровск'])
            ->add('1920', 'checkbox', ['label' => 'Запорожье'])
            ->add('668', 'checkbox', ['label' => 'Киев'])
            ->add('1579', 'checkbox', ['label' => 'Львов'])
            ->add('1413', 'checkbox', ['label' => 'Одесса'])
            ->add('1918', 'checkbox', ['label' => 'Харьков']);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'city_form';
    }

} 