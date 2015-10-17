<?php namespace lib\database;

use PDOException;

/**
 * This Exception is thrown when no tables where given when a least one was required.
 * @author Mike Stevens
 * @version 0.1.0.0
 */
class FieldCountException extends PDOException{}