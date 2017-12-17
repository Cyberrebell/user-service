<?php

use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;
use Silex\Provider\MonologServiceProvider;

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
