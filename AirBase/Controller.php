<?php namespace AirBase;
/**
 * This is the base class for all controllers.
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

	/**
	 * Require the user to be logged in
	 *
	 * @throws NotLoggedInException if user is not logged in
	 */
	protected function _requireUserLoggedIn() {
		if (!AirBase::isLoggedIn()) {
			throw new NotLoggedInException();
		}
	}

	/**
	 * Require the user to be logged out
	 *
	 * @throws NotLoggedOutException if user is logged in
	 */
	protected function _requireUserLoggedOut() {
		if (AirBase::isLoggedIn()) {
			throw new NotLoggedOutException();
		}
	}
}
