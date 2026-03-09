<?php

namespace Combodo\iTop\DataFeatureRemoval\Model;

use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException;
use DataFeatureRemoverAuditRule;
use DBObjectSearch;
use DBObjectSet;
use Exception;

class DataFeatureRemoverAuditRuleService
{
	private static DataFeatureRemoverAuditRuleService $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): DataFeatureRemoverAuditRuleService
	{
		if (!isset(self::$oInstance)) {
			self::$oInstance = new DataFeatureRemoverAuditRuleService();
		}

		return self::$oInstance;
	}

	final public static function SetInstance(?DataFeatureRemoverAuditRuleService $oInstance): void
	{
		self::$oInstance = $oInstance;
	}

	/**
	 * @param array $aGetRemovedClasses
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function SaveChecks(array $aGetRemovedClasses): void
	{
		$oSearch = DBObjectSearch::FromOQL('SELECT DataFeatureRemoverAuditRule', []);
		$oSearch->AllowAllData();
		$oSet = new DBObjectSet($oSearch);

		while (null != ($oObj = $oSet->Fetch())) {
			$oObj->DBDelete();
		}

		foreach ($aGetRemovedClasses as $sClass => $iCount) {
			$oObj = new DataFeatureRemoverAuditRule();
			$oObj->Set('rule_name', 'FINAL_CLASS');
			$oObj->Set('extension_code', $sClass);
			$oObj->Set('class_name', $sClass);
			$oObj->Set('count', $iCount);
			$oObj->DBWrite();
		}
	}

	/**
	 * @return array
	 * @throws \Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException
	 */
	public function ReadCheckRules(): array
	{
		try {
			$oSearch = DBObjectSearch::FromOQL('SELECT DataFeatureRemoverAuditRule', []);
			$oSearch->AllowAllData();
			$oSet = new DBObjectSet($oSearch);

			$aRes = [];
			while (null != ($oObj = $oSet->Fetch())) {
				$aRes[] = $oObj;
			}

			return $aRes;
		} catch (Exception $e) {
			throw new DataFeatureRemovalException(__FUNCTION__.' failed', 0, $e);
		}
	}
}
