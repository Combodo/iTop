<?php
/**
 * Usage: php run_class_by_class.php
 *
 * Execute the whole test suite (as declared in phpunit.xml.dist) one class at a time.
 * This is to ensure that test class are still independant from each other, after a rework of ItopTestCase, for instance.
 */
const PHP_EXE = 'php';
const ITOP_ROOT = __DIR__.'/../../..';

const ITOP_PHPUNIT = ITOP_ROOT.'/tests/php-unit-tests';
const PHPUNIT_COMMAND = PHP_EXE.' '.ITOP_PHPUNIT.'/vendor/phpunit/phpunit/phpunit';

function ListTests($sUnitaryTestsDir = '')
{
	$sConfigFile = ITOP_PHPUNIT."/phpunit.xml.dist";
	$sCommand = PHPUNIT_COMMAND." --configuration $sConfigFile --list-tests $sUnitaryTestsDir";
	exec($sCommand, $aOutput, $iResultCode);
	//passthru($sCommand, $iResultCode);
	if ($iResultCode != 0) { // or 1 in case of a failing test
		echo "Failed executing command: $sCommand\n";
		return [];
	}
	$aClasses = [];
	foreach ($aOutput as $iLine => $sLine) {
		// Example of formats to be filtered
		//- DatamodelsXmlFilesTest::testAllItopXmlFilesCovered
		//- Combodo\iTop\Test\UnitTest\Application\DashboardLayoutTest::testGetDashletCoordinates"OneColLayout-Cell0"
		//if (preg_match('@^- ([a-z]+\\\\)*([a-z]+::[a-z0-9]+)@i', $sLine, $aMatches)) {
		if (preg_match('@([a-z0-9]+)::test@i', $sLine, $aMatches)) {
			$sTestClass = $aMatches[1];
			$aClasses[$sTestClass] = $sTestClass;
		}
	}
	return array_keys($aClasses);
}

function RunTests($sFilterRegExp, $sUnitaryTestsDir = '', $bPassthru = false)
{
	$sRegExpShellArg = '"'.str_replace('"', '\\"', $sFilterRegExp).'"';
	$sConfigFile = ITOP_PHPUNIT."/phpunit.xml.dist";
	$sCommand = PHPUNIT_COMMAND." --configuration $sConfigFile --filter $sRegExpShellArg $sUnitaryTestsDir";
	///echo "executing <<<$sCommand>>>\n";
	if ($bPassthru) {
		passthru($sCommand, $iResultCode);
	}
	else {
		exec($sCommand, $aTrashedOutput, $iResultCode);
	}
	$bTestSuccess = ($iResultCode == 0); // or 1 in case of a failing test
	return $bTestSuccess;
}

$sUnitaryTestsDir = '';

$aTestClasses = ListTests($sUnitaryTestsDir);
echo "Found ".count($aTestClasses)." to execute: ".implode(", ", $aTestClasses)."\n";
echo "Testing...\n";
foreach ($aTestClasses as $sTestClass) {
	$fStarted = microtime(true);
	$bSuccess = RunTests($sTestClass);
	$sDuration = round(microtime(true) - $fStarted, 3);
	echo "$sTestClass: ".($bSuccess ? 'Ok' : "FAILURE")." [$sDuration s]\n";
}