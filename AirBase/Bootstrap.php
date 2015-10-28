<?php namespace AirBase;

use \ReflectionMethod;

/**
 * This will process the url and construct the controller for the page.
 */
class Bootstrap {

	/** @var array The url of the page (not including the domain) exploded at the '/'s */
	private $_url;

	/** @var Controller The controller for this page */
	private $_controller;

	/** @var string The path to the site's controllers */
	private $_controllers_path;

	/**
	 * Initialize the Bootstrap
	 *
	 * @throws PageNotFoundException if the url of a page the does not exist.
	 */
	public function __construct($controllers_path, $get_var) {
		$this->_controllers_path = $controllers_path;
		$index = 0;
		$this->_setUrl($get_var);							// set the url of the page
		$this->_loadController($index);				// load the controller for the page
		$this->_run($index);									// run the page
	}

	/**
	 * Set $this->_url.
	 * $this->_url will be set to an array constructed by exploding the url of the page
	 */
	private function _setUrl($get_var) {
		$url = $_GET[$get_var];
		$this->_url =
			isset($url) && !empty($url)					// if we where given a url (and it's not empty)
			? explode('/',											// explode the url at the '/'s
				filter_var(												// sanitize the url
					rtrim($url, '/'),								// remove any trailing '/'s from the url
					FILTER_SANITIZE_URL))
			: array('home');										// no url given - pretend we were given home
	}

	/**
	 * Load the controller for this page.
	 *
	 * @throws PageNotFoundException if the controller does not exist
	 */
	private function _loadController(&$index) {
		$file = $this->_findController($this->_controllers_path, $index);

		// if the controller doesn't exist then the page doesn't exist
		if (!file_exists($file)) {
			throw new PageNotFoundException("The file: $file does not exist.");
		}

		require($file);																		// load the controller's file
		$this->_controller = new $this->_url[$index]();		// construct a new controller
		$index++;																					// done with this part of the url
	}

	/**
	 * Will search from the given path recursively looking for the controller's file.
	 * The file is not guaranteed to actually exist.
	 *
	 * @param string The path to search in
	 * @param integer $index The index in the url we are up to (usually this should be 0 when called externally)
	 * @return string The file path of the controller
	 */
	private function _findController($path, &$index) {
		// if there is no more url to look at, but we are still searching
		if (count($this->_url) <= $index) {
			// take the index controller in the current folder
			$this->_url[] = 'index';
			return $path . 'index.php';
		}

		// does the directory exist
		if (file_exists($newpath = ($path.$this->_url[$index].'/'))) {
			$index++;
			return $this->_findController($newpath, $index);	// go into that directory and continue the search
		}
		return $path . $this->_url[$index] . '.php';				// the file of the controller
	}

	/**
	 * Run the page.
	 * Call a function on the page's controller dictated by the next part of the url.
	 * If there is no next part of the url, then call the default function (the index function).
	 */
	private function _run(&$index) {
		// is there more to the url than just the controller?
		if (isset($this->_url[$index])) {
			$hasMethod = false;

			// if the method ($url[$index]) exist in the controller (don't allow the index method to be specified)
			if ($this->_url[$index] != 'index' && method_exists($this->_controller, $this->_url[$index])) {
			 	// if the method is public
			 	$reflection = new ReflectionMethod($this->_controller, $this->_url[$index]);
			    if ($reflection->isPublic()) {
			    	$hasMethod = true;			// then we have a method
			    }
			 }

			// if there is a method to call
			if ($hasMethod) {
				if (count($this->_url) == $index + 1) {
					// there's nothing else to the url - just call the method
					$this->_controller->{$this->_url[$index]}(null);
				}
				else {
					// there's still more to the url - pass in the rest of the url to the method
					$this->_controller->{$this->_url[$index]}(array_slice($this->_url, $index + 1));
				}
			}
			// if there is no method to call
			else {
				// then call the index method and pass in the rest of the url
				$this->_controller->index(array_slice($this->_url, $index));
			}
		}
		// nothing more to the url
		else {
			$this->_controller->index(null);	// just call the default method
		}
	}
}
