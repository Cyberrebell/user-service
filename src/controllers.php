<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use User\Controllers\AuthenticationController;
use User\Controllers\UserController;

$app->get('/', function () {
    return RedirectResponse::create('/docs/');
});

$app->get('/health', function () use ($app) {
    return 'I am alive';
});


$app->post('/user', UserController::class . '::postAction');
$app->mount('/user', function ($user) {
    $user->get('/{userId}', UserController::class . '::getAction');
    $user->put('/{userId}', UserController::class . '::putAction');
    $user->delete('/{userId}', UserController::class . '::deleteAction');
});


$app->post('/authentication', AuthenticationController::class . '::postAction');
$app->delete('/authentication', AuthenticationController::class . '::deleteAction');
