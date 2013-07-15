<?php $this->load_view('header') ?>

<div style="float: left; width: 20%; min-height: 400px; background: blue">
	<?php $this->load_view('menu', $data, true) ?>
</div>

<div style="float: left; width: 80%; min-height: 400px; background: white;">
	<?php $this->load_view($data["page"], $data, true) ?>
</div>

<?php $this->load_view('footer') ?>