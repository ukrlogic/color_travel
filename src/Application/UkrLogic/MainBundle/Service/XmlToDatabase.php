<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 05.12.14
 * Time: 2:10
 */

namespace Application\UkrLogic\MainBundle\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;


/**
 * Class RepositoryCache
 * @package Application\UkrLogic\MainBundle\Service
 */
class RepositoryCache
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var EntityCache[]
     */
    private $repositories = [];

    /**
     * @param EntityManager $entityManager
     */
    function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $name
     * @return EntityCache
     */
    public function get($name)
    {
        if (!array_key_exists($name, $this->repositories)) {
            $repository = $this->entityManager->getRepository($name);
            $this->repositories[$name] = new EntityCache($repository);
        }

        return $this->repositories[$name];
    }
}

/**
 * Class EntityCache
 * @package Application\UkrLogic\MainBundle\Service
 */
class EntityCache
{
    /**
     * @var EntityRepository
     */
    private $repository;
    /**
     * @var array
     */
    private $entities = [];

    /**
     * @param EntityRepository $repository
     */
    function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function get($id)
    {
        if (!array_key_exists($id, $this->entities)) {
            $this->entities[$id] = $this->repository->find($id);
        }

        return $this->entities[$id];
    }

    /**
     * @return array
     */
    public function getAll()
    {
        foreach ($this->repository->findAll() as $entity) {
            $this->entities[$entity->getId()] = $entity;
        }

        return $this->entities;
    }
}

/**
 * Class XmlToDatabase
 * @package Application\UkrLogic\MainBundle\Service
 */
class XmlToDatabase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var array
     */
    private $cache;

    /**
     * @param EntityManager $entityManager
     */
    function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->cache = new RepositoryCache($this->entityManager);
    }

    /**
     * @param string $url
     * @return \SimpleXMLElement
     */
    public function loadXmlFromExternalSource($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        return simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
    }

    /**
     * @param string $filename
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    public function loadFromFile($filename)
    {
        if (!file_exists($filename)) {
            throw new \Exception(sprintf("File '%s' does not exists", $filename));
        }

        return simplexml_load_file($filename, "SimpleXMLElement", LIBXML_NOCDATA);
    }

    /**
     * @param string|\SimpleXMLElement $val
     * @param string|array $type
     * @return bool|int|null|object|string
     */
    protected function typeConvert($val, $type)
    {
        if (is_array($type)) {
            return $this->cache->get($type['targetEntity'])->get(intval($val));
        }

        if (is_string($val)) {
            return $val;
        }

        switch ($type) {
            case 'boolean':
                return boolval($val);
            case 'string':
                return (string)$val;
            case 'integer':
                return intval($val);
        }

        return null;
    }

    /**
     * @param string $entityClass
     * @param \SimpleXMLElement $xml
     * @return array
     */
    public function load($entityClass, $xml)
    {
        $meta = $this->entityManager->getClassMetadata($entityClass);
        $colsNames = $meta->getColumnNames();
        $entities = [];
        $fields = [];
        $cols = [];

        $existingEntities = $this->cache->get($entityClass)->getAll();

        foreach ($colsNames as $name) {
            $fields[$name] = $meta->getFieldName($name);
            $cols[$name] = $meta->getTypeOfField($fields[$name]);
        }

        foreach ($meta->getAssociationMappings() as $col => $map) {
            $cols[$col] = $map;
            $fields[$col] = $map['fieldName'];
        }

        foreach ($xml as $row) {
            if (array_key_exists(intval($row->id), $existingEntities)) {
                continue;
            }

            $entity = new $entityClass();

            foreach ($cols as $name => $type) {
                $method = 'set' . ucfirst($fields[$name]);
                call_user_func([$entity, $method], $this->typeConvert($row->$name, $type));
            }

            $entities[] = $entity;
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();

        return $entities;
    }

} 