<?php

namespace User\Service\User;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class UserStorageServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['user.storage'] = function () use ($app) {
            return new UserStorage($app['arangodb.document']);
        };
    }
}
