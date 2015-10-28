<?php namespace AirBase\database;

use PDOException;

/**
 * This Exception is thrown when no tables where given when a least one was required.
 */
class FieldCountException extends PDOException {
  
}
