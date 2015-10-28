<?php namespace AirBase\form\fieldvalidationrule;
/**
 * Validate that the data is an email address.
 */
class IsEmail extends FieldValidationRule {
	/**
	 * Create the rule
	 *
	 * @param string $errorMessage The error message display in the form.
	 */
	public function __construct($errorMessage='you did not enter a valid email address') {
		parent::__construct($errorMessage);
	}

	/**
	 * @param string $data The data to validate
	 * @return boolean True if the data is valid, otherwise false
	 * @todo finish
	 */
	public function validate($data) {
		return filter_var($data, FILTER_VALIDATE_EMAIL);
	}
}
