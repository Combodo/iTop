<?php

namespace Combodo\iTop\DataFeatureRemoval\Entity;

class DataCleanupSummaryEntity
{
	public string $sClass;
	public ?string $sIssue = null;
	public int $iUpdateCount = 0;
	public int $iDeleteCount = 0;

	/**
	 * @param string $sClass
	 */
	public function __construct(string $sClass)
	{
		$this->sClass = $sClass;
	}
}
