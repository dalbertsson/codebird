<?php
class Dashboard extends SilverCube {

	public function index() {

		$this->page->set_title('Dashboard');	

		$data["nav"]		= array("Dashboard", "Posts", "Pages", "Users");

		$data["content"] 	= array("header" => "Welcome");
		$data["page"] 		= "dashboard";  

		$this->session->set('sc_user_id', 1);

		$u = new User;
		$u->login_name = "strain";
		$u->login_password = "röv";
		$u->first_name = "Daniel";
		$u->last_name = "Albertsson";
		$u->email = "daniel@mortzen.se";
		$u->level = 10;

		$u->store();

		$this->load_view('template', $data);
	}

}