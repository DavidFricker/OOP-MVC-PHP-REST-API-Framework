<?php

namespace DavidFricker\RestAPI;

use DavidFricker\RestAPI\Capsule\Response;

/**
  * A wrapper around a DB driver to expose a uniform interface
  *
  * Bassically an abstraction over the complexity of the PDO class, but by design this could wrap any strctured storage mechanism 
  * A database engine adapter
  *
  * @param string $myArgument With a *description* of this argument, these may also
  *    span multiple lines.
  *
  * @return void
  */
class Router {
    // response code constants
    const CMD_PROCESSED = 1;
    const CMD_UNKNOWN = 2;
    const CMD_INVALID = 3;
    const CMD_MALFORMED = 4;
    const USR_UNAUTHORIZED = 5;
    const SRC_NOTFOUND = 6;
    const INTERNAL_ERROR = 8;
    const CMD_UNPROCESSABLE = 9;

    const API_METHOD_GET = 'GET';
    const API_METHOD_POST = 'POST';

    private $controller_namespace;
    private $model_namespace;

    public function __construct($controller_namespace, $model_namespace) {
      $this->controller_namespace = $controller_namespace;
      $this->model_namespace = $model_namespace;
    }

    public function serve($Request) {
      // Ensure request is formed correctly so we can route it to a controller
      if (empty($Request->getUrlElements())) {
          (new Response(self::CMD_MALFORMED))->render();
      }

      // any incorrect base name will get caught in the autoloader apart from is fine apart
      // from 'Abstract'
      //if (stristr('abstract', $Request->getUrlElements(0)) !== false) {
      //    (new Response(self::CMD_UNKNOWN))->render();
      //}

      // build model and controller names
      $model_namespace = $this->model_namespace;
      $controller_namespace = $this->controller_namespace;

      $end_point = ucfirst($Request->getUrlElements(0));
      $model_name = $model_namespace . $end_point . 'Model';
      $controller_name = $controller_namespace . $end_point . 'Controller';

      // check controller exists, else command is invalid
      // we assume that if the controller exists the model will toos
      if (!class_exists($controller_name)) {
          (new Response(self::CMD_UNKNOWN))->render();
      }

      // initalise the controller class and pass the databse connection, 
      // request, and model objects to the constructor
      // assumes if there is a model corresponding to the controller that passed the class_exists test
      $controller = new $controller_name($Request, new $model_name());

      // convert url and method to an underscore seperted string and then check if that exists in the class
      $method_name = $Request->getMethodName();

      // ensure the method corresponding to the action exists, allowing a graceful fail otherwise 
      // e.g. HEAD or stupid methods like that
      if (!method_exists($controller, $method_name)) {
          (new Response(self::CMD_INVALID))->render();
      }

      // call method on controller object
      $Response = call_user_func(array($controller, $method_name));

      // outputs response in json format to stream inc. extra payload if needed
      $Response->render();
    }
}
