<?php
/**
 *  @copyright   Copyright (C) 2010-2019 Combodo SARL
 *	@license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT."/setup/setuppage.class.inc.php");

$oP = new SetupPage("Maintenance");
$oP->p("<h2>Application is currently in maintenance mode</h2>");
$oP->output();
