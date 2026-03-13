<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Helper;

class DataFeatureRemovalHelper
{
	public const MODULE_NAME = 'combodo-data-feature-removal';

	public static function IsTimeLimitExceeded(int $iUnixTimeLimit): bool
	{
		if ($iUnixTimeLimit === 0) {
			//no time limit
			return false;
		}

		return (time() > $iUnixTimeLimit);
	}
}
