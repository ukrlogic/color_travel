<?php

namespace Application\Sonata\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class OrderTourAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('fio')
            ->add('phone')
            ->add('email')
            ->add('info')
            ->add('status')
            ->add('date')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('fio', null, [
                'identifier' => true,
                'route' => [
                    'name' => 'show',
                    'parameters' => [],
                ],
            ])
            ->add('phone')
            ->add('email')
            ->add('info')
            ->add('date')
            ->add('status', null, ['editable' => true])
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('fio')
            ->add('phone')
            ->add('email')
            ->add('info')
            ->add('status')
            ->add('id')
            ->add('date')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('fio')
            ->add('phone')
            ->add('email')
            ->add('info')
            ->add('status')
            ->add('date')
        ;
    }
}
