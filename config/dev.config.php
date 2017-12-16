<?php

use ArangoDBClient\ConnectionOptions;
use ArangoDBClient\UpdatePolicy;

$app['config'] = [
    'arangodb' => [
        ConnectionOptions::OPTION_DATABASE => 'user',
        ConnectionOptions::OPTION_ENDPOINT => 'tcp://arangodb:8529',

        ConnectionOptions::OPTION_CONNECTION => 'Keep-Alive',
        ConnectionOptions::OPTION_AUTH_TYPE => 'Basic',

        ConnectionOptions::OPTION_AUTH_USER => 'user',
        ConnectionOptions::OPTION_AUTH_PASSWD => 'user',

        ConnectionOptions::OPTION_TIMEOUT => 30,
        ConnectionOptions::OPTION_CREATE => false,
        ConnectionOptions::OPTION_UPDATE_POLICY => UpdatePolicy::LAST
    ]
];
