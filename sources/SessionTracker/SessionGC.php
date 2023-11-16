<?php

namespace Combodo\iTop\SessionTracker;

/**
 * Class SessionGC
 *
 * @author Olivier Dain <olivier.dain@combodo.com>
 * @package Combodo\iTop\SessionTracker
 * @since 3.1.1 3.2.0 N°6901
 */
class SessionGC implements \iBackgroundProcess
{
	/**
	 * @inheritDoc
	 */
	public function GetPeriodicity()
	{
		return 60 * 1; // seconds
	}

	/**
	 * @inheritDoc
	 */
	public function Process($iTimeLimit)
	{
		$iMaxLifetime = ini_get('session.gc_maxlifetime') ?? 1440;
		$oSessionHandler = new SessionHandler();
		$iProcessed = $oSessionHandler->gc_with_time_limit($iMaxLifetime, $iTimeLimit);
		return "processed $iProcessed tasks";
	}
}
