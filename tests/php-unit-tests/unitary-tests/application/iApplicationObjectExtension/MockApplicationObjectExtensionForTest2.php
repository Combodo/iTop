<?php
/**
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Test object for AbstractApplicationObjectExtension API
 */
class MockApplicationObjectExtensionForTest2 extends AbstractApplicationObjectExtension
{
	protected static $iCountModify;
	protected static $sClass;
	protected static $sAttCodeToModify;
	protected static $callBack;


	public function __construct()
	{
	}

	public static function SetCallBack($callBack)
	{
		static::$callBack = $callBack;
	}

	public static function SetModifications($sClass, $sAttCodeToModify, $iCountModify)
	{
		static::$sClass = $sClass;
		static::$sAttCodeToModify = $sAttCodeToModify;
		if (!MetaModel::IsValidClass($sClass) || !MetaModel::IsValidAttCode($sClass, $sAttCodeToModify)) {
			throw new Exception("Invalid class $sClass or attcode $sAttCodeToModify");
		}
		static::$iCountModify = $iCountModify;
	}

	public function OnDBUpdate($oObject, $oChange = null)
	{
		if (get_class($oObject) !== static::$sClass) {
			return;
		}

		if (!is_null(static::$callBack)) {
			call_user_func(static::$callBack, 'OnDBUpdate');
		}

		$aPreviousValues = $oObject->ListPreviousValuesForUpdatedAttributes();
		$sPreviousValues = print_r($aPreviousValues, true);

		IssueLog::Info(__METHOD__." received previous values:\n$sPreviousValues");

		if (static::$iCountModify > 0) {
			static::$iCountModify--;
			$oObject->Set(static::$sAttCodeToModify, 'Value_'.rand());
			$oObject->DBUpdate();
		}
	}

	public function OnDBInsert($oObject, $oChange = null)
	{
		if (get_class($oObject) !== static::$sClass) {
			return;
		}

		if (!is_null(static::$callBack)) {
			call_user_func(static::$callBack, 'OnDBInsert');
		}

		if (static::$iCountModify > 0) {
			static::$iCountModify--;
			$oObject->Set(static::$sAttCodeToModify, 'Value_'.rand());
			$oObject->DBUpdate();
		}
	}
}