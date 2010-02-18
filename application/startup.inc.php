<?php
define('ITOP_VERSION', '0.9');
define('ITOP_REVISION', '$WCREV$');
define('ITOP_BUILD_DATE', '$WCNOW$');

require_once('../application/utils.inc.php');

MetaModel::Startup(ITOP_CONFIG_FILE);

?>
