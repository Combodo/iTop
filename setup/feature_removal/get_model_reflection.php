<?php

require_once(dirname(__DIR__, 2).'/approot.inc.php');
require_once(APPROOT.'application/application.inc.php');
$sEnv = null;
if (isset($argv)) {
	foreach ($argv as $iArg => $sArg) {
		if (preg_match('/^--env=(.*)$/', $sArg, $aMatches)) {
			$sEnv = $aMatches[1];
		}
	}
}

if (is_null($sEnv)) {
	echo "No environment provided (--env) to read datamodel.";
	exit(1);
}

$sConfFile = utils::GetConfigFilePath($sEnv);

try {
	$oConfig = new Config($sConfFile);
	$oConfig->Set('expression_cache_enabled', false);
	MetaModel::Startup($oConfig, false /* $bModelOnly */, false /* $bAllowCache */, false /* $bTraceSourceFiles */, $sEnv);
} catch (\Throwable $e) {
	SetupLog::Enable(APPROOT.'log/setup.log');
	\SetupLog::Error(
		"Cannot read model from provided environment",
		null,
		[
			'env'   => $sEnv,
			'error' => $e->getMessage(),
			'stack' => $e->getTraceAsString(),
		]
	);

	//keep first echo to have proper setup feedbacks
	echo $e->getMessage();
	exit(1);
}

$aClasses = MetaModel::GetClasses();

echo json_encode($aClasses);
