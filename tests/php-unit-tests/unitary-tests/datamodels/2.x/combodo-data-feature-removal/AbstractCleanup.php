<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;

class AbstractCleanup extends ItopCustomDatamodelTestCase
{
	public function GetDatamodelDeltaAbsPath(): string
	{
		return __DIR__.'/data_cleanup_delta.xml';
	}

	protected array $aIdByClass;
	protected array $aIdByObjectName = [];

	protected function GivenDFRTreeInDB(string $sTree)
	{
		$this->aIdByClass = [];
		$aTree = explode("\n", $sTree);
		foreach ($aTree as $sLine) {
			if (trim($sLine) === '') {
				continue;
			}
			$this->GivenDFRTreeLineInDB($sLine);
		}
	}

	protected function GivenDFRTreeLineInDB(string $sLine)
	{
		[$sLeft, $sRight] = explode('<-', $sLine);
		$sLeft = trim($sLeft);

		$iLeftId = $this->aIdByObjectName[$sLeft] ?? 0;
		if ($iLeftId === 0) {
			[$sChildClass] = explode('_', $sLeft, 2);
			$iLeftId = $this->GivenObjectInDB($sChildClass, ['name' => $sLeft]);
			$this->aIdByClass[$sChildClass][] = $iLeftId;
			$this->aIdByObjectName[$sLeft] = $iLeftId;
		}

		$sRight = trim($sRight);
		if (preg_match("/(?<name>(?<class>[^_]+)_\d+)(\s+\((?<extkey>\w+)\))?/", $sRight, $aMatches) !== false) {
			$sName = $aMatches['name'];
			$sChildClass = $aMatches['class'];
			$sExtKey = $aMatches['extkey'] ?? 'extkey_id';

			$iRightId = $this->GivenObjectInDB($sChildClass, ['name' => $sName, $sExtKey => $iLeftId]);
			$this->aIdByClass[$sChildClass][] = $iRightId;
			$this->aIdByObjectName[$sRight] = $iRightId;
		}
	}
}
