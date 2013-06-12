<? $this->view->render('header') ?>

<div style="float: left; width: 20%; min-height: 400px; background: blue">
	<? $this->view->render('menu', $data, true) ?>
</div>

<div style="float: left; width: 80%; min-height: 400px; background: white;">
	<? $this->view->render($data["page"], $data, true) ?>
</div>

<? $this->view->render('footer') ?>