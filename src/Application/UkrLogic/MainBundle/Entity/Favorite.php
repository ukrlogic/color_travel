<?php

namespace Application\UkrLogic\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Favorite
 */
class Favorite
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $tourId;

    /**
     * @var string
     */
    private $tourType;


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
     * Set tourId
     *
     * @param string $tourId
     * @return Favorite
     */
    public function setTourId($tourId)
    {
        $this->tourId = $tourId;

        return $this;
    }

    /**
     * Get tourId
     *
     * @return string 
     */
    public function getTourId()
    {
        return $this->tourId;
    }

    /**
     * Set tourType
     *
     * @param string $tourType
     * @return Favorite
     */
    public function setTourType($tourType)
    {
        $this->tourType = $tourType;

        return $this;
    }

    /**
     * Get tourType
     *
     * @return string 
     */
    public function getTourType()
    {
        return $this->tourType;
    }
    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     */
    private $user;


    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return Favorite
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
