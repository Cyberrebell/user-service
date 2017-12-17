<?php

namespace User\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use User\Service\Health\Healer;

class HealthController
{
    public function getAction(Application $app)
    {
        /* @var $healer Healer */
        $healer = $app['healer'];
        if ($healer->checkHealth()) {
            return new Response('I am alive!');
        } else {
            return new Response('Aww! I can\'t feel my legs =(', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
