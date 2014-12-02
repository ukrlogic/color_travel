<?php

namespace Application\Sonata\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class BusTourAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('tourId')
            ->add('gateway')
            ->add('name')
            ->add('days')
            ->add('dates')
            ->add('dateFrom')
            ->add('dateTo')
            ->add('price_uah')
            ->add('price_usd')
            ->add('price_eur')
            ->add('currency')
            ->add('id')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('tourId')
            ->add('gateway')
            ->add('name')
            ->add('days')
            ->add('dates')
            ->add('dateFrom')
            ->add('dateTo')
            ->add('price_uah')
            ->add('price_usd')
            ->add('price_eur')
            ->add('currency')
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
            ->add('tourId')
            ->add('gateway')
            ->add('name')
            ->add('days')
            ->add('dates')
            ->add('dateFrom')
            ->add('dateTo')
            ->add('price_uah')
            ->add('price_usd')
            ->add('price_eur')
            ->add('currency')
            ->add('id')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('tourId')
            ->add('gateway')
            ->add('name')
            ->add('days')
            ->add('dates')
            ->add('dateFrom')
            ->add('dateTo')
            ->add('price_uah')
            ->add('price_usd')
            ->add('price_eur')
            ->add('currency')
            ->add('id')
        ;
    }
}
