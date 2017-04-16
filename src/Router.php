<?php

namespace DavidFricker\RestAPI;

use DavidFricker\RestAPI\Capsule\Response;

/**
 * Route HTTP requests for the REST API
 */
class Router {
    // response code constants, details in API documentation
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
    const API_METHOD_PUT = 'PUT';
    const API_METHOD_DELETE = 'DELETE';

    /**
     * Namespace to search for the controller, psr-4 compatible
     * @var string
     */
    private $controller_namespace;

    /**
     * Namespace to search for the model, psr-4 compatible
     * @var string
     */
    private $model_namespace;

    public function __construct($controller_namespace, $model_namespace) {
      if ($controller_namespace === null || $model_namespace === null) {
            throw new \ArgumentCountError ('Please supply controller and model namespaces');
      }

      $this->controller_namespace = $controller_namespace;
      $this->model_namespace = $model_namespace;
    }

    /**
     * Serves the request to the API by validating and building the request internally
     * 
     * @param  Request $Request Instance of the Request class, representing the HTTP request
     * @return Response Instance of the Response object, ready to be rendered
     */
    public function serve($Request) {
      if (!is_object($Request)) {
        throw new \InvalidArgumentException('Please supply a request object to serve');
      }

      // Ensure request is formed correctly so we can route it to a controller
      if (empty($Request->getUrlElements())) {
          return (new Response(self::CMD_MALFORMED))->message('You cannot call the base, please choose an end-point.');
      }

      // build model and controller names
      $model_namespace = $this->model_namespace;
      $controller_namespace = $this->controller_namespace;

      $end_point = ucfirst($Request->getUrlElements(0));
      $model_name = $model_namespace . $end_point . 'Model';
      $controller_name = $controller_namespace . $end_point . 'Controller';

      // check that the controller and model exist, else command is invalid
      // assumes classes are psr-4 autoloader compliant
      if (!class_exists($controller_name) || !class_exists($controller_name)) {
          return (new Response(self::CMD_UNKNOWN))->message('End-point not found, please refer to the documentation.');
      }

      // initialise the controller class and pass the database connection, 
      // request, and model objects to the constructor
      // assumes if there is a model corresponding to the controller that passed the class_exists test
      $controller = new $controller_name($Request, new $model_name());
      

      // convert URL and method to an underscore separated string and then check if that exists in the class
      $method_name = $Request->getMethodName();

      // ensure the method corresponding to the action exists, allowing a graceful fail otherwise 
      // e.g. HEAD or stupid methods like that
      if (!method_exists($controller, $method_name)) {
          return (new Response(self::CMD_INVALID))->message('Operation not possible on this end-point.');
      }

      // call method on controller object
      return call_user_func(array($controller, $method_name));
    }
}
