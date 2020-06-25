<?php
	require_once __DIR__ . '/../approot.inc.php';
	require_once __DIR__ . '/../application/webpage.class.inc.php';

	$oPage = new WebPage('FMD');

	$oPage->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'lib/necolas/normalize.css/normalize.css');

	$sCSSRelPath = utils::GetCSSFromSASS(
		'css/backoffice/main.scss',
		array(
			APPROOT.'css/backoffice/',
		)
	);
	$oPage->add_saas($sCSSRelPath);


	$oPage->add(<<<HTML
<div id="ibo-nav-menu" class="ibo-nav-menu">
	<div class="ibo-nav-menu--head">
		<div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div>
	</div>
	<div class="ibo-nav-menu--body"></div>
</div>
<div id="ibo-page-container">
	<div id="ibo-top-bar" class="ibo-top-bar">
		<span>item</span><span>item</span><span>item</span><span>item</span><span>item</span><span>item</span><span>item</span><span>item</span>
	</div>
	<div id="ibo-page-content">
		<div>item</div><div>itemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitemitem</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div><div>item</div>
	</div>
</div>
HTML
	);

	$oPage->output();
