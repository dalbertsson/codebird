<div class="content">
	<h2>Welcome to da Dashboard!</h2>

	<p>We have a few users here on this thing:</p>
	<? 
	foreach ($users as $user) {
		echo '<div style="padding: 8px; background: #eee; margin-bottom: 1px">' . $user->first_name . '</div>';
	}

	$this->paging->render();

	?>
</div>