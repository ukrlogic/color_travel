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
            ->add('nameRu')
            ->add('fullNameRu')
            ->add('search')
            ->add('alpha2')
            ->add('alpha3')
            ->add('iso')
            ->add('location')
            ->add('location_precise')
            ->add('id')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('nameRu')
            ->add('fullNameRu')
            ->add('search', null, ['editable' => true])
            ->add('alpha2')
            ->add('alpha3')
            ->add('iso')
            ->add('location')
            ->add('location_precise')
            ->add('id')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('nameRu')
            ->add('fullNameRu')
            ->add('search')
            ->add('alpha2')
            ->add('alpha3')
            ->add('iso')
            ->add('location')
            ->add('location_precise')
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
            ->add('nameRu')
            ->add('fullNameRu')
            ->add('search')
            ->add('alpha2')
            ->add('alpha3')
            ->add('iso')
            ->add('location')
            ->add('location_precise')
            ->add('id')
        ;
    }
}
