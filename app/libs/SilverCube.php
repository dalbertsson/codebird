<?php
/* -----------------------------------------------------

TO DO:

Clean this mess up.
Lots of duplicate code.
Probably a better way to check the arguments (IN ONE PLACE).


----------------------------------------------------- */

Class SilverCube {

	public $url;
	public $url_segments;

	/* Contains an instansiated Controller Class. */
	public $controller;

	private $output;

	public function init() {
		
		require_once LIBS . 'controller.php';
		require_once LIBS . 'view.php';
		require_once LIBS . 'sqlarray.php';
		require_once LIBS . 'session.php';

		// Initialise a session.
		session_start();

		$this->url 			= $_SERVER["REQUEST_URI"];
		$this->url_segments = null;

		// Remove the base URL from the request URI. We don't want it for parsing what controllers to load.
		$this->url = str_replace(BASE_URL, "", $this->url);

		// Remove trailing slashes.
		$this->url = rtrim($this->url, "/");

		// Check if we've got an URL to load, otherwise load START_PAGE.
		$this->url = ($this->url) ? $this->url : START_PAGE;

		// Load it up.
		$boot = array_filter(explode("/", $this->url));
		$boot = array_values($boot);

		// Initialise Output Buffering
		ob_start();

		if($boot) {

			$class = $boot[0];

			if(file_exists("controllers/$class.php")) {

				require "controllers/$class.php";

				// Instansiate the controller.
				$controller = new $class;
				
				// Method is passed, or it could be an argument for the index-method. Let's check if the method exists.
				if(count($boot)>1) {
					
					if(method_exists($controller, $boot[1])) {
						
						// Any arguments passed?
						$args = null;
						if(count($boot)>2) {
							
							$args = $boot;
							unset($args[0]);
							unset($args[1]);
							$args = array_values($args);
						}

						if(is_array($args))
						{
							
							// We've got arguments passed in the boot, let's see if the method takes arguments.
							$argCheck = new ReflectionMethod($controller, $boot[1]);
							$num = $argCheck->getNumberOfParameters();

							// Arguments match, or are less, which is also fine. Call the method with the arguments.
							if(count($args)<=$num)
							{
								call_user_func_array( array($controller, $boot[1]), $args);
							}
							// Too many arguments, throw an error.
							else 
							{
								echo 'Too many arguments';
							}
							
						}
						else
						{
							// No arguments set, just run the method.
							$controller->$boot[1]();
						}

					} else { // Let's check if the index-method has any arguments.

						$args = null;
						if(count($boot)>1) {
							
							$args = $boot;
							unset($args[0]);
							$args = array_values($args);
						}

						$argCheck = new ReflectionMethod($controller, "index");
						$num = $argCheck->getNumberOfParameters();
						
						// Arguments match, or are less, which is also fine. Call the method with the arguments.
						if($num > 0 || count($args)<=$num)
						{
							call_user_func_array( array($controller, "index"), $args);
						}
						
						else 
						{
							require "controllers/FourohFour.php";
							$controller = new FourohFour;
							$controller->index();
						}
					
					}

				// $boot only contains controller name. Run default method (index)
				} else { 
					
					$controller->index();
				}
			
			// Controller file doesn't exist.
			}
			else
			{
				require "controllers/FourohFour.php";
				$controller = new FourohFour;
				$controller->index();
			}


		} else {
			
			echo 'Invalid controller load data.'; exit;
		
		}

		$this->output = ob_get_clean();
		$this->draw();

	}

	private function draw() {
		echo $this->output;
	}
	
}