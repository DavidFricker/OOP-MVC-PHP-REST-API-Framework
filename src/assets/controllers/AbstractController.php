<?php
abstract class AbstractController 
{
	protected $db;
	protected $request;
	protected $model;
	protected $user_id = null;
    
	public function __construct($db, $request, $model)
	{
		$this->db = $db;
		$this->request = $request;
		$this->model = $model;
	}

	public is_authorised()
	{
		// ensure the caller is autherised
		// spllit into this method instead of the gateway becuase it is possible an endpoint may not need autherisation
		if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])
		{
		    return false;
		}

		$auth_user = $_SERVER['PHP_AUTH_USER'];
		$auth_pswd = $_SERVER['PHP_AUTH_PW'];

		if($this->db->row_count() < 1)
		{
		    return false;
		}

		// cache and distrobubte user id for future use
		$this->user_id = $query_result[0]['CustomerID'];
		$this->model->set_user_id($this->user_id);

		return true;
	}
}
