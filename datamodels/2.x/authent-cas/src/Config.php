<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     https://www.combodo.com/documentation/combodo-software-license.html
 *
 */

namespace Combodo\iTop\Cas;

use MetaModel;

class Config
{
	/**
	 * Get modules settings and general settings as a fallback
	 * This is done to allow compatibility with previous versions where
	 * CAS was configured in the general settings
	 *
	 * @param $sName
	 *
	 * @return mixed
	 */
	public static function Get($sName, $sDefaultValue = '')
	{
		$sValue = MetaModel::GetModuleSetting('authent-cas', $sName, '');
		if (empty($sValue))
		{
			$sValue = MetaModel::GetConfig()->Get($sName);
		}
		if (empty($sValue))
		{
			return $sDefaultValue;
		}

		return $sValue;
	}
}