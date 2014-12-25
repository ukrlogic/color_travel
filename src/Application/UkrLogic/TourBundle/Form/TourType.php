<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 30.11.14
 * Time: 13:09
 */

namespace Application\UkrLogic\TourBundle\Form;


use Application\UkrLogic\TourBundle\Service\TourRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TourType
 * @package Application\UkrLogic\TourBundle\Form
 */
class TourType extends AbstractType
{
    /**
     * @var TourRepository
     */
    protected $modifier;

    /**
     * @param TourRepository $modifier
     */
    function __construct(TourRepository $modifier)
    {
        $this->modifier = $modifier;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('type', 'hidden', ['data' => 'bus', 'attr' => ['class' => 'travel_type']])
            ->add('page', 'hidden', ['empty_data' => 1, 'data' => 1, 'attr' => ['class' => 'page']])
        ;

        $this->modifier->modify($builder);
    }
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection'   => false,
        ]);
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