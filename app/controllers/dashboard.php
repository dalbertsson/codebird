<?php
class Dashboard extends SilverCube {

	public function index() {

		$this->page->set_title('Dashboard');	

		$data["nav"]		= array("Dashboard", "Posts", "Pages", "Users");

		$data["content"] 	= array("header" => "Welcome");
		$data["page"] 		= "dashboard";  

		$this->session->set('sc_user_id', 1);

		$this->load_view('template', $data);
	}

}