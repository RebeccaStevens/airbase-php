<?php namespace AirBase;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * The main class for AirBase
 */
final class AirBase {

	/** @var AirBase The AirBase instance */
	private static $me;

	/** @var callable A callable function that will return whether or not the user is logged in */
	private $_isLoggedInFunction;

	private function __construct() {

	}

	/**
	 * Initialize AirBase
	 *
   * @throws \Exception if this method is called more than once
	 */
	public static function init() {
		if(isset(self::$me)) throw new \Exception('Cannot initialize AirBase again. This can only be done once.');
		self::$me = new AirBase();
	}

  /**
	 * Set the function used to determine whether the user is logged in or not.
	 *
   * @param callable $isLoggedInFunction A callable function that can be used to determine if the user is logged in
   * @throws \Exception
   */
  public static function setIsLoggedInFunction($isLoggedInFunction) {
    if(!isset($isLoggedInFunction) || !is_callable($isLoggedInFunction)) {
      throw new \Exception("the given value is not a callable function.");
    }
    self::$me->_isLoggedInFunction = $isLoggedInFunction;
  }

  /**
   * Is the user logged in?
	 * Will always return false unlease the isLoggedIn function is set.
	 *
	 * @see setIsLoggedInFunction
   * @return boolean whether or not the user is logged in
   */
  public static function isLoggedIn() {
    if(isset(self::$me->_isLoggedInFunction)) {
      return call_user_func(self::$me->_isLoggedInFunction);
    }
    return false;
  }
}
