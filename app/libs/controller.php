<?php

class Controller {

	public $view;
	//public $page;

	public function __construct() {
		global $coreview;
		$this->view = $coreview;
	}

}