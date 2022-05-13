<?php

use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContent;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

LoginWebPage::DoLogin(); // Check user rights and prompt if needed

$oLayout = new PageContent();
$oLayout->AddCSSClass('ibo-oauth-wizard--side-pane');
$oPage = new WebPage(Dict::S('UI:Schema:Title'));

$sJS = <<<JS
   window.addEventListener("message", function (event){
	   	   event.source.postMessage(window.location.href, event.origin);
			  window.close();
   }, false);
JS;

$oPage->add_script($sJS);


$oPage->output();
