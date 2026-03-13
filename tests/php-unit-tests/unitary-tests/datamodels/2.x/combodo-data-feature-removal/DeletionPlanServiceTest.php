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
use DeletionPlan;

/**
 * Unit tests for the DeletionPlanService class in the Combodo Data Feature Removal module.
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
		$this->RequireOnceItopFile('env-production/combodo-data-feature-removal/vendor/autoload.php');
	}

	//--- GetDeletionPlanSummary tests ---

	/**
	 * Tests that GetDeletionPlanSummary returns an empty array when passed null as input.
	 */
	public function testGetDeletionPlanSummaryReturnsEmptyArrayWhenNull(): void
	{
		$oService = DeletionPlanService::GetInstance();
		$aResult = $oService->GetDeletionPlanSummary(null);

		$this->assertIsArray($aResult, 'Expected result to be an array when input is null.');
		$this->assertEmpty($aResult, 'Expected result to be empty array when input is null.');
	}

	/**
	 * Tests that GetDeletionPlanSummary returns an empty array when the input class list is empty.
	 */
	public function testGetDeletionPlanSummaryReturnsEmptyArrayWhenEmptyClasses(): void
	{
		$oMockService = $this->getMockBuilder(DeletionPlanService::class)
			->disableOriginalConstructor()
			->onlyMethods(['GetDeletionPlan'])
			->getMock();

		$oDeletionPlan = new DeletionPlan();
		$oMockService->method('GetDeletionPlan')->willReturn($oDeletionPlan);

		DeletionPlanService::SetInstance($oMockService);

		$aResult = $oMockService->GetDeletionPlanSummary([]);

		$this->assertIsArray($aResult, 'Expected result to be an array when input class list is empty.');
		$this->assertEmpty($aResult, 'Expected result to be empty array when input class list is empty.');
	}

	/**
	 * Tests GetDeletionPlanSummary for various delete/update scenarios using a data provider.
	 * Verifies summary output for each class matches expected values.
	 *
	 * @dataProvider GetDeletionPlanSummaryWithDeletesProvider
	 */
	public function testGetDeletionPlanSummaryWithDeletes(array $aToDelete, array $aToUpdate, array $aExpected): void
	{
		$oDeletionPlan = $this->createMock(DeletionPlan::class);
		$oDeletionPlan->method('ListDeletes')->willReturn($aToDelete);
		$oDeletionPlan->method('ListUpdates')->willReturn($aToUpdate);

		$oMockService = $this->getMockBuilder(DeletionPlanService::class)
			->disableOriginalConstructor()
			->onlyMethods(['GetDeletionPlan'])
			->getMock();

		$oMockService->method('GetDeletionPlan')->willReturn($oDeletionPlan);

		$aResult = $oMockService->GetDeletionPlanSummary(['SomeClass']);

		foreach ($aExpected as $sClass => $aExpectedValues) {
			$this->assertArrayHasKey(
				$sClass,
				$aResult,
				"Expected key '$sClass' to exist in summary."
			);
			$this->assertInstanceOf(
				DeletionPlanSummaryEntity::class,
				$aResult[$sClass],
				"Expected summary for '$sClass' to be instance of DeletionPlanSummaryEntity."
			);
			$this->assertEquals(
				$aExpectedValues['iDeleteCount'],
				$aResult[$sClass]->iDeleteCount,
				"Expected iDeleteCount for '$sClass' to be {$aExpectedValues['iDeleteCount']}, got {$aResult[$sClass]->iDeleteCount}."
			);
			$this->assertEquals(
				$aExpectedValues['iUpdateCount'],
				$aResult[$sClass]->iUpdateCount,
				"Expected iUpdateCount for '$sClass' to be {$aExpectedValues['iUpdateCount']}, got {$aResult[$sClass]->iUpdateCount}."
			);
			$this->assertEquals(
				$aExpectedValues['iMode'],
				$aResult[$sClass]->iMode,
				"Expected iMode for '$sClass' to be {$aExpectedValues['iMode']}, got {$aResult[$sClass]->iMode}."
			);
			$this->assertEquals(
				$aExpectedValues['sIssue'],
				$aResult[$sClass]->sIssue,
				"Expected sIssue for '$sClass' to be '{$aExpectedValues['sIssue']}', got '{$aResult[$sClass]->sIssue}'."
			);
		}
	}

	/**
	 * Provides multiple scenarios for testGetDeletionPlanSummaryWithDeletes, including deletes, updates, issues, and multiple classes.
	 *
	 * @return array
	 */
	public function GetDeletionPlanSummaryWithDeletesProvider(): array
	{
		return [
			'single class with deletes only' => [
				'aToDelete' => [
					'Server' => [
						1 => ['to_delete' => null, 'mode' => DEL_AUTO, 'requested_explicitely' => true],
						2 => ['to_delete' => null, 'mode' => DEL_SILENT, 'requested_explicitely' => false],
					],
				],
				'aToUpdate' => [],
				'aExpected' => [
					'Server' => [
						'iDeleteCount' => 2,
						'iUpdateCount' => 0,
						'iMode' => DEL_AUTO,
						'sIssue' => null,
					],
				],
			],
			'single class with deletes and issue' => [
				'aToDelete' => [
					'Server' => [
						1 => ['to_delete' => null, 'mode' => DEL_MANUAL, 'requested_explicitely' => false, 'issue' => 'Cannot delete'],
					],
				],
				'aToUpdate' => [],
				'aExpected' => [
					'Server' => [
						'iDeleteCount' => 1,
						'iUpdateCount' => 0,
						'iMode' => DEL_MANUAL,
						'sIssue' => 'Cannot delete',
					],
				],
			],
			'single class with updates only' => [
				'aToDelete' => [],
				'aToUpdate' => [
					'Person' => [
						10 => ['to_reset' => null, 'attributes' => ['org_id' => null]],
						11 => ['to_reset' => null, 'attributes' => ['org_id' => null]],
						12 => ['to_reset' => null, 'attributes' => ['org_id' => null]],
					],
				],
				'aExpected' => [
					'Person' => [
						'iDeleteCount' => 0,
						'iUpdateCount' => 3,
						'iMode' => 0,
						'sIssue' => null,
					],
				],
			],
			'class with both deletes and updates' => [
				'aToDelete' => [
					'Server' => [
						1 => ['to_delete' => null, 'mode' => DEL_AUTO, 'requested_explicitely' => true],
					],
				],
				'aToUpdate' => [
					'Server' => [
						5 => ['to_reset' => null, 'attributes' => ['org_id' => null]],
					],
				],
				'aExpected' => [
					'Server' => [
						'iDeleteCount' => 1,
						'iUpdateCount' => 1,
						'iMode' => DEL_AUTO,
						'sIssue' => null,
					],
				],
			],
			'multiple classes' => [
				'aToDelete' => [
					'Server' => [
						1 => ['to_delete' => null, 'mode' => DEL_AUTO, 'requested_explicitely' => true],
					],
					'NetworkDevice' => [
						3 => ['to_delete' => null, 'mode' => DEL_SILENT, 'requested_explicitely' => false],
						4 => ['to_delete' => null, 'mode' => DEL_SILENT, 'requested_explicitely' => false],
					],
				],
				'aToUpdate' => [
					'Person' => [
						10 => ['to_reset' => null, 'attributes' => ['org_id' => null]],
					],
				],
				'aExpected' => [
					'Server' => [
						'iDeleteCount' => 1,
						'iUpdateCount' => 0,
						'iMode' => DEL_AUTO,
						'sIssue' => null,
					],
					'NetworkDevice' => [
						'iDeleteCount' => 2,
						'iUpdateCount' => 0,
						'iMode' => DEL_SILENT,
						'sIssue' => null,
					],
					'Person' => [
						'iDeleteCount' => 0,
						'iUpdateCount' => 1,
						'iMode' => 0,
						'sIssue' => null,
					],
				],
			],
		];
	}

	//--- ExecuteDeletionPlan tests ---

	/**
	 * Tests that ExecuteDeletionPlan throws a DataFeatureRemovalException when the deletion plan contains issues.
	 */
	public function testExecuteDeletionPlanThrowsExceptionWhenIssuesExist(): void
	{
		$this->RequireOnceItopFile('env-production/combodo-data-feature-removal/src/Helper/DataFeatureRemovalException.php');

		$oDeletionPlan = $this->createMock(DeletionPlan::class);
		$oDeletionPlan->method('GetIssues')->willReturn(['Some issue']);
		$oDeletionPlan->method('ListDeletes')->willReturn([]);
		$oDeletionPlan->method('ListUpdates')->willReturn([]);

		$oMockService = $this->getMockBuilder(DeletionPlanService::class)
			->disableOriginalConstructor()
			->onlyMethods(['GetDeletionPlan'])
			->getMock();

		$oMockService->method('GetDeletionPlan')->willReturn($oDeletionPlan);

		$this->expectException(DataFeatureRemovalException::class);
		$this->expectExceptionMessage('Deletion Plan cannot be executed due to issues');

		$oMockService->ExecuteDeletionPlan(['SomeClass']);
	}

	public function testExecuteDeletionPlan_DeleteAllWithoutLimit()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRToUpdate_1
			DFRToRemoveLeaf_1 <- DFRRemovedCollateral_1
			DFRRemovedCollateral_1 <- DFRRemovedCollateralCascade_1
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$aRes = DeletionPlanService::GetInstance()->ExecuteDeletionPlan($aClasses);
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
		$aRes = DeletionPlanService::GetInstance()->ExecuteDeletionPlan($aClasses);
		$aExpected = [
			['DFRToUpdate', 3, 0 ],
			['DFRToRemoveLeaf', 0, 3 ],
			['DFRRemovedCollateral', 0, 3 ],
			['DFRRemovedCollateralCascade', 0, 3 ],
		];
		$this->AssertSummaryEquals($aExpected, $aRes);
	}

	public function testExecuteDeletionPlan_ManualDeleteShouldFail()
	{
		$this->GivenDFRTreeInDB(<<<EOF
			DFRToRemoveLeaf_1 <- DFRManual_1
		EOF);

		$aClasses = [ 'DFRToRemoveLeaf' ];
		$this->expectException(DataFeatureRemovalException::class);
		$this->expectExceptionMessage('Deletion Plan cannot be executed due to issues');
		DeletionPlanService::GetInstance()->ExecuteDeletionPlan($aClasses);
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
		$aRes = DeletionPlanService::GetInstance()->ExecuteDeletionPlan($aClasses, 0, 3);
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
		$aRes = DeletionPlanService::GetInstance()->ExecuteDeletionPlan($aClasses, 0, 8);
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

		$aRes = DeletionPlanService::GetInstance()->ExecuteDeletionPlan($aClasses, 0, 5);
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
