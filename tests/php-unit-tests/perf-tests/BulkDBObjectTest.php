<?php

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class BulkDBObjectTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;

	protected function setUp(): void
	{
		parent::setUp();
	}

	public function testInsertAndReadPersonObjects()
	{
		echo "Part 1: Insert Persons\n";

		$sOrgId = $this->getTestOrgId();
		$idx = 1;
		$fStart = microtime(true);
		$fMaxExecutionTimeAllowed = 40.0;
		$iInitialPeak = 0;
		$sInitialPeak = '';
		$fStartLoop = $fStart;
		for ($i = 0; $i < 3000; $i++) {
			$oPerson = $this->CreateObject(Person::class, ['org_id' => $sOrgId, 'name' => "Person_$i", 'first_name' => 'John']);
			if (0 == ($idx % 100)) {
				$fDuration = microtime(true) - $fStartLoop;
				$iMemoryPeakUsage = memory_get_peak_usage();
				if ($iInitialPeak === 0) {
					$iInitialPeak = $iMemoryPeakUsage;
					$sInitialPeak = \utils::BytesToFriendlyFormat($iInitialPeak, 4);
				}

				$sCurrPeak = \utils::BytesToFriendlyFormat($iMemoryPeakUsage, 4);
				echo "$idx ".sprintf('%.1f ms', $fDuration * 1000)." - Peak Memory Usage: $sCurrPeak\n";
				$this->assertTrue($iMemoryPeakUsage === $iInitialPeak, "Peak memory changed from $sInitialPeak to $sCurrPeak after $idx insert loops");				$fStartLoop = microtime(true);

				$fTotalDuration = microtime(true) - $fStart;
				$sTotalDuration = sprintf('%.3f s', $fTotalDuration);
				$this->assertTrue($fTotalDuration < $fMaxExecutionTimeAllowed, "execution time $sTotalDuration should be < $fMaxExecutionTimeAllowed ($idx insert loops)");
			}
			$idx++;
		}

		$fTotalDuration = microtime(true) - $fStart;
		$sTotalDuration = sprintf('%.3f s', $fTotalDuration);
		echo "Total duration: $sTotalDuration\n\n";
		$this->assertTrue($fTotalDuration < $fMaxExecutionTimeAllowed, "Total execution time $sTotalDuration should be < $fMaxExecutionTimeAllowed");

		//////////////////////
		// Part 2 Fetch all the created persons
		echo "Part 1: Fetch Persons\n";

		$oSearch = DBSearch::FromOQL('SELECT Person WHERE org_id=:org_id');
		$oSet = new DBObjectSet($oSearch, [], ['org_id' => $sOrgId]);
		$idx = 1;
		$iInitialPeak = 0;
		$sInitialPeak = '';
		$fMaxExecutionTimeAllowed = 0.5;
		$fStart = microtime(true);
		$fStartLoop = $fStart;
		while ($oContact = $oSet->Fetch()) {
			if (0 == ($idx % 100)) {
				$fDuration = microtime(true) - $fStartLoop;
				$iMemoryPeakUsage = memory_get_peak_usage();
				if ($iInitialPeak === 0) {
					$iInitialPeak = $iMemoryPeakUsage;
					$sInitialPeak = \utils::BytesToFriendlyFormat($iInitialPeak, 4);
				}

				$sCurrPeak = \utils::BytesToFriendlyFormat($iMemoryPeakUsage, 4);
				echo "$idx ".sprintf('%.1f ms', $fDuration * 1000)." - Peak Memory Usage: $sCurrPeak\n";
				$this->assertTrue($iMemoryPeakUsage === $iInitialPeak, "Peak memory changed from $sInitialPeak to $sCurrPeak after $idx fetch loops");
				$fStartLoop = microtime(true);

				$fTotalDuration = microtime(true) - $fStart;
				$sTotalDuration = sprintf('%.3f s', $fTotalDuration);
				$this->assertTrue($fTotalDuration < $fMaxExecutionTimeAllowed, "Total execution time $sTotalDuration should be < $fMaxExecutionTimeAllowed ($idx fetch loops)");
			}
			$idx++;
		}
		$fTotalDuration = microtime(true) - $fStart;
		$sTotalDuration = sprintf('%.3f s', $fTotalDuration);
		echo "Total duration: $sTotalDuration\n\n";
		$this->assertTrue($fTotalDuration < $fMaxExecutionTimeAllowed, "Total execution time $sTotalDuration should be < $fMaxExecutionTimeAllowed");
	}
}