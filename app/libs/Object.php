<?php

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

	private $ignoreKeys = array("id", "tableName", "tableColumns", "objectColumns", "db");
	#-----------------------------------------------------------------------------
	
	public 		$id;

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

	public function createObject($optData = null) {

		$reqData = array(
			"object_type" 	=> $this->tableName,
			"created_date" 	=> date('Y-m-d H:i:s'),
			"created_by"	=> (isset($_SESSION['sc_user_id'])) ? $_SESSION['sc_user_id'] : null
		);

		// If we have optional data, merge the two arrays.
		$data = (is_array($optData)) ? array_merge($reqData, $optData) : $reqData;
		
		return $this->dbInsert($data, "Object");
	}

	public function updateObject($optData = null) {
		$reqData = array(
			"updated_date" 	=> date('Y-m-d H:i:s'),
			"updated_by"	=> (isset($_SESSION['sc_user_id'])) ? $_SESSION['sc_user_id'] : null
		);

		// If we have optional data, merge the two arrays.
		$data = (is_array($optData)) ? array_merge($reqData, $optData) : $reqData;
		
		return $this->dbInsert($data, "Object");	
	}

	#-----------------------------------------------------------------------------
	# Main function for storing data, always use this one when developing.

	public function store($objData = null) {

		$classVars = get_object_vars($this);

		$tableData = array();

		foreach($classVars as $key=>$val) :

			if(in_array($key, $this->ignoreKeys)) continue;

			if(in_array($key, $this->tableColumns)) $tableData[$key] = $val;

		endforeach;

		if(isset($this->id) && $this->id!="") :
			
			// ID is set, let's update the row.
			$this->dbUpdate($tableData, array("id"=>$this->id));
		
		else :
			
			// Create the object and store the ID in the data sent to store the table-data.
			$tableData["id"] = $this->createObject($objData);
			
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

}


?>