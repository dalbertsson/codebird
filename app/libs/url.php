<?php
if(!defined('BASE_URL')) die('No direct script access');

class Url {
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
		if(!$this->url) header('location: ' . START_PAGE);

		// Load it up.
		$this->segments = array_filter(explode("/", $this->url));
		$this->segments = array_values($this->segments);
	}

	public function get_segment($segment) {
		return (isset($this->segments[$segment])) ? $this->segments[$segment] : false;
	}

	public function pagination() {

		global $_GLOBALS;
		
		if(count($this->segments) > 1 and is_numeric($this->segments[count($this->segments)-1])) {
			if($this->segments[count($this->segments)-2]=='page') {
				$_GLOBALS["pagination"]["page"] = array_pop($this->segments);
				array_pop($this->segments);
			}
		}
	}

}

?>