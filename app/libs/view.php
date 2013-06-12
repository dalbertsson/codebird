<?php

class View {

	public $active_page;
	public $title;
	public $css = array("default.css");
	public $view;

	public function render($view, $data = null, $extract = false) {
		
		global $coreview;
		$this->view = $coreview;

		if($data and is_array($data) and $extract) extract($data);
		
		include "views/$view.php";
	}

	public function title($title) {
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