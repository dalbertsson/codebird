<?php
require_once('config.php');
require_once(CLASS_URL . 'url.php');
require_once(CLASS_URL . 'sqlarray.php');
require_once(CLASS_URL . 'table.php');
require_once(CLASS_URL . 'user.php');

if(count($_POST)>0) {
	
	$user = new user();
	$user->login_name 		= $_POST["login_name"];
	$user->login_password 	= $_POST["login_password"];

	if($user->login()) {
		echo 'Welcome to SilverCube, ' . $user->first_name . '.';
	} else {
		echo 'Login failed';
	}
}

echo url::get();

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>SilverCube</title>

<style type="text/css">
	body { font-family: Helvetica, Arial, sans-serif; line-height: 36px; font-size: 14px; font-weight: normal; color: #ccc; }
	h1 { font-family: Georgia; color: #666; font-weight: normal; font-style: italic; margin: 8px 0 0 0;}
	em { display: inline; color: #ff9900;}
	.input_wrap { padding: 15px; border-bottom: 1px solid #f1f1f1;}

	.input_wrap label { margin: 0; padding: 0 20px 0 0 ; float: left; width: 70px; text-align: right; }
	input { color: #666; font-size: 18px; background: #fff; padding: 6px; border: 1px solid #eee; border-radius: 3px; width: 250px; box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);}
</style>
</head>
<body style="background: #fff; padding: 100px 0 0 0 ;">
<!--
	<form method="post" action="">
	<div class="wrap" style="width: 400px; margin: 0 auto; box-sizing: border-box; border-radius: 4px; background: #fcfcfc; box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.1); border: 1px solid #ddd;">
		<div style="padding: 10px 20px 0 20px;">
			<h1>Welcome to <em>TypeFire</em></h1>
		</div>
		<div class="input_wrap">
			<label for="login_name">Username</label>
			<input type="text" name="login_name" id="login_name">
		</div>
		<div class="input_wrap">
			<label for="login_name">Password</label>
			<input type="password" name="login_password" id="login_password">
		</div>
		<div class="input_wrap" style="background: #f5f5f5; border-top: 1px solid #FFF;">
			<button type="submit">Log in</button>
		</div>
	</div>
	</form>
-->
</body>
</html>