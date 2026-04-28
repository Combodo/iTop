<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Module\DataFeatureRemoval\Service;

use Combodo\iTop\DataFeatureRemoval\Entity\DataCleanupSummaryEntity;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException;
use Combodo\iTop\DataFeatureRemoval\Helper\ExecutionLimits;
use Combodo\iTop\DataFeatureRemoval\Service\DataCleanupService;
use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DeletionPlan;

/**
 * Unit tests for the DeletionPlanService cf the Combodo Data Feature Removal module.
 *
 * These tests cover:
 * - GetCleanupSummary method: handling null and empty input, and verifying summary output for various delete/update scenarios.
 * - ExecuteCleanup method: confirming that an exception is thrown when issues are detected in the deletion plan.
 *
 * Key aspects tested:
 * - Consistent singleton instance management.
 * - Accurate summary generation for deletion and update operations, including mode and issue reporting per class.
 * - Edge cases such as null, empty, and multiple classes.
 * - Proper exception handling when the deletion plan contains issues.
 *
 * The tests use PHPUnit, mocks for DeletionPlan and DeletionPlanService, and data providers to cover multiple scenarios.
 *
 * @see DataCleanupService
 * @see DataCleanupSummaryEntity
 * @see ItopDataTestCase
 */
class DataCleanupServiceTest extends ItopCustomDatamodelTestCase
{
	private ExecutionLimits&\PHPUnit\Framework\MockObject\MockObject $oExecutionLimits;

	public function GetDatamodelDeltaAbsPath(): string
	{
		return __DIR__.'/data_cleanup_delta.xml';
	}

	//--- GetCleanupSummary tests ---

	/**
	 * Tests that GetCleanupSummary returns an empty array when passed null as input.
	 */
	public function testGetDeletionPlanSummaryReturnsEmptyArrayWhenNull(): void
	{
		$oService = new DataCleanupService();
		$aResult = $oService->GetCleanupSummary(null);

		$this->assertIsArray($aResult, 'Expected result to be an array when input is null.');
		$this->assertEmpty($aResult, 'Expected result to be empty array when input is null.');
	}

	//--- ExecuteCleanup tests ---

	public function testExecuteDeletionPlan_DeleteOneObjPerClassWithoutLimit()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRToUpdate_1
			DFRToRemoveLeaf_1 <- DFRRemovedCollateral_1
			DFRRemovedCollateral_1 <- DFRRemovedCollateralCascade_1
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$oService = new DataCleanupService();
		$aRes = $oService->ExecuteCleanup($aClasses);
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
		$oService = new DataCleanupService();
		$aRes = $oService->ExecuteCleanup($aClasses);
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
		$oService = new DataCleanupService();
		$aRes = $oService->GetCleanupSummary($aClasses);
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
		$oService = new DataCleanupService();
		$oService->GetCleanupSummary($aClasses);
	}

	private function AssertSummaryEquals(array $expected, $actual, $sMessage = '')
	{
		$aExpected = [];
		foreach ($expected as $line) {
			$sClass = $line[0];
			$iUpdate = $line[1];
			$iDelete = $line[2];

			$oDeletionPlanSummaryEntity = new DataCleanupSummaryEntity($sClass);
			$oDeletionPlanSummaryEntity->iUpdateCount = $iUpdate;
			$oDeletionPlanSummaryEntity->iDeleteCount = $iDelete;
			$aExpected[$sClass] = $oDeletionPlanSummaryEntity;
		}
		$this->assertEquals($aExpected, $actual, $sMessage);
	}

	public static function ExecuteDeletionPlan_StopInProcessKeepDatabaseOk(): array
	{
		return [
			'Stop after  1' => [
				1,
				[
					['DFRToUpdate', 1, 0],
				],
			],
			'Stop after  2' => [
				2,
				[
					['DFRToUpdate', 1, 0],
					['DFRRemovedCollateralCascade', 0, 1],
				],
			],
			'Stop after  3' => [
				3,
				[
					['DFRToUpdate', 1, 0],
					['DFRRemovedCollateralCascade', 0, 1],
					['DFRRemovedCollateral', 0, 1],
				],
			],
			'Stop after  4' => [
				4,
				[
					['DFRToUpdate', 1, 0],
					['DFRRemovedCollateralCascade', 0, 1],
					['DFRRemovedCollateral', 0, 1],
					['DFRToRemoveLeaf', 0, 1],
				],
			],
			'Stop after  5' => [
				5,
				[
					['DFRToUpdate', 2, 0],
					['DFRRemovedCollateralCascade', 0, 1],
					['DFRRemovedCollateral', 0, 1],
					['DFRToRemoveLeaf', 0, 1],
				],
			],
		];
	}

	/**
	 * @dataProvider ExecuteDeletionPlan_StopInProcessKeepDatabaseOk
	 */
	public function testExecuteDeletionPlan_StopInProcessKeepDatabaseOk(int $iExecutionCount, array $aExpected): void
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
		$oDeletionPlaService = new DataCleanupService();
		$this->GivenExecutionLimits($iExecutionCount);
		$this->SetNonPublicProperty($oDeletionPlaService, 'oExecutionLimits', $this->oExecutionLimits);
		$aRes = $oDeletionPlaService->ExecuteCleanup($aClasses);
		$this->AssertSummaryEquals($aExpected, $aRes);
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

	private function GivenExecutionLimits(int $iStopAfterCallNumberReached): void
	{
		$matcher = $this->any();

		$this->oExecutionLimits =  $this->createMock(ExecutionLimits::class);
		$this->oExecutionLimits->expects($matcher)
			->method('ShouldStopExecution')->willReturnCallback(function () use ($matcher, $iStopAfterCallNumberReached) {
				$invocationCount = $matcher->getInvocationCount();

				return ($invocationCount >= $iStopAfterCallNumberReached);
			});
	}
}
