<?php

require '../src/Router.php';
require '../src/capsule/Request.php';
require '../src/capsule/Response.php';
require '../src/controller/AbstractController.php';
require '../src/controller/ExampleController.php';
require '../src/model/AbstractModel.php';
require '../src/model/ExampleModel.php';
require '../src/auth/AbstractAuth.php';
require '../src/auth/BasicAuth.php';

use DavidFricker\RestAPI\Router;
use DavidFricker\RestAPI\Capsule\Request;

/*
	To add a new end point simply place a new controller and new model in thier respective folders
	Both should extend thier respective abstract classes
 */

// Parse the incoming request into an object - done via the constructor
$Request = new Request();
$Router = new Router('namespace\controllers\\', 'namespace\models\\');

$Router->serve($Request);