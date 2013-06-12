<?php

class View {

	public $active_page;
	public $title;
	public $css = array("default.css");

	public function __construct() {
		$this->view = $this;
	}

	public function render($view, $data = null, $extract = false) {

		if($data and is_array($data) and $extract) extract($data);
		
		include "views/$view.php";
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