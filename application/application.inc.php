<?php
// Includes all the classes to have the application up and running
require_once('../application/applicationcontext.class.inc.php');
require_once('../application/usercontext.class.inc.php');
require_once('../application/cmdbabstract.class.inc.php');
require_once('../application/displayblock.class.inc.php');
require_once('../application/iotask.class.inc.php');
require_once('../application/audit.category.class.inc.php');
require_once('../application/audit.rule.class.inc.php');
//require_once('../application/menunode.class.inc.php');
require_once('../application/utils.inc.php');

class ApplicationException extends CoreException
{
}
?>
