<?php namespace AirBase\form;

use AirBase\form\fieldvalidationrule\FieldValidationRule;
use AirBase\form\formvalidationrule\FormValidationRule;

/**
 * The Form class is used to validate submitted data from a form.
 * Example of use:
 * <code>
 * <?php
 * $form = new Form();
 * $form->post('name')
 * 		->validateField(new MaxLength(20, 'Your name can\'t be mor then 20 characters long'))
 * 		->post('age')
 * 		->validateField(new IsInteger('Your age must be an integer'))
 * 		->validateField(new MinValue(10, 'Your age cannot be less than 10'))
 * 		->validateField(new MaxValue(50, 'Your age cannot be more than 50'))
 * 		->post('gender')
 * 		->validateField(new IsOneOf(array('m', 'f'), 'Your gender must be either \'m\' or \'f\''));
 * ?>
 * </code>
 *
 * The validate function takes a ValidationRule that is uses to determine if the last data field is valid or not.
 *
 * The form can then be tested if it valid or not with:
 * <code>
 * <?php $form->isValid(); ?>
 * </code>
 *
 * Data can be take out of the form again, example:
 * <code>
 * <?php $form->getData('name'); ?>
 * </code>
 *
 * All invalid fields will have an error message that can be optained using the printError function, for example:
 * <code>
 * <?php $form->printError('name'); ?>
 * </code>
 *
 * By default printError(...) will just list the first validation error messages
 * for each field that failed validation (comma serperated).
 * This however can be changed by providing your own ErrorPrinter.
 * <code>
 * <?php
 * $form = new Form();
 * $form->setErrorPrinter(new MyErrorPrinter());
 *
 * class MyErrorPrinter extends ErrorPrinter{
 * 	public function printError(array $errors) {
 * 		//...
 * 		echo($errors[0]->getErrorMessage());
 * 		echo($errors[1]->getErrorMessage());
 * 		//...
 * 	}
 * }
 * ?>
 * </code>
 *
 * Note: Do not use the same field name more than once with get() and post().
 * If you do so, the second one will overwrite the first one.
 *
 * <code>
 * <?php
 * $form1->post('name');
 *       ->post('name');    // Bad
 *
 * $form2->get('name');
 *       ->get('name');     // Bad
 *
 * $form3->post('name');
 *       ->get('name');     // Bad
 * ?>
 * </code>
 */
class Form {

	/** @var array Stores submitted data */
	private $_data = array();

	/** @var array All the required field */
	private $_requiredFields = array();

	/** @var string The most recent given field */
	private $_currentField = null;

	/** @var array Stores the field validation rules that failed for each field */
	private $_fieldValidationFailures = array();

	/** @var array Stores the form validation rules that failed */
	private $_formValidationFailures = array();

	/** @var boolean Store whether or not any validations have failed so far */
	private $_hasError = false;

	/** @var booelan If true, remaining validation rules will not be tested once one has failed */
	private $_earlyEscape = false;

	/** @var ErrorPrinter The error print to use to print out error messages */
	private $_errorPrinter = null;

	/**
	 * Construct a Form.
	 *
	 * @param boolean $earlyEscape If true, remaining validation rules will not be tested once one has failed
	 */
	function __construct($earlyEscape=false) {
		$this->_earlyEscape = $earlyEscape;
	}

	/**
	 * Returns the string value of the given field (if not set, the current field will be used).
	 *
	 * @param string $field The field to get the data for
	 * @return string The value
	 */
	public function getData($field=null) {
		if (isset($field)) {
			return $this->_data[$field];
		}
		return $this->_data[$this->_currentField];
	}

	/**
	 * Returns whether a field with the given name has been processed and exist.
	 *
	 * @param string $field The field in question
	 * @return boolean Whether it exists
	 */
	public function fieldExist($field) {
		return array_key_exists($field, $this->_data);
	}

	/**
	 * Returns an associative array of the field validation rules that failed for each field.
	 *
	 * @return array
	 */
	public function getFieldValidationFailures() {
		return $this->_fieldValidationFailures;
	}

	/**
	 * Returns an array of the form validation rules that failed.
	 *
	 * @return array
	 */
	public function getFormValidationFailures() {
		return $this->_formValidationFailures;
	}

	/**
	 * Set the current field.
	 *
	 * @param string $field
	 * @return Form returns this form
	 */
	public function setCurrentField($field) {
		$this->_currentField = $field;
		return $this;
	}

	/**
	 * Add the given field to this form (sent with the post method)
	 * Example:
	 * <code>
	 * <?php
	 * // Note: isset($_POST['name']) must be true
	 * $form = new Form();
	 * $form->post('name');
	 * ?>
	 * </code>
	 *
	 * @param string $field The field to add
	 * @param boolean $required If false, if the field is empty it is consider valid.
	 * @return Form returns this form
	 */
	public function post($field, $required=true) {
		$this->_setupNewField($field, true, $required);
		return $this;
	}

	/**
	 * Add the given field to this form (sent with the get method)
	 * Example:
	 * <code>
	 * <?php
	 * // Note: isset($_GET['name']) must be true
	 * $form = new Form();
	 * $form->get('name');
	 * ?>
	 * </code>
	 *
	 * @param string $field The field to add
	 * @param boolean $required If false, if the field is empty it is consider valid.
	 * @return Form returns this form
	 */
	public function get($field, $required=true) {
		$this->_setupNewField($field, false, $required);
		return $this;
	}

	/**
	 * Validate the last given field using the given Field Validation Rule.
	 * Example:
	 * <code>
	 * <?php
	 * $form = new Form();
	 * $form->post('name')->validateField(new MinLength(5));
	 * ?>
	 *
	 * </code>
	 * @param FieldValidationRule $fieldValidationRule The validation rule to use
	 * @param boolean $not If ture, the validation rule will be cosided valid if it returns false
	 * @return Form returns this form
	 */
	public function validateField(FieldValidationRule $fieldValidationRule, $not=false) {
		// early escape
		if ($this->_earlyEscape && $this->_hasError) {
			return $this;
		}

		// if the field is not required
		if (!array_key_exists($this->_currentField, $this->_requiredFields)) {
			// if the field is empty
			if (empty($this->_data[$this->_currentField])) {
				return $this;	// return - the field is valid
			}
		}

		// if the validation rule fails
		if ($fieldValidationRule->validate($this->_data[$this->_currentField]) == $not) {
			// put the validation rule into the array of validation failures
			$this->_fieldValidationFailures[$this->_currentField][] = $fieldValidationRule;
			$this->_hasError = true;
		}

		return $this;
	}

	/**
	 * Validate the form using the given Form Validation Rule.
	 * This method should only be called after all data that the Form Validation Rule needs has been
	 * give to the form object (using the post/get methods).
	 *
	 * @param FormValidationRule $formValidationRule The validation rule to use
	 * @param boolean $not If ture, the validation rule will be cosided valid if it returns false
	 * @return Form returns this form
	 */
	public function validateForm(FormValidationRule $formValidationRule, $not=false) {
		// early escape
		if ($this->_earlyEscape && $this->_hasError) {
			return $this;
		}

		// if the validation rule fails
		if ($formValidationRule->validate($this->_data) == $not) {
			// put the validation rule into the array of validation failures
			$this->_formValidationFailures[] = $formValidationRule;
			$this->_hasError = true;
		}

		return $this;
	}

	/**
	 * Returns whether or not the form is valid
	 * (based on all the validate function calls made so far)
	 *
	 * @return boolean True if the form is valid, otherwise false
	 */
	public function isValid() {
		return !$this->_hasError;
	}

	/**
	 * Returns whether or not the given field in this form is valid
	 * (based on all the validate function calls made so far)
	 *
	 * @return boolean True if the given field for this form is valid, otherwise false
	 */
	public function isValidField($field) {
		return !array_key_exists($field, $this->_fieldValidationFailures) || empty($this->_fieldValidationFailures[$field]);
	}

	/**
	 * Print out the error message (if there is one) for the given field.
	 *
	 * @param string $field The field to print the error message for
	 * @param ErrorPrinter $errorPrinter The ErrorPrinter to use to print out the error messages
	 *                                   (if null the form will uses it's default error printer)
	 */
	public function printError($field, ErrorPrinter $errorPrinter=null) {
		// no error printer given for this print
		if ($errorPrinter === null) {
			// does the form have an error printer set?
			if (isset($this->_errorPrinter)) {
				$errorPrinter = $this->_errorPrinter;
			}
			else {
				// create a default error printer and use it (and set the form's error printer to it for next time)
				$errorPrinter = $this->_errorPrinter = new ErrorPrinter();
			}
		}

		//if(array_key_exists($field, $this->_fieldValidationFailures)) {
		$errorPrinter->printFieldError($this->_fieldValidationFailures[$field]);	// print the given field's errors
		//}
	}

	/**
	 * Set the error printer that the form will use by default to print out error messages.
	 *
	 * @param ErrorPrinter $errorPrinter The error printer to use
	 */
	public function setErrorPrinter(ErrorPrinter $errorPrinter) {
		$this->_errorPrinter = $errorPrinter;
	}

	/**
	 * Add a new field to form.
	 *
	 * @param string $field The field add to the form
	 * @param boolean $post If ture, the field's data will be taken from $_POST, otherwise from $_GET
	 * @param boolean $required If false, if the field is empty, it is valid
	 */
	private function _setupNewField($field, $post, $required) {
		$this->_data[$field] = $post ?
			(isset($_POST[$field]) ? $_POST[$field] : '') :
			(isset($_GET[$field])  ? $_GET[$field]  : '');				// get the field

		$this->setCurrentField($field);													// mark it as the current field
		$this->_fieldValidationFailures[$field] = array();			// create a space for any validation failures it may have

		if ($required) {
			$this->_requiredFields[$field] = true;								// if it's required, added it to the require fields collection
		}
	}
}
