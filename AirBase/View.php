<?php namespace AirBase;

/**
 * A View can render any 'view' using it's render method.
 */
class View {

	/** @var array The files that this view will render */
	private $_viewFiles;

	/** @var array The stylesheets used in this view */
	private $_stylesheet;

	/** @var array The javascript files used in this view */
	private $_javascript;

	/** @var array The meta tags used in the view */
	private $_meta;

	function __construct(array $viewFiles) {
		$this->_viewFiles = $viewFiles;
		$this->_stylesheet = array();
		$this->_javascript = array();
		$this->_meta = array();
	}

	/**
	 * Render out this page.
	 *
	 * @param array $data Data that will be made available to the view
	 */
	public function render($data=array()) {
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}

		foreach ($this->_viewFiles as $viewFile) {
			require $viewFile;
		}
	}

	/**
	 * Will print out the view as a json object string.
	 *
	 * @param array $data Data that will be made available to the view
	 * @param array $meta Extra data that will be append to the json object as well as made available to the view
	 * @param string $key The key to map the content to
	 */
	public function renderAsJSON($data=array(), $meta=array(), $key='html') {
		ob_start();
		$this->render(array_merge($data, $meta));

		$stnd = array(
			$key => ob_get_contents()
		);
		ob_end_clean();
		header('Content-type: application/json');
		echo json_encode(array_merge($stnd, $meta));
	}

	/**
	 * Add a stylesheet to the view.
	 * Multiple file names my be given by giving multiple arguements or
	 * alternatively, an array of file names can be given.
	 *
	 * @param string $filePath Path to the file to include
	 */
	public function addStyleSheet($filePath) {
		if (is_array($filePath)) {
			$this->_stylesheet = array_merge($this->_stylesheet, $filePath);
		}
		else {
			foreach (func_get_args() as $filePath) {
				$this->_stylesheet[] = $filePath;
			}
		}
	}

	/**
	 * Print out links to the stylesheets given to this view.
	 * Example Output: <link rel="stylesheet" type="text/css" href="mystylesheet.css" />
	 */
	public function printStyleSheets() {
		foreach ($this->_stylesheet as $stylesheet) {
			echo('<link rel="stylesheet" type="text/css" href="'.$stylesheet.'" />');
		}
	}

	/**
	 * Add a javascript file to view.
	 * Multiple file names my be given by giving multiple arguements or
	 * alternatively, an array of file names can be given.
	 *
	 * @param string $filePath Path to the file to include
	 */
	public function addJavaScriptFile($filePath) {
		if (is_array($filePath)) {
			$this->_javascript = array_merge($this->_javascript, $filePath);
		}
		else{
			foreach(func_get_args() as $filePath) {
				$this->_javascript[] = $filePath;
			}
		}
	}

	/**
	 * Print out links to the javascript files given to this view.
	 * Example Output: <script type="text/javascript" src="myscript.js"></script>
	 */
	public function printJavaScriptFiles() {
		foreach ($this->_javascript as $javascriptFile) {
			echo('<script type="text/javascript" src="'.$javascriptFile.'"></script>');
		}
	}

	/**
	 * Add meta data to the view in a name-content pair.
	 * Alternatively, instead of passing in a name-content pair, an associative array of name-content paris can be given.
	 *
	 * @param string $name The name value to use in the meta tag
	 * @param string $content The content value to use in the meta tag
	 */
	public function addMetaData($name, $content='') {
		if(is_array($name)) {
			$this->_meta = array_merge($this->_javascript, $name);
		}
		else {
			$this->_meta[$name] = $content;
		}
	}

	/**
	 * Print out the meta tags given to this view.
	 * Example Output: <meta name="description" content="An awesome website.">
	 */
	public function printMetaData() {
		foreach ($this->_meta as $name => $content) {
			echo('<meta name="'.$name.'" content="'.$content.'">');
		}
	}

	/**
	 * Print out page link numbers of the other pages.
	 * e.g.
	 * First | Previous | 1 | 2 | 3 | 4 | 5 | 6 | 7 | Next | Last
	 *
	 * The link will link to $url/page_number
	 *
	 * @param string $url The link to the pages - without the page number at the end
	 * @param integer $page The page number of the page we are currently on
	 * @param integer $lastPage The page number of the last page
	 * @param integer $numLinks The number of page number links to show each side of the current page number
	 */
	public function printMultiPageLinks($url, $page, $lastPage, $numLinks) {
		if($lastPage <= 1) return;	// only one page? don't need to show more links

		$_pageIsInteger = Util::isInteger($page);

		if ($page > 1 || !$_pageIsInteger) {
			echo '<a href="'.$url.'1">First</a> | ';
		} else {
			echo '<a>First</a> | ';
		}

		if ($page > 1 && $_pageIsInteger) {
			echo '<a href="'.$url.($page-1).'">Previous</a> | ';
		} else {
			echo '<a>Previous</a> | ';
		}

		$ti = max($page - $numLinks, 1);																	// calculate the first link number - temp value
		$ti_max = min($page + $numLinks, $lastPage);											// calculate the last link number  - temp value
		$i = max($ti - ($numLinks + ($page - $ti_max)), 1);								// calculate the real first link number using the temp value
		$i_max = min($ti_max + ($numLinks - ($page - $ti)), $lastPage);		// calculate the real last link number using the temp value

		// print the number links
		while ($i<=$i_max) {
			if ($i == $page) {
				echo '<a>'.$i.'</a> | ';
			} else {
				echo '<a href="'.$url.$i.'">'.$i.'</a> | ';
			}
			$i++;
		}

		if ($page < $lastPage && $_pageIsInteger) {
			echo '<a href="'.$url.($page+1).'">Next</a> | ';
		} else {
			echo '<a>Next</a> | ';
		}

		if ($page < $lastPage || !$_pageIsInteger) {
			echo '<a href="'.$url.$lastPage.'">Last</a>';
		} else {
			echo '<a>Last</a> | ';
		}
	}
}
