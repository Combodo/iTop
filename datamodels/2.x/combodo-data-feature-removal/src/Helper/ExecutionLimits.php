<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Helper;

use utils;

class ExecutionLimits
{
	private int $iMaxTime;
	private int $iMaxMemoryPercent;

	/**
	 * @param int $iMaxTime
	 * @param int $iMaxMemoryPercent
	 */
	public function __construct(int $iMaxTime = 0, int $iMaxMemoryPercent = 100)
	{
		$this->iMaxTime = $iMaxTime;
		$this->iMaxMemoryPercent = $iMaxMemoryPercent;
	}

	public function ShouldStopExecution(): bool
	{
		return utils::ShouldStopExecution($this->iMaxTime, $this->iMaxMemoryPercent);
	}

}
