<?php namespace AirBase\database;

use PDOException;

/**
 * This Exception is thrown when an unsupported database type is given during construction.
 */
class UnsupportedDatabaseTypeException extends PDOException {

}
