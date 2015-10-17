<?php namespace lib\form\fieldvalidationrule;
/**
 * Validate that the data is a date
 * 
 * @author Mike Stevens
 * @version 1.0.0.0
 */
class IsDate extends FieldValidationRule {
	/**
	 * Create the rule
	 * @param string $errorMessage The error message display in the form.
	 */
	public function __construct($errorMessage='you did not enter a valid date'){
		parent::__construct($errorMessage);
	}
	
	/**
	 * @param string $data The data to validate
	 * @param string $format The format the date should be in
	 * @return boolean True if the data is valid, otherwise false
	 * @todo finish
	 */
	public function validate($data, $format = 'd/m/Y'){
		$d = DateTime::createFromFormat($format, $data);	// create a DateTime from the data
    	return $d && $d->format($format) == $data;			// return whether or not the date was valid
	}
}