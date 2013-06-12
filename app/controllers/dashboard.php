<?php
class Dashboard extends SilverCube {

	public function index() {

		$data["nav"]		= array("Dashboard", "Posts", "Pages", "Users");

		$data["content"] 	= array("header" => "Welcome");
		$data["page"] 		= "dashboard";

		$this->view->set_title('Dashboard');
		$this->view->render('template', $data);
	}

}