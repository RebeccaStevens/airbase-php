<?php namespace lib\form\fieldvalidationrule;
/**
 * Validate that the data is of a maximum length.
 * 
 * @author Mike Stevens
 * @version 1.0.0.0
 */
class MaxLength extends FieldValidationRule {
	
	/** @var integer The maximum valid length to validate data agains */
	private $_maxLength;
	
	/**
	 * Create the rule
	 * @param integer $max the maximum valid length to validate the data agains
	 * @param string $errorMessage The error message display in the form.
	 */
	public function __construct($max, $errorMessage='invalid input'){
		parent::__construct($errorMessage);
		$this->_maxLength = $max;
	}
	
	/**
	 * @param string $data The data to validate
	 * @return boolean True if the data is valid, otherwise false
	 */
	public function validate($data){
		return strlen($data) <= $this->_maxLength;
	}
}