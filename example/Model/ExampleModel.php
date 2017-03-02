<?php

namespace DavidFricker\RestAPI\Example\Model;

use DavidFricker\RestAPI\Capsule\Response;
use DavidFricker\RestAPI\Router;

class ExampleModel
{
	const END_POINT_NAME = 'example';
	
	private function isHelper($foo) {
	    return true;
	}

	public function makeBar($foo)
	{
		if($this->isHelper($foo)) {
			return (new Response(Router::CMD_PROCESSED))->message('bar');
		}

		return (new Response(Router::CMD_PROCESSED))->message('rab');
	}
}
