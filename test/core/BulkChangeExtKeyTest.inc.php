<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */

//created a dedicated test for external keys imports.
// this test may delete Person objects to cover all usecases
//DO NOT CHANGE USE_TRANSACTION value to avoid any DB loss!
class BulkChangeExtKeyTest extends ItopDataTestCase {
	const CREATE_TEST_ORG = true;
	private $sUid;

	protected function setUp() : void {
		parent::setUp();
		require_once(APPROOT.'core/bulkchange.class.inc.php');
	}

	protected function tearDown() : void{
		parent::tearDown();
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
				$this->createObject('Rack', ['name' => $sRackName, 'org_id' => $iOrgId]);
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
				$this->iTestOrgId => ['RackTest1', 'RackTest2', 'RackTest3', 'RackTest4']
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
				$this->iTestOrgId => ['RackTest1', 'RackTest2'],
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
				$this->iTestOrgId => ['RackTest1', 'RackTest2', 'RackTest3', 'RackTest4']
			]
		);

		$this->performBulkChangeTest(
			"No match for value 'UnexistingRack'",
			"Some possible 'Rack' value(s): RackTest1, RackTest2, RackTest3...",
			null,
			$bIsRackReconKey
		);
	}

	private function GetUid(){
		if (is_null($this->sUid)){
			$this->sUid = date('dmYHis');
		}

		return $this->sUid;
	}

	/**	 *
	 * @param $aInitData
	 * @param $aCsvData
	 * @param $aAttributes
	 * @param $aExtKeys
	 * @param $aReconcilKeys
	 */
	public function performBulkChangeTest($sExpectedDisplayableValue, $sExpectedDescription, $oOrg=null, $bIsRackReconKey=false) {
		if (is_null($oOrg)){
			$iOrgId = $this->iTestOrgId;
			$sOrgName = "UnitTestOrganization";
		}else{
			$iOrgId = $oOrg->GetKey();
			$sOrgName = $oOrg->Get('name');
		}

		$sUid = $this->GetUid();

		$aCsvData = [[$sOrgName, "UnexistingRack", "$sUid"]];
		$aAttributes = ["name" => 2];
		$aExtKeys = ["org_id" => ["name" => 0], "rack_id" => ["name" => 1]];
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
			"__ERRORS__" => "Object not found",
		];

		if ($bIsRackReconKey){
			$aReconcilKeys[] = "rack_id";
			$aResult[2] = $sUid;
			$aResult["__STATUS__"] = "Issue: failed to reconcile";
		}


		CMDBSource::Query('START TRANSACTION');
		//change value during the test
		$db_core_transactions_enabled=MetaModel::GetConfig()->Get('db_core_transactions_enabled');
		MetaModel::GetConfig()->Set('db_core_transactions_enabled',false);

		$this->debug("aCsvData:".json_encode($aCsvData[0]));
		$this->debug("aReconcilKeys:". var_export($aReconcilKeys));
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
		var_dump($aRes);
		foreach ($aRes as $aRow) {
			if (array_key_exists('__STATUS__', $aRow)) {
				$sStatus = $aRow['__STATUS__'];
				$this->debug("sStatus:".$sStatus->GetDescription());
				$this->assertEquals($aResult["__STATUS__"], $sStatus->GetDescription());
				foreach ($aRow as $i => $oCell) {
					if ($i != "finalclass" && $i != "__STATUS__" && $i != "__ERRORS__") {
						$this->debug("i:".$i);
						$this->debug('GetDisplayableValue:'.$oCell->GetDisplayableValue());
						$this->debug("aResult:".var_export($aResult[$i]));
						if ($oCell instanceof \CellStatus_SearchIssue){
							$this->assertEquals( $aResult[$i][0], $oCell->GetDisplayableValue(), "failure on " . get_class($oCell) . ' cell type');
							$this->assertEquals( $aResult[$i][1], $oCell->GetDescription(), "failure on " . get_class($oCell) . ' cell type');
						}
					} else if ($i === "__ERRORS__") {
						$sErrors = array_key_exists("__ERRORS__", $aResult) ? $aResult["__ERRORS__"] : "";
						$this->assertEquals( $sErrors, $oCell->GetDescription());
					}
				}
				$this->assertEquals( $aResult[0], $aRow[0]->GetDisplayableValue());
			}
		}
		MetaModel::GetConfig()->Set('db_core_transactions_enabled',$db_core_transactions_enabled);
	}
}
