<?php

namespace Application\UkrLogic\TourBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusTour
 */
class BusTour
{
    /**
     * @var integer
     */
    private $tourId;

    /**
     * @var string
     */
    private $gateway;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $days;

    /**
     * @var string
     */
    private $dates;

    /**
     * @var float
     */
    private $price_uah;

    /**
     * @var float
     */
    private $price_usd;

    /**
     * @var float
     */
    private $price_eur;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $counties;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->counties = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set tourId
     *
     * @param integer $tourId
     * @return BusTour
     */
    public function setTourId($tourId)
    {
        $this->tourId = $tourId;

        return $this;
    }

    /**
     * Get tourId
     *
     * @return integer 
     */
    public function getTourId()
    {
        return $this->tourId;
    }

    /**
     * Set gateway
     *
     * @param string $gateway
     * @return BusTour
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;

        return $this;
    }

    /**
     * Get gateway
     *
     * @return string 
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return BusTour
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
     * Set days
     *
     * @param integer $days
     * @return BusTour
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days
     *
     * @return integer 
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Set dates
     *
     * @param string $dates
     * @return BusTour
     */
    public function setDates($dates)
    {
        $this->dates = $dates;

        return $this;
    }

    /**
     * Get dates
     *
     * @return string 
     */
    public function getDates()
    {
        return $this->dates;
    }

    /**
     * Set price_uah
     *
     * @param float $priceUah
     * @return BusTour
     */
    public function setPriceUah($priceUah)
    {
        $this->price_uah = $priceUah;

        return $this;
    }

    /**
     * Get price_uah
     *
     * @return float 
     */
    public function getPriceUah()
    {
        return $this->price_uah;
    }

    /**
     * Set price_usd
     *
     * @param float $priceUsd
     * @return BusTour
     */
    public function setPriceUsd($priceUsd)
    {
        $this->price_usd = $priceUsd;

        return $this;
    }

    /**
     * Get price_usd
     *
     * @return float 
     */
    public function getPriceUsd()
    {
        return $this->price_usd;
    }

    /**
     * Set price_eur
     *
     * @param float $priceEur
     * @return BusTour
     */
    public function setPriceEur($priceEur)
    {
        $this->price_eur = $priceEur;

        return $this;
    }

    /**
     * Get price_eur
     *
     * @return float 
     */
    public function getPriceEur()
    {
        return $this->price_eur;
    }

    /**
     * Set currency
     *
     * @param string $currency
     * @return BusTour
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string 
     */
    public function getCurrency()
    {
        return $this->currency;
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
     * Add counties
     *
     * @param \Application\UkrLogic\TourBundle\Entity\Country $counties
     * @return BusTour
     */
    public function addCounty(\Application\UkrLogic\TourBundle\Entity\Country $counties)
    {
        $this->counties[] = $counties;

        return $this;
    }

    /**
     * Remove counties
     *
     * @param \Application\UkrLogic\TourBundle\Entity\Country $counties
     */
    public function removeCounty(\Application\UkrLogic\TourBundle\Entity\Country $counties)
    {
        $this->counties->removeElement($counties);
    }

    /**
     * Get counties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCounties()
    {
        return $this->counties;
    }
    /**
     * @var \DateTime
     */
    private $dateFrom;

    /**
     * @var \DateTime
     */
    private $dateTo;


    /**
     * Set dateFrom
     *
     * @param \DateTime $dateFrom
     * @return BusTour
     */
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    /**
     * Get dateFrom
     *
     * @return \DateTime 
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * Set dateTo
     *
     * @param \DateTime $dateTo
     * @return BusTour
     */
    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    /**
     * Get dateTo
     *
     * @return \DateTime 
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }
}
