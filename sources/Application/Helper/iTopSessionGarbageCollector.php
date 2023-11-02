<?php

namespace Combodo\iTop\Application\Helper;

class iTopSessionGarbageCollector implements \iBackgroundProcess
{
	public function GetPeriodicity()
	{
		return 60 * 5; // seconds
	}

	public function Process($iTimeLimit)
	{
		$max_lifetime = ini_get('session.gc_maxlifetime') ?? 60;
		$oiTopSessionHandler = new iTopSessionHandler();
		$iProcessed = $oiTopSessionHandler->gcWithTimeLimit($max_lifetime, $iTimeLimit);
		return "processed $iProcessed tasks";
	}
}
