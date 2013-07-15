<?
error_reporting(E_ALL);

class view {

	public $output;

	public function render() {
		echo $this->output;
	}

}


class controller {

	private static	$instance;
	public 			$view;

	public function __construct() {

		if(!$this->view) {
			$this->view = new view;
			self::$instance =& $this;	
		}
		
		return self::$instance;

	}

	public static function getInstance() {

		if(!self::$instance) self::$instance = new controller;

		return self::$instance;

	}

}

class index extends controller {



}
controller::getInstance();

$page = new index;
$page2 = new index;

var_dump($page);
echo '<br>';
var_dump($page2);

?>