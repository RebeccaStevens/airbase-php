<?php namespace lib;
use Exception;

/** These are the universal custom exceptions that the lib library will use */

class PageNotFoundException extends Exception	{ function __construct($e=null){ parent::__construct($e); } }
class NotLoggedInException extends Exception	{ function __construct($e=null){ parent::__construct($e); } }
class NotLoggedOutException extends Exception	{ function __construct($e=null){ parent::__construct($e); } }
class ModelNotFoundException extends Exception	{ function __construct($e=null){ parent::__construct($e); } }