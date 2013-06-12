<?php $this->view->render('header') ?>

<div style="float: left; width: 20%; min-height: 400px; background: blue">
	<?php $this->view->render('menu', $data, true) ?>
</div>

<div style="float: left; width: 80%; min-height: 400px; background: white;">
	<?php $this->view->render($data["page"], $data, true) ?>
</div>

<?php $this->view->render('footer') ?>