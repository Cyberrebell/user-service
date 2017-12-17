<?php

use ApiMappingLayerGen\Generator\Php\EntityBuilder;
use ApiMappingLayerGen\Generator\Php\EntityGenerator\Native;
use ApiMappingLayerGen\Mapper\OpenApi\Mapper;
use Symfony\Component\Console\Application;

$console = new Application('user-service', 'n/a');
$console->setDispatcher($app['dispatcher']);

$console
    ->register('generate-api-entities')
    ->setDescription('Rebuild the api entities from OpenApi definition file')
    ->setCode(function () use ($app) {
        $mapper = new Mapper('docs/api.yml');
        $patterns = $mapper->getPatterns();

        $entityBuilder = new EntityBuilder(new Native());
        $entityBuilder->buildEntities($patterns, 'User\Api', __DIR__ . '/Api');
    })
;

return $console;
