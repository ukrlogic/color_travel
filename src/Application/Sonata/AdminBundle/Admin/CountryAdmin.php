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
            ->add('travelType', null, ['editable' => true])
            ->add('active', null, ['editable' => true])
            ->add('photos', null, ['mapped' => false, 'template' => 'ApplicationSonataAdminBundle:Country:images.html.twig'])
            ->add('regionName')
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Names', ['class' => 'col-md-6'])
                ->add('name')
                ->add('nameEng')
                ->add('nameRu')
                ->add('nick')
            ->end()
            ->with('Region', ['class' => 'col-md-6'])
                ->add('label')
                ->add('region')
                ->add('regionName')
                ->add('active')
            ->end()
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
