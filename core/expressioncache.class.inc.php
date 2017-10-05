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
	static private $aCache = array();

	static public function GetCachedExpression($sClass, $sAttCode)
	{
		// read current cache
		@include_once (static::GetCacheFileName());

		$oExpr = null;
		$sKey = static::GetKey($sClass, $sAttCode);
		if (array_key_exists($sKey, static::$aCache))
		{
			$oExpr =  static::$aCache[$sKey];
		}
		else
		{
			if (class_exists('ExpressionCacheData'))
			{
				if (array_key_exists($sKey, ExpressionCacheData::$aCache))
				{
					$sVal = ExpressionCacheData::$aCache[$sKey];
					$oExpr = unserialize($sVal);
					static::$aCache[$sKey] = $oExpr;
				}
			}
		}
		return $oExpr;
	}


	static public function Warmup()
	{
		$sFilePath = static::GetCacheFileName();

		if (!is_file($sFilePath))
		{
			$content = <<<EOF
<?php
// Copyright (c) 2010-2017 Combodo SARL
// Generated Expression Cache file

class ExpressionCacheData
{
	static \$aCache =  array(
EOF;

			foreach(MetaModel::GetClasses() as $sClass)
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

			file_put_contents($sFilePath, $content);
		}
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
		return utils::GetCachePath().'expressioncache.php';
	}

}



