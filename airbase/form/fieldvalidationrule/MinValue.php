<?php namespace AirBase\form\fieldvalidationrule;

/**
 * Validate that the data (as a number) is no smaller than min.
 */
class MinValue extends FieldValidationRule {

	/** @var number The minimum valid size of the data */
	private $_minValue;

	/**
	 * Create the rule.
	 *
	 * @param number $max the minimum valid size to validate the data agains
	 * @param string $errorMessage The error message display in the form.
	 */
	public function __construct($min, $errorMessage='invalid input') {
		parent::__construct($errorMessage);
		$this->_minValue = $min;
	}

	/**
	 * @param string $data The data to validate
	 * @return boolean True if the data is valid, otherwise false
	 */
	public function validate($data) {
		return $data >= $this->_minValue;
	}
}
