<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\TemporaryObjects;

use iBackgroundProcess;

/**
 * TemporaryObjectGC.
 *
 * Background task to collect and garbage expired temporary objects..
 *
 * @since 3.1
 */
class TemporaryObjectGC implements iBackgroundProcess
{
	/** @var TemporaryObjectManager */
	private TemporaryObjectManager $oTemporaryObjectManager;

	/**
	 * Constructor.
	 *
	 */
	public function __construct()
	{
		// Retrieve service dependencies
		$this->oTemporaryObjectManager = TemporaryObjectManager::GetInstance();
	}

	/** @inheritDoc * */
	public function GetPeriodicity()
	{
		return TemporaryObjectConfig::GetInstance()->GetWatchdogInterval();
	}

	/** @inheritDoc * */
	public function Process($iUnixTimeLimit)
	{
		// Garbage temporary objects
		$this->oTemporaryObjectManager->GarbageExpiredTemporaryObjects();
	}
}