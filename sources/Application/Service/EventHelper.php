<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service;

use IssueLog;
use LogChannels;
use utils;

class EventHelper
{
	public static function Trace($sMessage)
	{
		IssueLog::Trace($sMessage, LogChannels::EVENT_SERVICE);
	}

	public static function Debug($sMessage, $sEvent, $sources)
	{
		$oConfig = utils::GetConfig();
		$aLogEvents = $oConfig->Get('event_service.debug.filter_events');
		$aLogSources = $oConfig->Get('event_service.debug.filter_sources');

		if (is_array($aLogEvents)) {
			if (!in_array($sEvent, $aLogEvents)) {
				return;
			}
		}
		if (is_array($aLogSources)) {
			if (!self::MatchEventSource($aLogSources, $sources)) {
				return;
			}
		}
		IssueLog::Debug($sMessage, LogChannels::EVENT_SERVICE);
	}

	public static function Error($sMessage)
	{
		IssueLog::Error($sMessage, LogChannels::EVENT_SERVICE);
	}

	public static function Warning($sMessage)
	{
		IssueLog::Warning($sMessage, LogChannels::EVENT_SERVICE);
	}

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