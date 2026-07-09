<?php

namespace Combodo\iTop\Test\UnitTest\Module\DataFeatureRemoval;

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\DataFeatureRemoval\Entity\DataCleanupSummaryEntity;
use Combodo\iTop\DataFeatureRemoval\Service\StaticDeletionPlan;
use MetaModel;

require_once __DIR__."/AbstractCleanup.php";
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
		self::assertEquals(2, $oDeletionPlanItem->Count(), 'All entries of root table should be removed');
		self::assertEquals($this->aIdByClass['DFRToRemoveLeaf'], $oDeletionPlanItem->aIds, 'All the Ids found in root table should correspond to the one created');
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
		$sRemoteClass = 'DFRToUpdate';
		$oDeletionPlanItem = $oService->UpdateExtKeyNullable(
			$sRemoteClass,
			'extkey_id',
			implode(',', $this->aIdByClass['DFRToRemoveLeaf'])
		);

		// THEN
		self::assertEquals(3, $oDeletionPlanItem->Count(), 'All entries of root table should be removed');
		$sIdsToRemoveInTargetClass = implode(',', $this->aIdByClass['DFRToRemoveLeaf']);
		$aExpectedIds = $oService->GetRemoteIdsForExtKey($sRemoteClass, 'extkey_id', $sIdsToRemoveInTargetClass);

		self::assertEquals($aExpectedIds, $oDeletionPlanItem->aIds, 'All entries pointing on root class should be removed');
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

		self::assertArrayHasKey('DFRRemovedCollateralCascade', $aRes, 'The cleanup should descend to the cascaded classes');

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
		$oService = new StaticDeletionPlan();
		$aRes = $oService->GetStaticDeletionPlan($aClasses);

		self::assertEquals(1, $aRes['DFRManual']->oIssue->Count(), 'Issue should be found because of DEL_MANUAL deletion policy');
		self::assertEquals($this->aIdByClass['DFRManual'], $aRes['DFRManual']->oIssue->aIds, 'Issue should be correspond to the entries created');
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

		self::assertArrayHasKey('DFRToUpdate', $aRes, 'Class to update should be targeted');
		self::assertEquals(2, $aRes['DFRToUpdate']->oUpdate->Count(), 'Update should be counted only for removed pointed classes');
	}

	public function testGetCleanupSummaryWithIssues()
	{
		$this->GivenDFRObjectsInDB(<<<EOF
			create DFRToRemoveLeaf              (name = DFRToRemoveLeaf_1)
			create DFRToUpdate                  (name = DFRToUpdate_1, extkey_id = DFRToRemoveLeaf_1)
			create DFRRemovedCollateral         (name = DFRRemovedCollateral_1, extkey_id = DFRToRemoveLeaf_1)
			create DFRRemovedCollateralCascade  (name = DFRRemovedCollateralCascade_1, extkey_id = DFRRemovedCollateral_1)
			create DFRRemovedCollateralCascade  (name = DFRRemovedCollateralCascade_2, extkey_id = DFRRemovedCollateral_1)
			create DFRManual                    (name = DFRManual_1, extkey_id = DFRToRemoveLeaf_1)
EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$oService = new StaticDeletionPlan();
		$aRes = $oService->GetCleanupSummary($aClasses);

		$aExpected = [
			'DFRToRemoveLeaf'             => ['iDeleteCount' => 1],
			'DFRRemovedCollateral'        => ['iDeleteCount' => 1],
			'DFRRemovedCollateralCascade' => ['iDeleteCount' => 2],
			'DFRToUpdate'                 => ['iUpdateCount' => 1],
			'DFRManual'                   => ['iIssueCount' => 1],
		];

		$this->AssertSummaryEquals($aExpected, $aRes);
	}

	public function testCircularRefsShouldNotRunInfinitely()
	{
		$this->GivenDFRObjectsInDB(<<<EOF
			create DFRToRemoveLeaf      (name = DFRToRemoveLeaf_1)
			create DFRRemovedCollateral (name = DFRRemovedCollateral_1, extkey_id = DFRToRemoveLeaf_1)
			create DFRCircularRefs      (name = DFRCircularRefs_1, extkey_id = DFRRemovedCollateral_1)
			update DFRRemovedCollateral (name = DFRRemovedCollateral_1, circular_id = DFRCircularRefs_1)
EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$oService = new StaticDeletionPlan();
		$aRes = $oService->GetCleanupSummary($aClasses);

		$aExpected = [
			'DFRToRemoveLeaf'      => ['iDeleteCount' => 1],
			'DFRRemovedCollateral' => ['iDeleteCount' => 1],
			'DFRCircularRefs'      => ['iDeleteCount' => 1],
		];

		$this->AssertSummaryEquals($aExpected, $aRes);
	}

	public function testMultipleExtKeys()
	{
		$this->GivenDFRObjectsInDB(<<<EOF
			create DFRC1 (name = DFRC1_1)
			create DFRC1 (name = DFRC1_2)
			create DFRC1 (name = DFRC1_3)
			create DFRC1 (name = DFRC1_4)
			create DFRC1 (name = DFRC1_5)
		
			create DFRC3 (name = DFRC3_1, extkey1_id = DFRC1_1)
			create DFRC3 (name = DFRC3_2, extkey1_id = DFRC1_2)
			create DFRC3 (name = DFRC3_3, extkey1_id = DFRC1_3)
						
			create DFRC2 (name = DFRC2_1, extkey1_id = DFRC1_4, extkey2_id = DFRC1_5, extkey3_id = DFRC3_3)
			
			create DFRC4 (name = DFRC4_1, extkey1_id = DFRC2_1)
			
			update DFRC3 (name = DFRC3_2, extkey2_id = DFRC4_1)
		EOF);

		$aClasses = [ 'DFRC1' ];
		$oService = new StaticDeletionPlan();

		$aRes = $oService->GetCleanupSummary($aClasses);

		$aExpected = [
			'DFRC1' => ['iDeleteCount' => 5],
			'DFRC2' => ['iDeleteCount' => 1],
			'DFRC3' => ['iDeleteCount' => 3],
			'DFRC4' => ['iDeleteCount' => 1],
		];

		$this->AssertSummaryEquals($aExpected, $aRes);

	}

	private function AssertSummaryEquals(array $aExpected, array $aActual, string $sMessage = '')
	{
		foreach ($aExpected as $sClass => $aExpectedCounts) {
			$oExpectedCleanupSummaryEntity = new DataCleanupSummaryEntity($sClass);
			foreach ($aExpectedCounts as $sCount => $iExpectedValue) {
				$oExpectedCleanupSummaryEntity->$sCount = $iExpectedValue;
			}

			$this->assertEquals($oExpectedCleanupSummaryEntity, $aActual[$sClass], $sMessage);
		}
	}
}
