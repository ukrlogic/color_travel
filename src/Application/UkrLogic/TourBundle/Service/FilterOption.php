<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 02.12.14
 * Time: 16:24
 */

namespace Application\UkrLogic\TourBundle\Service;


/**
 * Class FilterOption
 * @package Application\UkrLogic\TourBundle\Service
 */
class FilterOption
{
    /**
     * @var array
     */
    private $options;

    /**
     * @param array $options
     */
    function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed Value
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->options) && $this->options[$key] !== null) {
            return $this->options[$key];
        }

        return $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     * @throws \Exception
     */
    public function set($key, $value)
    {
        if (array_key_exists($key, $this->options)) {
            throw new \Exception(sprintf("Key '%s' does not exists", $key));
        }

        $this->options[$key] = $value;

        return $this;
    }

} 