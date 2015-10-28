<?php namespace AirBase;

use Exception;

/**
 * Thrown when the user is required to be logged out but they arn't.
 */
class NotLoggedOutException extends Exception	{
  function __construct($e=null){
    parent::__construct($e);
  }
}
