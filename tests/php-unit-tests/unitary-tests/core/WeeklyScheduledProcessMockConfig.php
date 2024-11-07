<?php

/**
 * Tool class to mock the config in {@link AbstractWeeklyScheduledProcess}
 *
 * The weekdays will be set to working week days (all except saturday & sunday)
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 */
class WeeklyScheduledProcessMockConfig extends AbstractWeeklyScheduledProcess
{
	const MODULE_NAME = 'itop-zabu-gomeu';

	public function __construct($bEnabledValue, $sTimeValue, $sWeekDays)
	{
		$this->oConfig = new Config();
		$this->oConfig->SetModuleSetting(self::MODULE_NAME, self::MODULE_SETTING_ENABLED, $bEnabledValue);
		$this->oConfig->SetModuleSetting(self::MODULE_NAME, self::MODULE_SETTING_TIME, $sTimeValue);
		$this->oConfig->SetModuleSetting(self::MODULE_NAME, self::MODULE_SETTING_WEEKDAYS, $sWeekDays);
		utils::InitTimeZone($this->oConfig);
	}

	protected function GetModuleName()
	{
		return self::MODULE_NAME;
	}

	protected function GetDefaultModuleSettingTime()
	{
		return null; // config mock injected in the constructor
	}

	public function Process($iUnixTimeLimit)
	{
		// nothing to do here (not tested)
	}
}
