<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 15.12.14
 * Time: 20:10
 */

namespace Application\UkrLogic\TourBundle\Service;


/**
 * Class Tour
 * @package Application\UkrLogic\TourBundle\Service
 */
class Tour
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var mixed
     */
    private $info;

    /**
     * @param string $type
     * @param mixed $info
     */
    function __construct($type, $info)
    {
        $this->type = $type;
        $this->info = $info;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }
}