<?php

namespace Combodo\iTop\Application\Helper;

/**
 * Class iTopSessionGarbageCollector
 *
 * @author Olivier Dain <olivier.dain@combodo.com>
 * @package Combodo\iTop\Application\Helper
 * @since 3.1.1 3.2.0
 */
class iTopSessionGarbageCollector implements \iBackgroundProcess
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
		$iMaxLifetime = ini_get('session.gc_maxlifetime') ?? 60;
		$oiTopSessionHandler = new iTopSessionHandler();
		$iProcessed = $oiTopSessionHandler->gc_with_time_limit($iMaxLifetime, $iTimeLimit);
		return "processed $iProcessed tasks";
	}
}
