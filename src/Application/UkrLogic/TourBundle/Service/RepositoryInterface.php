<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 15.12.14
 * Time: 12:56
 */

namespace Application\UkrLogic\TourBundle\Service;


use Symfony\Component\Form\Form;

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
}