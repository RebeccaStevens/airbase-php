<?php namespace AirBase\form\fieldvalidationrule;

/**
 * Validate that the data is of a minimum length.
 */
class MinLength extends FieldValidationRule {

	/** @var integer The minimum valid length to validate data agains */
	private $_minLength;

	/**
	 * Create the rule.
	 * @param integer $max the maximum valid length to validate the data agains
	 * @param string $errorMessage The error message display in the form.
	 */
	public function __construct($min, $errorMessage='invalid input') {
		parent::__construct($errorMessage);
		$this->_minLength = $min;
	}

	/**
	 * @param string $data The data to validate
	 * @return boolean True if the data is valid, otherwise false
	 */
	public function validate($data) {
		return strlen($data) >= $this->_minLength;
	}
}
