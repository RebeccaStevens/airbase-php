<?php namespace AirBase\form\fieldvalidationrule;

/**
 * Validate that the data is a phone number.
 */
class IsPhone extends FieldValidationRule {
	/**
	 * Create the rule
	 *
	 * @param string $errorMessage The error message display in the form.
	 */
	public function __construct($errorMessage='you did not enter a valid phone number') {
		parent::__construct($errorMessage);
	}

	/**
	 * @param string $data The data to validate
	 * @return boolean True if the data is valid, otherwise false
	 * @todo finish
	 */
	public function validate($data) {
		return preg_match("/^([0-9]{1,3})?[\- ]?[0-9]{3}[\- ]?[0-9]{4}$/", $data);
	}
}
