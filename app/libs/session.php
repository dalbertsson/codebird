<?php

class Session {
	public static function set($key, $val) {
		$_SESSION[$key] = $val;
	}

	public static function get($key) {
		return $_SESSION[$key];
	}
}