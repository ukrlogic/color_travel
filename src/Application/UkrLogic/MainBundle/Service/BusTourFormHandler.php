<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 02.12.14
 * Time: 2:06
 */

namespace Application\UkrLogic\MainBundle\Service;


use Application\UkrLogic\MainBundle\Form\BusTourFilterForm;

/**
 * Class BusTourFormHandler
 * @package Application\UkrLogic\MainBundle\Service
 */
class BusTourFormHandler
{
    /**
     * @var BusTourFilterForm
     */
    private $form;

    /**
     * @param BusTourFilterForm $form
     */
    public function setForm(BusTourFilterForm $form)
    {
        $this->form = $form;
    }

    /**
     *
     */
    public function getTours()
    {
        foreach($this->form->get('countries')->getData() as $code => $value) {
            if ($value) {
                $country = $code;
                break;
            }
        }
    }
} 