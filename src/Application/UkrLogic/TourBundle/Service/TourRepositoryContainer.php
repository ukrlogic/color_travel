<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 15.12.14
 * Time: 12:49
 */

namespace Application\UkrLogic\TourBundle\Service;


use Traversable;

/**
 * Class TourRepositoryContainer
 * @package Application\UkrLogic\TourBundle\Service
 */
class TourRepositoryContainer implements \IteratorAggregate
{
    /**
     * @var RepositoryInterface[]
     */
    private $repositories = [];

    /**
     * @param RepositoryInterface $repository
     * @param string $alias
     * @return $this
     * @throws \Exception
     */
    public function addRepository(RepositoryInterface $repository, $alias)
    {
        if (array_key_exists($alias, $this->repositories)) {
            throw new \Exception(sprintf("Tour repository '%s' already exists", $alias));
        }

        $this->repositories[$alias] = $repository;

        return $this;
    }

    /**
     * @param string $alias
     * @return RepositoryInterface
     * @throws \Exception
     */
    public function getRepository($alias)
    {
        if (! array_key_exists($alias, $this->repositories)) {
            throw new \Exception(sprintf("Tour repository '%s' not found", $alias));
        }

        return $this->repositories[$alias];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->repositories);
    }


}