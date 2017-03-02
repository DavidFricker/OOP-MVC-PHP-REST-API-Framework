<?php

namespace DavidFricker\RestAPI\Example\Auth;

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
class BasicAuth {
  protected $user_id;

  public function isAuthorised()
  {
    // ensure the caller is autherised
    // spllit into this method instead of the gateway becuase it is possible an endpoint may not need autherisation
    if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
        return false;
    }

    $auth_user = $_SERVER['PHP_AUTH_USER'];
    $auth_pswd = $_SERVER['PHP_AUTH_PW'];

    /*
      Autherise user here
     */
    
    $this->user_id = 11;

    return true;
  }

  public function getUserId() {
    return $this->user_id;
  }
}