<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Helper;

use MetaModel;
use utils;

class DataFeatureRemovalConfig
{
	private static DataFeatureRemovalConfig $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): DataFeatureRemovalConfig
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new DataFeatureRemovalConfig();
		}

		return static::$oInstance;
	}

	public function Get(string $sParamName, $default = null)
	{
		return MetaModel::GetModuleSetting(DataFeatureRemovalHelper::MODULE_NAME, $sParamName, $default);
	}

	public function GetBoolean(string $sParamName, $default = null): bool
	{
		$res = $this->Get($sParamName, $default);

		return boolval($res);
	}

	public function IsEnabled(): bool
	{
		return $this->GetBoolean('enable', false);
	}

	public function Set(string $sParamName, $value)
	{
		$oConfig = utils::GetConfig();
		$oConfig->SetModuleSetting(DataFeatureRemovalHelper::MODULE_NAME, $sParamName, $value);
	}
}
