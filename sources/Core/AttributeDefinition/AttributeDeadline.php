<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use Dict;
use MetaModel;

/**
 * A dead line stored as a date & time
 * The only difference with the DateTime attribute is the display:
 * relative to the current time
 */
class AttributeDeadline extends AttributeDateTime
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

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		$sResult = self::FormatDeadline($value);

		return $sResult;
	}

	public static function FormatDeadline($value)
	{
		$sResult = '';
		if ($value !== null) {
			$iValue = AttributeDateTime::GetAsUnixSeconds($value);
			$sDate = AttributeDateTime::GetFormat()->Format($value);
			$difference = $iValue - time();

			if ($difference >= 0) {
				$sDifference = self::FormatDuration($difference);
			} else {
				$sDifference = Dict::Format('UI:DeadlineMissedBy_duration', self::FormatDuration(-$difference));
			}
			$sFormat = MetaModel::GetConfig()->Get('deadline_format');
			$sResult = str_replace(array('$date$', '$difference$'), array($sDate, $sDifference), $sFormat);
		}

		return $sResult;
	}

	static function FormatDuration($duration)
	{
		$days = floor($duration / 86400);
		$hours = floor(($duration - (86400 * $days)) / 3600);
		$minutes = floor(($duration - (86400 * $days + 3600 * $hours)) / 60);

		if ($duration < 60) {
			// Less than 1 min
			$sResult = Dict::S('UI:Deadline_LessThan1Min');
		} else {
			if ($duration < 3600) {
				// less than 1 hour, display it in minutes
				$sResult = Dict::Format('UI:Deadline_Minutes', $minutes);
			} else {
				if ($duration < 86400) {
					// Less that 1 day, display it in hours/minutes
					$sResult = Dict::Format('UI:Deadline_Hours_Minutes', $hours, $minutes);
				} else {
					// Less that 1 day, display it in hours/minutes
					$sResult = Dict::Format('UI:Deadline_Days_Hours_Minutes', $days, $hours, $minutes);
				}
			}
		}

		return $sResult;
	}
}