<?php
require_once('functions.php');

class SqlArray {
		
	// Globals define here.	
	protected $db;
	
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

	public function buildSelectSQL($arrWhere, $sqlSuffix = null) {
		
		$sql = 'select ';
		$columns = '';
		$wheres = '';

		if(count($arrWhere)>0) :
			foreach($arrWhere as $key => $val) :
				$wheres .= $key . '=' . '\'' . $this->realEscape($val) . '\' and ';
			endforeach;
			$wheres = 'and ' . substr($wheres, 0, -5);
		endif;

		foreach ($this->tableColumns as $column) :
			$columns .= $column . ',';
		endforeach;

		$sql .= substr($columns, 0, -1);
		$sql .= ' from ' . TABLE_PREFIX . $this->tableName;
		$sql .= ' where 1=1 ' . $wheres . ' ' . $sqlSuffix;

		return $sql;
	}

	public function dbGetRow($arrWhere, $sqlSuffix = null) {

		$resultSet = $this->db->query($this->buildSelectSQL($arrWhere, $sqlSuffix));

		if($this->db->affected_rows>1) $this->throwError('Query returned too many rows.');

		if($this->db->affected_rows==1) :
			return $resultSet->fetch_object();
		else :
			return false;
		endif;
	}

	public function dbGetObjectArray($arrWhere, $sqlSuffix) {

		$resultSet = $this->db->query($this->buildSelectSQL($arrWhere, $sqlSuffix));
		$data = array();
		
		while($row = $resultSet->fetch_object()) {
			$data[] = $row;
		}
		
		return $data;
	}
	
}
?>