<?php namespace lib\form\fieldvalidationrule;
/**
 * Validate that the data is an integer.
 * 
 * @author Mike Stevens
 * @version 1.0.0.0
 */
class IsNumeric extends FieldValidationRule {
	/**
	 * @param string $errorMessage The error message display in the form.
	 */
	public function __construct($errorMessage='invalid input'){
		parent::__construct($errorMessage);
	}
	
	/**
	 * @param string $data The data to validate
	 * @return boolean True if the data is valid, otherwise false
	 */
	public function validate($data){
		return is_numeric($data);
	}
	
}