<?php

class View {

	public $title;

	public function render($view, $data = null, $standalone = false) {
		
		if($data && is_array($data)) extract($data);
		
		if(!$standalone) require "views/header.php";
		
		require "views/$view.php";
		
		if(!$standalone) require "views/footer.php";
	
	}

	public function setTitle($title) {
	
		$this->title = $title;
	
	}
}