<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace DBObject\Utils;

/**
 * Add debug feature to test support class
 */
trait ClassesWithDebug
{
	public static function DebugStatic($sMsg)
	{
		if (static::$DEBUG_UNIT_TEST) {
			if (is_string($sMsg)) {
				echo "$sMsg\n";
			} else {
				print_r($sMsg);
			}
		}
	}
}
