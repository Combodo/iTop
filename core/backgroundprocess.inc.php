<?php
/**
 * Copyright (C) 2010-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */


interface iProcess
{
	/**
	 * @param int $iUnixTimeLimit
	 *
	 * @return string status message
	 * @throws \ProcessException
	 * @throws \ProcessFatalException
	 * @throws MySQLHasGoneAwayException
	 */
	public function Process($iUnixTimeLimit);
}

/**
 * interface iBackgroundProcess
 * Any extension that must be called regularly to be executed in the background 
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
interface iBackgroundProcess extends iProcess
{
	/**
	 * @return int repetition rate in seconds
	 */
	public function GetPeriodicity();
}

/**
 * interface iScheduledProcess
 * A variant of process that must be called at specific times
 *
 * @see \AbstractWeeklyScheduledProcess for a bootstrap implementation
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @copyright   Copyright (C) 2024 Combodo SAS
 */
interface iScheduledProcess extends iProcess
{
	/**
	 * @return DateTime exact time at which the process must be run next time
	 */
	public function GetNextOccurrence();
}


/**
 * Implementation of {@link iScheduledProcess}, using config parameters for module
 *
 * Use these parameters :
 *
 * * enabled
 * * week_days
 * * time
 *
 * Param names and some of their default values are in constant that can be overridden.
 *
 * Other info (module name and time default value) should be provided using a method that needs to be implemented.
 *
 * @since 2.7.0 PR #89
 * @since 2.7.0-2 N°2580 Fix {@link GetNextOccurrence} returning wrong value
 */
abstract class AbstractWeeklyScheduledProcess implements iScheduledProcess
{
	// param have default names/values but can be overridden
	const MODULE_SETTING_ENABLED = 'enabled';
	const DEFAULT_MODULE_SETTING_ENABLED = true;
	const MODULE_SETTING_WEEKDAYS = 'week_days';
	const DEFAULT_MODULE_SETTING_WEEKDAYS = 'monday, tuesday, wednesday, thursday, friday, saturday, sunday';
	const MODULE_SETTING_TIME = 'time';

	/**
	 * @var Config can be used to mock config for tests
	 */
	protected $oConfig;

	/**
	 * Module must be declared in each implementation
	 *
	 * @return string
	 */
	abstract protected function GetModuleName();

	/**
	 * @return string default value for {@link MODULE_SETTING_TIME} config param.
	 *         example '23:30'
	 */
	abstract protected function GetDefaultModuleSettingTime();

	/**
	 * @return \Config
	 */
	public function getOConfig()
	{
		if (!isset($this->oConfig))
		{
			$this->oConfig = MetaModel::GetConfig();
		}

		return $this->oConfig;
	}


	/**
	 * Interpret current setting for the week days
	 *
	 * @returns int[] (monday = 1)
	 * @throws ProcessInvalidConfigException
	 */
	public function InterpretWeekDays()
	{
		static $aWEEKDAYTON = array(
			'monday' => 1,
			'tuesday' => 2,
			'wednesday' => 3,
			'thursday' => 4,
			'friday' => 5,
			'saturday' => 6,
			'sunday' => 7,
		);
		$aDays = array();
		$sWeekDays = $this->getOConfig()->GetModuleSetting(
			$this->GetModuleName(),
			static::MODULE_SETTING_WEEKDAYS,
			static::DEFAULT_MODULE_SETTING_WEEKDAYS
		);

		if ($sWeekDays !== '')
		{
			$aWeekDaysRaw = explode(',', $sWeekDays);
			foreach ($aWeekDaysRaw as $sWeekDay)
			{
				$sWeekDay = strtolower(trim($sWeekDay));
				if (array_key_exists($sWeekDay, $aWEEKDAYTON))
				{
					$aDays[] = $aWEEKDAYTON[$sWeekDay];
				}
				else
				{
					throw new ProcessInvalidConfigException($this->GetModuleName().": wrong format for setting '".static::MODULE_SETTING_WEEKDAYS."' (found '$sWeekDay')");
				}
			}
		}
		if (count($aDays) === 0)
		{
			throw new ProcessInvalidConfigException($this->GetModuleName().': missing setting \''.static::MODULE_SETTING_WEEKDAYS.'\'');
		}
		$aDays = array_unique($aDays);
		sort($aDays);

		return $aDays;
	}

	/**
	 * @param string $sCurrentTime Date string to extract time dependency
	 *      this parameter is not present in the interface but as it is optional it's ok
	 *
	 * @return DateTime the exact time at which the process must be run next time
	 * @throws \ProcessInvalidConfigException
	 */
	public function GetNextOccurrence($sCurrentTime = 'now')
	{
		$bEnabled = $this->getOConfig()->GetModuleSetting(
			$this->GetModuleName(),
			static::MODULE_SETTING_ENABLED,
			static::DEFAULT_MODULE_SETTING_ENABLED
		);

		if (!$bEnabled)
		{
			return new DateTime('3000-01-01');
		}

		// 1st - Interpret the list of days as ordered numbers (monday = 1)
		//
		$aDays = $this->InterpretWeekDays();

		// 2nd - Find the next active week day
		//
		$sProcessTime = $this->getOConfig()->GetModuleSetting(
			$this->GetModuleName(),
			static::MODULE_SETTING_TIME,
			static::GetDefaultModuleSettingTime()
		);
		if (!preg_match('/[0-2]\d:[0-5]\d/', $sProcessTime))
		{
			throw new ProcessInvalidConfigException($this->GetModuleName().": wrong format for setting '".static::MODULE_SETTING_TIME."' (found '$sProcessTime')");
		}

		$oNow = new DateTime($sCurrentTime);
		$iNextPos = false;
		$sDay = $oNow->format('N');
		for ($iDay = (int) $sDay; $iDay <= 7; $iDay++)
		{
			$iNextPos = array_search($iDay, $aDays, true);
			if ($iNextPos !== false)
			{
				if (($iDay > $oNow->format('N')) || ($oNow->format('H:i') < $sProcessTime))
				{
					break;
				}
				$iNextPos = false; // necessary on sundays
			}
		}

		// 3rd - Compute the result
		//
		if ($iNextPos === false)
		{
			// Jump to the first day within the next week
			$iFirstDayOfWeek = $aDays[0];
			$iDayMove = $oNow->format('N') - $iFirstDayOfWeek;
			$oRet = clone $oNow;
			$oRet->modify('-'.$iDayMove.' days');
			$oRet->modify('+1 weeks');
		}
		else
		{
			$iNextDayOfWeek = $aDays[$iNextPos];
			$iMove = $iNextDayOfWeek - $oNow->format('N');
			$oRet = clone $oNow;
			$oRet->modify('+'.$iMove.' days');
		}
		list($sHours, $sMinutes) = explode(':', $sProcessTime);
		$oRet->setTime((int)$sHours, (int)$sMinutes);

		return $oRet;
	}

	/**
	 * @see \iProcess
	 *
	 * @param int $iUnixTimeLimit
	 *
	 * @return string
	 */
	abstract public function Process($iUnixTimeLimit);
}
