<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Events;

class EventHelper
{
	public static function MatchEventSource($srcRegistered, $srcEvent): bool
	{
		if (empty($srcRegistered)) {
			// no filtering
			return true;
		}
		if (empty($srcEvent)) {
			// no match (the registered source is not empty)
			return false;
		}
		if (is_string($srcRegistered)) {
			$aSrcRegistered = [$srcRegistered];
		} elseif (is_array($srcRegistered)) {
			$aSrcRegistered = $srcRegistered;
		} else {
			$aSrcRegistered = [];
		}

		if (is_string($srcEvent)) {
			$aSrcEvent = [$srcEvent];
		} elseif (is_array($srcEvent)) {
			$aSrcEvent = $srcEvent;
		} else {
			$aSrcEvent = [];
		}

		foreach ($aSrcEvent as $sSrcEvent) {
			if (in_array($sSrcEvent, $aSrcRegistered)) {
				// sources matches
				return true;
			}
		}

		// no match
		return false;
	}
}