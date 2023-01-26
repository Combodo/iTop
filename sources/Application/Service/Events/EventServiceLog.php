<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Events;

use IssueLog;
use LogChannels;
use utils;

class EventServiceLog extends IssueLog
{
	const CHANNEL_DEFAULT = LogChannels::EVENT_SERVICE;

	/**
	 * @param $sMessage
	 * @param $sEvent
	 * @param $sources
	 *
	 * @return void
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public static function DebugEvent($sMessage, $sEvent, $sources)
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
			if (!EventHelper::MatchEventSource($aLogSources, $sources)) {
				return;
			}
		}
		static::Debug($sMessage);
	}

}