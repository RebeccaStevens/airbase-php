<?php namespace AirBase\form\fieldvalidationrule;

/**
 * Used by Form to validate data.
 */
abstract class FieldValidationRule{

	/** @var string The error message if the validation fails */
	private $_errorMessage;
	
	/**
	 * Contruct a Validation Rule.
	 *
	 * @param string $errorMessage The error message if the validation fails
	 */
	public function __construct($errorMessage='invalid input') {
		$this->_errorMessage = $errorMessage;
	}

	/**
	 * Test if the given data is valid for this rule.
	 *
	 * @param string $data The data to validate
	 * @return boolean True if the data is valid, otherwise false
	 */
	public abstract function validate($data);

	/**
	 * Returns the error message about invalid data.
	 *
	 * @return string An error message
	 */
	public function getErrorMessage() {
		return $this->_errorMessage;
	}
}
