<?php

namespace Application\UkrLogic\AkkordTourBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AkkordCountry
 */
class AkkordCountry
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $country;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return AkkordCountry
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }
}
