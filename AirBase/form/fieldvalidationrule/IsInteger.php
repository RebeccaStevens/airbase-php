<?php namespace AirBase\form\fieldvalidationrule;

/**
 * Validate that the data is an integer.
 */
class IsInteger extends FieldValidationRule {

	/**
	 * @param string $errorMessage The error message display in the form.
	 */
	public function __construct($errorMessage='invalid input') {
		parent::__construct($errorMessage);
	}

	/**
	 * @param string $data The data to validate
	 * @return boolean True if the data is valid, otherwise false
	 */
	public function validate($data) {
		if($data == '') return false;
		if($data[0] == '-' || $data[0] == '+') return ctype_digit(substr($data, 1));
		return ctype_digit($data);
	}

}
