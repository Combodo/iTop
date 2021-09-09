#!/usr/bin/php
<?php
/**
 * Reject any commit containing .scss files, but no .css file !
 */

echo "Checking files staged...\n";
$sFilesToCommit = shell_exec('git diff --cached --name-only --diff-filter=ACMRT');
$aFilesToCommit = explode("\n", $sFilesToCommit);

$aScssFiles = GetFilesWithExtension('scss', $aFilesToCommit);
if (count($aScssFiles) === 0) {
    echo "No scss file : OK to go !\n";
    exit(0);
}

$aCssFiles = GetFilesWithExtension('css', $aFilesToCommit);
if (count($aCssFiles) === 0) {
    echo "There are SCSS files staged but no CSS file : REJECTING commit.\n";
    echo "You must push the compiled SCSS files by running the setup !\n";
    exit(1);
}

echo "We have SCSS but also CSS => OK to commit !\n";
exit(0);



function GetFilesWithExtension($sExtension, $aFiles) {
    return array_filter(
        $aFiles,
        function($item) use ($sExtension) {
            return (endsWith($item, '.'.$sExtension));
        }
    );
}

function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}

function exitWithMessage($sMessage, $iCode) {
    echo $sMessage;
    exit($iCode);
}