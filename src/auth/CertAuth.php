<?php
namespace DavidFricker\RestAPI\Auth;

use DavidFricker\RestAPI\Auth\AbstractAuth;

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
class CertAuth extends AbstractAuth 
{
  public function isAuthorised()
  {
    return true;

    // ensure the caller is autherised
    // spllit into this method instead of the gateway becuase it is possible an endpoint may not need autherisation
    if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']))
    {
        return false;
    }

    $auth_user = $_SERVER['PHP_AUTH_USER'];
    $auth_pswd = $_SERVER['PHP_AUTH_PW'];

    $query_result = $this->db->run('', array(':APIKey' => $auth_pswd, ':CustomerID' => $auth_user));
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