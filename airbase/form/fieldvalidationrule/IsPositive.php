<?php namespace AirBase\form\fieldvalidationrule;

/**
 * Validate that the data is positive.
 */
class IsPositive extends FieldValidationRule {

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
		return $data > 0;
	}

}
