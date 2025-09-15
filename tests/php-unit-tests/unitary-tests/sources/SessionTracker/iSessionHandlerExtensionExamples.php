<?php

namespace Combodo\iTop\Test\UnitTest\SessionTracker;

use Combodo\iTop\SessionTracker\iSessionHandlerExtension;

class BasicSessionHandlerExtension implements iSessionHandlerExtension {
	public function __construct(){
	}

	public function CompleteSessionData(array $aJson, array &$aData): void
	{
		$aData['shadok']='gabuzomeu';
	}
}