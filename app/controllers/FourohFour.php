<?php

class FourohFour extends SilverCube {
	
	public function index() {
		$this->view->set_title("404");
		$this->view->render("404", null, true);
	}

}