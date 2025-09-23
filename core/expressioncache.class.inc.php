<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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

/**
 * Class ExpressionCache
 */
class ExpressionCache
{

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @return mixed|null
	 */
	public static function GetCachedExpression($sClass, $sAttCode)
	{
		if (!utils::GetConfig()->Get('expression_cache_enabled'))
		{
			return null;
		}

		// read current cache
		@include_once (static::GetCacheFileName());

		$oExpr = null;
		$sKey = static::GetKey($sClass, $sAttCode);
		$sCacheClass = self::GetCacheClassName();
		if (class_exists($sCacheClass))
		{
			/** @noinspection PhpUndefinedFieldInspection The property is dynamically generated */
			if (array_key_exists($sKey, $sCacheClass::$aCache))
			{
				$sVal = $sCacheClass::$aCache[$sKey];
				$oExpr = unserialize($sVal);
			}
		}
		return $oExpr;
	}


	/**
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 */
	public static function Warmup()
	{
		if (!utils::GetConfig()->Get('expression_cache_enabled'))
		{
			return;
		}
		// Store current language
		$sUserLang = Dict::GetUserLanguage();
		$aLanguages = Dict::GetLanguages();
		foreach($aLanguages as $sLang => $aLang)
		{
			Dict::SetUserLanguage($sLang);
			$sFilePath = static::GetCacheFileName();
			$sCacheClass = self::GetCacheClassName();

			if (!is_file($sFilePath))
			{
				$content = <<<EOF
<?php
// Copyright (c) 2010-2024 Combodo SAS
// Generated Expression Cache file for $sLang

class $sCacheClass
{
	static \$aCache =  array(
EOF;

				foreach (MetaModel::GetClasses() as $sClass)
				{
					$content .= static::GetSerializedExpression($sClass, 'friendlyname');
					if (MetaModel::IsObsoletable($sClass))
					{
						$content .= static::GetSerializedExpression($sClass, 'obsolescence_flag');
					}
				}

				$content .= <<<EOF
	);
}
EOF;

				SetupUtils::builddir(dirname($sFilePath));
				file_put_contents($sFilePath, $content, LOCK_EX);
			}
		}
		Dict::SetUserLanguage($sUserLang);
	}

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @return string
	 * @throws \CoreException
	 */
	private static function GetSerializedExpression($sClass, $sAttCode)
	{
		$sKey = static::GetKey($sClass, $sAttCode);
		$oExpr = DBObjectSearch::GetPolymorphicExpression($sClass, $sAttCode);
		return var_export($sKey, true)." => ".var_export(serialize($oExpr), true).",\n"; // Beware, string values can contain quotes, backslashes, etc!
	}

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @return string
	 */
	private static function GetKey($sClass, $sAttCode)
	{
		return $sClass.'::'.$sAttCode;
	}

	/**
	 * @return string
	 */
	public static function GetCacheFileName()
	{
		$sLangName = self::GetLangName();
		return utils::GetCachePath().'expressioncache/expressioncache-' . $sLangName . '.php';
	}

	/**
	 * @return string
	 */
	private static function GetCacheClassName()
	{
		$sLangName = self::GetLangName();
		$sCacheClass = "ExpressionCacheData$sLangName";
		return $sCacheClass;
	}

	/**
	 * @return mixed
	 */
	private static function GetLangName()
	{
		$sLang = Dict::GetUserLanguage();
		$sLangName = str_replace(" ", "", $sLang);
		return $sLangName;
	}
}



