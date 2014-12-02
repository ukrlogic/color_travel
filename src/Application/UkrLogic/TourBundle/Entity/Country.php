<?php

namespace Application\UkrLogic\TourBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 */
class Country
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $nameRu;

    /**
     * @var boolean
     */
    private $search;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set name
     *
     * @param string $name
     * @return Country
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
     * Set nameRu
     *
     * @param string $nameRu
     * @return Country
     */
    public function setNameRu($nameRu)
    {
        $this->nameRu = $nameRu;

        return $this;
    }

    /**
     * Get nameRu
     *
     * @return string 
     */
    public function getNameRu()
    {
        return $this->nameRu;
    }

    /**
     * Set search
     *
     * @param boolean $search
     * @return Country
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Get search
     *
     * @return boolean 
     */
    public function getSearch()
    {
        return $this->search;
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
     * @var string
     */
    private $fullNameRu;

    /**
     * @var string
     */
    private $alpha2;

    /**
     * @var string
     */
    private $alpha3;

    /**
     * @var string
     */
    private $iso;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $location_precise;


    /**
     * Set fullNameRu
     *
     * @param string $fullNameRu
     * @return Country
     */
    public function setFullNameRu($fullNameRu)
    {
        $this->fullNameRu = $fullNameRu;

        return $this;
    }

    /**
     * Get fullNameRu
     *
     * @return string 
     */
    public function getFullNameRu()
    {
        return $this->fullNameRu;
    }

    /**
     * Set alpha2
     *
     * @param string $alpha2
     * @return Country
     */
    public function setAlpha2($alpha2)
    {
        $this->alpha2 = $alpha2;

        return $this;
    }

    /**
     * Get alpha2
     *
     * @return string 
     */
    public function getAlpha2()
    {
        return $this->alpha2;
    }

    /**
     * Set alpha3
     *
     * @param string $alpha3
     * @return Country
     */
    public function setAlpha3($alpha3)
    {
        $this->alpha3 = $alpha3;

        return $this;
    }

    /**
     * Get alpha3
     *
     * @return string 
     */
    public function getAlpha3()
    {
        return $this->alpha3;
    }

    /**
     * Set iso
     *
     * @param string $iso
     * @return Country
     */
    public function setIso($iso)
    {
        $this->iso = $iso;

        return $this;
    }

    /**
     * Get iso
     *
     * @return string 
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Country
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set location_precise
     *
     * @param string $locationPrecise
     * @return Country
     */
    public function setLocationPrecise($locationPrecise)
    {
        $this->location_precise = $locationPrecise;

        return $this;
    }

    /**
     * Get location_precise
     *
     * @return string 
     */
    public function getLocationPrecise()
    {
        return $this->location_precise;
    }
}
