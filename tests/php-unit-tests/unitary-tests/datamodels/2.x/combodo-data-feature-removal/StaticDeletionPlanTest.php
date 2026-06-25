<?php

namespace Combodo\iTop\Test\UnitTest\Module\DataFeatureRemoval;

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use CMDBSource;
use Combodo\iTop\DataFeatureRemoval\Service\DataCleanupService;
use Combodo\iTop\DataFeatureRemoval\Service\StaticDeletionPlan;
use MetaModel;

class StaticDeletionPlanTest extends \AbstractCleanup
{
	public function GetDatamodelDeltaAbsPath(): string
	{
		return __DIR__.'/data_cleanup_delta.xml';
	}

	public function testGetInitialClassDeletionPlan()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRToUpdate_1
			DFRToRemoveLeaf_1 <- DFRToUpdate_2
			DFRToRemoveLeaf_2 <- DFRToUpdate_3
		EOF);

		$oService = new StaticDeletionPlan();
		$aRes = $oService->GetInitialClassDeletionPlan('DFRToRemoveLeaf');
		self::assertCount(2, $aRes[1]);
		self::assertEquals($this->aIdByClass['DFRToRemoveLeaf'], $aRes[1]);
		$sTable = MetaModel::DBGetTable('DFRToRemoveLeaf');
		$sExpectedSQL = "DELETE FROM $sTable";
		self::assertEquals($sExpectedSQL, $aRes[0]);
	}

	public function testUpdateExtKeyNullable()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRToUpdate_1
			DFRToRemoveLeaf_1 <- DFRToUpdate_2
			DFRToRemoveLeaf_2 <- DFRToUpdate_3
			DFRLeafNotToRemove_1 <- DFRToUpdate_4
		EOF);

		// WHEN
		$oService = new StaticDeletionPlan();
		$sRemoteTable = MetaModel::DBGetTable('DFRToUpdate');
		$aRes = $oService->UpdateExtKeyNullable(
			$sRemoteTable,
			'extkey_id',
			implode(',', $this->aIdByClass['DFRToRemoveLeaf'])
		);
		$sUpdateSQL = $aRes[0];
		$aIds = $aRes[1];

		// THEN
		$sExpectedSQLEnd = " IN (".implode(',', $this->aIdByClass['DFRToRemoveLeaf']).")";
		self::assertStringEndsWith($sExpectedSQLEnd, $sUpdateSQL);

		self::assertCount(3, $aIds);
		$sIdsToRemoveInTargetClass = implode(',', $this->aIdByClass['DFRToRemoveLeaf']);
		$aExpectedIds = $oService->GetRemoteIdsForExtKey($sRemoteTable, 'extkey_id', $sIdsToRemoveInTargetClass);

		self::assertEquals($aExpectedIds, $aIds);

		//		var_export($aRes);
		//		var_export($this->aIdByClass);
	}

	/**
	 * Tests that GetCleanupSummary returns an empty array when passed null as input.
	 */
	public function testGetCleanupSummaryReturnsEmptyArrayWhenNull(): void
	{
		$oService = new StaticDeletionPlan();
		$aResult = $oService->GetStaticDeletionPlan([]);

		$this->assertIsArray($aResult, 'Expected result to be an array when input is null.');
		$this->assertEmpty($aResult, 'Expected result to be empty array when input is null.');
	}

	public function testExecuteCleanup_DeleteOneObjPerClass()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRToUpdate_1
			DFRToRemoveLeaf_1 <- DFRRemovedCollateral_1
			DFRRemovedCollateral_1 <- DFRRemovedCollateralCascade_1
			DFRRemovedCollateral_1 <- DFRRemovedCollateralCascade_2
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$oService = new StaticDeletionPlan();
		$aRes = $oService->GetStaticDeletionPlan($aClasses);

		var_export($aRes);

		var_export($this->aIdByClass);
	}
}
