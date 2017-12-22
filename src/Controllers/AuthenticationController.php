<?php

namespace User\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use User\Api\Entities\Login;
use User\Service\User\AuthenticationStorage;

class AuthenticationController
{
    const SESSION_COOKIE_NAME = 'AUTH_TOKEN';

    public function postAction(Application $app, Request $request)
    {
        $response = new JsonResponse();

        $login = new Login(json_decode($request->getContent(), 1));
        /* @var $authenticationStorage AuthenticationStorage */
        $authenticationStorage = $app['authentication.storage'];

        $token = $request->cookies->get(self::SESSION_COOKIE_NAME);
        if (!empty($token)) {
            $authenticationStorage->unauthenticate($token);
        }

        $userAgent = $request->headers->get('User-Agent');
        if (!empty($userAgent)) {
            $token = $authenticationStorage->authenticate(
                $login,
                $request->getClientIp(),
                $userAgent
            );
            $response->headers->setCookie(new Cookie(self::SESSION_COOKIE_NAME, $token));
        }

        $response->setData([
            self::SESSION_COOKIE_NAME => $token
        ]);

        return $response;
    }

    public function getAction(Application $app, Request $request)
    {
        $response = new JsonResponse();

        $token = $request->get('token');
        if ($token === null) {
            $token = $request->cookies->get(self::SESSION_COOKIE_NAME);
        }
        if (!empty($token)) {
            /* @var $authenticationStorage AuthenticationStorage */
            $authenticationStorage = $app['authentication.storage'];
            $authentication = $authenticationStorage->getUserData($token);
            $response->setData($authentication->toArray());
        }

        return $response;
    }

    public function deleteAction(Application $app, Request $request)
    {
        $response = new JsonResponse();

        $token = $request->get('token');
        if ($token === null) {
            $token = $request->cookies->get(self::SESSION_COOKIE_NAME);
            $response->headers->clearCookie(self::SESSION_COOKIE_NAME);
        }
        if (!empty($token)) {
            /* @var $authenticationStorage AuthenticationStorage */
            $authenticationStorage = $app['authentication.storage'];
            $authenticationStorage->unauthenticate($token);
        }

        return $response;
    }
}
