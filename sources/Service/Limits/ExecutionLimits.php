<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Limits;

use utils;

class ExecutionLimits
{
	private int $iMaxTime;
	private int $iMaxMemoryPercent;

	/**
	 * @param int $iMaxDuration      Max duration time in s
	 * @param int $iMaxMemoryPercent Max memory percent allowed (0-100)
	 */
	public function __construct(int $iMaxDuration = 0, int $iMaxMemoryPercent = 100)
	{
	public function __construct(int $iMaxDuration = 0, int $iMaxMemoryPercent = 100)
	{
		$this->iMaxTime = ($iMaxDuration > 0) ? ($iMaxDuration + time()) : 0;
		$this->iMaxMemoryPercent = (int)min(max($iMaxMemoryPercent, 0), 100);
	}
	/**
	 * @return bool true when duration is
	 */
	public function ShouldStopExecution(): bool
	{
		if (($this->iMaxTime != 0) && (time() > $this->iMaxTime)) {
			\IssueLog::Debug(__METHOD__.' timeout '.time()." (current) > $this->iMaxTime (max)");
			return true;
		}

		$iMaxMemoryPercent = (int)min(max($this->iMaxMemoryPercent, 0), 100);
		$iMemory = memory_get_usage(true);
		$iMaxMemory = utils::GetMemoryLimit() * $iMaxMemoryPercent / 100;
		if ($iMemory > $iMaxMemory) {
			\IssueLog::Debug(__METHOD__." Memory limit $iMemory (current) > $iMaxMemory (max)");
			return true;
		}

		return false;
	}

}
