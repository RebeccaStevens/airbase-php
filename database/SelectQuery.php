<?php namespace lib\database;
/**
 * @author Mike Stevens
 * @version 0.1.2.0
 */
class SelectQuery extends AbstractQuery {

    /** @var array The fields used in the query */
    protected $_fields;

    /** @var array The tables used in the query */
    protected $_tables;

    /** @var array The where clauses used in the query (these are joined by AND) */
    protected $_where;

    /** @var array The fields used to order the results */
    protected $_orderByFields;

    /** @var mixed Array of booleans, The order the orderBy results should be returned in (for each field). true: ASCENDING order, false: DESCENDING order */
    protected $_orderByOrder;

    /** @var integer The number of recorders to limit the results to */
    protected $_limitAmount;

    /** @var integer The offset to use when get the record results */
    protected $_limitOffset;

    /** @var integer The fetch mode to use */
    protected $_fetchMode;

    function __construct(Database $db, array $fields=null){
        parent::__construct($db);

        foreach($fields as $field){
            $this->_fields[] = str_replace($this->getGraveAccent(), '', $field);	// add the field to the array of fields, remove any `s for the field name
        }
        return $this;
    }

    /**
     * Specify the table(s) to use.
     * Grave Accents (`) do not need to be given - they will be added automatically.
     *
     * @param string $table A name of a table to add to the query (more than 1 can be given)
     * @return SelectQuery $this
     */
    public function from($table){
        $arguments = is_array($table) ? $table : func_get_args();
        foreach($arguments as $table){
            $this->_tables[] = str_replace($this->getGraveAccent(), '', $table);	// add the table to the array of tables, remove any `s for the table name
        }
        return $this;
    }

    /**
     * Add a where clause/clauses to the query.
     * If multiple clause are given, they are joined by AND.
     * Grave Accents (`) will <strong>NOT</strong> be added automatically, insert them where needed.
     *
     * @param string $clause A where clause to use in the query (more than 1 can be given)
     * @return SelectQuery $this
     */
    public function where($clause){
        $arguments = is_array($clause) ? $clause : func_get_args();
        foreach($arguments as $clause){
            $this->_where[] = $clause;							// add the clause to the array of where clauses
        }
        return $this;
    }

    /**
     * Limit the number of effected records.
     * Note: if two arguments are given, the first one will be used as the offset
     * while the second one will be used as the limit amount.
     *
     * @param integer $arg0 When $arg1 is unset, The amount to limit the number of effected records to, otherwise the offset
     * @param integer $arg1 When set, The amount to limit the number of effected recoreds to
     * @return SelectQuery $this
     */
    public function limit($arg0, $arg1=null){
        $this->_limitAmount = isset($arg1) ? $arg1 : $arg0;		// set the limit amount
        $this->_limitOffset = isset($arg1) ? $arg0 : null;		// set the limit offset
        return $this;
    }

    /**
     * The amount to offset the starting point of the effected recorders by.
     * Note: The offset will be ignored if a limit is not also set.
     *
     * @param integer $offset The amount to offset the effected records by
     * @return SelectQuery $this
     */
    public function offset($offset){
        $this->_limitOffset = $offset;							// set the limit offset
        return $this;
    }

    /**
     * Specify the fields to use when ordering the results of the query.
     * Fields will be sorted in ascending order unless otherwise specified (using the orderByOrder method).
     * Multiple fields can be given to specify how to sort records that are equivalent in the previous field.
     *
     * @param string $field A field to sort the results by (more than 1 can be given)
     * @return SelectQuery $this
     */
    public function orderBy($field){
        $fields = is_array($field) ? $field : func_get_args();
        foreach($fields as $field){
            $this->_orderByFields[] = str_replace($this->getGraveAccent(), '', $field);	// add the field to the array of fields to order the results by, remove any `s for the field name
        }
        return $this;
    }

    /**
     * Specify the fields to use when ordering the results of the query.
     * Fields will be sorted in descending order.
     * Multiple fields can be given to specify how to sort records that are equivalent in the previous field.
     *
     * @param string $field A field to sort the results by (more than 1 can be given)
     * @return SelectQuery $this
     */
    public function orderByDESC($field){
        $arguments = is_array($field) ? $field : func_get_args();
        $orders = array_fill(0, count($arguments), self::DESC);
        $this->orderBy($arguments);
        $this->orderByOrder($orders);
        return $this;
    }

    /**
     * Set the order (ASC or DESC) the orderBy fields will return in
     * The same number of arguments should be give as was given to the orderBy method
     * @param mixed $asc database::ASC or database::DESC to specify the order of the corresponding field (more than 1 can be given, can also be given as an array)
     * @return SelectQuery $this
     */
    public function orderByOrder($asc){
        $this->_orderByOrder = is_array($asc) ? $asc : func_get_args(); // TODO check: array should be copied, not passed by reference
        return $this;
    }
    
    public function groupBy(){
    	// TODO
    }

    public function execute(array $bindData=array()){
        if(count($this->_tables) < 1){
            throw new TableCountException('No table specified to select from.');
        }

        return $this->_execute($bindData, $this->_fetchMode);
    }

    public function getSQL(){
        return
            'SELECT ' .     $this->_buildSqlFields($this->_fields) .
            ' FROM ' .      $this->_buildSqlTables($this->_tables) .
            (isset($this->_where)         ? ' WHERE '    .  $this->_buildSqlWhere($this->_where) : '') .
            (isset($this->_orderByFields) ? ' ORDER BY ' .  $this->_buildSqlOrderBy($this->_orderByFields, $this->_orderByOrder) : '') .
            (isset($this->_limitAmount)   ? ' LIMIT '    .  $this->_buildSqlLimit($this->_limitOffset, $this->_limitAmount) : '');
    }

    /**
     * Set the fetch mode to use.
     * See http://www.php.net/manual/en/pdo.constants.php for available fetch modes
     * @param int $fetchMode A PDO fetch mode
     * @return $this
     */
    public final function setFetchMode($fetchMode){
        $this->_fetchMode = $fetchMode;
        return $this;
    }
}