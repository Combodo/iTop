<?php

namespace Combodo\iTop\Test\UnitTest;

use PHPUnit\Framework\TestCase;
use GlobIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

/**
 * Performs code static analysis to detect patterns that will change the values of static data and therefor could affect other tests while running them in a single process
 *
 * @runClassInSeparateProcess
 * @preserveGlobalState disabled
 */
class detectStaticPollutionTest extends TestCase
{
	protected function FindMatches($sFile, $sFileContents, $sRegexp)
	{
		$aRes = [];
		foreach (explode("\n", $sFileContents) as $iLine => $sLine) {
			if (preg_match_all($sRegexp, $sLine, $aMatches, PREG_PATTERN_ORDER)) {
				$sLine = $iLine + 1;
				$aRes[] = "$sFile:$sLine";
			}
		}
		return $aRes;
	}

	/**
	 * @dataProvider PollutingPatterns
	 * @param $sPattern
	 *
	 * @return void
	 */
	function testDetectPolluters($sPattern, $sFix)
	{
		$sScannedDir = dirname(__FILE__).'/../unitary-tests';

		$aPolluters = [];
		$oDirectory = new RecursiveDirectoryIterator($sScannedDir);
		$Iterator = new RecursiveIteratorIterator($oDirectory);
		foreach (new RegexIterator($Iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH) as $aMatch) {
			$sFile = $aMatch[0];
			if(is_file($sFile)) {
				$sFileContents = file_get_contents($sFile);
				if (preg_match_all($sPattern, $sFileContents, $keys, PREG_PATTERN_ORDER)) {
					$aPolluters = array_merge($aPolluters, $this->FindMatches($sFile, $sFileContents, $sPattern));
				}
			}
		}
		$iPolluters = count($aPolluters);
		static::assertTrue($iPolluters === 0, "Found polluter(s) for pattern $sPattern, $sFix:\n".implode("\n", $aPolluters));

	}

	public function PollutingPatterns()
	{
		return [
			'ContextTags' => ['/ContextTag::AddContext/i', 'Use new ContextTag() instead'],
			'Dict::Add' => ['/Dict::Add/i', 'TODO: implement a facade into ItopDataTestCase'],
			'EventService::RegisterListener' => ['/EventService::RegisterListener/i', 'Use ItopDataTestCase::EventService_RegisterListener instead'],
		];
	}

}
