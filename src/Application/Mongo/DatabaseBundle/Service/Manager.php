<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 07.12.14
 * Time: 1:44
 */

namespace Application\Mongo\DatabaseBundle\Service;


/**
 * Class Manager
 * @package Application\Mongo\DatabaseBundle\Service
 */
class Manager
{
    /**
     * @var \MongoDB
     */
    private $connection;

    /**
     * Constructor
     */
    function __construct()
    {
        $connection = new \MongoClient();
        $db = $connection->selectDB('test_db');
        $this->connection = $db;
    }

    /**
     * @param string $collectionName
     * @return \MongoCollection
     */
    public function getCollection($collectionName)
    {
        return array_search($collectionName, $this->connection->getCollectionNames())
            ? $this->connection->selectCollection($collectionName)
            : $this->connection->createCollection($collectionName);

    }

}