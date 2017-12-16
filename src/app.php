<?php

use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;
use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use User\Service\ArangoDbServiceProvider;

$app = new Application();
$app->error(function (\Exception $e, Request $request, int $code) use ($app) {
    if ($code < Response::HTTP_INTERNAL_SERVER_ERROR) {
        $message = $e->getMessage();
    } else {
        $message = Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR];
    }
    return $app->json(
        [
            'error' => $message
        ]
    );
});
$app->register(
    new MonologServiceProvider(),
    [
        'monolog.logfile' => 'php://stderr',
        'monolog.level' => Logger::NOTICE,
        'monolog.formatter' => function () {
            return new JsonFormatter();
        }
    ]
);
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new ArangoDbServiceProvider());

return $app;
