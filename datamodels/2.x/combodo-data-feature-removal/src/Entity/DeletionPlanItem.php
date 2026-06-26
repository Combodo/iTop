<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Entity;

class DeletionPlanItem
{
	public array $aQueries = [];
	public array $aIds = [];

	/**
	 * @param array $aQueries
	 * @param array $aIds
	 */
	public function __construct(array $aQueries = [], array $aIds = [])
	{
		$this->aQueries = $aQueries;
		$this->aIds = $aIds;
	}

	public function Merge(DeletionPlanItem $oItem): void
	{
		$this->aQueries = array_merge($this->aQueries, $oItem->aQueries);
		$this->aIds = array_unique(array_merge($this->aIds, $oItem->aIds));
	}

	public function Count(): int
	{
		return count($this->aIds);
	}

	public function FilterBy(DeletionPlanItem $oItem): void
	{
		$this->aIds = array_diff($this->aIds, $oItem->aIds);
	}
}
