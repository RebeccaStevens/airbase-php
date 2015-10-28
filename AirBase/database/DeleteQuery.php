<?php namespace AirBase\database;

class DeleteQuery extends AbstractQuery {

  /** @var string The table to insert the data into */
  protected  $_table;

  /** @var array The where clauses used in the query (these are joined by AND) */
  protected $_where;

  /** @var integer The number of recorders to limit the results to */
  protected $_limitAmount;

  function __construct(Database $db) {
      parent::__construct($db);
      return $this;
  }

  public function from($table) {
      $this->_table = $table;
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
  public function where($clause) {
    $arguments = is_array($clause) ? $clause : func_get_args();
    foreach ($arguments as $clause) {
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
  public function limit($arg0, $arg1=null) {
    $this->_limitAmount = isset($arg1) ? $arg1 : $arg0;		// set the limit amount
    $this->_limitOffset = isset($arg1) ? $arg0 : null;		// set the limit offset
    return $this;
  }

  public function execute(array $bindData=array()) {
    if(!isset($this->_where) || count($this->_where) == 0) {
      throw new WhereClauseException('A WHERE clause must be given when deleting entries.');
    }

    return $this->_execute($bindData);
  }

  public function getSQL() {
    return
      'DELETE FROM ' . $this->getGraveAccent() . $this->_table . $this->getGraveAccent() .
      ' WHERE ' .  $this->_buildSqlWhere($this->_where) .
      (isset($this->_limitAmount)   ? ' LIMIT ' .  $this->_buildSqlLimit(null, $this->_limitAmount) : '');
  }
}
