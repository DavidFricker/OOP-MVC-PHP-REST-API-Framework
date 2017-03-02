<?php

namespace DavidFricker\RestAPI\Example\Controller;

use DavidFricker\RestAPI\Example\Auth\BasicAuth;
use DavidFricker\RestAPI\Capsule\Response;

/*
	NB: In production, bases classes and any other class that should not be created by the public through the router should not be stored in this folder 
 */
class BaseController
{
	protected $request;
	protected $model;
	private $auth_controller;

	public function __construct($request, $model)
	{
		$this->request = $request;
		$this->model = $model;
	}

	protected function isAuthorised()
	{
		if ($this->auth_controller == null) {
			$this->auth_controller = new BasicAuth($this->request);
		}

		return $this->auth_controller->isAuthorised();
	}

	protected function isWhitelisted() {
		// client is in the whitelist
		return true;		
	}

	protected function getSenderId() {
		if (!$this->isAuthorised()) {
			return false;
		}

		if ($this->user_id != null) {
			return $this->user_id;
		}

		return $this->user_id = $this->auth_controller->getUserId();
	}
}
