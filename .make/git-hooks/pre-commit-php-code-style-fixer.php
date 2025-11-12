<?php

/**
 * Git pre-commit hook to run PHP-CS-Fixer on staged PHP files which are not in third-party libs.
 */

$sRootFolderAbsPath = dirname(dirname(__DIR__));
$sPhpCsFixerBinaryAbsPath = $sRootFolderAbsPath.'/tests/php-code-style/vendor/bin/php-cs-fixer';
$sPhpCsFixerConfigFileAbsPath = $sRootFolderAbsPath.'/tests/php-code-style/.php-cs-fixer.dist.php';

// Retrieve list of staged (`--cached`) files (ACMR: Added, Changed, Modified, Renamed only)
$sStagedFilesCmd = 'git diff --cached --name-only --diff-filter=ACMR';
exec($sStagedFilesCmd, $aStagedFiles);

// Ignore non-PHP files and third-party folders
$aStagedFiles = array_filter($aStagedFiles, function ($sStagedFile) {
	// Ignore non-PHP files
	if (false === preg_match('/\.php$/i', $sStagedFile)) {
		return false;
	}
	// Ignore files in third-party folders
	$sNormalizedStagedFile = str_replace('\\', '/', $sStagedFile);
	if (
		strpos($sNormalizedStagedFile, 'lib/') !== false             // Our root Composer libs folder
		|| strpos($sNormalizedStagedFile, 'vendor/') !== false       // Any sub-folder Composer libs folder
		|| strpos($sNormalizedStagedFile, 'node_modules/') !== false // OUr root NPM libs folder
	) {
		return false;
	}

	return true;
});

$iStagedFilesCount = count($aStagedFiles);
if ($iStagedFilesCount === 0) {
	echo "No file to check for PHP code style.\n";
	exit(0);
}
echo "{$iStagedFilesCount} file(s) to check for PHP code style.\n";

// Prepare batch of files to process (limit command line length as it could fail on some systems)
$aChunks = array_chunk(array_values($aStagedFiles), 50);
$iExitCode = 0;

// Execute chunks
$iNbChunks = count($aChunks);
foreach ($aChunks as $iIdx => $aChunk) {
	$iChunkNumber = $iIdx + 1;

	$sChunkFilesAsArgs = implode(' ', array_map('escapeshellarg', $aChunk));
	$sPhpCsFixerCmd = escapeshellcmd(PHP_BINARY)
		.' '.escapeshellarg($sPhpCsFixerBinaryAbsPath)
		.' fix --using-cache=no --config='.escapeshellarg($sPhpCsFixerConfigFileAbsPath)
		.' --verbose '.$sChunkFilesAsArgs;

	echo "Executing chunk {$iChunkNumber}/{$iNbChunks} : {$sPhpCsFixerCmd}\n\n";
	passthru($sPhpCsFixerCmd, $iExitCode);
	if ($iExitCode !== 0) {
		echo "Failed to fix chunk #{$iChunkNumber} Aborting.\n";
		exit($iExitCode);
	}
}

// Find which files have been fixed and re-stage them
$sFixedFilesCmd = 'git diff --name-only --diff-filter=M';
exec($sFixedFilesCmd, $aFixedFiles);
$aFixedFilesToRestage = array_intersect($aFixedFiles, $aStagedFiles);

// Re-stage fixed files to include them in the commit
if (count($aFixedFilesToRestage) === 0) {
	echo "No file needed PHP code style fixing, it was already ok.\n";
	exit(0);
}

echo "Re-staging fixed files:\n";
foreach ($aFixedFilesToRestage as $sFixedFileToRestage) {
	$sGitAddCmd = 'git add '.escapeshellarg($sFixedFileToRestage);

	echo " - {$sFixedFileToRestage}\n";
	passthru($sGitAddCmd, $iRetCode);
	if ($iRetCode !== 0) {
		echo "  Failed to re-stage fixed file '{$sFixedFileToRestage}'. Continuing anyway.\n";
	}
}

echo "All done, file(s) PHP code style fixed and added to commit.\n";
exit($iExitCode);
