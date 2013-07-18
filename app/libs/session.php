<?php
if(!defined('BASE_URL')) die('No direct script access');

class Session {
	public function set($key, $val) {
		$_SESSION[$key] = $val;
	}

	public function get($key) {
		return $_SESSION[$key];
	}
}