<?php

/*******************************************************************************
 * Tool to automate events list retrieval before release
 ******************************************************************************/



require_once (__DIR__.'/../../approot.inc.php');
require_once APPROOT.'application/startup.inc.php';

$aList = \Combodo\iTop\Service\Event::GetEventNameList();

var_dump($aList);