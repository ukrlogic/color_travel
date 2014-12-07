<?php

namespace Application\UkrLogic\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 */
class Comment
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var boolean
     */
    private $moderated;

    /**
     * @var \stdClass
     */
    private $user;

    public function __construct()
    {
        $this->setDate(new \DateTime());
        $this->setModerated(false);
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
     * Set text
     *
     * @param string $text
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Comment
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set moderated
     *
     * @param boolean $moderated
     * @return Comment
     */
    public function setModerated($moderated)
    {
        $this->moderated = $moderated;

        return $this;
    }

    /**
     * Get moderated
     *
     * @return boolean 
     */
    public function getModerated()
    {
        return $this->moderated;
    }

    /**
     * Set user
     *
     * @param \stdClass $user
     * @return Comment
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \stdClass 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @var string
     */
    private $tourType;

    /**
     * @var string
     */
    private $tourId;


    /**
     * Set tourType
     *
     * @param string $tourType
     * @return Comment
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
     * Set tourId
     *
     * @param string $tourId
     * @return Comment
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
}
