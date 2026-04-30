<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Service;

use CMDBSource;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalLog;
use DBObject;
use DBObjectSearch;
use MetaModel;

class ObjectService extends ObjectServiceSummary
{
	public function Update(DBObject $oToUpdate, string $sAttCode, $value): void
	{
		$oToUpdate->Set($sAttCode, $value);
		$oToUpdate->DBUpdate();
		parent::Update($oToUpdate, $sAttCode, $value);
	}

	public function Delete(string $sClass, string $sId): void
	{
		try {
			CMDBSource::Query('START TRANSACTION');
			// Delete any existing change tracking about the current object
			$oFilter = new DBObjectSearch('CMDBChangeOp');
			$oFilter->AddCondition('objclass', $sClass, '=');
			$oFilter->AddCondition('objkey', $sId, '=');
			MetaModel::PurgeData($oFilter);

			// Delete the entry
			$aClassesToRemove = array_merge(MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL), MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_EXCLUDELEAF, false));
			foreach ($aClassesToRemove as $sParentClass) {
				/** @var DBObjectSearch $oFilter */
				$oFilter = DBObjectSearch::FromOQL_AllData("SELECT $sParentClass WHERE id=:id");
				$sQuery = $oFilter->MakeDeleteQuery(['id' => $sId]);
				CMDBSource::DeleteFrom($sQuery);
			}

			CMDBSource::Query('COMMIT');
			parent::Delete($sClass, $sId);

		} catch (\Exception $e) {
			DataFeatureRemovalLog::Exception(__METHOD__.': Cleanup failed', $e);
			CMDBSource::Query('ROLLBACK');
			throw $e;
		}
	}

	public function SetIssue(string $sClass): void
	{
		throw new DataFeatureRemovalException('Deletion Plan cannot be executed due to issues');
	}

}
