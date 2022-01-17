<?php
$aHooks = [
	'pre-commit.php',
];

$sAppRoot = dirname(__DIR__, 2);


foreach ($aHooks as $sSourceHookFileName) {
	echo "Processing for `{$sSourceHookFileName}`...\n";
	$sSourceHookPath = __DIR__.DIRECTORY_SEPARATOR.$sSourceHookFileName;

	$aPathParts = pathinfo($sSourceHookFileName);
	$sTargetHookPath = $sAppRoot.DIRECTORY_SEPARATOR.'.git'.DIRECTORY_SEPARATOR.'hooks'.DIRECTORY_SEPARATOR.$aPathParts['filename'];

	if (file_exists($sTargetHookPath) || is_link($sTargetHookPath)) {
		echo "Existing $sTargetHookPath ! Removing...";
		unlink($sTargetHookPath);
		echo "OK !\n";
	}

	echo "Creating symlink for hook in $sTargetHookPath...";
	symlink($sSourceHookPath, $sTargetHookPath);
	echo "OK !\n";
}

