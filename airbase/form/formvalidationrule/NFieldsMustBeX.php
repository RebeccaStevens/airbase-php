<?php namespace AirBase\form\formvalidationrule;
/**
 * Validate that n of the given fields must be equal to x
 */
class NFieldsMustBeX extends FormValidationRule {

	private $_fields;
	private $_n;
	private $_x;
	private $_strict;

	/**
	 * Create the rule.
	 *
	 * @param array $fields The fields to check against x
	 * @param mixed $x The value to test against
	 * @param integer $n The number of fields that must be equal to x
	 * @param string $strict If true, exactly n fields must equal x, otherwise at least n fields must equal x
	 * @param string $errorMessage The error message display in the form
	 */
	public function __construct(array $fields, $x, $n=1, $strict=false, $errorMessage=null){
		if (!isset($errorMessage)) {
			$errorMessage = ($strict ? 'Exactly ' : 'At least ') . $n . ' field(s) from [';

			$field_lenght = count($fields);
			for ($i=0; $i<$field_lenght;) {
				$errorMessage .= $fields[$i];
				if (++$i < $field_lenght) {
					$errorMessage .= ', ';
				}
			}
			$errorMessage .= '] must be equal to ';
			if ($x === true) {
				$errorMessage .= 'true';
			}
			else if ($x === false) {
				$errorMessage .= 'false';
			}
			else {
				$errorMessage .= $x;
			}
		}

		parent::__construct($errorMessage);
		$this->_fields = $fields;
		$this->_n = $n;
		$this->_x = $x;
	}

	/**
	 * Test if the given data in the form valid for this rule.
	 *
	 * @param array $data The data of all the fields in the form
	 * @return boolean True if the data is valid, otherwise false
	 */
	public function validate(array $data) {
		$nCount = 0;
		foreach ($this->_fields as $field) {
			if ($data[$field] == $this->_x) {
				$nCount++;
				if ($this->_strict && $nCount > $this->_n) {
					return false;
				}
				if (!$this->_strict && $nCount >= $this->_n) {
					return true;
				}
			}
		}
		return $this->_strict && $nCount == $this->_n;
	}
}
