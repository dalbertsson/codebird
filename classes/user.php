<?php
class user extends sqlArray {

	public $id;
	protected $tableName 	= "tUser";
	protected $tableColumns = array('login_name', 'login_password', 'first_name', 'last_name', 'email_address', 'num_logins', 'created_date', 'last_login_date', 'user_level');

	public function __construct($user_id = null) {
		parent::__construct();
	}

	public function create() {
		
		if(!$this->login_name) 		$this->throwError('Cannot create user without login name');
		if(!$this->login_password) 	$this->throwError('Cannot create user without login password');

		$arrData = array(
			"login_name"		=> $this->login_name,
			"login_password"	=> md5($this->login_password),		// TEMPORARY md5, replace with proper security class later.
			"first_name"		=> $this->first_name,
			"last_name"			=> $this->last_name,
			"email_address"		=> $this->email_address,
			"first_name"		=> $this->first_name,
			"user_level"		=> $this->user_level,
			"email_address" 	=> $this->email_address,
			"created_date"		=> stdDate()
		);

		if($this->dbInsert($arrData)) {
			echo 'inserted'; return true;
		}
	}

	public function login() {
		$logincheck = $this->dbGetRow(array(
			'login_name' 		=> $this->login_name,
			'login_password' 	=> md5($this->login_password)),
			'order by login_name asc');

		if($logincheck) :

			foreach ($logincheck as $key => $val) :
				$this->$key = $val;
			endforeach;

			$this->prev_login_date = $this->last_login_date;

			$this->updateLogins();

			$this->num_logins 		= (int) $this->num_logins + 1;
			$this->last_login_date	= stdDate();

			$_SESSION["typefire_logged_in"] = true;
			$_SESSION["typefire_user_id"] = $this->id;

			return true;
		endif;
	}

	public function updateLogins() {
		$vals = array(
				"num_logins"		=> (int) $this->num_logins+1,
				"last_login_date"	=> stdDate()
			);
		$where = array(
				"id" => $this->id
			);

		$this->dbUpdate($vals, $where);

	}

	public function getAllUsers() {
		$users = $this->dbGetObjectArray(array(), 'order by login_name asc');
		return $users;
	}
}
?>