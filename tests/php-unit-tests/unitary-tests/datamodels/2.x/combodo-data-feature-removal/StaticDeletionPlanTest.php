<?php

namespace Combodo\iTop\Test\UnitTest\Module\DataFeatureRemoval;

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException;
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
		$oDeletionPlanItem = $oService->GetInitialClassDeletionPlan('DFRToRemoveLeaf');
		self::assertEquals(2, $oDeletionPlanItem->Count());
		self::assertEquals($this->aIdByClass['DFRToRemoveLeaf'], $oDeletionPlanItem->aIds);
		$sTable = MetaModel::DBGetTable('DFRToRemoveLeaf');
		$sExpectedSQL = "DELETE FROM $sTable";
		self::assertEquals($sExpectedSQL, $oDeletionPlanItem->aQueries[0]);
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
		$oDeletionPlanItem = $oService->UpdateExtKeyNullable(
			$sRemoteTable,
			'extkey_id',
			implode(',', $this->aIdByClass['DFRToRemoveLeaf'])
		);
		$sUpdateSQL = $oDeletionPlanItem->aQueries['extkey_id'];

		// THEN
		$sExpectedSQLEnd = " IN (".implode(',', $this->aIdByClass['DFRToRemoveLeaf']).")";
		self::assertStringEndsWith($sExpectedSQLEnd, $sUpdateSQL);

		self::assertEquals(3, $oDeletionPlanItem->Count());
		$sIdsToRemoveInTargetClass = implode(',', $this->aIdByClass['DFRToRemoveLeaf']);
		$aExpectedIds = $oService->GetRemoteIdsForExtKey($sRemoteTable, 'extkey_id', $sIdsToRemoveInTargetClass);

		self::assertEquals($aExpectedIds, $oDeletionPlanItem->aIds);

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

	public function testGetStaticDeletionPlan_DeleteObjRecursively()
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

		self::assertArrayHasKey('DFRRemovedCollateralCascade', $aRes);

		//		echo json_encode($aRes, JSON_PRETTY_PRINT)."\n";
		//		echo json_encode($this->aIdByClass, JSON_PRETTY_PRINT);
	}

	public function testGetStaticDeletionPlan_IssuesArePresent()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRToUpdate_1
			DFRToRemoveLeaf_1 <- DFRRemovedCollateral_1
			DFRRemovedCollateral_1 <- DFRRemovedCollateralCascade_1
			DFRRemovedCollateral_1 <- DFRRemovedCollateralCascade_2
			DFRToRemoveLeaf_1 <- DFRManual_1
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		//		$this->expectException(DataFeatureRemovalException::class);
		//		$this->expectExceptionMessage('Deletion Plan cannot be executed due to issues');
		$oService = new StaticDeletionPlan();
		$aRes = $oService->GetStaticDeletionPlan($aClasses);

		self::assertEquals(1, $aRes['DFRManual']->oIssue->Count());
		self::assertEquals($this->aIdByClass['DFRManual'], $aRes['DFRManual']->oIssue->aIds);

		//		echo json_encode($aRes, JSON_PRETTY_PRINT)."\n";
		//		echo json_encode($this->aIdByClass, JSON_PRETTY_PRINT);

	}

	public function testGetStaticDeletionPlan_UpdateMultipleExtKeys()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRToUpdate_1 (extkey_id)
			DFRToRemoveLeaf_2 <- DFRToUpdate_2 (extkey2_id)
			DFRLeafNotToRemove_1 <- DFRToUpdate_3 (extkey_id)
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$oService = new StaticDeletionPlan();
		$aRes = $oService->GetStaticDeletionPlan($aClasses);

		self::assertArrayHasKey('DFRToUpdate', $aRes);

		echo json_encode($aRes, JSON_PRETTY_PRINT)."\n";
		echo json_encode($this->aIdByClass, JSON_PRETTY_PRINT);
	}

	public function testGetCleanupSummary()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRToUpdate_1
			DFRToRemoveLeaf_1 <- DFRRemovedCollateral_1
			DFRRemovedCollateral_1 <- DFRRemovedCollateralCascade_1
			DFRRemovedCollateral_1 <- DFRRemovedCollateralCascade_2
			DFRToRemoveLeaf_1 <- DFRManual_1
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$oService = new StaticDeletionPlan();
		$aRes = $oService->GetCleanupSummary($aClasses);

		echo json_encode($aRes, JSON_PRETTY_PRINT)."\n";
		echo json_encode($this->aIdByClass, JSON_PRETTY_PRINT);

		self::assertEquals(1, $aRes['DFRManual']->iIssueCount);

	}

}
