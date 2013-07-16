<?php

function stdDate() {
	return date("Y-m-d H:i:s");
}

function prettify($_) {
	$_ = preg_replace("/(\[.*\])/", '<span style="color:#e74c3c;">$1</span>', $_);
	$_ = preg_replace("/(\=>.*)/", '<span style="color:#2c3e50;">$1</span>', $_);
	echo $_;
}

function dump($_) {
	echo '<pre style="color: #222;display; block;">';
		ob_start();
		print_r($_);
		$dump = ob_get_clean();
		
		prettify($dump);
	echo '</pre>';
}

?>