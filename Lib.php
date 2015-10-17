<?php namespace lib;
/**
 * @author Mike Stevens
 * @version 0.1.2.0
 */
final class Lib {
	
	/** @var Lib The Lib instance */
	private static $me;
	
	/** @var string The path to this class's directory */
	private $_path;
	
	/** @var callable A callable function that will return whether or not the user is logged in */
	private $_isLoggedInFunction;

	private function __construct($path){
		$this->_path = $path;
	}
	
	/**
	 * initialize the lib library
	 * @param string $path The path the library is located in
     * @throws \Exception if this method is called more than once
	 */
	public static function init($path){
		if(isset(self::$me)) throw new \Exception('Cannot init the lib library again. This can only be done once.');
		
		require($path . __NAMESPACE__ . '/Exceptions.php');
		require($path . __NAMESPACE__ . '/Util.php');

        // register the autoload function
		spl_autoload_register(function($class){
            if(!Util::stringStartsWith($class, __NAMESPACE__ . "\\")) return;
            require(self::$me->_path . str_replace("\\", '/', $class) . '.php');
        });
		
		self::$me = new Lib($path);
	}

    /**
     * TODO
     * @param callable $isLoggedInFunction A callable function that can be used to determine if the user is logged in
     * @throws \Exception
     */
    public static function setIsLoggedInFunction($isLoggedInFunction){
        if(!isset($isLoggedInFunction) || !is_callable($isLoggedInFunction)){
            throw new \Exception("the given isLoggedInFunction function is invalid.");
        }
        self::$me->_isLoggedInFunction = $isLoggedInFunction;
    }

    /**
     * Returns whether or not the use is logged in.
     * The method calls the function provided when this class was constructed.
     * If no function was given, false will always be returned.
     * @return boolean whether or not the user is logged in
     */
    public static function isLoggedIn(){
        if(isset(self::$me->_isLoggedInFunction)){
            return call_user_func(self::$me->_isLoggedInFunction);
        }
        return false;
    }
}