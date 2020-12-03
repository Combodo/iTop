<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use CMDBSource;
use MetaModel;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class BulkChangeTest extends ItopDataTestCase {
	const CREATE_TEST_ORG = true;

	protected function setUp() {
		parent::setUp();
		require_once(APPROOT.'core/coreexception.class.inc.php');
		require_once(APPROOT.'core/bulkchange.class.inc.php');

	}

	//bug 2888: csv import / data synchro issue with password validation
	public function testPasswordBulkChangeIssue() {
		/** @var Personn $oPerson */
		$oPerson = $this->createObject('Person', array(
			'first_name' => 'isaac',
			'name' => 'asimov',
			'email' => 'isaac.asimov@fundation.org',
			'org_id' => $this->getTestOrgId(),
		));

		$aData = array(
			array(
				$oPerson->Get("first_name"),
				$oPerson->Get("name"),
				$oPerson->Get("email"),
				"EN US",
				"iasimov",
				"harryseldon",
				"profileid->name:Administrator",
			),
		);
		$aAttributes = array("language" => 3, "login" => 4, "password" => 5, "profile_list" => 6);
		$aExtKeys = array(
			"contactid" =>
				array("first_name" => 0, "name" => 1, "email" => 2),
		);
		$oBulk = new \BulkChange(
			"UserLocal",
			$aData,
			$aAttributes,
			$aExtKeys,
			array("login"),
			null,
			null,
			"Y-m-d H:i:s", // date format
			true // localize
		);

		$oChange = \CMDBObject::GetCurrentChange();
		$aRes = $oBulk->Process($oChange);
		static::assertNotNull($aRes);

		foreach ($aRes as $aRow) {
			if (array_key_exists('__STATUS__', $aRow)) {
				$sStatus = $aRow['__STATUS__'];
				$this->assertFalse(strstr($sStatus->GetDescription(), "CoreCannotSaveObjectException"),
					"CSVimport/Datasynchro: Password validation failed with: ".$sStatus->GetDescription());
			}
		}
	}


	/**
	 * test $oBulk->Process with server 1 from demo datas
	 * @dataProvider BulkChangeProvider
	 *
	 * @param $aData
	 * @param $aAttributes
	 * @param $aExtKeys
	 * @param $aReconcilKeys
	 */
	public function testBulkChangeIssue($aData, $aAttributes, $aExtKeys, $aReconcilKeys, $aResult) {
		$this->debug("aReconcilKeys:".$aReconcilKeys[0]);
		$oBulk = new \BulkChange(
			"Server",
			$aData,
			$aAttributes,
			$aExtKeys,
			$aReconcilKeys,
			null,
			null,
			"Y-m-d H:i:s", // date format
			true // localize
		);

		$oChange = \CMDBObject::GetCurrentChange();
		$aRes = $oBulk->Process($oChange);
		static::assertNotNull($aRes);

		foreach ($aRes as $aRow) {
			if (array_key_exists('__STATUS__', $aRow)) {
				$sStatus = $aRow['__STATUS__'];
				//$this->debug("sStatus:".$sStatus->GetDescription());
				$this->assertEquals($sStatus->GetDescription(), $aResult["__STATUS__"]);
				foreach ($aRow as $i => $oCell) {
					if ($i != "finalclass" && $i != "__STATUS__") {
						$this->debug("i:".$i);
						$this->debug('GetDisplayableValue:'.$oCell->GetDisplayableValue());
						$this->debug("aResult:".$aResult[$i]);
						$this->assertEquals($oCell->GetDisplayableValue(), $aResult[$i]);
					}
				}
			}
		}
	}

	public function BulkChangeProvider() {
		return [
			"Case 3, 5 et 8 : unchanged" => [
				[["Demo", "Server1", "1", "production", ""]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[0 => "Demo", "org_id" => "3", 1 => "Server1", 2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "unchanged"],
			],
			"Case 9 : wrong date format" => [
				[["Demo", "Server1", "1", "production", "date"]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[ 0 => "Demo", "org_id" => "n/a", 1 => "Server1", 2 => "1", 3 => "production", 4 => "date", "id" => 1, "__STATUS__" => "Issue: wrong date format"],
			],
			"Case 1 : no match" => [
				[["Bad", "Server1", "1", "production", ""]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				["org_id" => "",1 => "Server1",2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "Issue: Unexpected attribute value(s)"],
			],
			"Case 10 : Missing mandatory value" => [
				[["", "Server1", "1", "production", ""]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[ "org_id" => "", 1 => "Server1", 2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "Issue: Unexpected attribute value(s)"],
			],
			"Case 6 : Unexpected value" => [
				[["Demo", "Server1", "1", "<svg onclick\"alert(1)\">", ""]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[0 => "Demo", "org_id" => "3", 1 => "Server1", 2 => "1", 3 => "&lt;svg onclick&quot;alert(1)&quot;&gt;", 4 => "", "id" => 1, "__STATUS__" => "Issue: Unexpected attribute value(s)"],
			],
		];
	}

	/**
	 * test $oBulk->Process with new server datas
	 * @dataProvider CSVImportProvider
	 *
	 * @param $aInitData
	 * @param $aCsvData
	 * @param $aAttributes
	 * @param $aExtKeys
	 * @param $aReconcilKeys
	 */
	public function testCas1BulkChangeIssue($aInitData, $aCsvData, $aAttributes, $aExtKeys, $aReconcilKeys, $aResult) {
		CMDBSource::Query('START TRANSACTION');
		//change value during the test
		$db_core_transactions_enabled=MetaModel::GetConfig()->Get('db_core_transactions_enabled');
		MetaModel::GetConfig()->Set('db_core_transactions_enabled',false);
		/** @var Server $oServer */
		$oServer = $this->createObject('Server', array(
			'name' => $aInitData[1],
			'status' => $aInitData[2],
			'org_id' => $aInitData[0],
			'purchase_date' => $aInitData[3],
		));
		$aCsvData[0][2]=$oServer->GetKey();
		$aResult[2]=$oServer->GetKey();
		$aResult["id"]=$oServer->GetKey();
		$this->debug("oServer->GetKey():".$oServer->GetKey());
		$this->debug("aCsvData:".json_encode($aCsvData[0]));
		$this->debug("aReconcilKeys:".$aReconcilKeys[0]);
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
				$this->debug("sStatus:".$sStatus->GetDescription());
				$this->assertEquals($sStatus->GetDescription(), $aResult["__STATUS__"]);
				foreach ($aRow as $i => $oCell) {
					if ($i != "finalclass" && $i != "__STATUS__") {
						$this->debug("i:".$i);
						$this->debug('GetDisplayableValue:'.$oCell->GetDisplayableValue());
						$this->debug("aResult:".$aResult[$i]);
						$this->assertEquals( $aResult[$i], $oCell->GetDisplayableValue());
					}
				}
				$this->assertEquals( $aResult[0], $aRow[0]->GetDisplayableValue());
			}
		}
		CMDBSource::Query('ROLLBACK');
		MetaModel::GetConfig()->Set('db_core_transactions_enabled',$db_core_transactions_enabled);
	}

	public function CSVImportProvider() {
		return [
			"Case 6 - 1 : Unexpected value" => [
				["1", "ServerTest", "production", ""],
				[["Demo", "ServerTest", "key", "BadValue", ""]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[0 => "Demo", "org_id" => "3", 1 => "ServerTest", 2 => "1", 3 => "BadValue", 4 => "", "id" => 1, "__STATUS__" => "Issue: Unexpected attribute value(s)"],
			],
			"Case 6 - 2 : Unexpected value" => [
				["1", "ServerTest", "production", ""],
				[["Demo", "ServerTest", "key", "<svg onclick\"alert(1)\">", ""]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[0 => "Demo", "org_id" => "3", 1 => "ServerTest", 2 => "1", 3 => "&lt;svg onclick&quot;alert(1)&quot;&gt;", 4 => "", "id" => 1, "__STATUS__" => "Issue: Unexpected attribute value(s)"],
			],
			"Case 8  : unchanged name" => [
				["1", "<svg onclick\"alert(1)\">", "production", ""],
				[["Demo", "<svg onclick\"alert(1)\">", "key", "production", ""]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[0 => "Demo", "org_id" => "3", 1 => "&lt;svg onclick&quot;alert(1)&quot;&gt;", 2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "updated 1 cols"],
			],
			"Case 3, 5 et 8 : unchanged 2" => [
				["1", "ServerTest", "production", ""],
				[["Demo", "ServerTest", "1", "production", ""]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[0 => "Demo", "org_id" => "3", 1 => "ServerTest", 2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "updated 1 cols"],
			],
			"Case 9 - 1: wrong date format" => [
				["1", "ServerTest", "production", ""],
				[["Demo", "ServerTest", "1", "production", "date"]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[ 0 => "Demo", "org_id" => "n/a", 1 => "ServerTest", 2 => "1", 3 => "production", 4 => "date", "id" => 1, "__STATUS__" => "Issue: wrong date format"],
			],
			"Case 9 - 2: wrong date format" => [
				["1", "ServerTest", "production", ""],
				[["Demo", "ServerTest", "1", "production", "<svg onclick\"alert(1)\">"]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[ 0 => "Demo", "org_id" => "n/a", 1 => "ServerTest", 2 => "1", 3 => "production", 4 => "&lt;svg onclick&quot;alert(1)&quot;&gt;", "id" => 1, "__STATUS__" => "Issue: wrong date format"],
			],
			"Case 1 - 1 : no match" => [
				["1", "ServerTest", "production", ""],
				[["Bad", "ServerTest", "1", "production", ""]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[ 0 => "Bad", "org_id" => "",1 => "ServerTest",2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "Issue: Unexpected attribute value(s)"],
			],
			"Case 1 - 2 : no match" => [
				["1", "ServerTest", "production", ""],
				[["<svg fonclick\"alert(1)\">", "ServerTest", "1", "production", ""]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[ 0 => "&lt;svg fonclick&quot;alert(1)&quot;&gt;", "org_id" => "",1 => "ServerTest",2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "Issue: Unexpected attribute value(s)"],
			],
			"Case 10 : Missing mandatory value" => [
				["1", "ServerTest", "production", ""],
				[["", "ServerTest", "1", "production", ""]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[ 0 => "",  "org_id" => "", 1 => "ServerTest", 2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "Issue: Unexpected attribute value(s)"],
			],

			"Case 0 : Date format" => [
				["1", "ServerTest", "production", "2020-02-01"],
				[["Demo", "ServerTest", "1", "production", "2020-20-03"]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[ 0 => "Demo", "org_id" => "n/a", 1 => "ServerTest", 2 => "1", 3 => "production", 4 => "2020-20-03", "id" => 1, "__STATUS__" => "Issue: wrong date format"],
			],
		];
	}



	/**
	 * test $oBulk->Process with new server and new organization datas
	 * @dataProvider CSVImportProvider2
	 *
	 * @param $aInitData
	 * @param $aCsvData
	 * @param $aAttributes
	 * @param $aExtKeys
	 * @param $aReconcilKeys
	 */
	public function testCas2BulkChangeIssue($aInitData, $aCsvData, $aAttributes, $aExtKeys, $aReconcilKeys, $aResult) {
		CMDBSource::Query('START TRANSACTION');
		//change value during the test
		$db_core_transactions_enabled=MetaModel::GetConfig()->Get('db_core_transactions_enabled');
		MetaModel::GetConfig()->Set('db_core_transactions_enabled',false);
		/** @var Server $oServer */
		$oOrganisation = $this->createObject('Organization', array(
			'name' =>$aInitData[0]
		));
		$aResult["org_id"]=$oOrganisation->GetKey();
		$oServer = $this->createObject('Server', array(
			'name' => $aInitData[1],
			'status' => $aInitData[2],
			'org_id' => $oOrganisation->GetKey(),
			'purchase_date' => $aInitData[3],
		));
		$aCsvData[0][2]=$oServer->GetKey();
		$aResult[2]=$oServer->GetKey();
		$aResult["id"]=$oServer->GetKey();
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
		$oChange = \CMDBObject::GetCurrentChange();
		$aRes = $oBulk->Process($oChange);
		static::assertNotNull($aRes);
		foreach ($aRes as $aRow) {
			foreach ($aRow as $i => $oCell) {
				if ($i != "finalclass" && $i != "__STATUS__") {
					$this->debug("i:".$i);
					$this->debug('GetDisplayableValue:'.$oCell->GetDisplayableValue());
					$this->debug("aResult:".$aResult[$i]);
					$this->assertEquals($aResult[$i], $oCell->GetDisplayableValue());
				}
				elseif ($i == "__STATUS__") {
					$sStatus = $aRow['__STATUS__'];
					$this->assertEquals($aResult["__STATUS__"], $sStatus->GetDescription());
				}
			}
			$this->assertEquals($aResult[0], $aRow[0]->GetDisplayableValue());
		}
		CMDBSource::Query('ROLLBACK');
		MetaModel::GetConfig()->Set('db_core_transactions_enabled',$db_core_transactions_enabled);
	}

	public function CSVImportProvider2() {
		return [
			"Case 3 : unchanged name" => [
				["dodo","ServerYO", "production", ""],
				[["dodo", "ServerYO", "key", "production", ""]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[0 => "dodo", "org_id" => "3", 1 => "ServerYO", 2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "unchanged"],
			],
			"Case 3 bis : unchanged name" => [
				["<svg >","ServerYO", "production", ""],
				[["<svg >", "ServerYO", "key", "production", ""]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[0 => "&lt;svg &gt;", "org_id" => "3", 1 => "ServerYO", 2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "unchanged"],
			],
			"Case 3 ter : unchanged name" => [
				["<svg onclick\"alert(1)\" >","ServerYO", "production", ""],
				[["<svg onclick\"alert(1)\" >", "ServerYO", "key", "production", ""]],
				["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				["org_id" => ["name" => 0]],
				["id"],
				[0 => "&lt;svg onclick&quot;alert(1)&quot; &gt;", "org_id" => "3", 1 => "ServerYO", 2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "unchanged"],
			],
		];
	}

}