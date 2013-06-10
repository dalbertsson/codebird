<?php

class dashboard extends controller {
	
	public function index() {
		echo 'We are at the dashboard index. <br />';
	}

	public function show($amount = 0, $offset = 0) {
		echo "We're in show <br />";
		echo "Args: " . $amount . ' / ' . $offset;
	}

}