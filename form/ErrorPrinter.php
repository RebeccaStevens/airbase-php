<?php namespace lib\form;

/**
 * Print the error message out
 * @param array $errors An array of Validation Rules to print error messages
 * @author Mike Stevens
 * @version 1.0.0.0
 */
class ErrorPrinter{
	/**
	 * Print the field error messages out
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