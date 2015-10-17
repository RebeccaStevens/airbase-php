<?php namespace lib;
/**
 * A wrapper class for sessions
 * 
 * @author Mike Stevens
 * @version 0.1.4.0
 */
class Session{
	
	/**
	 * Start a session.
	 */
	public static function start(){
		session_start();
	}
	
	/**
	 * End the session and delete it.
	 */
	public static function destroy(){
		unset($_SESSION);
		session_destroy();
	}
	
	/**
	 * Get the value of the given key from the session.
	 * If the given key does not exist, false is returned.
	 * @param string $key The key to get the value of
	 * @return mixed The value of the key
	 */
	public static function get($key){
		if(isset($_SESSION[$key])){
			return $_SESSION[$key];
		}
		return false;
	}
	
	/**
	 * Set a key in the session.
	 * @param string $key The key to set
	 * @param mixed $value The value to set the key to
	 * @return mixed Returns $value
	 */
	public static function set($key, $value){
		return $_SESSION[$key] = $value;
	}
	
	/**
	 * Append an element to an array stored in the session
	 * @param mixed $array the name (session key) of the array to append to
	 * @param mixed $value the vaue to append
	 */
	public static function append($array, $value){
		if(!isset($_SESSION[$array])){
			$_SESSION[$array] = array();
		}
		$_SESSION[$array][] = $value;
	}
	
	/**
	 * Clear (unset) a key in the session.
	 * @param string $key The key to clear
	 */
	public static function clear($key){
		unset($_SESSION[$key]);
	}
	
	/**
	 * Clear (unset) the key at the given index of an array stored in the session
	 * @param mixed $array the name (session key) of the array
	 * @param mixed $index the index or key to unset
	 */
	public static function clearIndex($array, $index){
		if(!isset($_SESSION[$array])){ return; }
		unset($_SESSION[$array][$index]);
	}
	
	/**
	 * Returns whether or not the given key exist in the session.
	 * @param string $key The key to test if it exist
	 * @return boolean True is the key is set, otherwise false
	 */
	public static function hasKey($key){
		return array_key_exists($key, $_SESSION);
	}
	
	/**
	 * Returns whether or not the given key exist and has a value.
	 * @param string $key The key to test if it has a value.
	 * @return boolean True is the key exist and has a value, otherwise false
	 */
	public static function hasKeyValue($key){
		return self::hasKey($key) && isset($_SESSION[$key]);
	}
}