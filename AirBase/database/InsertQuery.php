<?php namespace AirBase\database;

class InsertQuery extends AbstractQuery {

  /** @var string The table to insert the data into */
  protected  $_table;

  /** @var array  */
  protected $_fields;

  /** @var array The values to be inserted into the table */
  protected $_values;

  function __construct(Database $db, $into) {
    parent::__construct($db);
    $this->_table = str_replace($this->getGraveAccent(), '', $into);   // TODO null check on $into
    return $this;
  }

  public function fields(array $fields) {
  	foreach ($fields as $field) {
  		$this->_fields[] = str_replace($this->getGraveAccent(), '', $field);
  	}
  	return $this;
  }

  public function values(array $values) {
  	foreach (func_get_args() as $valueSet) {
  		$x = $this->_values[] = array();
      foreach ($valueSet as $value) {
        $x[] = $value;
      }
  	}
    return $this;
  }

  public function execute(array $bindData=array()) {
    return $this->_execute($bindData);
  }

  public function getSQL() {
    $sql =
      'INSERT INTO ' . $this->getGraveAccent() . $this->_table . $this->getGraveAccent() . ' ( ' .
      $this->_buildSqlArrayValues($this->_fields) .
      ' ) VALUES ';

    $i = 0;
    $c = count($valueSet);
    foreach ($this->_values as $valueSet) {
      $sql .= ' ( ' . $this->_buildSqlArrayValues($valueSet, ', ', false) . ' )';
		  if(++$i < $c) {
        $sql .= ', ';
      }
    }
    return $sql;
  }
}
