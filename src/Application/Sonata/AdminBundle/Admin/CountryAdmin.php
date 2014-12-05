<?php

namespace Application\Sonata\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CountryAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('nameEng')
            ->add('nameRu')
            ->add('nick')
            ->add('active')
            ->add('label')
            ->add('region')
            ->add('regionName')
            ->add('id')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('nick')
            ->add('active')
            ->add('label', null, ['editable' => true])
            ->add('regionName')
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('nameEng')
            ->add('nameRu')
            ->add('nick')
            ->add('active')
            ->add('label')
            ->add('region')
            ->add('regionName')
            ->add('id')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('nameEng')
            ->add('nameRu')
            ->add('nick')
            ->add('active')
            ->add('label')
            ->add('region')
            ->add('regionName')
            ->add('id')
        ;
    }
}
