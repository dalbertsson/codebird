<?php

class View {

	public $title;
	public $css = array("default.css");

	public function render($view, $data = null, $standalone = false) {
		
		if($data && is_array($data)) extract($data);
		
		if(!$standalone) require "views/header.php";
		
		require "views/$view.php";
		
		if(!$standalone) require "views/footer.php";
	
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