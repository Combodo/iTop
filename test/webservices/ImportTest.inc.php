<?php

namespace Combodo\iTop\Test\UnitTest\Webservices;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class ImportTest extends ItopDataTestCase {
	const CREATE_TEST_ORG = true;
	const USE_TRANSACTION = false;

	private $sUrl;
	private $sUid;
	private $sLogin;
	private $sPassword = "Iuytrez9876543ç_è-(";
	private $sTmpFile = "";
	private $oOrg;

	protected function tearDown() {
		parent::tearDown();
		if (!empty($this->sTmpFile) && is_file($this->sTmpFile)){
			unlink($this->sTmpFile);
		}
	}

	protected function setUp() {
		parent::setUp();

		$this->sTmpFile = tempnam(sys_get_temp_dir(), 'import_csv_');

		require_once(APPROOT.'application/startup.inc.php');
		$this->sUid = date('dmYHis');
		$this->sLogin = "import-" .$this->sUid;
		$this->oOrg = $this->CreateOrganization($this->sUid);

		$sConfigFile = \utils::GetConfig()->GetLoadedFile();
		@chmod($sConfigFile, 0770);
		$this->sUrl = \MetaModel::GetConfig()->Get('app_root_url');
		@chmod($sConfigFile, 0444); // Read-only

		$oRestProfile = \MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'REST Services User'), true);
		$oAdminProfile = \MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'Administrator'), true);

		if (is_object($oRestProfile) && is_object($oAdminProfile))
		{
			$oUser = $this->CreateUser($this->sLogin, $oRestProfile->GetKey(), $this->sPassword);
			$this->AddProfileToUser($oUser, $oAdminProfile->GetKey());
		}
	}

	public function ImportProvider(){
		$sFirstName = "firstname_UID";
		$sLastName = "lastname_UID";
		$sEmail = "email_UID@toto.fr";

		$aTestOkParams = [
			'sCsvHeaders' => '"first_name","name", "email", "org_id->name"',
			'sCsvFirstLineValues' => sprintf('"%s", "%s", "%s", UID', $sFirstName, $sLastName, $sEmail),
			'sExpectedLastLineNeedle' => sprintf('ORGID;"%s";"%s";"%s"', $sFirstName, $sLastName, $sEmail),
			'sReconciliationKeys' => null,
			'iExpectedIssue' => '0',
			'iExpectedCreated' => '1',
		];

		$aTestOkParamsWithReconciliationKeys = array_merge($aTestOkParams,
			['sReconciliationKeys' => "name,first_name,org_id->name"]);

		$aTestFailedExtKeys = [
			'sCsvHeaders' => '"first_name","name", "email", "org_id->name"',
			'sCsvFirstLineValues' => sprintf('"%s", "%s", "%s", gabuzomeu', $sFirstName, $sLastName, $sEmail),
			'sExpectedLastLineNeedle' => 'Issue: Unexpected attribute value(s);n/a;n/a;No match for value \'gabuzomeu\'. Possible \'Organization\' value(s): ',
		];

		$aTestFailedExtKeysWithReconciliationKeys = array_merge($aTestFailedExtKeys,
			[
				'sReconciliationKeys' => "name,first_name,org_id->name",
				'sExpectedLastLineNeedle' => 'Issue: failed to reconcile;n/a;n/a;No match for value \'gabuzomeu\'. Possible \'Organization\' value(s): ',
			]
		);

		return [
			'import OK' => $aTestOkParams,
			'import OK / with reconciliation keys' => $aTestOkParamsWithReconciliationKeys,
			'import ERROR : invalid enum value' => [
				'sCsvHeaders' => '"first_name","name", "email", "org_id->name", status',
				'sCsvFirstLineValues' => sprintf('"%s", "%s", "%s", UID, toto', $sFirstName, $sLastName, $sEmail),
				'sExpectedLastLineNeedle' => sprintf(
						'Issue: Unexpected attribute value(s);n/a;n/a;ORGID;"%s";"%s";"%s";\'toto\' is an invalid value. Unexpected value for attribute \'status\': Value not allowed [toto]', $sFirstName, $sLastName, $sEmail
				),
			],
			'import ERROR : invalid date value' => [
				'sCsvHeaders' => '"first_name","name", "email", "org_id->name", obsolescence_date',
				'sCsvFirstLineValues' => sprintf('"%s", "%s", "%s", UID, toto', $sFirstName, $sLastName, $sEmail),
				'sExpectedLastLineNeedle' => sprintf(
					'Issue: Internal error: Exception, Wrong format for date attribute obsolescence_date, expecting "Y-m-d" and got "toto";n/a;n/a;n/a;%s;%s;%s;toto', $sFirstName, $sLastName, $sEmail
				),
			],
			'import ERROR : invalid ext field value' => $aTestFailedExtKeys,
			'import ERROR : invalid ext field value / reconciliation keys' => $aTestFailedExtKeysWithReconciliationKeys,
		];
	}

	/**
	 * @dataProvider ImportProvider
	 */
	public function testImport($sCsvHeaders, $sCsvFirstLineValues, $sExpectedLastLineNeedle, $sReconciliationKeys=null, $iExpectedIssue=1, $iExpectedCreated=0) {
		$sContent = <<<CSVFILE
$sCsvHeaders
$sCsvFirstLineValues
CSVFILE;
		file_put_contents($this->sTmpFile, str_replace("UID", $this->sUid, $sContent));

		$aParams = [
			'class' => 'Person',
			'csvfile' => $this->sTmpFile,
			'charset' => 'UTF-8',
			'no_localize' => '1',
			'output' => 'details',
		];

		if (null != $sReconciliationKeys){
			$aParams["reconciliationkeys"] = $sReconciliationKeys;
		}

		$aRes = \utils::ExecITopScript('webservices/import.php', $aParams, $this->sLogin, $this->sPassword);
		$aOutput = $aRes[1];
		$sOutput = implode("\n", $aOutput);
		$sLastline = $aOutput[sizeof($aOutput) - 1];
		$iRes = $aRes[0];
		$this->assertEquals(0, $iRes, $sOutput);
		$this->assertContains("#Issues: $iExpectedIssue", $sOutput, $sOutput);
		$this->assertContains("#Warnings: 0", $sOutput, $sOutput);
		$this->assertContains("#Created: $iExpectedCreated", $sOutput, $sOutput);
		$this->assertContains("#Updated: 0", $sOutput, $sOutput);
		var_dump($sLastline);
		if ($iExpectedCreated === 1) {
			$this->assertContains("created;Person", $sLastline, $sLastline);
		}

		$iOrgId = $this->oOrg->GetKey();
		$sLastLineNeedle = $sExpectedLastLineNeedle;
		foreach (["ORGID" => $iOrgId, "UID" => $this->sUid] as $sSearch => $sReplace){
			$sLastLineNeedle = str_replace($sSearch, $sReplace, $sLastLineNeedle);
		}
		$this->assertContains($sLastLineNeedle, $sLastline, $sLastline);

		$sPattern = "/Person;(\d+);/";
		if (preg_match($sPattern,$sLastline,$aMatches)){
			var_dump($aMatches);
			$iObjId  = $aMatches[1];
			$oObj = MetaModel::GetObject("Person", $iObjId);
			$oObj->DBDelete();
		}

		//date
		//ext key
	}
}
