<?php

class url {
	public $http_host;
	public $url;
	public $segments = array();

	public function __construct() {

		$this->http_host 	= $_SERVER["HTTP_HOST"];
		
		$this->url 			= $_SERVER["REQUEST_URI"];
		$this->segments 	= null;

		// Remove the base URL from the request URI. We don't want it for parsing what controllers to load.
		$this->url = str_replace(BASE_URL, "", $this->url);

		// Remove trailing slashes.
		$this->url = rtrim($this->url, "/");

		// Check if we've got an URL to load, otherwise load START_PAGE.
		$this->url = ($this->url) ? $this->url : START_PAGE;

		// Load it up.
		$this->segments = array_filter(explode("/", $this->url));
		$this->segments = array_values($this->segments);
	}

	public function get_segment($segment) {
		return (isset($this->segments[$segment])) ? $this->segments[$segment] : false;
	}

}

?>