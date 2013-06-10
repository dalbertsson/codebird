<?php

class url {
	public $http_host;
	public $url;
	public $segments = array();

	public function __construct() {

		$this->http_host 	= $_SERVER["HTTP_HOST"];
		$this->url 			= $_SERVER["REQUEST_URI"];
		$this->segments 	= array_filter(explode("/", $this->url));
	}

}

?>