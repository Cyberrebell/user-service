<?php

namespace User\Service\Health;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class HealerServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['healer'] = function () use ($app) {
            return new Healer($app['logger'], $app['arangodb.collection']);
        };
    }
}
