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

			$iRightId = $this->aIdByObjectName[$sName] ?? 0;
			if ($iRightId === 0) {
				$iRightId = $this->GivenObjectInDB($sChildClass, ['name' => $sName, $sExtKey => $iLeftId]);
				$this->aIdByClass[$sChildClass][] = $iRightId;
				$this->aIdByObjectName[$sName] = $iRightId;
			} else {
				// Update object
				$oObj = MetaModel::GetObject($sChildClass, $iRightId);
				$oObj->Set($sExtKey, $iLeftId);
				$oObj->DBUpdate();
			}
		}
	}

	/**
	 * format of object:
	 *  [create|update] CLASS (name = NAME, ...)
	 *
	 * @param string $sObjects
	 *
	 * @return void
	 */
	protected function GivenDFRObjectsInDB(string $sObjects)
	{
		$this->aIdByClass = [];
		$sObjects = explode("\n", $sObjects);
		foreach ($sObjects as $sLine) {
			$sLine = trim($sLine);
			if ($sLine === '') {
				continue;
			}
			$this->GivenDFRObjectLineInDB($sLine);
		}
	}

	protected function GivenDFRObjectLineInDB(string $sLine)
	{
		if (preg_match("/(?<verb>\w+)\s+(?<class>\w+)\s*\((?<att_list>[^)]+)\)/", $sLine, $aMatches) !== false) {
			$sVerb = $aMatches['verb'];
			$sClass = $aMatches['class'];
			$sAttList = $aMatches['att_list'];

			$aAttSet = explode(',', $sAttList);
			$aAttributes = [];
			foreach ($aAttSet as $sAtt) {
				[$sAttName, $sAttValue] = explode('=', $sAtt, 2);
				$sAttName = trim($sAttName);
				$sAttValue = trim($sAttValue);
				$aAttributes[$sAttName] = $sAttValue;
			}

			$sName = $aAttributes['name'] ?? '';
			// Transform names in external keys into ids
			foreach ($aAttributes as $sAttName => $sAttValue) {
				if ($sAttName !== 'name') {
					$aAttributes[$sAttName] = $this->aIdByObjectName[$sAttValue] ?? $sAttValue;
				}
			}

			switch ($sVerb) {
				case 'create':
					$sId = $this->GivenObjectInDB($sClass, $aAttributes);
					$this->aIdByClass[$sClass][] = $sId;
					$this->aIdByObjectName[$sName] = $sId;
					break;
				case 'update':
					$sId = $this->aIdByObjectName[$sName];
					$oObj = MetaModel::GetObject($sClass, $sId);
					foreach ($aAttributes as $sAttName => $sAttValue) {
						$oObj->Set($sAttName, $sAttValue);
					}
					$oObj->DBUpdate();
			}
		}
	}

}
