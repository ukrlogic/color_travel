<?php

namespace Application\UkrLogic\TourBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Meal
 */
class Meal
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $nick;

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
    private $description;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Meal
     */
    public function setName ($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * Set nick
     *
     * @param string $nick
     * @return Meal
     */
    public function setNick ($nick)
    {
        $this->nick = $nick;

        return $this;
    }

    /**
     * Get nick
     *
     * @return string
     */
    public function getNick ()
    {
        return $this->nick;
    }

    /**
     * Set nameEng
     *
     * @param string $nameEng
     * @return Meal
     */
    public function setNameEng ($nameEng)
    {
        $this->nameEng = $nameEng;

        return $this;
    }

    /**
     * Get nameEng
     *
     * @return string
     */
    public function getNameEng ()
    {
        return $this->nameEng;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Meal
     */
    public function setActive ($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive ()
    {
        return $this->active;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Meal
     */
    public function setDescription ($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription ()
    {
        return $this->description;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Meal
     */
    public function setId ($id)
    {
        $this->id = $id;

        return $this;
    }

    public function __toString ()
    {
        return $this->getName();
    }
}
