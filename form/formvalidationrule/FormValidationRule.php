<?php namespace lib\form\formvalidationrule;

/**
 * Used by Form to validate data.
 * 
 * @author Mike Stevens
 * @version 1.0.0.0
 */
abstract class FormValidationRule{
	
	/** @var string The error message if the validation fails */
	private $_errorMessage;
	
	/**
	 * Contruct a Validation Rule
	 * @param string $errorMessage The error message if the validation fails
	 */
	public function __construct($errorMessage='invalid form state'){
		$this->_errorMessage = $errorMessage;
	}
	
	/**
	 * Test if the given data in the form valid for this rule.
	 * @param array $data The data of all the fields in the form
	 * @return boolean True if the data is valid, otherwise false
	 */
	public abstract function validate(array $data);
	
	/**
	 * Returns the error message about invalid data.
	 * @return string An error message 
	 */
	public function getErrorMessage(){
		return $this->_errorMessage;
	}
}