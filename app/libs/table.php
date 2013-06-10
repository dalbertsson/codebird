<?php

class table extends sqlArray {
	
	protected 	$tableColumns = array();
	public 		$tableName = null;
	public 		$id;

	public function __construct() {
		
		parent::__construct();

		$this->tableName = (get_class($this)=="table") ? null : get_class($this);
		
		if($this->tableName) $this->setTableColumns();
	}

	public function setTableColumns() {
		if(isset($this->tableName) && $this->tableName!="") :

			$this->tableColumns = null;

			$q = "select * from " . TABLE_PREFIX . $this->tableName;
			$cols = $this->db->query($q);

			$fields = $cols->fetch_fields();
			
			foreach($fields as $key) :
				if($key->name=="id") continue;
				$this->tableColumns[] = $key->name;
			endforeach;

		endif;
	}

	public function setTable($table) {
		$this->tableName = $table;
		$this->setTableColumns();
	}

}


?>