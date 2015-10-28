<?php namespace AirBase\form\fieldvalidationrule;

/**
 * Validate that the data is one of the given options.
 */
class IsOneOf extends FieldValidationRule {

	/** @var array The valid options */
	private $_options;

	/**
	 * Create the rule
	 * @param array $options the valid value for the data
	 * @param string $errorMessage The error message display in the form.
	 */
	public function __construct(array $options, $errorMessage='invalid input') {
		parent::__construct($errorMessage);
		$this->_options = $options;
	}

	/**
	 * @param string $data The data to validate
	 * @return boolean True if the data is valid, otherwise false
	 */
	public function validate($data) {
		foreach ($this->_options as $option) {
			if ($data === $option) {
				return true;
			}
		}
		return false;
	}
}
