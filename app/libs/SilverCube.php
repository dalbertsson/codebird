<?php

if(!defined('BASE_URL')) die('No direct script access');

/* -----------------------------------------------------

TO DO:

Clean this mess up.
Lots of duplicate code.
Probably a better way to check the arguments (IN ONE PLACE).

----------------------------------------------------- */
require_once LIBS . 'page.php';
require_once LIBS . 'sqlarray.php';
require_once LIBS . 'Object.php';
require_once LIBS . 'user.php';
require_once LIBS . 'session.php';
require_once LIBS . 'url.php';

$_GLOBALS = array();

Class SilverCube {

	public $time_start;
	public $time_end;

	protected $output;

	#-----------------------------------------------------------------------------
	# Constructor sets our libraries as local variables.

	public function __construct() {
		$this->page 	= new Page;
		$this->url 		= new Url;
		$this->session 	= new Session;
	}
	#-----------------------------------------------------------------------------

	public function load_view($view, $data = null) {

		if($data and is_array($data)) extract($data);
		
		$view_to_load = "views/$view.php";
		
		if(file_exists($view_to_load)) {
			include $view_to_load;
		} else {
			echo "The view $view_to_load doesn't exist."; exit;
		}
		
	}

	public function init() {

		
		#-----------------------------------------------------------------------------
		# Start the timer. Tick tock tick tock.
		
		$this->time_start = microtime(true);
		#-----------------------------------------------------------------------------
		


		#-----------------------------------------------------------------------------
		# Initialise the session
		
		session_start();
		#-----------------------------------------------------------------------------



		#-----------------------------------------------------------------------------
		# Initialise output buffering. Handled by the output class later on
		
		ob_start();
		#-----------------------------------------------------------------------------


		if($this->url->segments) {

			// THIS NEEDS FIXING LATER.
			// Currently you can't have subfolders in the controllers-folder. That's bad.
			$class = $this->url->segments[0];

			if(file_exists("controllers/$class.php")) {

				require "controllers/$class.php";

				// Instantiate the controller.
				$controller = new $class;
				
				// Method is passed, or it could be an argument for the index-method. Let's check if the method exists.
				if(count($this->url->segments)>1) {
					
					if(method_exists($controller, $this->url->segments[1])) {
	
						// Any arguments passed?
						$args = null;
						if(count($this->url->segments)>2) {
							
							$args = $this->url->segments;
							unset($args[0]);
							unset($args[1]);
							$args = array_values($args);
						}

						if(is_array($args))
						{
							// We've got arguments passed, let's see if the method takes arguments.
							$argCheck = new ReflectionMethod($controller, $this->url->segments[1]);
							$num = $argCheck->getNumberOfParameters();

							// Arguments match, or are less, which is also fine. Call the method with the arguments.
							if(count($args)<=$num)
							{
								call_user_func_array( array($controller, $this->url->segments[1]), $args );
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
							$controller->$this->url->segments[1]();
						}

					} else { // Let's check if the index-method has any arguments.

						$args = null;
						if(count($this->url->segments)>1) {
							
							$args = $this->url->segments;
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

				// $this->url->segments only contains controller name. Run default method (index)
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
		
		$this->time_end = microtime(true);

		echo "\n<!-- ///////////////////////////////////////////////////////////////////////" . "\n";
		echo "SilverCube executed page in: " . ($this->time_end - $this->time_start)/60 . " seconds.";
		echo "\n//////////////////////////////////////////////////////////////////////// -->";
	}
	
}
