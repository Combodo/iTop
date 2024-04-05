<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use BulkChange;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

class BulkChangeTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;

	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('core/bulkchange.class.inc.php');

	}

	//bug 2888: csv import / data synchro issue with password validation
	public function testPasswordBulkChangeIssue() {
		/** @var Person $oPerson */
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
		$oBulk = new BulkChange(
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

		$aRes = $oBulk->Process();
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
	 *
	 * @dataProvider bulkChangeWithoutInitDataProvider
	 *
	 * @param $aCSVData
	 * @param $aAttributes
	 * @param $aExtKeys
	 * @param $aReconcilKeys
	 */
	public function testBulkChangeWithoutInitData($aCSVData, $aAttributes, $aExtKeys, $aReconcilKeys, $aResult, array $aResultHTML = null) {
		$this->debug("aReconcilKeys:".$aReconcilKeys[0]);
		$oBulk = new BulkChange(
			"Server",
			$aCSVData,
			$aAttributes,
			$aExtKeys,
			$aReconcilKeys,
			null,
			null,
			"Y-m-d H:i:s", // date format
			true // localize
		);

		$aRes = $oBulk->Process();
		static::assertNotNull($aRes);

		foreach ($aRes as $aRow) {
			if (array_key_exists('__STATUS__', $aRow)) {
				$sStatus = $aRow['__STATUS__'];
				//$this->debug("sStatus:".$sStatus->GetDescription());
				$this->assertEquals($aResult["__STATUS__"], $sStatus->GetDescription());
				foreach ($aRow as $i => $oCell) {
					if ($i !== "finalclass" && $i !== "__STATUS__" && $i !== "__ERRORS__" && array_key_exists($i, $aResult)) {
						$this->debug("i:".$i);
						$this->debug('GetCLIValue:'.$oCell->GetCLIValue());
						$this->debug("aResult:".$aResult[$i]);
						$this->assertEquals($aResult[$i], $oCell->GetCLIValue());
						if (null !== $aResultHTML) {
							$this->assertEquals($aResultHTML[$i], $oCell->GetHTMLValue());
						}
					}
				}
			}
		}
	}

	public function bulkChangeWithoutInitDataProvider() {
		return [
			"Case 3, 5 et 8 : unchanged" => [
				"csvData" =>
					[["Demo", "Server1", "1", "production", ""]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconciliation Keys"=>
					["id"],
				"expectedResult"=>
					[0 => "Demo", "org_id" => "3", 1 => "Server1", 2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "unchanged"],
				"expectedResultHTML"=>
					[0 => "Demo", "org_id" => "3", 1 => "Server1", 2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "unchanged"],
			],
			"Case 9 : wrong date format" => [
				"csvData" =>
					[["Demo", "Server1", "1", "production", "<date"]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconciliation Keys"=>
					["id"],
				"expectedResult"=>
					[ 0 => "Demo", "org_id" => "n/a", 1 => "Server1", 2 => "1", 3 => "production", 4 => "'<date' is an invalid value", "id" => 1, "__STATUS__" => "Issue: wrong date format"],
				"expectedResultHTML"=>
					[ 0 => "Demo", "org_id" => "n/a", 1 => "Server1", 2 => "1", 3 => "production", 4 => "'&lt;date' is an invalid value", "id" => 1, "__STATUS__" => "Issue: wrong date format"],
				],
			"Case 1 : no match" => [
				"csvData" =>
					[["<Bad", "Server1", "1", "production", ""]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconciliation Keys"=>
					["id"],
				"expectedResult"=>
					[0 => '<Bad', "org_id" => "No match for value '<Bad'",1 => "Server1",2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "Issue: Unexpected attribute value(s)"],
				"expectedResultHTML"=>
					[0 => '&lt;Bad', "org_id" => "No match for value &apos;&lt;Bad&apos;",1 => "Server1",2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "Issue: Unexpected attribute value(s)"],
			],
			"Case 10 : Missing mandatory value" => [
				"csvData" =>
					[["", "Server1", "1", "production", ""]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconciliation Keys"=>
					["id"],
				"expectedResult"=>
					[0 => null, "org_id" => "Invalid value for attribute", 1 => "Server1", 2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "Issue: Unexpected attribute value(s)"],
				"expectedResultHTML"=>
					[0 => null, "org_id" => "Invalid value for attribute", 1 => "Server1", 2 => "1", 3 => "production", 4 => "", "id" => 1, "__STATUS__" => "Issue: Unexpected attribute value(s)"],
				],
			"Case 6 : Unexpected value" => [
				"csvData" =>
					[["Demo", "Server1", "1", "<svg onclick\"alert(1)\">", ""]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconciliation Keys"=>
					["id"],
			"expectedResult"=>
					[
						0 => "Demo",
						"org_id" => "3",
						1 => "Server1",
						2 => "1",
						3 => '\'<svg onclick"alert(1)">\' is an invalid value',
						4 => "",
						"id" => 1,
						"__STATUS__" => "Issue: Unexpected attribute value(s)",
						"__ERRORS__" => "Unexpected value for attribute 'status': no match found, check spelling"
					],
				"expectedResultHTML"=>
					[
						0 => "Demo",
						"org_id" => "3",
						1 => "Server1",
						2 => "1",
						3 => '\'&lt;svg onclick&quot;alert(1)&quot;&gt;\' is an invalid value',
						4 => "",
						"id" => 1,
						"__STATUS__" => "Issue: Unexpected attribute value(s)",
						"__ERRORS__" => "Unexpected value for attribute 'status': no match found, check spelling"
					],
			],
		];
	}

	/**
	 * test $oBulk->Process with new server datas
	 *
	 * @dataProvider bulkChangeWithExistingDataProvider
	 *
	 * @param $aInitData
	 * @param $aCsvData
	 * @param $aAttributes
	 * @param $aExtKeys
	 * @param $aReconcilKeys
	 */
	public function testBulkChangeWithExistingData($aInitData, $aCsvData, $aAttributes, $aExtKeys, $aReconcilKeys, $aResult, $aResultHTML= null) {
		//change value during the test
		$db_core_transactions_enabled=MetaModel::GetConfig()->Get('db_core_transactions_enabled');
		MetaModel::GetConfig()->Set('db_core_transactions_enabled',false);

		if (is_array($aInitData) && sizeof($aInitData) != 0) {
			/** @var Server $oServer */
			$oServer = $this->createObject('Server', array(
				'name' => $aInitData[1],
				'status' => $aInitData[2],
				'org_id' => $aInitData[0],
				'purchase_date' => $aInitData[3],
			));
			$aCsvData[0][2]=$oServer->GetKey();
			$aResult[2]=$oServer->GetKey();
			if ($aResult["id"]==="{Id of the server created by the test}") {
				$aResult["id"]=$oServer->GetKey();
				if ($aResultHTML!==null){
					$aResultHTML[2]=$oServer->GetKey();
					$aResultHTML["id"]=$oServer->GetKey();
				}
			}
			$this->debug("oServer->GetKey():".$oServer->GetKey());
		}
		$oBulk = new BulkChange(
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
		$aRes = $oBulk->Process();
		static::assertNotNull($aRes);
		foreach ($aRes as $aRow) {
			if (array_key_exists('__STATUS__', $aRow)) {
				$sStatus = $aRow['__STATUS__'];
				$this->debug("sStatus:".$sStatus->GetDescription());
				$this->assertEquals($aResult["__STATUS__"], $sStatus->GetDescription());
				foreach ($aRow as $i => $oCell) {
					if ($i !== "finalclass" && $i !== "__STATUS__" && $i !== "__ERRORS__" && array_key_exists($i, $aResult)) {
						$this->debug("i:".$i);
						$this->debug('GetCLIValue:'.$oCell->GetCLIValue());
						$this->debug("aResult:".$aResult[$i]);
						$this->assertEquals( $aResult[$i], $oCell->GetCLIValue(), "failure on " . get_class($oCell) . ' cell type for cell number ' . $i );
						if (null !== $aResultHTML) {
							$this->assertEquals($aResultHTML[$i], $oCell->GetHTMLValue(), "failure on " . get_class($oCell) . ' cell type for cell number ' . $i);
						}
					} else if ($i === "__ERRORS__") {
						$sErrors = array_key_exists("__ERRORS__", $aResult) ? $aResult["__ERRORS__"] : "";
						$this->assertEquals( $sErrors, $oCell->GetDescription());
					}
				}
				$this->assertEquals( $aResult[0], $aRow[0]->GetCLIValue());
				if (null !== $aResultHTML) {
					$this->assertEquals($aResultHTML[0], $aRow[0]->GetHTMLValue());
				}
			}
		}
		MetaModel::GetConfig()->Set('db_core_transactions_enabled',$db_core_transactions_enabled);
	}

	public function bulkChangeWithExistingDataProvider() {
		return [
			"Ambigous case on reconciliation" => [
					"initData"=>
						["1", "Server1", "production", ""],
					"csvData" =>
						[[">Demo", "Server1"]],
						 "attributes"=>
							 ["name" => 1],
						 "extKeys"=>
							 ["org_id" => ["name" => 0]],
						 "reconcilKeys"=>
							 ["name"],
						 "expectedResult"=>
							 [
								 0 => ">Demo",
								 "org_id" => "n/a",
								 1 => "Server1",
								"id" => "Invalid value for attribute",
								 "__STATUS__" => "Issue: ambiguous reconciliation",
								 "__ERRORS__" => "Allowed 'status' value(s): stock,implementation,production,obsolete",
							 ],
					"expectedResultHTML"=>
						[
							0 => "&gt;Demo",
							"org_id" => "n/a",
							1 => "Server1",
							"id" => "Invalid value for attribute",
							"__STATUS__" => "Issue: ambiguous reconciliation",
							"__ERRORS__" => "Allowed 'status' value(s): stock,implementation,production,obsolete",
						],
			],
			"Case 6 - 1 : Unexpected value (update)" => [
			    "initData"=>
					["1", ">ServerTest", "production", ""],
			     "csvData"=>
					[["Demo", ">ServerTest", "key - will be automatically overwritten by test", ">BadValue", ""]],
			     "attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["id"],
				"expectedResult"=>
					[
						"id" => "{Id of the server created by the test}",
						0 => "Demo",
						"org_id" => "3",
						1 => ">ServerTest",
						2 => "1",
						3 => "'>BadValue' is an invalid value",
						4 => "",
						"__STATUS__" => "Issue: Unexpected attribute value(s)",
						"__ERRORS__" => "Allowed 'status' value(s): stock,implementation,production,obsolete",
					],
			    "expectedResultHTML"=>
				    [
					    "id" => "{Id of the server created by the test}",
					    0 => "Demo",
					    "org_id" => "3",
					    1 => "&gt;ServerTest",
					    2 => "1",
					    3 => "'&gt;BadValue' is an invalid value",
					    4 => "",
					    "__STATUS__" => "Issue: Unexpected attribute value(s)",
					    "__ERRORS__" => "Allowed 'status' value(s): stock,implementation,production,obsolete",
				    ],

			],
			"Case 6 - 2 : Unexpected value (update)" => [
				"initData"=>
					["1", ">ServerTest", "production", ""],
				"csvData"=>
					[["Demo", ">ServerTest", "key", "<svg onclick\"alert(1)\">", ""]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["id"],
				"expectedResult"=>
					[
						"id" => "{Id of the server created by the test}",
						0 => "Demo",
						"org_id" => "3",
						1 => ">ServerTest",
						2 => "1",
						3 => '\'<svg onclick"alert(1)">\' is an invalid value',
						4 => "",
						"__STATUS__" => "Issue: Unexpected attribute value(s)",
						"__ERRORS__" => "Allowed 'status' value(s): stock,implementation,production,obsolete",
					],
				"expectedResultHTML"=>
					[
						"id" => "{Id of the server created by the test}",
						0 => "Demo",
						"org_id" => "3",
						1 => "&gt;ServerTest",
						2 => "1",
						3 => "'&lt;svg onclick&quot;alert(1)&quot;&gt;' is an invalid value",
						4 => "",
						"__STATUS__" => "Issue: Unexpected attribute value(s)",
						"__ERRORS__" => "Allowed 'status' value(s): stock,implementation,production,obsolete",
					],
			],
			"Case 6 - 3 : Unexpected value (creation)" => [
				"initData"=>
					[],
				"csvData"=>
					[["Demo", ">ServerTest", "<svg onclick\"alert(1)\">", ""]],
				"attributes"=>
					["name" => 1, "status" => 2, "purchase_date" => 3],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["name"],
				"expectedResult"=>
					[
						"id" => "{Id of the server created by the test}",
						0 => "Demo",
						"org_id" => "3",
						1 => "\">ServerTest\"",
						2 => '\'<svg onclick"alert(1)">\' is an invalid value',
						3 => "",
						"__STATUS__" => "Issue: Unexpected attribute value(s)",
						"__ERRORS__" => "Allowed 'status' value(s): stock,implementation,production,obsolete",
					],
				"expectedResultHTML"=>
					[
						"id" => "{Id of the server created by the test}",
						0 => "Demo",
						"org_id" => "3",
						1 => "&gt;ServerTest",
						2 => "'&lt;svg onclick&quot;alert(1)&quot;&gt;' is an invalid value",
						3 => "",
						"__STATUS__" => "Issue: Unexpected attribute value(s)",
						"__ERRORS__" => "Allowed 'status' value(s): stock,implementation,production,obsolete",
					],
			],
			"Case 8  : unchanged name" => [
				"initData"=>
					["1", "<svg onclick\"alert(1)\">", "production", ""],
				"csvData"=>
					[["Demo", "<svg onclick\"alert(1)\">", "key", "production", ""]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["id"],
				"expectedResult"=>
					[						"id" => "{Id of the server created by the test}",
					                         0 => "Demo", "org_id" => "3", 1 => '<svg onclick"alert(1)">', 2 => "1", 3 => "production", 4 => "", "__STATUS__" => "updated 1 cols"],
				"expectedResultHTML"=>
					[						"id" => "{Id of the server created by the test}",
					                         0 => "Demo", "org_id" => "3", 1 => '&lt;svg onclick&quot;alert(1)&quot;&gt;', 2 => "1", 3 => "production", 4 => "", "__STATUS__" => "updated 1 cols"],
			],
			"Case 3, 5 et 8 : unchanged 2" => [
				"initData"=>
					["1", ">ServerTest", "production", ""],
				"csvData"=>
					[["Demo", ">ServerTest", "1", "production", ""]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["id"],
				"expectedResult"=>
					[						"id" => "{Id of the server created by the test}",
					                         0 => "Demo", "org_id" => "3", 1 => ">ServerTest", 2 => "1", 3 => "production", 4 => "", "__STATUS__" => "updated 1 cols"],
				"expectedResultHTML"=>
					[						"id" => "{Id of the server created by the test}",
					                         0 => "Demo", "org_id" => "3", 1 => "&gt;ServerTest", 2 => "1", 3 => "production", 4 => "", "__STATUS__" => "updated 1 cols"],
			],
			"Case 9 - 1: wrong date format" => [
				"initData"=>
					["1", ">ServerTest", "production", ""],
				"csvData"=>
					[["Demo", ">ServerTest", "1", "production", "date>"]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["id"],
				"expectedResult"=>
					[						"id" => "{Id of the server created by the test}",
					                         0 => "Demo", "org_id" => "n/a", 1 => ">ServerTest", 2 => "1", 3 => "production", 4 => "'date>' is an invalid value", "__STATUS__" => "Issue: wrong date format"],
				"expectedResultHTML"=>
					[						"id" => "{Id of the server created by the test}",
					                         0 => "Demo", "org_id" => "n/a", 1 => "&gt;ServerTest", 2 => "1", 3 => "production", 4 => "'date&gt;' is an invalid value", "__STATUS__" => "Issue: wrong date format"],
				],
			"Case 9 - 2: wrong date format" => [
				"initData"=>
					["1", ">ServerTest", "production", ""],
				"csvData"=>
					[["Demo", ">ServerTest", "1", "production", "<svg onclick\"alert(1)\">"]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["id"],
				"expectedResult"=>
					[ 						"id" => "{Id of the server created by the test}",
					                         0 => "Demo", "org_id" => "n/a", 1 => ">ServerTest", 2 => "1", 3 => "production", 4 => '\'<svg onclick"alert(1)">\' is an invalid value',"__STATUS__" => "Issue: wrong date format"],
				"expectedResultHTML"=>
					[ 						"id" => "{Id of the server created by the test}",
					                         0 => "Demo", "org_id" => "n/a", 1 => "&gt;ServerTest", 2 => "1", 3 => "production", 4 => '\'&lt;svg onclick&quot;alert(1)&quot;&gt;\' is an invalid value',"__STATUS__" => "Issue: wrong date format"],

			],
			"Case 1 - 1 : no match" => [
				"initData"=>
					["1", ">ServerTest", "production", ""],
				"csvData"=>
					[[">Bad", ">ServerTest", "1", "production", ""]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["id"],
				"expectedResult"=>
					[ 						"id" => "{Id of the server created by the test}",
					                         0 => ">Bad", "org_id" => "No match for value '>Bad'",1 => ">ServerTest",2 => "1", 3 => "production", 4 => "", "__STATUS__" => "Issue: Unexpected attribute value(s)",
					                         "__ERRORS__" => "Object not found",
					],
				"expectedResultHTML"=>
					[ 						"id" => "{Id of the server created by the test}",
					                         0 => "&gt;Bad", "org_id" => "No match for value &apos;&gt;Bad&apos;",1 => "&gt;ServerTest",2 => "1", 3 => "production", 4 => "", "__STATUS__" => "Issue: Unexpected attribute value(s)",
					                         "__ERRORS__" => "Object not found",
					],
			],
			"Case 1 - 2 : no match" => [
				"initData"=>
					["1", ">ServerTest", "production", ""],
				"csvData"=>
					[["<svg onclick\"alert(1)\">", ">ServerTest", "1", "production", ""]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["id"],
				"expectedResult"=>
					[						"id" => "{Id of the server created by the test}",
					                         0 => '<svg onclick"alert(1)">', "org_id" => "No match for value '<svg onclick\"alert(1)\">'",1 => ">ServerTest",2 => "1", 3 => "production", 4 => "", "__STATUS__" => "Issue: Unexpected attribute value(s)",
					                         "__ERRORS__" => "Object not found",
					],
				"expectedResultHTML"=>
					[						"id" => "{Id of the server created by the test}",
					                         0 => '&lt;svg onclick&quot;alert(1)&quot;&gt;', "org_id" => "No match for value &apos;&lt;svg onclick&quot;alert(1)&quot;&gt;&apos;",1 => "&gt;ServerTest",2 => "1", 3 => "production", 4 => "", "__STATUS__" => "Issue: Unexpected attribute value(s)",
					                         "__ERRORS__" => "Object not found",
					],
			],
			"Case 10 : Missing mandatory value" => [
				"initData"=>
					["1", ">ServerTest", "production", ""],
				"csvData"=>
					[["", ">ServerTest", "1", "production", ""]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["id"],
				"expectedResult"=>
					[ 						"id" => "{Id of the server created by the test}",
					                         0 => "",  "org_id" => "Invalid value for attribute", 1 => ">ServerTest", 2 => "1", 3 => "production", 4 => "", "__STATUS__" => "Issue: Unexpected attribute value(s)",
					                         "__ERRORS__" => "Null not allowed",
					],
				"expectedResultHTML"=>
					[ 						"id" => "{Id of the server created by the test}",
					                         0 => "",  "org_id" => "Invalid value for attribute", 1 => "&gt;ServerTest", 2 => "1", 3 => "production", 4 => "", "__STATUS__" => "Issue: Unexpected attribute value(s)",
					                         "__ERRORS__" => "Null not allowed",
					],
			],
			"Case 0 : Date format ok but incorrect date" => [
				"initData"=>
					["1", ">ServerTest", "production", "2020-02-01"],
				"csvData"=>
					[["Demo", ">ServerTest", "1", "production", "2020-20-03"]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["id"],
				"expectedResult"=>
					[						"id" => "{Id of the server created by the test}",
					                         0 => "Demo", "org_id" => "n/a", 1 => ">ServerTest", 2 => "1", 3 => "production", 4 => "'2020-20-03' is an invalid value", "id" => 1, "__STATUS__" => "Issue: wrong date format"],
			],
		];
	}



	/**
	 * test $oBulk->Process with new server and new organization datas
	 *
	 * @dataProvider bulkChangeWithExistingDataAndSpecificOrgProvider
	 *
	 * @param $aInitData
	 * @param $aCsvData
	 * @param $aAttributes
	 * @param $aExtKeys
	 * @param $aReconcilKeys
	 */
	public function testBulkChangeWithExistingDataAndSpecificOrg($aInitData, $aCsvData, $aAttributes, $aExtKeys, $aReconcilKeys, $aResult, $aResultHTML = null) {
		//change value during the test
		$db_core_transactions_enabled=MetaModel::GetConfig()->Get('db_core_transactions_enabled');
		MetaModel::GetConfig()->Set('db_core_transactions_enabled',false);
		if (is_array($aInitData) && sizeof($aInitData) != 0) {
			/** @var Server $oServer */
			$oOrganisation = $this->createObject('Organization', array(
				'name' => $aInitData["orgName"]
			));
			if ($aResult["org_id"]==="{org id of the server created by the test}"){
				$aResult["org_id"] = $oOrganisation->GetKey();
				if ($aResultHTML!==null){
					$aResultHTML["org_id"]=$oOrganisation->GetKey();
				}
			}

			$oServer = $this->createObject('Server', array(
				'name' => $aInitData["serverName"],
				'status' => $aInitData["serverStatus"],
				'org_id' => $oOrganisation->GetKey(),
				'purchase_date' => $aInitData["serverPurchaseDate"],
			));
			$aCsvData[0][2]=$oServer->GetKey();
			$aResult[2]=$oServer->GetKey();
			if ($aResult["id"]==="{Id of the server created by the test}") {
				$aResult["id"]=$oServer->GetKey();
				if ($aResultHTML!==null){
					$aResultHTML[2]=$oServer->GetKey();
					$aResultHTML["id"]=$oServer->GetKey();
				}
			}
		}
		$oBulk = new BulkChange(
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
		$aRes = $oBulk->Process();
		static::assertNotNull($aRes);
		foreach ($aRes as $aRow) {
			foreach ($aRow as $i => $oCell) {
				if ($i !== "finalclass" && $i !== "__STATUS__" && $i !== "__ERRORS__" && array_key_exists($i, $aResult)) {
					$this->debug("i:".$i);
					$this->debug('GetCLIValue:'.$oCell->GetCLIValue());
					$this->debug("aResult:".$aResult[$i]);
					$this->assertEquals($aResult[$i], $oCell->GetCLIValue(), "$i cell is incorrect");
					if (null !== $aResultHTML) {
						$this->assertEquals($aResultHTML[$i], $oCell->GetHTMLValue());
					}
				} elseif ($i === "__STATUS__") {
					$sStatus = $aRow['__STATUS__'];
					$this->assertEquals($aResult["__STATUS__"], $sStatus->GetDescription());
				} else if ($i === "__ERRORS__") {
					$sErrors = array_key_exists("__ERRORS__", $aResult) ? $aResult["__ERRORS__"] : "";
					$this->assertEquals( $sErrors, $oCell->GetDescription());
				}
			}
			$this->assertEquals($aResult[0], $aRow[0]->GetCLIValue());
			if (null !== $aResultHTML) {
				$this->assertEquals($aResultHTML[0], $aRow[0]->GetHTMLValue());
			}
		}
		MetaModel::GetConfig()->Set('db_core_transactions_enabled',$db_core_transactions_enabled);
	}

	public function bulkChangeWithExistingDataAndSpecificOrgProvider() {
		return [
			"Ambigous case " => [
				"initData"=>
					["orgName" => "Demo", "serverName" => ">ServerYO", "serverStatus" => "production", "serverPurchaseDate" =>""],
				"csvData" =>
					[["Demo", ">Server1"]],
				"attributes"=>
					["name" => 1],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["org_id"],
				"expectedResult"=>
					[
						0 => "Demo",
						"org_id" => "Invalid value for attribute",
						1 => ">Server1",
						"id" => "Invalid value for attribute",
						"__STATUS__" => "Issue: failed to reconcile",
						"__ERRORS__" => "Allowed 'status' value(s): stock,implemfentation,production,obsolete",
					],
				"expectedResultHTML"=>
					[
						0 => "Demo",
						"org_id" => "Invalid value for attribute",
						1 => "&gt;Server1",
						"id" => "Invalid value for attribute",
						"__STATUS__" => "Issue: failed to reconcile",
						"__ERRORS__" => "Allowed 'status' value(s): stock,implemfentation,production,obsolete",
					],
			],
			"Case 3 : unchanged name" => [
				"initData"=>
					["orgName" => ">dodo", "serverName" => ">ServerYO", "serverStatus" => "production", "serverPurchaseDate" =>""],
				"csvData"=>
					[[">dodo", ">ServerYO", "key will be set by the test", "production", ""]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["id"],
				"expectedResult"=>
					[0 => ">dodo", "org_id" => "{org id of the server created by the test}", 1 => ">ServerYO", 2 => "1", 3 => "production", 4 => "", "id" => "{Id of the server created by the test}", "__STATUS__" => "unchanged"],
				"expectedResultHTML"=>
					[0 => "&gt;dodo", "org_id" => "{org id of the server created by the test}", 1 => "&gt;ServerYO", 2 => "1", 3 => "production", 4 => "", "id" => "{Id of the server created by the test}", "__STATUS__" => "unchanged"],
			],
			"Case 3 bis : unchanged name" => [
				"initData"=>
					["orgName" =>"<svg >", "serverName" => ">ServerYO",  "serverStatus" => "production", "serverPurchaseDate" =>""],
				"csvData"=>
					[["<svg >", ">ServerYO", "key", "production", ""]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["id"],
				"expectedResult"=>
					[0 => "<svg >", "org_id" => "{org id of the server created by the test}", 1 => ">ServerYO", 2 => "1", 3 => "production", 4 => "", "id" => "{Id of the server created by the test}", "__STATUS__" => "unchanged"],
				"expectedResultHTML"=>
					[0 => "&lt;svg &gt;", "org_id" => "{org id of the server created by the test}", 1 => "&gt;ServerYO", 2 => "1", 3 => "production", 4 => "", "id" => "{Id of the server created by the test}", "__STATUS__" => "unchanged"],
			],
			"Case 3 ter : unchanged name" => [
				"initData"=>
					["orgName" => "<svg onclick\"alert(1)\" >", "serverName" => ">ServerYO", "serverStatus" => "production", "serverPurchaseDate" =>""],
				"csvData"=>
					[["<svg onclick\"alert(1)\" >", ">ServerYO", "key", "production", ""]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["id"],
				"expectedResult"=>
					[0 => '<svg onclick"alert(1)" >', "org_id" => "{org id of the server created by the test}", 1 => ">ServerYO", 2 => "1", 3 => "production", 4 => "", "id" => "{Id of the server created by the test}", "__STATUS__" => "unchanged"],
				"expectedResultHTML"=>
					[0 => '&lt;svg onclick&quot;alert(1)&quot; &gt;', "org_id" => "{org id of the server created by the test}", 1 => "&gt;ServerYO", 2 => "1", 3 => "production", 4 => "", "id" => "{Id of the server created by the test}", "__STATUS__" => "unchanged"],
			],
			"Case reconciliation on external key" => [
				"initData"=>
					["orgName" => "<svg onclick\"alert(1)\" >", "serverName" => ">ServerYO", "serverStatus" => "production", "serverPurchaseDate" =>""],
				"csvData"=>
					[["<svg onclick\"alert(1)\" >", ">ServerYO", "key", "production", ""]],
				"attributes"=>
					["name" => 1, "id" => 2, "status" => 3, "purchase_date" => 4],
				"extKeys"=>
					["org_id" => ["name" => 0]],
				"reconcilKeys"=>
					["org_id", "name"],
				"expectedResult"=>
					[0 => '<svg onclick"alert(1)" >', "org_id" => "{org id of the server created by the test}", 1 => ">ServerYO", 2 => "1", 3 => "production", 4 => "", "id" => "{Id of the server created by the test}", "__STATUS__" => "unchanged"],
				"expectedResultHTML"=>
					[0 => '&lt;svg onclick&quot;alert(1)&quot; &gt;', "org_id" => "{org id of the server created by the test}", 1 => "&gt;ServerYO", 2 => "1", 3 => "production", 4 => "", "id" => "{Id of the server created by the test}", "__STATUS__" => "unchanged"],
			],
		];
	}

}
