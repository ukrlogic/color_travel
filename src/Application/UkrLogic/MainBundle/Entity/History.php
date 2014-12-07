<?php

namespace Application\UkrLogic\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * History
 */
class History
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
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
     * @param integer $tourId
     * @return History
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
     * Set tourType
     *
     * @param string $tourType
     * @return History
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
     * @return History
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
