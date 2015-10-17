<?php namespace lib;
/**
 * 
 * @author Mike Stevens
 * @version 0.1.0.0
 */
class Hash {

	/**
	 * Create a hash of the given data using the given salt.
	 * @param string $data the data to be encoded
	 * @param string $salt the salt used in the encoding
	 * @param string $algorithm the algorithm used in the encoding (default: sha512)
	 * @return an hash of the data
	 */
	public static function create($data, $salt, $algorithm='sha512'){
		$context = hash_init($algorithm, HASH_HMAC, $salt);
		hash_update($context, $data);
		return hash_final($context, true);
	}
	
	/**
	 * Generate a new salt
	 * @param int $size The number of bytes to make the salt (default: 64)
	 * @return string The salt
	 */
	public static function generateSalt($size=64){
		return openssl_random_pseudo_bytes($size);
	}
	
}