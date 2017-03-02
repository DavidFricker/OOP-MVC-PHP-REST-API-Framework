<?php

namespace DavidFricker\RestAPI\Example\Controller;

use DavidFricker\RestAPI\Example\Controller\BaseController;
use DavidFricker\RestAPI\Capsule\Response;
use DavidFricker\RestAPI\Router;

class ExampleController extends BaseController
{
    // Genertate a single receipt key to sign a single email
    public function post() {
        if (!$this->isAuthorised() || !$this->isWhitelisted()) {
            return new Response(Router::USR_UNAUTHORIZED);
        }
        
        /*
        if (!$this->request->getParameters('foo')) {
            return (new Response(Router::CMD_MALFORMED))->message('Please supply the meaning of foo.');
        }
        */

        return $this->model->makeBar($this->request->getParameters('foo'));
    }
}
