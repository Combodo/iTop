<?php

namespace Combodo\iTop\DataFeatureRemoval\Service;

use DataFeatureRemovalBackgroundOperation;
use DBObjectSet;
use DBSearch;
use MetaModel;

class BackgroundOperationService
{
	private static BackgroundOperationService $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): BackgroundOperationService
	{
		if (!isset(self::$oInstance)) {
			self::$oInstance = new BackgroundOperationService();
		}

		return self::$oInstance;
	}

	final public static function SetInstance(?BackgroundOperationService $oInstance): void
	{
		static::$oInstance = $oInstance;
	}

	/**
* @return \DataFeatureRemovalBackgroundOperation
* @throws \CoreException
* @throws \CoreUnexpectedValue
* @throws \MySQLException
* @throws \OQLException
	 */
	public function GetNext(): ?DataFeatureRemovalBackgroundOperation
	{
		$sOQL = <<<OQL
SELECT DataFeatureRemovalBackgroundOperation
OQL;

		$oSet = new DBObjectSet(DBSearch::FromOQL($sOQL), ['creation_date' => true]);
		/** @var DataFeatureRemovalBackgroundOperation $oDBObject */
		$oDBObject = $oSet->Fetch();

		return $oDBObject;
	}

	/**
* @param array $aExtensionsCodes
* @param array $aClasses
* @return void
* @throws \ArchivedObjectException
* @throws \CoreCannotSaveObjectException
* @throws \CoreException
* @throws \CoreUnexpectedValue
* @throws \CoreWarning
* @throws \MySQLException
* @throws \OQLException
	 */
	public function CreateOperation(array $aExtensionsCodes, array $aClasses): void
	{
		sort($aExtensionsCodes);
		sort($aClasses);

		$aValues = [
			'creation_date' => time(),
			'features_code' => '|'.implode('|', $aExtensionsCodes).'|',
			'classes' => implode(',', $aClasses),
		];
		$oObj = MetaModel::NewObject('DataFeatureRemovalBackgroundOperation', $aValues);
		$oObj->DBWrite();
	}

	/**
* @param string $sExtensionCode
* @return bool
* @throws \CoreException
* @throws \MissingQueryArgument
* @throws \MySQLException
* @throws \MySQLHasGoneAwayException
* @throws \OQLException
	 */
	public function IsExtensionBeingCleaned(string $sExtensionCode): bool
	{
		$sOQL = <<<OQL
SELECT DataFeatureRemovalBackgroundOperation WHERE features_code LIKE '%|$sExtensionCode|%'
OQL;

		$oSet = new DBObjectSet(DBSearch::FromOQL($sOQL));
		return $oSet->Count() > 0;
	}

	/**
* @param \DataFeatureRemovalBackgroundOperation $oBackgroundOperation
* @return array
	 */
	public function GetClasses(DataFeatureRemovalBackgroundOperation $oBackgroundOperation): array
	{
		return explode(',', $oBackgroundOperation->Get('classes'));
	}
}
