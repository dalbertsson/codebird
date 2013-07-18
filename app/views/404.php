<!DOCTYPE html>
<html dir="ltr" lang="sv-SE">
<head>
	
	<title>SilverCube | <?=$this->page->title?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8" />
	<?=$this->page->print_css()?>
</head>

<body class="fourohfour">
<div style="float: left; width: 500px;">
	<img src="/<?=BASE_URL?>/files/images/404.png">
</div>
<div style="float: left; width: 550px;">
	<h1 class="animated tada">404!</h1>
	<h2 class="swing animated">We totally couldn't find that page!</h2>
	<h3 class="swing animated">You broke it! <br />Why would you break it? :(</h3>
	<a href="/<?=BASE_URL?>">Let's unbreak it! :)</a>
</div>
<div style="clear: both;"></div>
</body>
</html>