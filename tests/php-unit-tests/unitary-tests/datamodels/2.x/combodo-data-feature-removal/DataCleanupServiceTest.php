<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Module\DataFeatureRemoval\Service;

use Combodo\iTop\DataFeatureRemoval\Entity\DataCleanupSummaryEntity;
use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalException;
use Combodo\iTop\DataFeatureRemoval\Service\DataCleanupService;
use Combodo\iTop\Service\Limits\ExecutionLimits;
use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use PHPUnit\Framework\MockObject\MockObject;

require_once __DIR__."/AbstractCleanup.php";

/**
 * Unit tests for the CleanupService cf the Combodo Data Feature Removal module.
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
 * The tests use PHPUnit, mocks for Cleanup and CleanupService, and data providers to cover multiple scenarios.
 *
 * @see DataCleanupService
 * @see DataCleanupSummaryEntity
 * @see ItopDataTestCase
 */
class DataCleanupServiceTest extends \AbstractCleanup
{
	private ExecutionLimits&MockObject $oExecutionLimits;

	//--- GetCleanupSummary tests ---

	/**
	 * Tests that GetCleanupSummary returns an empty array when passed null as input.
	 */
	public function testGetCleanupSummaryReturnsEmptyArrayWhenNull(): void
	{
		$oService = new DataCleanupService();
		$aResult = $oService->GetCleanupSummary(null);

		$this->assertIsArray($aResult, 'Expected result to be an array when input is null.');
		$this->assertEmpty($aResult, 'Expected result to be empty array when input is null.');
	}

	//--- ExecuteCleanup tests ---

	public function testExecuteCleanup_DeleteOneObjPerClassWithoutLimit()
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

	public function testExecuteCleanup_DeleteManyObjPerClassWithoutLimit()
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

	public function testGetCleanupSummary_DeleteManyObjPerClassWithoutLimit()
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

	public function testExecuteCleanup_ManualDeleteShouldFail()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRManual_1
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$this->expectException(DataFeatureRemovalException::class);
		$this->expectExceptionMessage('Deletion Plan cannot be executed due to issues');
		$oService = new DataCleanupService();
		$oService->ExecuteCleanup($aClasses);
	}

	public function testGetCleanupSummary_ManualDeleteShouldFail()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRManual_1
			DFRToRemoveLeaf_1 <- DFRManual_2
			DFRToRemoveLeaf_2 <- DFRManual_3
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$oService = new DataCleanupService();
		$aRes = $oService->GetCleanupSummary($aClasses);
		$aExpected = [
			['DFRManual', 0, 0, 3 ],
			['DFRToRemoveLeaf', 0, 2],
		];
		$this->AssertSummaryEquals($aExpected, $aRes);
	}

	private function AssertSummaryEquals(array $expected, $actual, $sMessage = '')
	{
		$aExpected = [];
		foreach ($expected as $line) {
			$sClass = $line[0];
			$iUpdate = $line[1];
			$iDelete = $line[2];
			$iIssue = $line[3] ?? 0;
			$iTotalUpdate = $line[4] ?? $iUpdate;
			$iTotalDelete = $line[5] ?? $iDelete;

			$oCleanupSummaryEntity = new DataCleanupSummaryEntity($sClass);
			$oCleanupSummaryEntity->iUpdateCount = $iUpdate;
			$oCleanupSummaryEntity->iDeleteCount = $iDelete;
			$oCleanupSummaryEntity->iIssueCount = $iIssue;
			$oCleanupSummaryEntity->iTotalUpdateCount = $iTotalUpdate;
			$oCleanupSummaryEntity->iTotalDeleteCount = $iTotalDelete;

			$aExpected[$sClass] = $oCleanupSummaryEntity;
		}
		$this->assertEquals($aExpected, $actual, $sMessage);
	}

	public static function ExecuteCleanup_StopInProcessKeepDatabaseOk(): array
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
	 * @dataProvider ExecuteCleanup_StopInProcessKeepDatabaseOk
	 */
	public function testExecuteCleanup_StopInProcessKeepDatabaseOk(int $iExecutionCount, array $aExpected): void
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
