<?php

namespace User\Service;

use ArangoDBClient\CollectionHandler;
use ArangoDBClient\Connection;
use ArangoDBClient\DocumentHandler;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ArangoDbServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['arangodb.connection'] = function () use ($app) {
            return new Connection($app['config']['arangodb']);
        };
        $app['arangodb.collection'] = function () use ($app) {
            return new CollectionHandler($app['arangodb.connection']);
        };
        $app['arangodb.document'] = function () use ($app) {
            return new DocumentHandler($app['arangodb.connection']);
        };
    }
}