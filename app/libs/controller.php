<?php

class Controller {

	public $view;
	public $page;

	public function __construct() {
		$this->view = new View();
	}

}