<?php namespace lib\database;

/**
 * @author Mike Stevens
 * @version 0.1.1.0
 */
abstract class AbstractQuery {
    /** @var Database The database to query */
    protected $_db;

    /** @var boolean Ascending order */
    const ASC = true;

    /** @var boolean Descending order */
    const DESC = false;

    /**
     * Create a database Query.
     * @param database $db The database to execute the query on
     */
    function __construct(Database $db){
        $this->_db = $db;
        $this->_fetchMode = $this->_db->getFetchMode();
    }

    /**
     * Execute the sql query ($this->getSQL()) on the database.
     * @param array $bindData The data to be used in this query (prepared statement)
     * @param integer $fetchMode A PDO fetch mode to use, if null, the default will be used
     * @return mixed The results returned from the database
     */
    protected function _execute(array $bindData=array(), $fetchMode=null){
        return $this->_db->execute($this->getSQL(), $bindData, $fetchMode);
    }

    /**
     * Execute the query on the database.
     * @param array $bindData The data to be used in this query (prepared statement)
     * @return mixed The results returned from the database
     */
    public abstract function execute(array $bindData=array());

    /**
     * Get the sql query that will be executed on the database
     * @return string An SQL query (prepared statement)
     */
    public abstract function getSQL();

    /**
     * Will take an array and return it as string that can be used in an SQL query.
     * @param array $data The array to build the sql off of
     * @param boolean $buildKeys Whether to use the array's keys in the result
     * @param boolean $buildValues Whether to use the array's values in the result
     * @param string $connector String used to seperate keys and values (ignored if !($buildKeys && $buildValues))
     * @param string $separator String used to seperate values (or key-value pairs)
     * @param boolean $addGraveAccentsToKeys Whether or not to added Grave Accents (`) to the array's keys
     * @param boolean $addGraveAccentsToValues Whether or not to added Grave Accents (`) to the array's values
     * @return string A SQL snippit
     */
    private function _buildSqlArray(array $data, $buildKeys=false, $buildValues=true, $connector=' = ', $separator=', ', $addGraveAccentsToKeys=true, $addGraveAccentsToValues=false){
        $i_max = count($data);							        // the loop's upper bound
        $str = '';											    // string to return
        if($i_max == 0) return $str;                            // no data given
        $i=0;
        foreach($data as $key=>$value){
            if($buildKeys){
                if($addGraveAccentsToKeys)   $str .= $this->getGraveAccent() . str_replace('.', $this->getGraveAccent() . '.' . $this->getGraveAccent(), $key) . $this->getGraveAccent();
                else                         $str .= $key;
            }

            if($buildKeys && $buildValues){
                $str .= $connector;
            }

            if($buildValues){
                if($addGraveAccentsToValues) $str .= $this->getGraveAccent() . str_replace('.', $this->getGraveAccent() . '.' . $this->getGraveAccent(), $value) . $this->getGraveAccent();
                else                         $str .= $value;
            }

            if(++$i < $i_max) $str .= $separator;			    // increment i and then if we haven't appended all the data item yet, append a comma
        }
        return $str;
    }

    /**
     * Will take the values in an array and return a string that can be used as part of an SQL query.
     * @param array $data The array of values
     * @param string $separator The separatoring string used between values
     * @param string $addGraveAccents Whether to add grave accents (`) to the values
     * @return string A SQL snippit
     */
    protected function _buildSqlArrayValues(array $data, $separator=', ', $addGraveAccents=false){
        return $this->_buildSqlArray($data, false, true, null, $separator, true, $addGraveAccents);
    }

    /**
     * Will take the keys in an array and return a string that can be used as part of an SQL query.
     * @param array $data The array of keys
     * @param string $separator The separatoring string used between keys 
     * @param string $addGraveAccents Whether to add grave accents (`) to the keys
     * @return string A SQL snippit
     */
    protected function _buildSqlArrayKeys(array $data, $separator=', ', $addGraveAccents=true){
        return $this->_buildSqlArray($data, true, false, null, $separator, $addGraveAccents, false);
    }

    /**
     * Will take the key-value pairs in an array and return a string that can be used as part of an SQL query.
     * @param array $data The array to use
     * @param string $connector The separatoring string used between keys and values
     * @param string $separator The separatoring string used between key-value pairs
     * @param string $addGraveAccentsToKeys Whether to add grave accents (`) to the keys
     * @param string $addGraveAccentsToValues Whether to add grave accents (`) to the values
     * @return string A SQL snippit
     */
    protected function _buildSqlAssociativeArray(array $data, $connector=' = ', $separator=', ', $addGraveAccentsToKeys=true, $addGraveAccentsToValues=false){
        return $this->_buildSqlArray($data, true, true, $connector, $separator, $addGraveAccentsToKeys, $addGraveAccentsToValues);
    }

    /**
     * Will build the values in the given array in to a list of comma seperated values
     * that can be used in an SQL query (such as a SELECT query)
     * Note: if $fields is not set, '*' will be returned.
     * @param array $fields The array of fields to use
     * @return string A SQL snippit
     */
    protected function _buildSqlFields(array $fields=null){
        return isset($fields)
            ? $this->_buildSqlArrayValues($fields, ', ', true)
            : '*';
    }

    protected function _buildSqlTables(array $tables=null){
        return $this->_buildSqlArrayValues($tables);
    }

    protected function _buildSqlWhere(array $where=null){
        if(!isset($where) || count($where) < 1) return '';
        return '( ' . $this->_buildSqlArrayValues($where, ' ) AND ( ', false) . ' )';
    }

    protected function _buildSqlOrderBy(array $orderByFields=null, array $orderByOrder=null){
        $orderByOrderSet = false;
        if(count($orderByOrder) > 0){
            $orderByOrderSet = true;
            if(count($orderByFields) != count($orderByOrder)){
                throw new FieldCountException('The number of fields given to order the data by does not match the number of order by orders given.');
            }
        }
        $i_max = count($orderByFields);					            // the loop's upper bound
        if($i_max <= 0) return '';						    		// no order by fields given - we are done here
        $orderBy = '';					                            // the order by fields
        for($i=0; true;){
            $orderBy .= $this->getGraveAccent() . $orderByFields[$i] . $this->getGraveAccent();	            // append the next order by field
            if($orderByOrderSet) $orderBy .= ($orderByOrder[$i] ? ' ASC' : ' DESC');
            else $orderBy .= ' ASC';
            if(++$i < $i_max) $orderBy .= ', ';					    // increment i and then if we haven't appended all the order by fields yet, append a comma
            else break;											    // else, break out of the loop
        }
        // fix up any missing `s (`table.field` --> `table`.`field`)
        return str_replace('.', $this->getGraveAccent() . '.' . $this->getGraveAccent(), $orderBy);
    }

    protected function _buildSqlLimit($limitOffset, $limitAmount){
        if(!isset($limitAmount)) return '';				            // if no limit amount was set, we don't need to do anything here

        if(isset($limitOffset)){							        // was a limit offset set
            return $limitOffset . ', ' . $limitAmount;	            // return the limit amount and limit offset
        }
        else{
            return $limitAmount;				                    // return the limit amount
        }
    }
    
    
	protected final function getGraveAccent(){
		return $this->_db->getDatabaseType() == Database::TYPE_MYSQL  ? '`' : '';
	}
}