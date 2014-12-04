<?php

namespace Application\Sonata\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class HotelAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('nameAlt')
            ->add('nameEng')
            ->add('nameIndx')
            ->add('trash')
            ->add('active')
            ->add('cat')
            ->add('chagebaby')
            ->add('chagesmall')
            ->add('chagebig')
            ->add('resort')
            ->add('resortname')
            ->add('country')
            ->add('countryname')
            ->add('catname')
            ->add('assoc')
            ->add('description')
            ->add('fullDescription')
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
            ->add('catname')
            ->add('photos', null, ['mapped' => false, 'template' => 'ApplicationSonataAdminBundle:Hotel:images.html.twig'])
            ->add('trash', null, ['editable' => true])
            ->add('active', null, ['editable' => true])
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('nameAlt')
            ->add('nameEng')
            ->add('nameIndx')
            ->add('trash')
            ->add('active')
            ->add('cat')
            ->add('chagebaby')
            ->add('chagesmall')
            ->add('chagebig')
            ->add('resort')
            ->add('resortname')
            ->add('country')
            ->add('countryname')
            ->add('catname')
            ->add('assoc')
            ->add('description')
            ->add('fullDescription')
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
            ->add('nameAlt')
            ->add('nameEng')
            ->add('nameIndx')
            ->add('trash')
            ->add('active')
            ->add('cat')
            ->add('chagebaby')
            ->add('chagesmall')
            ->add('chagebig')
            ->add('resort')
            ->add('resortname')
            ->add('country')
            ->add('countryname')
            ->add('catname')
            ->add('assoc')
            ->add('description')
            ->add('fullDescription')
            ->add('id')
        ;
    }
}
