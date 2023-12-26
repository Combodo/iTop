<?php
/*
 * Copyright (C) 2013-2023 Combodo SARL
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

namespace Combodo\iTop\FormSDK\Helper;

use Dict;

/**
 *
 * @package FormSDK
 * @since 3.2.0
 */
class SelectDataProvider
{
	/**
	 * Return array of application languages.
	 *
	 * @return array
	 */
	static public function GetApplicationLanguages() : array
	{
		$aAvailableLanguages = Dict::GetLanguages();
		$aLanguageCodes = array();
		foreach($aAvailableLanguages as $sLangCode => $aInfo){
			$sLanguageLabel = $aInfo['description'].' ('.$aInfo['localized_description'].')';
			$aLanguageCodes[$sLanguageLabel] = $sLangCode;
		}

		return $aLanguageCodes;
	}

	/**
	 * Return array of modes.
	 *
	 * @return array
	 */
	static public function GetModes() : array
	{
		return [
			'Minimal' => 0,
			'Optimal' => 1,
			"Maximal" => 2
		];
	}

	/**
	 * Return array of options.
	 *
	 * @return array
	 */
	static public function GetOptions() : array
	{
		return [
			'Option A' => 0,
			'Option B' => 1,
			"Option C" => 2,
			"Option D" => 3,
			"Option E" => 4,
			"Option F" => 5
		];
	}
}