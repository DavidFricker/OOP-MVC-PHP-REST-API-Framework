<?php

abstract class AbstractModel 
{
	protected $db;
	protected $user_id;

	public function __construct($db)
	{
		$this->db = $db;
	}

	public function set_user_id($user_id) 
	{
		$this->user_id = $user_id;
	}
}