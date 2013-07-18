<?php

class FourohFour extends SilverCube {
	
	public function index() {
		$this->page->set_title("404");
		$this->page->css('404.css');
		$this->load_view("404", null, true);
	}

}