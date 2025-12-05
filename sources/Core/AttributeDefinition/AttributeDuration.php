<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use DBObject;
use Dict;
use Str;

/**
 * Store a duration as a number of seconds
 *
 * @package     iTopORM
 */
class AttributeDuration extends AttributeInteger
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function GetEditClass()
	{
		return "Duration";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "INT(11) UNSIGNED";
	}

	public function GetNullValue()
	{
		return '0';
	}

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue)) {
			return null;
		}
		if (!is_numeric($proposedValue)) {
			return null;
		}
		if (((int)$proposedValue) < 0) {
			return null;
		}

		return (int)$proposedValue;
	}

	public function ScalarToSQL($value)
	{
		if (is_null($value)) {
			return null;
		}

		return $value;
	}

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2html(self::FormatDuration($value));
	}

	public static function FormatDuration($duration)
	{
		$aDuration = self::SplitDuration($duration);

		if ($duration < 60) {
			// Less than 1 min
			$sResult = Dict::Format('Core:Duration_Seconds', $aDuration['seconds']);
		} else {
			if ($duration < 3600) {
				// less than 1 hour, display it in minutes/seconds
				$sResult = Dict::Format('Core:Duration_Minutes_Seconds', $aDuration['minutes'], $aDuration['seconds']);
			} else {
				if ($duration < 86400) {
					// Less than 1 day, display it in hours/minutes/seconds
					$sResult = Dict::Format(
						'Core:Duration_Hours_Minutes_Seconds',
						$aDuration['hours'],
						$aDuration['minutes'],
						$aDuration['seconds']
					);
				} else {
					// more than 1 day, display it in days/hours/minutes/seconds
					$sResult = Dict::Format(
						'Core:Duration_Days_Hours_Minutes_Seconds',
						$aDuration['days'],
						$aDuration['hours'],
						$aDuration['minutes'],
						$aDuration['seconds']
					);
				}
			}
		}

		return $sResult;
	}

	public static function SplitDuration($duration)
	{
		$duration = (int)$duration;
		$days = floor($duration / 86400);
		$hours = floor(($duration - (86400 * $days)) / 3600);
		$minutes = floor(($duration - (86400 * $days + 3600 * $hours)) / 60);
		$seconds = ($duration % 60); // modulo

		return ['days' => $days, 'hours' => $hours, 'minutes' => $minutes, 'seconds' => $seconds];
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\DurationField';
	}

	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null) {
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}
		parent::MakeFormField($oObject, $oFormField);

		// Note : As of today, this attribute is -by nature- only supported in readonly mode, not edition
		$sAttCode = $this->GetCode();
		$oFormField->SetCurrentValue($oObject->Get($sAttCode));
		$oFormField->SetReadOnly(true);

		return $oFormField;
	}

}
