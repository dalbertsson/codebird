<?php
if(!defined('BASE_URL')) die('No direct script access');

class Paging {

	public $current_page;
	public $item_count;
	public $items_per_page;

	public function render() {
		
		global $_GLOBALS;

		if(isset($_GLOBALS["pagination"]["page"])) 				$this->current_page 	= $_GLOBALS["pagination"]["page"];
		if(isset($_GLOBALS["pagination"]["count"])) 			$this->item_count  		= $_GLOBALS["pagination"]["count"];
		if(isset($_GLOBALS["pagination"]["items_per_page"])) 	$this->items_per_page  	= $_GLOBALS["pagination"]["items_per_page"];

		$pages = ceil($this->item_count / $this->items_per_page);

		echo '<div id="paging" class="paging"><ol>';
		for($page=1; $page<=$pages; $page++) {

			$class = ($this->current_page==$page) ? "active" : null;
			echo '<li><a class="' . $class . '" href="/' . BASE_URL . $_GLOBALS["url"]["current_page"] . '/page/' . $page . '">' . $page . '</a></li>';
		

		}
		echo '</ol></div>';

	}
}