<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 01.12.14
 * Time: 23:16
 */

namespace Application\UkrLogic\MainBundle\Form;


use Application\UkrLogic\TourBundle\Entity\Country;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CountryType
 * @package Application\UkrLogic\MainBundle\Form
 */
class CountryType extends AbstractType
{
    /**
     * @var EntityRepository
     */
    private $cRepo;

    /**
     * @param EntityRepository $cRepo
     */
    function __construct(EntityRepository $cRepo)
    {
        $this->cRepo = $cRepo;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Country $country */
        foreach ($this->cRepo->findAll() as $country) if ($country->getActive()) {
            $builder->add($country->getId(), 'checkbox', [
                'label' => sprintf('%s <i class="flag-%s"></i>', $country->getName(), $country->getNick()),
                'required' => false
            ]);
        }
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'country_form';
    }

} 