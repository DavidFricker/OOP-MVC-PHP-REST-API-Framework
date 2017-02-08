<?php

namespace DavidFricker\RestAPI\Auth;

abstract class AbstractAuth
{
  protected $db;
  protected $user_id;

  public function __construct($db)
  {
    //$this->db = AppConentProvider::init();
  }

  public function isAuthorised() {}
}