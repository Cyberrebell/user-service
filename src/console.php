<?php

use ApiMappingLayerGen\Generator\JsonSchema\JsonSchemaBuilder;
use ApiMappingLayerGen\Generator\JsonSchema\OpenApi\JsonSchemaGenerator;
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

$console
    ->register('generate-json-schema')
    ->setDescription('Rebuild the json schema files from OpenApi definition file')
    ->setCode(function () use ($app) {
        $mapper = new Mapper('docs/api.yml');
        $definition = $mapper->getDefinition();

        $jsonBuilder = new JsonSchemaBuilder(new JsonSchemaGenerator());
        $jsonBuilder->buildSchemas($definition, __DIR__ . '/../docs/json');
    })
;

return $console;
