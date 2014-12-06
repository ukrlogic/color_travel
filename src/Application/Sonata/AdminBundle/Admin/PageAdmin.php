<?php

namespace Application\Sonata\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PageAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('url')
            ->add('name')
            ->add('metaTitle');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('url')
            ->add('metaTitle');
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Settings', ['class' => 'col-md-8'])
                ->add('url')
                ->add('name')
            ->end()
            ->with('Seo',['class' => 'col-md-4'])
                ->add('metaTitle')
                ->add('metaKeywords')
                ->add('metaDescription')
            ->end()
            ->with('Content', ['class' => 'col-md-12'])
                ->add('content', null, ['attr' => ['class' => 'ckeditor']])
            ->end();
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('url')
            ->add('name')
            ->add('metaTitle')
            ->add('metaKeywords')
            ->add('metaDescription')
            ->add('content');
    }
}
