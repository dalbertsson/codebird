<?php

class FourohFour extends SilverCube {
	
	public function index() {
		$this->page->set_title("404");
		$this->load_view("404", null, true);
	}

}