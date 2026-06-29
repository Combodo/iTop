<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Entity;

class DeletionPlanItem
{
	public array $aIds = [];

	/**
	 * @param array $aIds
	 */
	public function __construct(array $aIds = [])
	{
		$this->aIds = $aIds;
	}

	public function Merge(DeletionPlanItem $oItem): void
	{
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
