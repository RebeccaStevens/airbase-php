<?php namespace AirBase;

use Exception;

/**
 * Thrown when a requested page is not found.
 */
class PageNotFoundException extends Exception	{
  function __construct($e=null) {
    parent::__construct($e);
  }
}
