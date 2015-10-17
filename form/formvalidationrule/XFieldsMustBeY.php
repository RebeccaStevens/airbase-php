<?php namespace lib\form\formvalidationrule;
/**
 * Validate that x given fields must be equal to y
 * 
 * @author Mike Stevens
 * @version 1.0.0.0
 */
class XFieldsMustBeY extends FormValidationRule {
	
	private $_fields;
	private $_x;
	private $_y;
	private $_strict;
	
	/**
	 * Create the rule.
	 * @param array $fields The fields to check against y 
	 * @param mixed $y The value to test against
	 * @param integer $x The number of fields that must be equal to y
	 * @param string $strict If true, exactly x fields must equal y, others at least x fields must equal y
	 * @param string $errorMessage The error message display in the form
	 */
	public function __construct(array $fields, $y, $x=1, $strict=false, $errorMessage=null){
		if(!isset($errorMessage)){
			$errorMessage = ($strict ? 'Exactly ' : 'At least ') . $x . ' field(s) from [';
			
			$field_lenght = count($fields);
			for($i=0; $i<$field_lenght;){
				$errorMessage .= $fields[$i];
				if(++$i < $field_lenght){
					$errorMessage .= ', ';
				}
			}
			$errorMessage .= '] must be equal to ';
			if($y === true)			$errorMessage .= 'true';
			else if($y === false)	$errorMessage .= 'false';
			else 					$errorMessage .= $y;
		}
		
		parent::__construct($errorMessage);
		$this->_fields = $fields;
		$this->_x = $x;
		$this->_y = $y;
	}
	
	/**
	 * Test if the given data in the form valid for this rule.
	 * @param array $data The data of all the fields in the form
	 * @return boolean True if the data is valid, otherwise false
	 */
	public function validate(array $data){
		$xCount = 0;
		foreach($this->_fields as $field){
			if($data[$field] == $this->_y){
				$xCount++;
				if($this->_strict && $xCount > $this->_x){
					return false;
				}
				if(!$this->_strict && $xCount >= $this->_x){
					return true;
				}
			}
		}
		return $this->_strict && $xCount == $this->_x;
	}
}