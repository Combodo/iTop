<?php

require_once '../approot.inc.php';
require_once APPROOT.'setup/setuputilslight.class.php';


$aResult = array();
SetupUtilsLight::CheckPhpVersion($aResult);

var_dump($aResult);