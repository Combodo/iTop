<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Entity;

use MetaModel;

class DeletionPlanEntity
{
	public readonly DeletionPlanItem $oDelete;
	public readonly DeletionPlanItem $oUpdate;
	public readonly DeletionPlanItem $oIssue;

	public array $aQueriesForTempTable;
	public array $aDependsOnTempTable;
	public string $sClass;

	/**
	 * @param \Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanItem $oDelete
	 * @param \Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanItem $oUpdate
	 * @param \Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanItem $oIssue
	 */
	public function __construct(string $sClass)
	{
		$this->sClass = $sClass;
		$this->oDelete = $oDelete ?? new DeletionPlanItem();
		$this->oUpdate = $oUpdate ?? new DeletionPlanItem();
		$this->oIssue = $oIssue ?? new DeletionPlanItem();
		$this->aQueriesForTempTable = [];
		$this->aDependsOnTempTable = [];
	}

	public function TotalCount(): int
	{
		return $this->oDelete->Count() + $this->oUpdate->Count() + $this->oIssue->Count();
	}

	public function FilterUpdatesByDeletes()
	{
		$this->oUpdate->FilterBy($this->oDelete);
	}

	public function AddQueryForTempTable(string $sQuery, ?string $sTempTableName = null): void
	{
		if (!in_array($sQuery, $this->aQueriesForTempTable)) {
			$this->aQueriesForTempTable[] = $sQuery;
			if (!is_null($sTempTableName) && !in_array($sTempTableName, $this->aDependsOnTempTable)) {
				$this->aDependsOnTempTable[] = $sTempTableName;
			}
		}
	}

	public static function GetTempTableName(string $sClass): string
	{
		$sRootClass = MetaModel::GetRootClass($sClass);
		return 'temp_'.MetaModel::DBGetTable($sRootClass);
	}
}
