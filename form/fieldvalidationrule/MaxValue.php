<?php namespace lib\form\fieldvalidationrule;
/**
 * Validate that the data (as a number) is no larger than max.
 * 
 * @author Mike Stevens
 * @version 1.0.0.0
 */
class MaxValue extends FieldValidationRule {
	/** @var number The maximum valid size of the data */
	private $_maxValue;
	
	/**
	 * Create the rule
	 * @param number $max the maximum valid size to validate the data agains
	 * @param string $errorMessage The error message display in the form.
	 */
	public function __construct($max, $errorMessage='invalid input'){
		parent::__construct($errorMessage);
		$this->_maxValue = $max;
	}
	
	/**
	 * @param string $data The data to validate
	 * @return boolean True if the data is valid, otherwise false
	 */
	public function validate($data){
		return $data <= $this->_maxValue;
	}
}