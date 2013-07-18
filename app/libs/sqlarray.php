<?php
if(!defined('BASE_URL')) die('No direct script access');

require_once('functions.php');

class SqlArray {

	// Globals define here.	
	protected $db;
	private $sqlWhere;
	private $sqlOrderBy;
	private $sqlLike;
	private $sqlLimit;
	
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
	public function limit($key, $val) 	{ $this->sqlLimit = "$key, $val"; }
	public function like($key, $val) 	{ $this->sqlLike[$key] = $val; }
	
	public function filter($type, $key, $val) {
		switch($type) {
			case "where" :
				$this->where($key, $val);
				break;
			case "order" :
				$this->order($key, $val);
				break;
			case "like" :
				$this->like($key, $val);
				break;
			case "limit" :
				$this->limit($key, $val);
		}
	}

	public function dbUpdate($arrData, $arrWhere = array(), $table = null) {

		$table = ($table) ? $table : $this->tableName; 
		$sql 	= 'update ' . TABLE_PREFIX . $table . ' set ';
		$vals 	= '';
		$wheres = '';

		foreach($arrData as $key => $value) :
			
			if(!$value) continue;
			
			if(is_int($value)) {
				$vals .= $key . '=' . $this->realEscape($value) . ',';
			} else {
				$vals .= $key . '=' . '\'' . $this->realEscape($value) . '\',';
			}
		endforeach;

		$sql .= substr($vals, 0, -1);

		if(count($arrWhere)>0) :
			$wheres = ' where ';
			foreach($arrWhere as $key => $value) :
				if(is_int($value)) {
					$wheres .= $key . '=' . $this->realEscape($value) . ' and ';
				} else {
					$wheres .= $key . '=' . '\'' . $this->realEscape($value) . '\' and ';
				}
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

		global $_GLOBALS;

		$columns 	= '';
		$wheres 	= '';
		$likes 		= '';
		$order 		= '';
		$limit 		= '';
		$table 		= ($table) ? TABLE_PREFIX . $table : TABLE_PREFIX . $this->tableName; 

		
		#-----------------------------------------------------------------------------
		# Where filters
		
		if(is_array($this->sqlWhere)) :
			foreach($this->sqlWhere as $key => $val) :
				
				// Make sure we load from correct table
				$targetTable = (in_array($key, $this->tableColumns)) ? $table : TABLE_PREFIX . "Object";

				$wheres .= $targetTable . '.' . $key . '=' . '\'' . $this->realEscape($val) . '\' and ';
			
			endforeach;
			$wheres = 'and ' . substr($wheres, 0, -5);
		endif;
		#-----------------------------------------------------------------------------

		

		#-----------------------------------------------------------------------------
		# Like filters
		
		if(is_array($this->sqlLike)) :
			foreach($this->sqlLike as $key => $val) :
				
				// Make sure we load from correct table
				$targetTable = (in_array($key, $this->tableColumns)) ? $table : TABLE_PREFIX . "Object";

				$likes .= $targetTable . '.' . $key . ' like ' . '\'%' . $this->realEscape($val) . '%\' and ';
			
			endforeach;
			$likes = 'and ' . substr($likes, 0, -5);
		endif;
		#-----------------------------------------------------------------------------
		

		

		#-----------------------------------------------------------------------------
		# Columns to select
		
		foreach ($this->tableColumns as $column) :
			$columns .= $table . '.' . $column . ',';
		endforeach;
		
		foreach ($this->objectColumns as $column) :
			$columns .= TABLE_PREFIX . "Object" . '.' . $column . ',';
		endforeach;
		
		$columns = substr($columns, 0, -1);
		#-----------------------------------------------------------------------------
		

		
		
		#-----------------------------------------------------------------------------
		# Perform Object Join
		
		$join = " inner join " . TABLE_PREFIX . "Object on " . $table . '.id = ' . TABLE_PREFIX . "Object.id";
		#-----------------------------------------------------------------------------



		
		#-----------------------------------------------------------------------------
		# Order by
		
		if(is_array($this->sqlOrderBy)) :
			$order = ' order by ';
			foreach($this->sqlOrderBy as $key=>$val) {
				$targetTable = (in_array($key, $this->tableColumns)) ? $table : TABLE_PREFIX . "Object";
				$order .= $targetTable . '.' . $key . ' ' . $val . ',';
			}
		endif;
		$order = substr($order, 0, -1);
		#-----------------------------------------------------------------------------

		

		#-----------------------------------------------------------------------------
		# Limit
		
		if($this->sqlLimit) :
			$limit = " limit $this->sqlLimit";
		endif;
		#-----------------------------------------------------------------------------

		
		

		#-----------------------------------------------------------------------------
		# Build SQL string

		$sql  = 'select ' . TABLE_PREFIX . "Object.id, ";
		$sql .= $columns;
		$sql .= ' from ';
		$sql .= $table;
		$sql .= $join;
		$sql .= ' where 1=1 ';
		$sql .= $wheres;
		$sql .= $likes;
		$sql .= $order;
		$sql .= $limit;
		#-----------------------------------------------------------------------------

		


		#-----------------------------------------------------------------------------
		# Get the total number of results, without the $limit.
		# Used for the paging object since we need the total number of records.
		
		$totSql = "select count(" . TABLE_PREFIX . "Object.id) as paging_count from $table $join where 1=1 $wheres $likes";
		$count 	= $this->db->query($totSql);
		$data = null;
		
		while($row = $count->fetch_object()) {
			$data[] = $row;
		}

		if(is_array($data)) $_GLOBALS["paging_count"] = $data[0]->paging_count;
		#-----------------------------------------------------------------------------


		

		#-----------------------------------------------------------------------------
		# Reset filters
		
		$this->sqlWhere 	= null;
		$this->sqlLike 		= null;
		$this->sqlOrderBy 	= null;
		$this->sqlLimit 	= null;
		#-----------------------------------------------------------------------------

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