<?php

namespace Application\UkrLogic\TourBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Resort
 */
class Resort
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $nameEng;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @var string
     */
    private $countryName;

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
     * @return Resort
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
     * Set nameEng
     *
     * @param string $nameEng
     * @return Resort
     */
    public function setNameEng($nameEng)
    {
        $this->nameEng = $nameEng;

        return $this;
    }

    /**
     * Get nameEng
     *
     * @return string 
     */
    public function getNameEng()
    {
        return $this->nameEng;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Resort
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set countryName
     *
     * @param string $countryName
     * @return Resort
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;

        return $this;
    }

    /**
     * Get countryName
     *
     * @return string 
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Resort
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
     * @return Resort
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
