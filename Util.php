<?php namespace lib;
/**
 * A class of static utility methods
 * 
 * @author Mike Stevens
 */
class Util{
	
	/**
	 * Round a number up to the nearest integer.
	 * Examples:
	 *  6.7 -->  7
	 *  3.2 -->  4
	 * -2.1 --> -2
	 *  3.0 -->  3
	 * 
	 * @param number $number The number to round up to the nearest integer
	 * @return integer The result - the rounded up number
	 */
	public static function roundUp($number){
		if(Util::isInteger($number)){				// is the number we where given already an integer?
			return $number;							// if so just return it
		}
		
		if($number > 0){							// if the number is positive
			return intval($number) + 1;				// round it towards zero (down) and add 1
		}
		
		// else the number is negitive
		return intval($number);						// round it towards zero (up)
	}
	
	/**
	 * Round a number down to the nearest integer.
	 * Examples:
	 *  6.7 -->  6
	 *  3.2 -->  3
	 * -2.1 --> -3
	 *  3.0 -->  3
	 * 
	 * @param number $number The number to round down to the nearest integer
	 * @return integer The result - the rounded down number
	 */
	public static function roundDown($number){
		if(Util::isInteger($number)){				// is the number we where given already an integer?
			return $number;							// if so just return it
		}
		
		if($number > 0){							// if the number is positive
			return intval($number);					// round it towards zero (down)
		}
		
		// else the number is negative
		return intval($number) - 1;					// round it towards zero (up) and subtract 1
	}
	
	/**
	 * Tells whether or not the given number is an integer or not.
	 * 
	 * @param number $number The number to test
	 * @return boolean True if the number is an integer, otherwise false
	 */
	public static function isInteger($number){
		return is_numeric($number) && intval($number) == $number;
	}
	
	/** 
	 * Returns a safe output version of the given string.
	 * @param string $string The string to process
	 * @return string A safe version of the string
	 */
	public static function stringToSafeOutput($string){
//		$string = trim($string);									// remove any unnecessary whitespace at the beginning and end of the string
//		$string = htmlspecialchars($string);						// convert all the html special characters i.e. "&" becomes "&amp;"	
//		$string = preg_replace('{\s*\n\s*}', '<br />', $string);	// replace new line character with break tag (remove unneeded white space)
//		$string = preg_replace('{\s\s+}', ' ', $string);			// remove extra whitespace
//		return $string;
		
		return preg_replace('{\s\s+}', ' ', preg_replace('{\s*\n\s*}', '<br />', htmlspecialchars(trim($string))));
	}
	
	/**
	 * Test whether the given $string starts with the given $prefix.
	 * @param string $string The string to test on
	 * @param string $prefix The prefix to test with
	 * @return boolean True if $string starts with $prefix
	 */
	public static function stringStartsWith($string, $prefix){
	    return $prefix === '' || strpos($string, $prefix) === 0;
	}
	
	/**
	 * Test whether the given $string ends with the given $suffix.
	 * @param string $string The string to test on
	 * @param string $suffix The suffix to test with
	 * @return boolean True if $string ends with $suffix
	 */
	public static function stringEndsWith($string, $suffix){
	    return $suffix === '' || substr($string, -strlen($suffix)) === $suffix;
	}
	
	/**
	 * Will return whether or not the given array has any data in it.
	 * @param array $data The data to test
	 * @return boolean True if $data isset and is not empty
	 */
	public static function arrayHasData(array $data=null){
		return isset($data) && !empty($data);
	}
}