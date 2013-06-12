<?php
class Dashboard extends Controller {

	public function index() {

		$data["nav"]		= array("Dashboard", "Posts", "Pages", "Users");

		$data["content"] 	= array("header" => "Welcome");
		$data["page"] 		= "dashboard";

		$this->view->title('Dashboard');
		$this->view->render('template', $data);
	}

}