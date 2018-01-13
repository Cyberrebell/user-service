<?php

namespace User\Service\Health;

use ArangoDBClient\Collection;
use ArangoDBClient\CollectionHandler;
use ArangoDBClient\ConnectException;
use ArangoDBClient\ServerException;
use Psr\Log\LoggerInterface;

class Healer
{
    const DOCUMENT_COLLECTIONS = [
        'user',
        'identity'
    ];

    const EDGE_COLLECTIONS = [
        'authentication'
    ];

    protected $logger;
    protected $collection;

    public function __construct(LoggerInterface $logger, CollectionHandler $collection)
    {
        $this->logger = $logger;
        $this->collection = $collection;
    }

    public function checkHealth() : bool
    {
        return $this->checkDatabaseHealth();
    }

    /**
     * Check the database health and try to reanimate if dead
     *
     * @param int $trysLeft
     * @return bool
     */
    public function checkDatabaseHealth() : bool
    {
        $requiredCollections = array_merge(
            self::DOCUMENT_COLLECTIONS,
            self::EDGE_COLLECTIONS
        );

        foreach ($requiredCollections as $collectionName) {
            try {
                $this->collection->get($collectionName);
            } catch (ServerException $exception) {
                if ($exception->getMessage() === "unknown collection '" . $collectionName . "'") {
                    $this->createMissingCollection($collectionName);
                    $this->logger->emergency('collection "' . $collectionName . '" did not exist and was recreated!');
                }
            } catch (ConnectException $exception) {
                $this->logger->emergency($exception->getMessage());
                return false;
            }
        }
        return true;
    }

    protected function createMissingCollection(string $collectionName)
    {
        if (in_array($collectionName, self::DOCUMENT_COLLECTIONS, true)) {
            $collection = new Collection($collectionName);
            $collection->setType(Collection::TYPE_DOCUMENT);
            $this->collection->create($collection);
        } elseif (in_array($collectionName, self::EDGE_COLLECTIONS, true)) {
            $collection = new Collection($collectionName);
            $collection->setType(Collection::TYPE_EDGE);
            $this->collection->create($collection);
        }
    }
}
