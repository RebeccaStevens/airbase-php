<?php namespace lib;
/**
 * This is the base class for all models.
 * All model's should call their super constructor. 
 * 
 * @author Mike Stevens
 * @version 0.1.0.1
 */
class Model {
	
	public function __construct(){
		
	}
	
	/**
	 * Connect to a database.
	 * 
	 * @param string $type The database type (e.g. `mysql`)
	 * @param string $host the host to connect to (e.g. `localhost`)
	 * @param string $dbname the name of the database to connect to (e.g. `mydatabase`)
	 * @param string $username the username to connect with (e.g. `root`)
	 * @param string $password the password to connect with
     * @return Database A new database object
	 */
	protected function _databaseConnect($type, $host, $dbname, $username, $password){
		return new Database($type, $host, $dbname, $username, $password);
	}
}