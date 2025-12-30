<?php

/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DBTools\Service;

use CMDBSource;
use DBObjectSearch;
use DBObjectSet;
use IssueLog;

class DBToolsUtils
{
	private static bool $bAnalyzed = false;

	private static function AnalyzeTables()
	{
		if (self::$bAnalyzed) {
			return;
		}

		$oResult = CMDBSource::Query('SHOW TABLES;');
		while ($aRow = $oResult->fetch_array()) {
			$sTable = $aRow['0'];
			CMDBSource::Query("ANALYZE TABLE `$sTable`; ");
		}
		self::$bAnalyzed = true;
	}

	/**
	 * @return int
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	final public static function GetDatabaseSize()
	{
		self::AnalyzeTables();
		$sSchema = CMDBSource::DBName();

		$sReq = <<<EOF
SELECT sum(data_length+index_length) AS sz
FROM information_schema.tables 
WHERE table_schema = '$sSchema';
EOF;

		$oResult = CMDBSource::Query($sReq);
		if ($oResult !== false) {
			$aRow = $oResult->fetch_assoc();
			$sSize = $aRow['sz'];
			return (int)$sSize;
		}

		return 0;
	}
	/**
	 * @return int
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	final public static function GetDBDataSize()
	{
		self::AnalyzeTables();
		$sSchema = CMDBSource::DBName();

		$sReq = <<<EOF
SELECT sum(data_length) AS sz
FROM information_schema.tables 
WHERE table_schema = '$sSchema';
EOF;

		$oResult = CMDBSource::Query($sReq);
		if ($oResult !== false) {
			$aRow = $oResult->fetch_assoc();
			$sSize = $aRow['sz'];
			return (int)$sSize;
		}

		return 0;
	}
	/**
	 * @return int
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	final public static function GetDBIndexSize()
	{
		self::AnalyzeTables();
		$sSchema = CMDBSource::DBName();

		$sReq = <<<EOF
SELECT sum(index_length) AS sz
FROM information_schema.tables 
WHERE table_schema = '$sSchema';
EOF;

		$oResult = CMDBSource::Query($sReq);
		if ($oResult !== false) {
			$aRow = $oResult->fetch_assoc();
			$sSize = $aRow['sz'];
			return (int)$sSize;
		}

		return 0;
	}

	final public static function GetDatamodelVersion()
	{
		$oFilter = DBObjectSearch::FromOQL('SELECT ModuleInstallation AS mi WHERE mi.name="datamodel"');
		$oSet = new DBObjectSet($oFilter, ['installed' => false]); // Most recent first
		$oSet->SetLimit(1);
		/** @var \DBObject $oModuleInstallation */
		$oModuleInstallation = $oSet->Fetch();
		return $oModuleInstallation->Get('version');
	}

	public static function GetPreviousInstallations($iLimitCount = 10)
	{
		$oFilter = DBObjectSearch::FromOQL('SELECT ModuleInstallation AS mi WHERE mi.parent_id=0 AND mi.name!="datamodel"');
		$oSet = new DBObjectSet($oFilter, ['installed' => false]); // Most recent first
		$oSet->SetLimit($iLimitCount);
		$aRawValues = $oSet->ToArrayOfValues();
		$aValues = [];
		foreach ($aRawValues as $aRawValue) {
			$aValue = [];
			foreach ($aRawValue as $sAliasAttCode => $sValue) {
				// remove 'mi.' from AttCode
				$sAttCode = substr($sAliasAttCode, 3);
				$aValue[$sAttCode] = $sValue;
			}

			$aValues[] = $aValue;
		}
		return $aValues;
	}

	/**
	 * Return previous module installation. offset is applied on parent_id.
	 * @param $iOffset
	 * @return array
	 */
	public static function GetPreviousModuleInstallationsByOffset(int $iOffset = 0): array
	{
		$oFilter = DBObjectSearch::FromOQL('SELECT ModuleInstallation AS mi WHERE mi.parent_id=0 AND mi.name!="datamodel"');
		$oSet = new DBObjectSet($oFilter, ['installed' => false]); // Most recent first
		$oSet->SetLimit($iOffset + 1);

		$iParentId = 0;
		/** @var \DBObject $oModuleInstallation */
		while ($oModuleInstallation = $oSet->Fetch()) {
			if ($iOffset == 0) {
				$iParentId = $oModuleInstallation->Get('id');
				break;
			}
			$iOffset--;
		}

		if ($iParentId === 0) {
			IssueLog::Error("no ITOP_APPLICATION ModuleInstallation found", null, ['offset' => $iOffset]);
			throw new \Exception("no ITOP_APPLICATION ModuleInstallation found");
		}

		$oFilter = DBObjectSearch::FromOQL("SELECT ModuleInstallation AS mi WHERE mi.id=$iParentId OR mi.parent_id=$iParentId");
		$oSet = new DBObjectSet($oFilter); // Most recent first
		$aRawValues = $oSet->ToArrayOfValues();
		$aValues = [];
		foreach ($aRawValues as $aRawValue) {
			$aValue = [];
			foreach ($aRawValue as $sAliasAttCode => $sValue) {
				// remove 'mi.' from AttCode
				$sAttCode = substr($sAliasAttCode, 3);
				$aValue[$sAttCode] = $sValue;
			}

			$aValues[] = $aValue;
		}
		return $aValues;
	}

	public static function GetDBTablesInfo()
	{
		self::AnalyzeTables();
		$sSchema = CMDBSource::DBName();

		$sReq = <<<EOF
SELECT `table_name`,
	table_rows,
	data_length / 1024 / 1024 as data_length_mb, 
	index_length / 1024 / 1024 as index_length_mb, 
	(data_length + index_length) / 1024 / 1024 as total_length_mb
FROM information_schema.tables 
WHERE table_schema = '$sSchema'
AND table_type = 'BASE TABLE';
EOF;

		$oResult = CMDBSource::Query($sReq);
		if ($oResult !== false) {
			return $oResult->fetch_all(MYSQLI_ASSOC);
		}

		return [];
	}

	public static function GetDBVariables()
	{
		$sReq = 'SHOW variables';
		$oResult = CMDBSource::Query($sReq);
		if ($oResult !== false) {
			return $oResult->fetch_all(MYSQLI_ASSOC);
		}
		return [];
	}

	public static function GetDBStatus()
	{
		$sReq = 'SHOW status';
		$oResult = CMDBSource::Query($sReq);
		if ($oResult !== false) {
			return $oResult->fetch_all(MYSQLI_ASSOC);
		}
		return [];
	}
}
