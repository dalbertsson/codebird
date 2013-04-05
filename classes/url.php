<?php

class url {
	public $url;
	public $segments;

	public static function get() {

		$base = $_SERVER["REQUEST_URI"];

		return $base;

	}

}

?>