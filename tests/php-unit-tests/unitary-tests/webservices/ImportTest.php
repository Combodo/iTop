<?php

namespace Combodo\iTop\Test\UnitTest\Webservices;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

class ImportTest extends ItopDataTestCase {
	const USE_TRANSACTION = false;

	private $sUrl;
	private $sUid;
	private $sLogin;
	private $sPassword = "abcDEF12345##";
	private $sTmpFile = "";
	private $oOrg;

	protected function tearDown() : void{
		parent::tearDown();
		if (!empty($this->sTmpFile) && is_file($this->sTmpFile)){
			unlink($this->sTmpFile);
		}
	}

	protected function setUp() : void{
		parent::setUp();

		$this->sTmpFile = tempnam(sys_get_temp_dir(), 'import_csv_');

		require_once(APPROOT.'application/startup.inc.php');
		$this->sUid = date('dmYHis');
		$this->sLogin = "import-" .$this->sUid;
		$this->oOrg = $this->CreateOrganization($this->sUid);

		$this->sUrl = \MetaModel::GetConfig()->Get('app_root_url');

		$oRestProfile = \MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'REST Services User'), true);
		$oAdminProfile = \MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'Administrator'), true);

		if (is_object($oRestProfile) && is_object($oAdminProfile))
		{
			$oUser = $this->CreateContactlessUser($this->sLogin, $oRestProfile->GetKey(), $this->sPassword);
			$this->AddProfileToUser($oUser, $oAdminProfile->GetKey());
		} else {
			throw new \Exception("setup failed. test cannot work as usual");
		}
	}

	public function ImportOkProvider(){
		return [
			'with reconciliation key' => [ "sReconciliationKeys" => "name,first_name,org_id->name" ],
			'without reconciliation key' => [ "sReconciliationKeys" => null ],
		];
	}
	/**
	 * @dataProvider ImportOkProvider
	 */
	public function testImportOk($sReconciliationKeys){
		$sFirstName = "firstname_UID";
		$sLastName = "lastname_UID";
		$sEmail = "email_UID@toto.fr";

		$this->performImportTesting(
			'"first_name","name", "email", "org_id->name"',
			sprintf('"%s", "%s", "%s", UID', $sFirstName, $sLastName, $sEmail),
			sprintf('ORGID;"%s";"%s";"%s"', $sFirstName, $sLastName, $sEmail),
			$sReconciliationKeys,
			0,
			1
		);
	}

	public function ImportFailProvider(){
		return [
			'without reconciliation key' => [
				"sReconciliationKeys" => null,
				"sExpectedLastLineNeedle" => 'Issue: Unexpected attribute value(s);n/a;n/a;No match for value \'gabuzomeu\'. Some possible \'Organization\' value(s): '
			],
			'with reconciliation key' => [
				"sReconciliationKeys" => "name,first_name,org_id->name",
				"sExpectedLastLineNeedle" => 'Issue: failed to reconcile;n/a;n/a;No match for value \'gabuzomeu\'. Some possible \'Organization\' value(s): '
			],
		];
	}
	/**
	 * @dataProvider ImportFailProvider
	 */

	public function testImportFail_ExternalKey($sReconciliationKeys, $sExpectedLastLineNeedle){
		$sFirstName = "firstname_UID";
		$sLastName = "lastname_UID";
		$sEmail = "email_UID@toto.fr";

		$this->performImportTesting(
			'"first_name","name", "email", "org_id->name"',
			sprintf('"%s", "%s", "%s", gabuzomeu', $sFirstName, $sLastName, $sEmail),
			$sExpectedLastLineNeedle,
			$sReconciliationKeys,
			1,
			0
		);
	}

	public function testImportFail_Enum(){
		$sFirstName = "firstname_UID";
		$sLastName = "lastname_UID";
		$sEmail = "email_UID@toto.fr";

		$this->performImportTesting(
			'"first_name","name", "email", "org_id->name", status',
			sprintf('"%s", "%s", "%s", UID, toto', $sFirstName, $sLastName, $sEmail),
			sprintf(
				'Issue: Unexpected attribute value(s);n/a;n/a;ORGID;"%s";"%s";"%s";\'toto\' is an invalid value. Unexpected value for attribute \'status\': Value not allowed [toto]', $sFirstName, $sLastName, $sEmail
			),
			null,
			1,
			0
		);
	}

	public function testImportFail_Date(){
		$sFirstName = "firstname_UID";
		$sLastName = "lastname_UID";
		$sEmail = "email_UID@toto.fr";

		$this->performImportTesting(
			'"first_name","name", "email", "org_id->name", obsolescence_date',
			sprintf('"%s", "%s", "%s", UID, toto', $sFirstName, $sLastName, $sEmail),
			sprintf(
				'Issue: Internal error: Exception, Wrong format for date attribute obsolescence_date, expecting "Y-m-d" and got "toto";n/a;n/a;n/a;%s;%s;%s;toto', $sFirstName, $sLastName, $sEmail
			),
			null,
			1,
			0
		);
	}

	private function performImportTesting($sCsvHeaders, $sCsvFirstLineValues, $sExpectedLastLineNeedle, $sReconciliationKeys=null, $iExpectedIssue=1, $iExpectedCreated=0) {
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
		$this->assertStringContainsString("#Issues: $iExpectedIssue", $sOutput, $sOutput);
		$this->assertStringContainsString("#Warnings: 0", $sOutput, $sOutput);
		$this->assertStringContainsString("#Created: $iExpectedCreated", $sOutput, $sOutput);
		$this->assertStringContainsString("#Updated: 0", $sOutput, $sOutput);
		var_dump($sLastline);
		if ($iExpectedCreated === 1) {
			$this->assertStringContainsString("created;Person", $sLastline, $sLastline);
		}

		$iOrgId = $this->oOrg->GetKey();
		$sLastLineNeedle = $sExpectedLastLineNeedle;
		foreach (["ORGID" => $iOrgId, "UID" => $this->sUid] as $sSearch => $sReplace){
			$sLastLineNeedle = str_replace($sSearch, $sReplace, $sLastLineNeedle);
		}
		$this->assertStringContainsString($sLastLineNeedle, $sLastline, $sLastline);

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
