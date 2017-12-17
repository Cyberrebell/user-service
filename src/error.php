<?php

use ArangoDBClient\ServerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->error(function (\Exception $e, Request $request, int $code) use ($app) {
    $message = null;
    if ($e instanceof ServerException && $e->getMessage() == 'document not found') {
        $code = Response::HTTP_NOT_FOUND;
        $message = Response::$statusTexts[Response::HTTP_NOT_FOUND];
    }

    if ($message === null) {
        if ($code < Response::HTTP_INTERNAL_SERVER_ERROR) {
            $message = $e->getMessage();
        } else {
            $message = Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    return $app->json(
        [
            'error' => $message
        ],
        $code
    );
});
