<?php
if(!defined('BASE_URL')) die('No direct script access');

class Page {

	public $active_page;
	public $title;
	public $css = array("default.css");

	public function __construct() {
		
	}

	public function set_title($title) {
		$this->title = $title;
	}

	public function css($sheet) {
		$this->css[] = $sheet;
	}
	public function print_css() {
		foreach ($this->css as $stylesheet)
			echo '<link rel="stylesheet" href="/' . BASE_URL . 'files/css/' . $stylesheet . '">' . "\n	";
	}
}