<?php
/**
 * Usage :
 * `php changelog.php 2.7.4`
 *
 * As argument is passed the git ref (tag name or sha1) we want to use as reference
 *
 * Outputs :
 *
 * 1. List of bugs as CSV :
 * bug ref;link
 * Example :
 * <code>
 * Bug_ref;Bug_URL;sha1
 * 1234;https://support.combodo.com/pages/UI.php?operation=details&class=Bug&id=1234;949b213f9|b1ca1f263|a1271da74
 * </code>
 *
 * 2. List of commits sha1/message without bug ref
 * Example :
 * <code>
 * sha1;subject
 * a6aa183e2;:bookmark: Prepare 2.7.5
 * </code>
 */


if (count($argv) === 1) {
	echo '⚠ You must pass the base tag/sha1 as parameter';
	exit(1);
}
$sBaseReference = $argv[1];


//--- Get log
$sGitLogCommand = 'git log --decorate --pretty="%h;%s" --date-order --no-merges '.$sBaseReference.'..HEAD';
$sGitLogRaw = shell_exec($sGitLogCommand);


//--- Analyze log
$aGitLogLines = preg_split('/\n/', trim($sGitLogRaw));;
$aLogLinesWithBugRef = [];
$aLogLineNoBug = [];
foreach ($aGitLogLines as $sLogLine) {
	$sBugRef = preg_match('/[nN]°(\d{3,4})/', $sLogLine, $aLineBugRef);
	if (($sBugRef === false) || empty($aLineBugRef)) {
		$aLogLineNoBug[] = $sLogLine;
		continue;
	}

	$iBugId = $aLineBugRef[1];
	$sSha = substr($sLogLine, 0, 9);

	if (array_key_exists($iBugId, $aLogLinesWithBugRef)) {
		$aBugShaRefs = $aLogLinesWithBugRef[$iBugId];
		$aBugShaRefs[] = $sSha;
		$aLogLinesWithBugRef[$iBugId] = $aBugShaRefs;
	} else {
		$aLogLinesWithBugRef[$iBugId] = [$sSha];
	}
}
$aBugsList = array_keys($aLogLinesWithBugRef);
sort($aBugsList, SORT_NUMERIC);


//-- Output results
echo "# Bugs included\n";
echo "Bug_ref;Bug_URL;sha1\n";
foreach ($aBugsList as $sBugRef) {
	$sShaRefs = implode('|', $aLogLinesWithBugRef[$sBugRef]);
	echo "{$sBugRef};https://support.combodo.com/pages/UI.php?operation=details&class=Bug&id={$sBugRef};$sShaRefs\n";
}
echo "\n";
echo "# Logs line without bug referenced\n";
echo "sha1;subject\n";
foreach ($aLogLineNoBug as $sLogLine) {
	echo "$sLogLine\n";
}