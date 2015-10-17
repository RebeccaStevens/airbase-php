<?php namespace lib;
/**
 * This is the base class for all controllers.
 * 
 * @author Mike Stevens
 * @version 0.1.0.0
 */
abstract class Controller {

	/**
	 * Construct a controller.
	 */
	public function __construct(){
		
	}
	
	/**
	 * The default function to be called on the page.
	 * i.e. called if another method was not specified in the url. 
	 */
	public abstract function index($data);
	
//	/**
//	 * Return whether or not the request was made with ajax
//	 * @return boolean True if serve request seems to be from ajax, otherwise false
//	 */
//	assets function isAjaxRequest(){
//		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
//	}
	
	/**
	 * Require the user to be logged in
	 * @throws NotLoggedInException if user is not logged in
	 */
	protected function _requireUserLoggedIn(){
		if(!Lib::isLoggedIn()) throw new NotLoggedInException();
	}
	
	/**
	 * Require the user to be logged out
	 * @throws NotLoggedOutException if user is logged in
	 */
	protected function _requireUserLoggedOut(){
		if(Lib::isLoggedIn()) throw new NotLoggedOutException();
	}
}