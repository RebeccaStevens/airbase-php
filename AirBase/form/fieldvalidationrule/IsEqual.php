<?php namespace AirBase\form\fieldvalidationrule;
/**
 * Validate that the data is the same as the given value.
 */
class IsEqual extends FieldValidationRule {

	/** @var string The valid options */
	private $_value;

	/**
	 * Create the rule
	 *
	 * @param string $value the value the data must be to be valid
	 * @param string $errorMessage The error message display in the form.
	 */
	public function __construct($value, $errorMessage='invalid input'){
		parent::__construct($errorMessage);
		$this->_value = $value;
	}

	/**
	 * @param string $data The data to validate
	 * @return boolean True if the data is valid, otherwise false
	 */
	public function validate($data){
		return $data == $this->_value;
	}
}
