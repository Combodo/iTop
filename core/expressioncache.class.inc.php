<?php
// Copyright (c) 2010-2017 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
//

class ExpressionCache
{

	static public function GetCachedExpression($sClass, $sAttCode)
	{
		// read current cache
		@include_once (static::GetCacheFileName());

		$oExpr = null;
		$sKey = static::GetKey($sClass, $sAttCode);
		$sCacheClass = self::GetCacheClassName();
		if (class_exists($sCacheClass))
		{
			if (array_key_exists($sKey, $sCacheClass::$aCache))
			{
				$sVal = $sCacheClass::$aCache[$sKey];
				$oExpr = unserialize($sVal);
			}
		}
		return $oExpr;
	}


	static public function Warmup()
	{
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
// Copyright (c) 2010-2019 Combodo SARL
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
				file_put_contents($sFilePath, $content);
			}
		}
		Dict::SetUserLanguage($sUserLang);
	}

	static private function GetSerializedExpression($sClass, $sAttCode)
	{
		$sKey = static::GetKey($sClass, $sAttCode);
		$oExpr = DBObjectSearch::GetPolymorphicExpression($sClass, $sAttCode);
		return "'".$sKey."' => '".serialize($oExpr)."',\n";
	}

	/**
	 * @param $sClass
	 * @param $sAttCode
	 * @return string
	 */
	static private function GetKey($sClass, $sAttCode)
	{
		return $sClass.'::'.$sAttCode;
	}

	public static function GetCacheFileName()
	{
		$sLangName = self::GetLangName();
		return utils::GetCachePath().'expressioncache-' . $sLangName . '.php';
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



