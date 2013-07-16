<?php
require_once('functions.php');

class SqlArray {
		
	// Globals define here.	
	protected $db;
	private $sqlWhere;
	private $sqlOrderBy;
	private $sqlLike;   
	
	public function __construct() {
		$this->db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$this->db->query("SET GLOBAL sql_mode='STRICT_ALL_TABLES'");
		$this->db->set_charset("utf8");
	}
	
	public function __destruct() {
		$this->db->close();
		unset($this->db);
	}

	public function throwError($message) {
		echo '<div style="margin: 0 60px 0 60px; padding: 15px; color: red; background: pink; border: 1px solid maroon;">' . $message . '</div>';
		exit;
	}

	public function realEscape($_) {
		return $this->db->real_escape_string($_);
	}

	public function where($key, $val) 	{ $this->sqlWhere[$key] = $val; }
	public function order($key, $val) 	{ $this->sqlOrderBy[$key] = $val; }
	public function like($key, $val) 	{ $this->sqlLike[$key] = $val; }
	
	public function dbUpdate($arrData, $arrWhere = array()) {

		$sql 	= 'update ' . TABLE_PREFIX . $this->tableName . ' set ';
		$vals 	= '';
		$wheres = '';

		foreach($arrData as $key => $value) :
			$vals .= $key . '=' . '\'' . $this->realEscape($value) . '\',';
		endforeach;

		$sql .= substr($vals, 0, -1);

		if(count($arrWhere)>0) :
			$wheres = ' where ';
			foreach($arrWhere as $key => $value) :
				$wheres .= $key . '=' . '\'' . $this->realEscape($value) . '\' and ';
			endforeach;
		endif;

		$sql .= substr($wheres, 0, -5);

		$this->db->query($sql);

		if($this->db->affected_rows>0) :
			return $this->db->affected_rows;
		else :
			return false;
		endif;

	}

	public function dbInsert($arrData, $table = null) {

		$targetTable = ($table) ? $table : $this->tableName;
		$sql = 'insert into ' . TABLE_PREFIX . $targetTable . '(';
		$rows = "";
		$vals = "";

		foreach ($arrData as $key => $val) :
			$rows .= $key . ',';
			$vals .= '\'' . $this->realEscape($val) . '\',';
		endforeach;

		$sql .= substr($rows, 0, -1) . ') VALUES (' . substr($vals, 0, -1) . ')';
		$this->db->query($sql);

		
		if($this->db->affected_rows>0) :
			return $this->db->insert_id;
		else :
			$this->throwError($this->db->error);
		endif;
	}

	public function buildSelectSQL($table = null) {
		
		$sql 		= 'select ';
		$columns 	= '';
		$wheres 	= '';
		$likes 		= '';
		$order 		= '';
		$table 		= ($table) ? TABLE_PREFIX . $table : TABLE_PREFIX . $this->tableName; 

		// Where filters
		if(is_array($this->sqlWhere)) :
			foreach($this->sqlWhere as $key => $val) :
				
				// Make sure we load from correct table
				$targetTable = (in_array($key, $this->tableColumns)) ? $table : TABLE_PREFIX . "Object";

				$wheres .= $targetTable . '.' . $key . '=' . '\'' . $this->realEscape($val) . '\' and ';
			
			endforeach;
			$wheres = 'and ' . substr($wheres, 0, -5);
		endif;

		// Like filters
		if(is_array($this->sqlLike)) :
			foreach($this->sqlLike as $key => $val) :
				
				// Make sure we load from correct table
				$targetTable = (in_array($key, $this->tableColumns)) ? $table : TABLE_PREFIX . "Object";

				$likes .= $targetTable . '.' . $key . ' like ' . '\'%' . $this->realEscape($val) . '%\' and ';
			
			endforeach;
			$likes = 'and ' . substr($likes, 0, -5);
		endif;

		foreach ($this->tableColumns as $column) :
			$columns .= $table . '.' . $column . ',';
		endforeach;
		
		foreach ($this->objectColumns as $column) :
			$columns .= TABLE_PREFIX . "Object" . '.' . $column . ',';
		endforeach;

		$sql .= substr($columns, 0, -1);

		$sql .= ' from ' . $table;
		
		// Perform Object Join
		$sql .= " inner join " . TABLE_PREFIX . "Object on " . $table . '.id = ' . TABLE_PREFIX . "Object.id";

		$sql .= ' where 1=1 ' . $wheres . ' ' . $likes;

		// Order by
		if(is_array($this->sqlOrderBy)) :
			$order = ' order by ';
			foreach($this->sqlOrderBy as $key=>$val) {
				$targetTable = (in_array($key, $this->tableColumns)) ? $table : TABLE_PREFIX . "Object";
				$order .= $targetTable . '.' . $key . ' ' . $val . ',';
			}
		endif;

		$sql .= substr($order, 0, -1);

		echo $sql;

		// Reset filters
		$this->sqlWhere 	= null;
		$this->sqlLike 		= null;
		$this->sqlOrderBy 	= null;

		return $sql;
	}

	public function dbGetRow($table = null) {

		$resultSet = $this->db->query($this->buildSelectSQL($table));

		if($this->db->affected_rows>1) $this->throwError('Query returned too many rows.');

		if($this->db->affected_rows==1) :
			return $resultSet->fetch_object();
		else :
			return false;
		endif;
	}

	public function dbGetObjectArray($sqlSuffix, $table = null) {

		$resultSet = $this->db->query($this->buildSelectSQL($sqlSuffix, $table));
		$data = array();
		
		while($row = $resultSet->fetch_object()) {
			$data[] = $row;
		}
		
		return $data;
	}
	
}
?>