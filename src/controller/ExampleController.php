<?php

namespace DavidFricker\RestAPI\Controller;

use DavidFricker\RestAPI\Controller\AbstractController;
use DavidFricker\RestAPI\Capsule\Response;
use DavidFricker\RestAPI\Router;

class ExampleController extends AbstractController
{
    public function get()
    {
        if(!$this->isAuthorised())
        {
            return new Response(Router::USR_UNAUTHORIZED);
        }

        if(!$this->request->getParameters('domain'))
        {
            return (new Response(Router::CMD_MALFORMED))->payload(['message' => 'Please supply a domain as a parameter.']);
        }

        $result = $this->model->getDomain($this->request->getParameters('domain'));
        
        return (new Response(Router::CMD_PROCESSED))->payload($result);
    }
    
    // Genertate a single receipt key to sign a single email
    public function post()
    {
        if(!$this->isAuthorised())
        {
            return new Response(Router::USR_UNAUTHORIZED);
        }

        return (new Response(Router::CMD_PROCESSED))->payload(['receipt' => $receipt]);
    }
}
