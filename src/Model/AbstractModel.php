<?php

namespace DavidFricker\RestAPI\Model;

use DavidFricker\RestAPI\Capsule\Response;

abstract class AbstractModel 
{
	protected $db;
	protected $user_id;

	public function __construct()
	{
		//$this->db = AppConentProvider::init();
	}
}