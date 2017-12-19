<?php

namespace User\Service\Health;

use ArangoDBClient\Collection;
use ArangoDBClient\CollectionHandler;
use ArangoDBClient\ConnectException;
use ArangoDBClient\Exception;
use ArangoDBClient\ServerException;
use Psr\Log\LoggerInterface;

class Healer
{
    const REANIMATIONS_MAX = 1;

    protected $logger;
    protected $collection;
    protected $checks = [];

    public function __construct(LoggerInterface $logger, CollectionHandler $collection)
    {
        $this->logger = $logger;
        $this->collection = $collection;

        $this->checks[] = function () {
            $this->collection->get('user');
        };
        $this->checks[] = function () {
            $this->collection->get('identity');
        };
        $this->checks[] = function () {
            $this->collection->get('authentication');
        };
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
    public function checkDatabaseHealth(int $trysLeft = self::REANIMATIONS_MAX) : bool
    {
        try {
            foreach ($this->checks as $key => $checkClosure) {
                $checkClosure();
                unset($this->checks[$key]);
            }
        } catch (Exception $exception) {
            if ($trysLeft > 0 && $this->reanimateDatabase($exception)) {
                return $this->checkDatabaseHealth($trysLeft - 1);
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * Try to fix a problem or log the emergency
     *
     * Returns false if the problem can't be fixed
     *
     * @param Exception $exception
     * @return bool
     */
    protected function reanimateDatabase(Exception $exception) : bool
    {
        if ($exception instanceof ConnectException) {
            $this->logger->emergency($exception->getMessage());
            return false;
        } elseif ($exception instanceof ServerException) {
            switch ($exception->getMessage()) {
                case 'unknown collection \'user\'':
                    $this->logger->emergency('collection "user" did not exist and was recreated!');
                    $collection = new Collection('user');
                    $collection->setType(Collection::TYPE_DOCUMENT);
                    $this->collection->create($collection);
                    break;
                case 'unknown collection \'identity\'':
                    $this->logger->emergency('collection "identity" did not exist and was recreated!');
                    $collection = new Collection('identity');
                    $collection->setType(Collection::TYPE_DOCUMENT);
                    $this->collection->create($collection);
                    break;
                case 'unknown collection \'authentication\'':
                    $this->logger->emergency('collection "authentication" did not exist and was recreated!');
                    $collection = new Collection('authentication');
                    $collection->setType(Collection::TYPE_EDGE);
                    $this->collection->create($collection);
                    break;
                default:
                    break;
            }
        }
        return true;
    }
}
