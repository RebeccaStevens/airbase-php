<?php namespace lib\database;
/**
 * @author Mike Stevens
 * @version 0.1.0.0
 */
class UpdateQuery extends AbstractQuery {

    /** @var array The fields used in the query */
    protected $_fieldsToValues;

    /** @var string The table to insert the data into */
    protected  $_table;

    /** @var array The where clauses used in the query (these are joined by AND) */
    protected $_where;

    function __construct(Database $db, $table){
        parent::__construct($db);
        $this->_table = $table;
        return $this;
    }

    public function set(array $fieldsToValues){
        foreach($fieldsToValues as $field => $value){
            $this->_fieldsToValues[str_replace($this->getGraveAccent(), '', $field)] = $value;
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

    public function execute(array $bindData=array()){
        return $this->_execute($bindData);
    }

    public function getSQL(){
        return
            'UPDATE ' . $this->getGraveAccent() . $this->_table . $this->getGraveAccent() .
            ' SET ' .    $this->_buildSqlAssociativeArray($this->_fieldsToValues) .
            ' WHERE ' .  $this->_buildSqlWhere($this->_where);
    }
}