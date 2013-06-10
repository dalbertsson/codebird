<?php

Class SilverCube {
	
	public $url;
	public $url_segments;

	public function init() {
		
		require_once LIBS . 'controller.php';

		$this->url 			= $_SERVER["REQUEST_URI"];
		$this->url_segments = null;

		// Remove the base URL from the request URI. We don't want it for parsing what controllers to load.
		$this->url = str_replace(BASE_URL, "", $this->url);

		// Remove trailing slashes.
		$this->url = rtrim($this->url, "/");

		// Check if we've got an URL to load, otherwise load START_PAGE.
		$load = ($this->url) ? $this->url : START_PAGE;

		// Load it up.
		$controller = new controller;
		$controller->load($load);

	}
	
}