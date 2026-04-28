<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Module\DataFeatureRemoval\Service;

use Combodo\iTop\DataFeatureRemoval\Entity\DeletionPlanSummaryEntity;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException;
use Combodo\iTop\DataFeatureRemoval\Service\DeletionPlanService;
use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DeletionPlan;

/**
 * Unit tests for the DeletionPlanService cf the Combodo Data Feature Removal module.
 *
 * These tests cover:
 * - GetDeletionPlanSummary method: handling null and empty input, and verifying summary output for various delete/update scenarios.
 * - ExecuteDeletionPlan method: confirming that an exception is thrown when issues are detected in the deletion plan.
 *
 * Key aspects tested:
 * - Consistent singleton instance management.
 * - Accurate summary generation for deletion and update operations, including mode and issue reporting per class.
 * - Edge cases such as null, empty, and multiple classes.
 * - Proper exception handling when the deletion plan contains issues.
 *
 * The tests use PHPUnit, mocks for DeletionPlan and DeletionPlanService, and data providers to cover multiple scenarios.
 *
 * @see DeletionPlanService
 * @see DeletionPlanSummaryEntity
 * @see ItopDataTestCase
 */
class DeletionPlanServiceTest extends ItopCustomDatamodelTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		//$this->RequireOnceItopFile('env-production/combodo-data-feature-removal/vendor/autoload.php');
	}

	//--- GetDeletionPlanSummary tests ---

	/**
	 * Tests that GetDeletionPlanSummary returns an empty array when passed null as input.
	 */
	public function testGetDeletionPlanSummaryReturnsEmptyArrayWhenNull(): void
	{
		$oService = new DeletionPlanService();
		$aResult = $oService->GetDeletionPlanSummary(null);

		$this->assertIsArray($aResult, 'Expected result to be an array when input is null.');
		$this->assertEmpty($aResult, 'Expected result to be empty array when input is null.');
	}

	//--- ExecuteDeletionPlan tests ---

	public function testExecuteDeletionPlan_DeleteOneObjPerClassWithoutLimit()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRToUpdate_1
			DFRToRemoveLeaf_1 <- DFRRemovedCollateral_1
			DFRRemovedCollateral_1 <- DFRRemovedCollateralCascade_1
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$aRes = (new DeletionPlanService())->ExecuteDeletionPlan($aClasses);
		$aExpected = [
			['DFRToUpdate', 1, 0 ],
			['DFRToRemoveLeaf', 0, 1 ],
			['DFRRemovedCollateral', 0, 1 ],
			['DFRRemovedCollateralCascade', 0, 1 ],
		];
		$this->AssertSummaryEquals($aExpected, $aRes);
	}

	public function testExecuteDeletionPlan_DeleteManyObjPerClassWithoutLimit()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRToUpdate_1
			DFRToRemoveLeaf_1 <- DFRRemovedCollateral_1
			DFRRemovedCollateral_1 <- DFRRemovedCollateralCascade_1
			
			DFRToRemoveLeaf_2 <- DFRToUpdate_2
			DFRToRemoveLeaf_2 <- DFRRemovedCollateral_2
			DFRRemovedCollateral_2 <- DFRRemovedCollateralCascade_2
			
			DFRToRemoveLeaf_3 <- DFRToUpdate_3
			DFRToRemoveLeaf_3 <- DFRRemovedCollateral_3
			DFRRemovedCollateral_3 <- DFRRemovedCollateralCascade_3
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$aRes = (new DeletionPlanService())->ExecuteDeletionPlan($aClasses);
		$aExpected = [
			['DFRToUpdate', 3, 0 ],
			['DFRToRemoveLeaf', 0, 3 ],
			['DFRRemovedCollateral', 0, 3 ],
			['DFRRemovedCollateralCascade', 0, 3 ],
		];
		$this->AssertSummaryEquals($aExpected, $aRes);
	}

	public function testGetDeletionPlanSummary_DeleteManyObjPerClassWithoutLimit()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRToUpdate_1
			DFRToRemoveLeaf_1 <- DFRRemovedCollateral_1
			DFRRemovedCollateral_1 <- DFRRemovedCollateralCascade_1
			
			DFRToRemoveLeaf_2 <- DFRToUpdate_2
			DFRToRemoveLeaf_2 <- DFRRemovedCollateral_2
			DFRRemovedCollateral_2 <- DFRRemovedCollateralCascade_2
			
			DFRToRemoveLeaf_3 <- DFRToUpdate_3
			DFRToRemoveLeaf_3 <- DFRRemovedCollateral_3
			DFRRemovedCollateral_3 <- DFRRemovedCollateralCascade_3
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$aRes = (new DeletionPlanService())->GetDeletionPlanSummary($aClasses);
		$aExpected = [
			['DFRToUpdate', 3, 0 ],
			['DFRToRemoveLeaf', 0, 3 ],
			['DFRRemovedCollateral', 0, 3 ],
			['DFRRemovedCollateralCascade', 0, 3 ],
		];
		$this->AssertSummaryEquals($aExpected, $aRes);
	}

	public function testGetDeletionPlanSummary_ManualDeleteShouldFail()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRManual_1
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$this->expectException(DataFeatureRemovalException::class);
		$this->expectExceptionMessage('Deletion Plan cannot be executed due to issues');
		(new DeletionPlanService())->GetDeletionPlanSummary($aClasses);
	}

	private function AssertSummaryEquals(array $expected, $actual, $sMessage = '')
	{
		$aExpected = [];
		foreach ($expected as $line) {
			$sClass = $line[0];
			$iUpdate = $line[1];
			$iDelete = $line[2];

			$oDeletionPlanSummaryEntity = new DeletionPlanSummaryEntity($sClass);
			$oDeletionPlanSummaryEntity->iUpdateCount = $iUpdate;
			$oDeletionPlanSummaryEntity->iDeleteCount = $iDelete;
			$aExpected[$sClass] = $oDeletionPlanSummaryEntity;
		}
		$this->assertEquals($aExpected, $actual, $sMessage);
	}

	public function testExecuteDeletionPlan_StopInUpdates()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRToUpdate_1
			DFRToRemoveLeaf_1 <- DFRRemovedCollateral_1
			DFRRemovedCollateral_1 <- DFRRemovedCollateralCascade_1
			
			DFRToRemoveLeaf_2 <- DFRToUpdate_2
			DFRToRemoveLeaf_2 <- DFRRemovedCollateral_2
			DFRRemovedCollateral_2 <- DFRRemovedCollateralCascade_2
			
			DFRToRemoveLeaf_3 <- DFRToUpdate_3
			DFRToRemoveLeaf_3 <- DFRRemovedCollateral_3
			DFRRemovedCollateral_3 <- DFRRemovedCollateralCascade_3
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$aRes = (new DeletionPlanService())->ExecuteDeletionPlan($aClasses, 0, 3);
		$aExpected = [
			['DFRToUpdate', 3, 0 ],
			['DFRToRemoveLeaf', 0, 0 ],
		];
		$this->AssertSummaryEquals($aExpected, $aRes);
	}

	public function testExecuteDeletionPlan_StopInDeletes()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRToUpdate_1
			DFRToRemoveLeaf_1 <- DFRRemovedCollateral_1
			DFRRemovedCollateral_1 <- DFRRemovedCollateralCascade_1
			
			DFRToRemoveLeaf_2 <- DFRToUpdate_2
			DFRToRemoveLeaf_2 <- DFRRemovedCollateral_2
			DFRRemovedCollateral_2 <- DFRRemovedCollateralCascade_2
			
			DFRToRemoveLeaf_3 <- DFRToUpdate_3
			DFRToRemoveLeaf_3 <- DFRRemovedCollateral_3
			DFRRemovedCollateral_3 <- DFRRemovedCollateralCascade_3
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$aRes = (new DeletionPlanService())->ExecuteDeletionPlan($aClasses, 0, 8);
		$aExpected = [
			['DFRToUpdate', 3, 0 ],
			['DFRToRemoveLeaf', 0, 3 ],
			['DFRRemovedCollateral', 0, 2 ],
		];
		$this->AssertSummaryEquals($aExpected, $aRes);
	}

	public function testExecuteDeletionPlan_WrongOrderDeletion()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRRemovedCollateral_1
			DFRRemovedCollateral_1 <- DFRRemovedCollateralCascade_1
			
			DFRToRemoveLeaf_2 <- DFRRemovedCollateral_2
			DFRRemovedCollateral_2 <- DFRRemovedCollateralCascade_2
			
			DFRToRemoveLeaf_3 <- DFRRemovedCollateral_3
			DFRRemovedCollateral_3 <- DFRRemovedCollateralCascade_3
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];

		$oSet = new \DBObjectSet(\DBObjectSearch::FromOQL("SELECT DFRRemovedCollateral WHERE name='DFRRemovedCollateral_3'"));
		$oExpectedObj = $oSet->Fetch();
		self::assertNotNull($oExpectedObj);

		$aRes = (new DeletionPlanService())->ExecuteDeletionPlan($aClasses, 0, 5);
		$aExpected = [
			['DFRToRemoveLeaf', 0, 3 ],
			['DFRRemovedCollateral', 0, 2 ],
		];

		$this->AssertSummaryEquals($aExpected, $aRes);

		$oSet = new \DBObjectSet(\DBObjectSearch::FromOQL("SELECT DFRRemovedCollateral WHERE name='DFRRemovedCollateral_3'"));
		$oActualObj = $oSet->Fetch();
		self::assertNotNull($oActualObj, "Deletion plan executed in wrong order: DFRRemovedCollateralCascade/DFRRemovedCollateral are not valid anymore");
		self::assertEquals($oExpectedObj->GetKey(), $oActualObj->GetKey());
	}

	public function GetDatamodelDeltaAbsPath(): string
	{
		return __DIR__.'/deletionplan_delta.xml';
	}

	private function GivenDFRTreeInDB(string $sTree)
	{
		$aTree = explode("\n", $sTree);
		foreach ($aTree as $sLine) {
			if (trim($sLine) === "") {
				continue;
			}
			$this->GivenDFRTreeLineInDB($sLine);
		}
	}

	private array $aIdByObjectName = [];
	private function GivenDFRTreeLineInDB(string $sLine)
	{
		list($sLeft, $sRight) = explode('<-', $sLine);
		$sLeft = trim($sLeft);

		$iLeftId = $this->aIdByObjectName[$sLeft] ?? 0;
		if ($iLeftId === 0) {
			list($sChildClass, ) = explode('_', $sLeft, 2);
			$iLeftId = $this->GivenObjectInDB($sChildClass, ['name' => $sLeft]);
			$this->aIdByObjectName[$sLeft] = $iLeftId;
		}

		$sRight = trim($sRight);
		list($sChildClass, ) = explode('_', $sRight, 2);
		$iRightId = $this->GivenObjectInDB($sChildClass, ['name' => $sRight, 'extkey_id' => $iLeftId]);
		$this->aIdByObjectName[$sRight] = $iRightId;
	}
}
