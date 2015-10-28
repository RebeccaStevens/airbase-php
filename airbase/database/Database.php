<?php namespace AirBase\database;

use PDO;

class Database extends PDO {

	const TYPE_MYSQL = 0;
	const TYPE_PGSQL = 1;

	/** @var string The last executed query */
	private $_lastQuery = null;

	/** @var integer The default fetch mode to use */
	private $_fetchMode = PDO::FETCH_ASSOC;

	private $_type;

	/**
	 * Construct the database object
	 *
	 * @param string $type The database type (e.g. `mysql`)
	 * @param string $host the host to connect to (e.g. `localhost`)
	 * @param string $dbname the name of the database to connect to (e.g. `mydatabase`)
	 * @param string $username the username to connect with (e.g. `root`)
	 * @param string $password the password to connect with
	 */
	public function __construct($type, $host, $dbname, $username, $password) {
		if ($type === Database::TYPE_MYSQL || $type == 'mysql') {
			$this->_type = Database::TYPE_MYSQL;
			$type = 'mysql';
		}
		else if ($type === Database::TYPE_PGSQL || $type == 'pgsql') {
			$this->_type = Database::TYPE_PGSQL;
			$type = 'pgsql';
		}
		else{
			throw new UnsupportedDatabaseTypeException($type);
		}
		parent::__construct($type.':host='.$host.';dbname='.$dbname, $username, $password, array(
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,			// set the default fetch mode to associative
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION						// throw exceptions upon errors
		));
	}

	public function select($field=null) {
		return new SelectQuery($this, func_get_args());
	}

  public function insert($into) {
      return new InsertQuery($this, $into);
  }

  public function update($table) {
      return new UpdateQuery($this, $table);
  }

  public function delete() {
      return new DeleteQuery($this);
  }

	/**
	 * Execute the given query on the database in a prepared statement.
	 *
	 * @param string $sql The SQL statement to execute
	 * @param array $bindData The data to be used in this query (prepared statement)
	 * @param integer $fetchMode The fetch mode to use
	 * @return mixed True if the query was successful or the results returned from the database if appropriate
	 */
	public function execute($sql, array $bindData, $fetchMode) {
		$this->_lastQuery = $sql;

		$sth = $this->prepare($sql);							// prepare the query to be executed
		foreach ($bindData as $key=>$value) {			// bind the give data into the query
			$sth->bindValue($key, $value);
		}

		$sth->execute();													// execute the prepared statement
    if (isset($fetchMode)) {
	    return $sth->fetchAll($fetchMode);			// get and return the results using the specified fetch mode
    }
    return true;
	}

	/**
	 * Returns the last query (prepared statement) that was executed.
	 * Note: The return string will not have the data bond in to it.
	 *
	 * @return string The last query executed
	 */
	public function getLastQuery() {
		return $this->_lastQuery;								// return the last query
	}

	/**
	 * Get the default fetch mode that is being used.
	 * For available fetch modes see <a href="http://www.php.net/manual/en/pdo.constants.php">www.php.net/manual/en/pdo.constants.php</a>.
	 *
	 * @return integer The default fetch mode
	 */
	public final function getFetchMode() {
		return $this->_fetchMode;
	}

	/**
	 * Set the default fetch mode to use for future queries.
	 * For available fetch modes see <a href="http://www.php.net/manual/en/pdo.constants.php">www.php.net/manual/en/pdo.constants.php</a>.
	 *
	 * @param int $fetchMode A PDO fetch mode
	 */
	public final function setFetchMode($fetchMode) {
		$this->_fetchMode = $fetchMode;
	}

	public function getDatabaseType() {
		return $this->_type;
	}
}
