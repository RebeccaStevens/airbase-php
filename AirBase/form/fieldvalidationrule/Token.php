<?php namespace AirBase\form\fieldvalidationrule;

/**
 * Validate the given token is valid.
 */
class Token extends FieldValidationRule {

	/** @var string The key to use when storing the token in the user's session */
	const TOKEN = 'token';

	/**
	 * Generates a token - A random string of characters.
	 * Note: A session must be started before calling this method.
	 *
	 * @return string The token
	 */
	public static function generate(){
		return $_SESSION[self::TOKEN] = base64_encode(openssl_random_pseudo_bytes(32));
	}

	/**
	 * @param string $errorMessage The error message display in the form.
	 */
	public function __construct($errorMessage='invalid input'){
		parent::__construct($errorMessage);
	}

	/**
	 * Check if a the give data matches the token that was generated for the form.
	 * Note: A session must be started before calling this method.
	 *
	 * @param string $data The data to validate
	 * @return boolean True if the data is valid, otherwise false
	 */
	public function validate($data){
		if (isset($_SESSION[self::TOKEN]) && $data === $_SESSION[self::TOKEN]) {
			unset($_SESSION[self::TOKEN]);
			return true;
		}
		return false;
	}
}
