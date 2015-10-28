<?php namespace AirBase;

use Exception;

/**
 * Thrown when the user is required to be logged in but they arn't.
 */
class NotLoggedInException extends Exception {
  function __construct($e=null) {
    parent::__construct($e);
  }
}
