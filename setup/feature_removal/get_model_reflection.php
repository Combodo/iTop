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
	MetaModel::Startup($sConfFile, false /* $bModelOnly */, false /* $bAllowCache */, false /* $bTraceSourceFiles */, $sEnv);
} catch (\Throwable $e) {
	echo $e->getMessage();
	echo $e->getTraceAsString();
	\SetupLog::Error(
		"Cannot read model from provided environment",
		null,
		[
			'env'   => $sEnv,
			'error' => $e->getMessage(),
			'stack' => $e->getTraceAsString(),
		]
	);
	echo "Cannot read model from provided environment";
	exit(1);
}

$aClasses = MetaModel::GetClasses();

echo json_encode($aClasses);
