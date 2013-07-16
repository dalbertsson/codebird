<?php
class User extends Object {

	public function __construct($user_id = null) {
		parent::__construct();
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
}
?>