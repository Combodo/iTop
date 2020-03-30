<?php

namespace Combodo\iTop\Test\UnitTest\Core;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class BulkChangeTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;
	protected function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'core/coreexception.class.inc.php');
		require_once(APPROOT.'core/bulkchange.class.inc.php');

	}

	//bug 2888: csv import / data synchro issue with password validation
	public function testPasswordBulkChangeIssue()
	{
		/** @var Personn $oPerson */
		$oPerson = $this->createObject('Person', array(
			'first_name' => 'isaac',
			'name' => 'asimov',
			'email' => 'isaac.asimov@fundation.org',
			'org_id' => $this->getTestOrgId(),
		));

		$aData = array(
			array($oPerson->Get("first_name"),
				$oPerson->Get("name"),
				$oPerson->Get("email"),
				"EN US",
				"iasimov",
				"harryseldon",
				"profileid->name:Administrator"
			)
		);
		$aAttributes = array("language" => 3, "login" => 4, "password" => 5, "profile_list" => 6);
		$aExtKeys = array("contactid" =>
			array("first_name" => 0, "name" => 1, "email" => 2));
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

		foreach ($aRes as $aRow)
		{
			if (array_key_exists('__STATUS__', $aRow))
			{
				$sStatus = $aRow['__STATUS__'];
				$this->assertFalse(strstr($sStatus->GetDescription(), "CoreCannotSaveObjectException"), "CSVimport/Datasynchro: Password validation failed with: " . $sStatus->GetDescription());
			}
		}
	}

}