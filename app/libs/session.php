<?php

class Session {
	public function set($key, $val) {
		$_SESSION[$key] = $val;
	}

	public function get($key) {
		return $_SESSION[$key];
	}
}