<?php namespace AirBase;

use Exception;

/**
 * Thrown when the requested model doesn't exist.
 */
class ModelDoesNotExistException extends Exception	{
  function __construct($e=null) {
    parent::__construct($e);
  }
}
