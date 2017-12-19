<?php

namespace User\Service\User;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class AuthenticationStorageServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['authentication.storage'] = function () use ($app) {
            return new AuthenticationStorage($app['arangodb.connection'], $app['arangodb.document']);
        };
    }
}
