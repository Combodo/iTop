<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Service;

use Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanSummaryEntity;
use DBObject;

class ObjectServiceSummary implements iObjectService
{
	private array $aSummary = [];

	public function Update(DBObject $oToUpdate, string $sAttCode, $value): void
	{
		$sClass = get_class($oToUpdate);
		if (! array_key_exists($sClass, $this->aSummary)) {
			$this->aSummary[$sClass] = new DeletionPlanSummaryEntity($sClass);
		}
		$oDeletionPlanSummaryEntity = $this->aSummary[$sClass];
		$oDeletionPlanSummaryEntity->iUpdateCount++;
	}

	public function Delete(string $sClass, string $sId): void
	{
		if (!array_key_exists($sClass, $this->aSummary)) {
			$this->aSummary[$sClass] = new DeletionPlanSummaryEntity($sClass);
		}
		$oDeletionPlanSummaryEntity = $this->aSummary[$sClass];
		$oDeletionPlanSummaryEntity->iDeleteCount++;
	}

	public function GetSummary(): array
	{
		return $this->aSummary;
	}
}
