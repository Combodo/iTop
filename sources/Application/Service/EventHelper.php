<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service;

use SetupUtils;
use utils;

class EventHelper
{

	public static function GetCachedClasses($sModuleName, callable $ListBuilder)
	{
		$aClasses = [];
		$sCacheFileName = '';

		if (!utils::IsDevelopmentEnvironment()) {
			// Try to read from cache
			$sCacheFileName = utils::GetCachePath()."EventsClassList/$sModuleName.php";
			if (is_file($sCacheFileName)) {
				$aClasses = include $sCacheFileName;
			}
		}

		if (empty($aClasses)) {
			$aClasses = call_user_func($ListBuilder);

			if (!utils::IsDevelopmentEnvironment() && !empty($aClasses)) {
				// Save to cache
				$sCacheContent = "<?php\n\nreturn ".var_export($aClasses, true).";";
				SetupUtils::builddir(dirname($sCacheFileName));
				file_put_contents($sCacheFileName, $sCacheContent);
			}
		}

		return $aClasses;
	}

}