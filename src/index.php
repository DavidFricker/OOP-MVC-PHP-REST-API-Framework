<?php
// include API constants
require_once 'assets/libs/api.const.php';

// include the autoloader
require_once 'assets/libs/autoloader.init.php';

// connect to the database
$db = new db('mysql:host=localhost;dbname=', '', '');

// Parse the incoming request into an object - done via the constructor
$Request = new Request();

// Ensure request is formed correctly so we can route it to a controller
if(empty($Request->get_url_elements()))
{
    (new Response(CMD_MALFORMED))->render();
}

// any incorrect base name will get caught in the autoloader apart from is fine apart
// from 'Abstract'
if(stristr('abstract', $Request->get_url_elements(0)) !== false)
{
    (new Response(CMD_UNKNOWN))->render();
}

// build model and controller names
$end_point = ucfirst($Request->get_url_elements(0));
$model_name = $end_point . 'Model';
$controller_name = $end_point . 'Controller';

// check controller exists, else command is invalid
// we assume that if the controller exists the model will toos
if(!class_exists($controller_name))
{
    (new Response(CMD_UNKNOWN))->render();
}

// initalise the controller class and pass the databse connection, 
// request, and model objects to the constructor
// assumes if there is a model corresponding to the controller that passed the class_exists test
$controller = new $controller_name($db, $Request, new $model_name($db));

// convert url and method to an underscore seperted string and then check if that exists in the class
$method_name = $Request->get_method_name();

// ensure the method corresponding to the action exists, allowing a graceful fail otherwise 
// e.g. HEAD or stupid methods like that
if(!method_exists($controller, $method_name))
{
    (new Response(CMD_INVALID))->render();
}

// call method on controller object
$Response = call_user_func(array($controller, $method_name));

// outputs response in json format to stream inc. extra payload if needed
$Response->render();