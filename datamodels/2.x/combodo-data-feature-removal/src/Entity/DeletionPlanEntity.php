<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Entity;

class DeletionPlanEntity
{
	public readonly DeletionPlanItem $oDelete;
	public readonly DeletionPlanItem $oUpdate;
	public readonly DeletionPlanItem $oIssue;
	
	public function __construct()
	{
		$this->oDelete = new DeletionPlanItem();
		$this->oUpdate = new DeletionPlanItem();
		$this->oIssue = new DeletionPlanItem();
	}

	public function TotalCount(): int
	{
		return $this->oDelete->Count() + $this->oUpdate->Count() + $this->oIssue->Count();
	}

	public function FilterUpdatesByDeletes()
	{
		$this->oUpdate->FilterBy($this->oDelete);
	}
}
