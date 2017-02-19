<?php

namespace DavidFricker\RestAPI\Controller;

use DavidFricker\RestAPI\Auth\BasicAuth;
use DavidFricker\RestAPI\Capsule\Response;

abstract class AbstractController 
{
	protected $db;
	protected $request;
	protected $model;
	protected $user_id = null;
	private $auth_controller = null;
    
	public function __construct($request, $model)
	{
		//$this->db = AppConentProvider::init();
		$this->request = $request;
		$this->model = $model;
	}

	public function isAuthorised()
	{
		if ($this->auth_controller === null) {
			$this->auth_controller = new BasicAuth($this->request);
		}

		return $this->auth_controller->isAuthorised();
	}
}