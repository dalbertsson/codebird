<?php
class User extends Object {

	public function __construct($user_id = null) {
		parent::__construct();
	}

	public function login() {
		
		$this->where('login_name', $this->login_name);
		$this->where('login_password', md5($this->login_password));
		$logincheck = $this->dbGetRow();

		if($logincheck) :

			foreach ($logincheck as $key => $val) :
				$this->$key = $val;
			endforeach;

			$this->prev_login_date = $this->last_login_date;

			$this->num_logins 		= (int) $this->num_logins + 1;
			$this->last_login_date	= stdDate();

			// Store updated number of logins and last login date
			$this->store();

			$_SESSION["sc_logged_in"] = true;
			$_SESSION["sc_user_id"] = $this->id;

			return true;
		endif;
	}
}
?>