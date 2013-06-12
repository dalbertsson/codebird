<? $this->view->render('header') ?>

<div style="float: left; width: 20%; min-height: 400px; background: blue">
	<? $this->view->render('menu') ?>
</div>

<div style="float: left; width: 80%; min-height: 400px; background: white;">
	<? $this->view->render($page) ?>
</div>

<? $this->view->render('footer') ?>