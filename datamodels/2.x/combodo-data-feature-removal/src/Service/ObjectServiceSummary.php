<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Service;

use Combodo\iTop\DataFeatureRemoval\Entity\DataCleanupSummaryEntity;
use DBObject;
use IssueLog;

/**
 * Manage operation summary instead of doing the actual update or delete
 *
 * The summary is an array [class => DeletionPlanSummaryEntity]
 */
class ObjectServiceSummary implements iObjectService
{
	private array $aSummary = [];

	public function Update(DBObject $oToUpdate, string $sAttCode, $value): void
	{
		$sClass = get_class($oToUpdate);
		IssueLog::Info('Update object', null, ['class' => $sClass, 'id' => $oToUpdate->GetKey(), 'code' => $sAttCode, 'value' => "$value"]);
		if (! array_key_exists($sClass, $this->aSummary)) {
			$this->aSummary[$sClass] = new DataCleanupSummaryEntity($sClass);
		}
		$oDeletionPlanSummaryEntity = $this->aSummary[$sClass];
		$oDeletionPlanSummaryEntity->iUpdateCount++;
	}

	public function Delete(string $sClass, string $sId): void
	{
		IssueLog::Info('Delete object', null, ['class' => $sClass, 'id' => $sId]);
		if (!array_key_exists($sClass, $this->aSummary)) {
			$this->aSummary[$sClass] = new DataCleanupSummaryEntity($sClass);
		}
		$oDeletionPlanSummaryEntity = $this->aSummary[$sClass];
		$oDeletionPlanSummaryEntity->iDeleteCount++;
	}

	public function GetSummary(): array
	{
		return $this->aSummary;
	}
}
