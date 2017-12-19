<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use User\Service\ArangoDbServiceProvider;
use User\Service\Health\HealerServiceProvider;
use User\Service\User\AuthenticationStorageServiceProvider;
use User\Service\User\UserStorageServiceProvider;

$app = new Application();
require __DIR__ . '/error.php';
require __DIR__ . '/logger.php';

$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new ArangoDbServiceProvider());
$app->register(new HealerServiceProvider());
$app->register(new UserStorageServiceProvider());
$app->register(new AuthenticationStorageServiceProvider());

return $app;
