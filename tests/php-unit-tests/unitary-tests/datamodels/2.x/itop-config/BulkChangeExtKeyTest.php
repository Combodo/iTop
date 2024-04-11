<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;


/**
 * created a dedicated test for external keys imports.
 *
 * Class BulkChangeExtKeyTest
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 */
class BulkChangeExtKeyTest extends ItopDataTestCase {
	const CREATE_TEST_ORG = true;

	/**
	 * this test may delete Person objects to cover all usecases
	 * DO NOT CHANGE USE_TRANSACTION value to avoid any DB loss!
	 */
	const USE_TRANSACTION = true;

	private $sUid;

	protected function setUp() : void {
		parent::setUp();
		require_once(APPROOT.'core/bulkchange.class.inc.php');
	}

	private function deleteAllRacks(){
		$oSearch = \DBSearch::FromOQL("SELECT Rack");
		$oSet = new \DBObjectSet($oSearch);
		$iCount = $oSet->Count();
		if ($iCount != 0){
			while ($oRack = $oSet->Fetch()){
				$oRack->DBDelete();
			}
		}
	}

	/**
	 * @dataProvider ReconciliationKeyProvider
	 */
	public function testExternalFieldIssueImportFail_NoObjectAtAll($bIsRackReconKey){
		$this->deleteAllRacks();

		$this->performBulkChangeTest(
			'There are no \'Rack\' objects',
			"",
			null,
			$bIsRackReconKey
		);
	}

	public function createRackObjects($aRackDict) {
		foreach ($aRackDict as $iOrgId => $aRackNames) {
			foreach ($aRackNames as $sRackName) {
				$this->createObject('Rack', ['name' => $sRackName, 'description' => "{$sRackName}Desc", 'org_id' => $iOrgId]);
			}
		}
	}

	private function createAnotherUserInAnotherOrg() {
		$oOrg2 = $this->CreateOrganization('UnitTestOrganization2');
		$oProfile = \MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'Configuration Manager'), true);

		$sUid = $this->GetUid();

		$oUserProfile = new \URP_UserProfile();
		$oUserProfile->Set('profileid', $oProfile->GetKey());
		$oUserProfile->Set('reason', 'UNIT Tests');
		$oSet = \DBObjectSet::FromObject($oUserProfile);

		$oPerson = $this->CreatePerson('666', $oOrg2->GetKey());
		$oUser = $this->createObject('UserLocal', array(
			'contactid' => $oPerson->GetKey(),
			'login' => $sUid,
			'password' => "ABCdef$sUid@12345",
			'language' => 'EN US',
			'profile_list' => $oSet,
		));

		$oAllowedOrgList = $oUser->Get('allowed_org_list');
		/** @var \URP_UserOrg $oUserOrg */
		$oUserOrg = \MetaModel::NewObject('URP_UserOrg', ['allowed_org_id' => $oOrg2->GetKey(),]);
		$oAllowedOrgList->AddItem($oUserOrg);
		$oUser->Set('allowed_org_list', $oAllowedOrgList);
		$oUser->DBWrite();
		return [$oOrg2, $oUser];
	}

	public function ReconciliationKeyProvider(){
		return [
			'rack_id NOT a reconcilication key' => [ false ],
			'rack_id reconcilication key' => [ true ],
		];
	}


	/**
	 * @dataProvider ReconciliationKeyProvider
	 */
	public function testExternalFieldIssueImportFail_NoObjectVisibleByCurrentUser($bIsRackReconKey){
		$this->deleteAllRacks();
		$this->createRackObjects(
			[
				$this->getTestOrgId() => ['RackTest1', 'RackTest2', 'RackTest3', 'RackTest4']
			]
		);

		list($oOrg2, $oUser) = $this->createAnotherUserInAnotherOrg();
		\UserRights::Login($oUser->Get('login'));

		$this->performBulkChangeTest(
			"There are no 'Rack' objects found with your current profile",
			"",
			$oOrg2,
			$bIsRackReconKey
		);
	}

	/**
	 * @dataProvider ReconciliationKeyProvider
	 */
	public function testExternalFieldIssueImportFail_SomeObjectVisibleByCurrentUser($bIsRackReconKey){
		$this->deleteAllRacks();
		list($oOrg2, $oUser) = $this->createAnotherUserInAnotherOrg();
		$this->createRackObjects(
			[
				$this->getTestOrgId() => ['RackTest1', 'RackTest2'],
				$oOrg2->GetKey() => ['RackTest3', 'RackTest4'],
			]
		);

		\UserRights::Login($oUser->Get('login'));

		$this->performBulkChangeTest(
			"There are some 'Rack' objects not visible with your current profile",
			"Some possible 'Rack' value(s): RackTest3, RackTest4",
			$oOrg2,
			$bIsRackReconKey
		);
	}

	/**
	 * @dataProvider ReconciliationKeyProvider
	 */
	public function testExternalFieldIssueImportFail_AllObjectsVisibleByCurrentUser($bIsRackReconKey){
		$this->deleteAllRacks();
		$this->createRackObjects(
			[
				$this->getTestOrgId() => ['RackTest1', 'RackTest2', 'RackTest3', 'RackTest4']
			]
		);

		$this->performBulkChangeTest(
			"No match for value 'UnexistingRack'",
			"Some possible 'Rack' value(s): RackTest1, RackTest2, RackTest3...",
			null,
			$bIsRackReconKey
		);
	}

	/**
	 * @dataProvider ReconciliationKeyProvider
	 */
	public function testExternalFieldIssueImportFail_AllObjectsVisibleByCurrentUser_AmbigousMatch($bIsRackReconKey){
		$this->deleteAllRacks();
		$this->createRackObjects(
			[
				$this->getTestOrgId() => ['UnexistingRack', 'UnexistingRack']
			]
		);

		$this->performBulkChangeTest(
			"Invalid value for attribute",
			"Ambiguous: found 2 objects",
			null,
			$bIsRackReconKey,
			null,
			null,
			null,
			'Found 2 matches'
		);
	}


	/**
	 * @dataProvider ReconciliationKeyProvider
	 */
	public function testExternalFieldIssueImportFail_AllObjectsVisibleByCurrentUser_FurtherExtKeyForRack($bIsRackReconKey){
		$this->deleteAllRacks();
		$this->createRackObjects(
			[
				$this->getTestOrgId() => ['RackTest1', 'RackTest2', 'RackTest3', 'RackTest4']
			]
		);

		$aCsvData = [["UnexistingRackDescription"]];
		$aExtKeys = ["org_id" => ["name" => 0], "rack_id" => ["name" => 1, "description" => 3]];

		$sSearchLinkUrl = 'UI.php?operation=search&filter='.\rawurlencode('%5B%22SELECT+%60Rack%60+FROM+Rack+AS+%60Rack%60+WHERE+%28%28%60Rack%60.%60name%60+%3D+%3Aname%29+AND+%28%60Rack%60.%60description%60+%3D+%3Adescription%29%29%22%2C%7B%22name%22%3A%22UnexistingRack%22%2C%22description%22%3A%22UnexistingRackDescription%22%7D%2C%5B%5D%5D');
		$this->performBulkChangeTest(
			"No match for value 'UnexistingRack UnexistingRackDescription'",
			"Some possible 'Rack' value(s): RackTest1 RackTest1Desc, RackTest2 RackTest2Desc, RackTest3 RackTest3Desc...",
			null,
			$bIsRackReconKey,
			$aCsvData,
			$aExtKeys,
			$sSearchLinkUrl
		);
	}


	private function GetUid(){
		if (is_null($this->sUid)){
			$this->sUid = uniqid('test');
		}

		return $this->sUid;
	}

	public function performBulkChangeTest($sExpectedDisplayableValue, $sExpectedDescription, $oOrg, $bIsRackReconKey,
		$aAdditionalCsvData=null, $aExtKeys=null, $sSearchLinkUrl=null, $sError="Object not found") {
		if ($sSearchLinkUrl===null){
			$sSearchLinkUrl = 'UI.php?operation=search&filter='.rawurlencode('%5B%22SELECT+%60Rack%60+FROM+Rack+AS+%60Rack%60+WHERE+%28%60Rack%60.%60name%60+%3D+%3Aname%29%22%2C%7B%22name%22%3A%22UnexistingRack%22%7D%2C%5B%5D%5D');
		}
		if (is_null($oOrg)){
			$iOrgId = $this->getTestOrgId();
			$sOrgName = "UnitTestOrganization";
		}else{
			$iOrgId = $oOrg->GetKey();
			$sOrgName = $oOrg->Get('name');
		}

		$sUid = $this->GetUid();

		$aCsvData = [[$sOrgName, "UnexistingRack", "$sUid"]];
		if ($aAdditionalCsvData !== null){
			foreach ($aAdditionalCsvData as $i => $aData){
				foreach ($aData as $sData){
					$aCsvData[$i][] = $sData;
				}
			}
		}
		$aAttributes = ["name" => 2];
		if ($aExtKeys == null){
			$aExtKeys = ["org_id" => ["name" => 0], "rack_id" => ["name" => 1]];
		}
		$aReconcilKeys = [ "name" ];

		$aResult = [
			0 => $sOrgName,
			"org_id" => $iOrgId,
			1 => "UnexistingRack",
			2 => "\"$sUid\"",
			"rack_id" => [
				$sExpectedDisplayableValue,
				$sExpectedDescription
			],
			"__STATUS__" => "Issue: Unexpected attribute value(s)",
			"__ERRORS__" => $sError,
		];

		if ($bIsRackReconKey){
			$aReconcilKeys[] = "rack_id";
			$aResult[2] = $sUid;
			$aResult["__STATUS__"] = "Issue: failed to reconcile";
		}


		CMDBSource::Query('START TRANSACTION');
		try {

			//change value during the test
			$db_core_transactions_enabled = MetaModel::GetConfig()->Get('db_core_transactions_enabled');
			MetaModel::GetConfig()->Set('db_core_transactions_enabled', false);

			$this->debug("aCsvData:" . json_encode($aCsvData[0]));
			$this->debug("aReconcilKeys:" . var_export($aReconcilKeys));
			$oBulk = new \BulkChange(
				"Server",
				$aCsvData,
				$aAttributes,
				$aExtKeys,
				$aReconcilKeys,
				null,
				null,
				"Y-m-d H:i:s", // date format
				true // localize
			);
			$this->debug("BulkChange:");
			$oChange = \CMDBObject::GetCurrentChange();
			$this->debug("GetCurrentChange:");
			$aRes = $oBulk->Process($oChange);
			$this->debug("Process:");
			static::assertNotNull($aRes);
			$this->debug("assertNotNull:");
			foreach ($aRes as $aRow) {
				if (array_key_exists('__STATUS__', $aRow)) {
					$sStatus = $aRow['__STATUS__'];
					$this->debug("sStatus:" . $sStatus->GetDescription());
					$this->assertEquals($aResult["__STATUS__"], $sStatus->GetDescription());
					foreach ($aRow as $i => $oCell) {
						if ($i != "finalclass" && $i != "__STATUS__" && $i != "__ERRORS__") {
							$this->debug("i:" . $i);
							if (array_key_exists($i, $aResult)) {
								$this->debug("aResult:" . var_export($aResult[$i]));
								if ($oCell instanceof \CellStatus_SearchIssue ||
									$oCell instanceof \CellStatus_Ambiguous) {
									$this->assertEquals($aResult[$i][0], $oCell->GetCLIValue(),
										"failure on " . get_class($oCell) . ' cell type');
									$this->assertEquals($sSearchLinkUrl, $oCell->GetSearchLinkUrl(),
										"failure on " . get_class($oCell) . ' cell type');
									$this->assertEquals($aResult[$i][1], $oCell->GetDescription(),
										"failure on " . get_class($oCell) . ' cell type');
								}
							}
						} else if ($i === "__ERRORS__") {
							$sErrors = array_key_exists("__ERRORS__", $aResult) ? $aResult["__ERRORS__"] : "";
							$this->assertEquals($sErrors, $oCell->GetDescription());
						}
					}
					$this->assertEquals($aResult[0], $aRow[0]->GetCLIValue());
				}
			}
			MetaModel::GetConfig()->Set('db_core_transactions_enabled', $db_core_transactions_enabled);
		} finally {
			CMDBSource::Query('ROLLBACK');
		}
	}
}
