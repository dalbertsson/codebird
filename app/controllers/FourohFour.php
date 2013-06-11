<?php

class FourohFour extends Controller {
	
	public function index() {
		$this->view->title("404");
		$this->view->render("404", null, true);
	}

}