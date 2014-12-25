<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 15.12.14
 * Time: 12:56
 */

namespace Application\UkrLogic\TourBundle\Service;


use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;

/**
 * Interface RepositoryInterface
 * @package Application\UkrLogic\TourBundle\Service
 */
interface RepositoryInterface
{
    /**
     * @param Form $form
     * @param integer $limit
     * @return Tour[]
     */
    public function find(Form $form, $limit);

    /**
     * Adds specified fields to form builder
     *
     * @param FormBuilder $form
     */
    public function modify(FormBuilder $form);
}