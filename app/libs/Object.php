<?php

if(!defined('BASE_URL')) die('No direct script access');

abstract class Object extends sqlArray {
	
	#-----------------------------------------------------------------------------
	# Array populated with columns from the SilverCube Objects table
	
	protected $objectColumns = array();
	#-----------------------------------------------------------------------------

	#-----------------------------------------------------------------------------
	# Defines what table we want to load columns from

	public $tableName = null;
	#-----------------------------------------------------------------------------

	#-----------------------------------------------------------------------------
	# Array populated with columns from the defined table
	
	protected $tableColumns = array();
	#-----------------------------------------------------------------------------

	#-----------------------------------------------------------------------------
	# These are the class variables we don't want to process in our inserts/updates

	private $ignoreKeys = array("id", "tableName", "tableColumns", "objectColumns", "db", "sqlWhere", "sqlOrderBy", "sqlLike", "sqlLimit", "sqlPaging");
	#-----------------------------------------------------------------------------
	
	public $id;

	public function __construct() {
		
		parent::__construct();

		$this->tableName = (get_class($this)=="Object") ? null : get_class($this);
		
		if($this->tableName) $this->setTableColumns();
	}

	public function setTableColumns() {
		if(isset($this->tableName) && $this->tableName!="") :

			$this->tableColumns = null;

			$q 			= "select * from " . TABLE_PREFIX . $this->tableName;
			$q2 		= "select * from " . TABLE_PREFIX . "Object";

			$cols 		= $this->db->query($q);
			$cols2 		= $this->db->query($q2);

			$fields 	= $cols->fetch_fields();
			$fields2	= $cols2->fetch_fields();
			
			foreach($fields as $key) :
				if($key->name=="id") continue;
				$this->tableColumns[] = $key->name;
			endforeach;

			foreach($fields2 as $key) :
				if($key->name=="id") continue;
				$this->objectColumns[] = $key->name;
			endforeach;

		endif;
	}

	public function setTable($table) {
		$this->tableName = $table;
		$this->setTableColumns();
	}

	public function createObject() {
		
		$tableData = array();
		$classVars = get_object_vars($this);

		$tableData = array(
			"object_type" 	=> $this->tableName,
			"created_date" 	=> date('Y-m-d H:i:s'),
			"created_by"	=> (isset($_SESSION['sc_user_id'])) ? $_SESSION['sc_user_id'] : null
		);

		foreach($classVars as $key=>$val) :

			if(in_array($key, $this->ignoreKeys)) continue;

			if(in_array($key, $this->objectColumns)) $tableData[$key] = $val;

		endforeach;

		return $this->dbInsert($tableData, "Object");
	}

	public function updateObject() {

		$tableData = array();
		$classVars = get_object_vars($this);
		
		$tableData = array(
			"modified_date" => date('Y-m-d H:i:s'),
			"modified_by"	=> (isset($_SESSION['sc_user_id'])) ? $_SESSION['sc_user_id'] : null
		);

		foreach($classVars as $key=>$val) :

			if(in_array($key, $this->ignoreKeys)) continue;

			if(in_array($key, $this->objectColumns)) $tableData[$key] = $val;

		endforeach;

		return $this->dbUpdate($tableData, array("id"=>$this->id), "Object");	
	}

	#-----------------------------------------------------------------------------
	# Main function for storing data, always use this one when developing.

	public function store() {

		$classVars = get_object_vars($this);

		$tableData = array();

		foreach($classVars as $key=>$val) :

			if(in_array($key, $this->ignoreKeys)) continue;

			if(in_array($key, $this->tableColumns)) $tableData[$key] = $val;

		endforeach;

		if(isset($this->id) && $this->id!="") :
			
			// ID is set, let's update the rows.
			$this->updateObject();
			$this->dbUpdate($tableData, array("id"=>$this->id), null);
		
		else :
			
			// Create the object and store the ID in the data sent to store the table-data.
			$tableData["id"] = $this->createObject();
			
			// Store the table-data and return ID
			return $this->dbInsert($tableData);
		
		endif;
	}
	#-----------------------------------------------------------------------------

	public function load() {
		if(isset($this->id) && $this->id!="") :

			$this->where('id', $this->id);
			$result = $this->dbGetRow();
			
			foreach($result as $key=>$val) :
				$this->$key = $val;
			endforeach;

			return true;

		else :
			$this->throwError("Cannot load without an ID.");
		endif;
	}

	public function loadAll() {
		return $this->dbGetObjectArray("");
	}

	public function delete() {
		if(isset($this->id) && $this->id!="") :

			$sql = "delete from " . TABLE_PREFIX . "Object where `id` = $this->id"; 				$this->db->query($sql);
			$sql = "delete from " . TABLE_PREFIX . $this->tableName . " where `id` = $this->id";	$this->db->query($sql);

			return true;

		else :
			$this->throwError("Cannot delete without an ID.");
		endif;	
	}

}


?>