<?php
class Dashboard extends SilverCube {

	public function index() {

		global $_GLOBALS;

		$this->page->set_title('Dashboard');	

		$data["nav"]		= array("Dashboard", "Posts", "Pages", "Users");

		$data["content"] 	= array("header" => "Welcome");
		$data["page"] 		= "dashboard";  

		$this->session->set('sc_user_id', 1);	

		$u = new User;
		$u->filter("like", "email", "b");
		$u->filter("limit", 0, 2);
		$users = $u->loadAll();

		dump($users);
		dump($_GLOBALS);

		$this->load_view('template', $data);
	}

}