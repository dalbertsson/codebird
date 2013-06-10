<?php

class controller {

	public $controller;

	public function __construct() {
		
	}

	public function load($url) {
		$url = array_filter(explode("/", $url));
		$url = array_values($url);

		if($url) {

			$class 	= $url[0];
			$method = (count($url)>1) ? $url[1] : null;
			$args 	= null;

			// Any arguments passed?
			if(count($url)>2) {
				
				$args = $url;
				unset($args[0]);
				unset($args[1]);
				$args = array_values($args);
			}

			if(file_exists("controllers/$class.php")) {

				require "controllers/$class.php";

				// Instansiate the controller.
				$this->controller = new $class;
				
				// We've got a specific method to load.
				if($method) {
					if(method_exists($this->controller, $method)) {
						
						if(is_array($args))
						{
							call_user_func_array( array($this->controller, $method), $args);
						}
						else
						{
							$this->controller->$method();
						}

					} else {
						

						echo "Whoah dude, that page like totally doesn't exist."; die;


					}

				// Run default method (index)
				} else { 
					
					$this->controller->index();
				}
			} else {
				echo "Whoah dude, that page like totally doesn't exist."; die;
			}


		} else {
			
			echo 'Invalid controller load data.'; exit;
		
		}

	}

}