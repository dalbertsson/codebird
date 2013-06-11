<?php

class FourohFour extends Controller {
	
	public function index() {
		$this->view->setTitle("404");
		$this->view->render("404", null, true);
	}

}