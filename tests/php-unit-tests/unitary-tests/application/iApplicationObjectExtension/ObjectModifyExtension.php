<?php
/**
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Test object for AbstractApplicationObjectExtension API
 */
class ObjectModifyExtension extends AbstractApplicationObjectExtension
{
	private static $iCountModify;
	private static $bAlwaysChanged;
	private static $sClass;
	private static $sAttCodeToModify;
	private static $callBack;


	public function __construct()
	{
	}

	public static function SetCallBack($callBack)
	{
		self::$callBack = $callBack;
	}

	public static function SetModifications($sClass, $sAttCodeToModify, $iCountModify)
	{
		self::$sClass = $sClass;
		self::$sAttCodeToModify = $sAttCodeToModify;
		if (!MetaModel::IsValidClass($sClass) || !MetaModel::IsValidAttCode($sClass, $sAttCodeToModify)) {
			throw new Exception("Invalid class $sClass or attcode $sAttCodeToModify");
		}
		self::$iCountModify = $iCountModify;
	}

	public static function SetAlwaysChanged($bAlwaysChanged)
	{
		self::$bAlwaysChanged = $bAlwaysChanged;
	}

	public function OnDBUpdate($oObject, $oChange = null)
	{
		if (get_class($oObject) !== self::$sClass) {
			return;
		}

		if (self::$iCountModify > 0) {
			if (!empty($oObject->ListPreviousValuesForUpdatedAttributes())) {
				if (!is_null(self::$callBack)) {
					call_user_func(self::$callBack, 'OnDBUpdate');
				}
				self::$iCountModify--;
				$oObject->Set(self::$sAttCodeToModify, 'Value_'.rand());
				$oObject->DBUpdate();
			} else {
				return;
			}
		}
	}

	public function OnIsModified($oObject)
	{
		return self::$bAlwaysChanged;
	}
}