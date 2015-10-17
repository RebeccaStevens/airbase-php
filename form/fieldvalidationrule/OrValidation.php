<?php namespace lib\form\fieldvalidationrule;
/**
 * As long as one of the Validation rules given validates to true, 
 * this validation rule will validate to true.
 * 
 * @author Mike Stevens
 * @version 1.0.0.0
 */
class OrValidation extends FieldValidationRule {
	/** @var array The validation rules */
	private $_validationRules;
	
	/**
	 * Create the rule
	 * @param array $validationRules the validation rules to join with or.
	 * @param string $errorMessage The error message display in the form.
	 */
	public function __construct(array $validationRules, $errorMessage='invalid input'){
		parent::__construct($errorMessage);
		$this->_validationRules = $validationRules;
	}
	
	/**
	 * @param string $data The data to validate
	 * @return boolean True if the data is valid, otherwise false
	 */
	public function validate($data){
		foreach($this->_validationRules as $rule){
			if($rule->validate($data)){
				return true;
			}
		}
		return false;
	}
}