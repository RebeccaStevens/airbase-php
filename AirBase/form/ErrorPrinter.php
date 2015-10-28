<?php namespace AirBase\form;

/**
 * Print a Form's field's error messages.
 */
class ErrorPrinter{

	/**
	 * Print the field error messages out.
	 *
	 * @param array $errors An array of Validation Rules to print error messages
	 */
	public function printFieldError(array $errors){
		$str = '';
		$error_lenght = count($errors);

		if($error_lenght == 0) return;

		for($i=0; $i<$error_lenght;){
			$str .= $errors[$i]->getErrorMessage();
			if(++$i < $error_lenght){
				$str .= ', ';
			}
		}
		echo $str . '.';
	}
}
