<?php

namespace Application\Sonata\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PostAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('date')
            ->add('active')
            ->add('id')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title', null, ['editable' => true])
            ->add('date')
            ->add('active', null, ['editable' => true])
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Settings', ['class' => 'col-md-8'])
                ->add('title')
                ->add('file', 'file', ['required' => false])
                ->add('active')
            ->end()
            ->with('Seo',['class' => 'col-md-4'])
                ->add('metaTitle')
                ->add('metaKeywords')
                ->add('metaDescription')
            ->end()
            ->with('Content', ['class' => 'col-md-12'])
                ->add('content', 'ckeditor', ['config' => ['allowedContent' => true]])
            ->end();
    }

    public function prePersist($object)
    {
        $object->getFile() && $object->upload();
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('url')
            ->add('title')
            ->add('content')
            ->add('date')
            ->add('active')
            ->add('image')
            ->add('metaTitle')
            ->add('metaDescription')
            ->add('metaKeywords')
            ->add('id')
        ;
    }
}
