<?php
class dashboard extends Controller {

	public function index() {
		
		$data["users"] = array(
			0 => array(
				"login" 	=> "daniel",
				"password" 	=> "rövhatt"
			),
			1 => array(
				"login" 	=> "manekiel",
				"password" 	=> "skansenkrull"
			)
		);

		$this->view->title("Dashboard");
		$this->view->css("extra.css");
		$this->view->render('dashboard', $data);
	}

}