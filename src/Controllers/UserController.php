<?php

namespace User\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use User\Api\Entities\User;
use User\Response\HalResponse;
use User\Service\User\UserStorage;

class UserController
{
    public function postAction(Application $app, Request $request)
    {
        $user = new User(json_decode($request->getContent(), 1));
        /* @var $userStorage UserStorage */
        $userStorage = $app['user.storage'];
        $userId = $userStorage->createUser($user);

        $response = new HalResponse();
        $response->addLink('created', '/user/' . $userId);
        return $response;
    }

    public function getAction(Application $app, string $userId)
    {
        /* @var $userStorage UserStorage */
        $userStorage = $app['user.storage'];
        $user = $userStorage->readUser($userId);
        return new JsonResponse($user);
    }

    public function putAction(Application $app, Request $request, string $userId)
    {
        $user = new User(json_decode($request->getContent(), 1));
        /* @var $userStorage UserStorage */
        $userStorage = $app['user.storage'];
        $userStorage->updateUser($userId, $user);

        $response = new HalResponse();
        $response->addLink('updated', '/user/' . $userId);
        return $response;
    }

    public function deleteAction(Application $app, string $userId)
    {
        /* @var $userStorage UserStorage */
        $userStorage = $app['user.storage'];
        $userStorage->deleteUser($userId);

        return new Response();
    }
}
