<?php

namespace Application\UkrLogic\TourBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * City
 */
class City
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\UkrLogic\TourBundle\Entity\Country
     */
    private $country;


    /**
     * Set name
     *
     * @param string $name
     * @return City
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return City
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * @param \Application\UkrLogic\TourBundle\Entity\Country $country
     * @return City
     */
    public function setCountry(\Application\UkrLogic\TourBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Application\UkrLogic\TourBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }
}
