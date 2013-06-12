<?php
class dashboard extends Controller {

	public function index() {
		
		$data["content"] 	= array("header" => "Welcome");
		$data["page"] 		= "dashboard";
		
		$this->view->title('Dashboard');
		
		$this->view->render('template', $data);
	}

}