<?php namespace lib\database;

use PDOException;

/**
 * This Exception is thrown when an unsupported database type is given during construction.
 * @author Mike Stevens
 * @version 0.1.0.0
 */
class UnsupportedDatabaseTypeException extends PDOException{}