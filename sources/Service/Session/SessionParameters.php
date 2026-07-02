<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Session;

use IssueLog;
use LogChannels;
use utils;

class SessionParameters
{
	private string $sSessionArrayName;
	private array $aParameters;

	public function __construct(string $sParamsName)
	{
		$this->sSessionArrayName = $sParamsName;
		$this->aParameters = Session::Get($this->sSessionArrayName, []);
		IssueLog::Enable(APPROOT.'log/error.log');
	}

	/**
	 * Reads a "persistent" parameter from the wizard's context
	 * @param string $sParamCode The code identifying this parameter
	 * @param mixed $defaultValue The default value of the parameter in case it was not set
	 */
	public function GetParameter(string $sParamCode, mixed $defaultValue = '')
	{
		if (array_key_exists($sParamCode, $this->aParameters)) {
			return $this->aParameters[$sParamCode];
		}

		return $defaultValue;
	}

	/**
	 * Stores a "persistent" parameter in the wizard's context
	 *
	 * @param string $sParamCode The code identifying this parameter
	 * @param mixed $value The value to store
	 */
	public function SetParameter($sParamCode, $value): void
	{
		$this->aParameters[$sParamCode] = $value;
		$this->Save();
	}

	/**
	 * Stores the value of the page's parameter in a "persistent" parameter in the wizard's context
	 * @param string $sParamCode The code identifying this parameter
	 * @param mixed $defaultValue The default value for the parameter
	 * @param string $sSanitizationFilter A 'sanitization' fitler. Default is 'raw_data', which means no filtering
	 */
	public function SetParameterFromPostedParams(string $sParamCode, mixed $defaultValue, string $sSanitizationFilter = 'raw_data'): void
	{
		$value = utils::ReadPostedParam($sParamCode, $defaultValue, $sSanitizationFilter);
		$this->aParameters[$sParamCode] = $value;
		$this->Save();
	}

	/**
	 * Stores the value of the page's parameter in a "persistent" parameter in the wizard's context
	 * @param string $sParamCode The code identifying this parameter
	 * @param mixed $defaultValue The default value for the parameter
	 * @param string $sSanitizationFilter A 'sanitization' fitler. Default is 'raw_data', which means no filtering
	 */
	public function SetParameterFromParams(string $sParamCode, mixed $defaultValue, string $sSanitizationFilter = 'raw_data'): void
	{
		$value = utils::ReadParam($sParamCode, $defaultValue, false, $sSanitizationFilter);
		$this->aParameters[$sParamCode] = $value;
		$this->Save();
	}

	private function Save(): void
	{
		Session::Set($this->sSessionArrayName, $this->aParameters);
	}

	public function Erase(): void
	{
		$this->aParameters = [];
		$this->Save();
		$this->LogParameters();
	}

	public function LogParameters(): void
	{
		IssueLog::Debug('---------------------------------', LogChannels::SESSION_PARAMETERS);
		IssueLog::Debug(json_encode(Session::Get($this->sSessionArrayName, []), JSON_PRETTY_PRINT), LogChannels::SESSION_PARAMETERS);
		IssueLog::Debug('---------------------------------', LogChannels::SESSION_PARAMETERS);
	}
}
