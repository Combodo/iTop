<?php

namespace Combodo\iTop\Test\UnitTest\Webservices;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Exception;


/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class RestTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;

	private $sTmpFile = "";
	private $bPassJsonDataAsFile = false;
	private $sUrl;
	private $sLogin;
	private $sPassword = "Iuytrez9876543ç_è-(";

	/**
     * @throws Exception
     */
    protected function setUp(): void
    {
	    parent::setUp();

	    $this->sLogin = "rest-user-".date('dmYHis');
	    $this->CreateTestOrganization();

	    if (!empty($this->sTmpFile)) {
		    unlink($this->sTmpFile);
	    }

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

	/**
	 * @dataProvider BasicProvider
	 * @param bool $bPassJsonDataAsFile
	 */
	public function testCreateApi($bPassJsonDataAsFile)
	{
		$this->bPassJsonDataAsFile = $bPassJsonDataAsFile;

		//create ticket
		$description = date('dmY H:i:s');
		$sOuputJson = $this->CreateTicketViaApi($description);
		$aJson = json_decode($sOuputJson, true);
		$this->assertContains("0", "".$aJson['code'], $sOuputJson);
		$sUserRequestKey = $this->array_key_first($aJson['objects']);
		$this->assertContains('UserRequest::', $sUserRequestKey);
		$iId = $aJson['objects'][$sUserRequestKey]['key'];
		$sExpectedJsonOuput=<<<JSON
{"objects":{"UserRequest::$iId":{"code":0,"message":"created","class":"UserRequest","key":"$iId","fields":{"id":"$iId"}}},"code":0,"message":null}
JSON;
		$this->assertEquals($sExpectedJsonOuput, $sOuputJson);

		$sExpectedJsonOuput=<<<JSON
{"objects":{"UserRequest::$iId":{"code":0,"message":"","class":"UserRequest","key":"$iId","fields":{"id":"$iId","description":"<p>$description<\/p>"}}},"code":0,"message":"Found: 1"}
JSON;
		$this->assertEquals($sExpectedJsonOuput, $this->GetTicketViaRest($iId));

		$aCmdbChangeUserInfo = $this->GetCmdbChangeUserInfo($iId);
		$this->assertEquals(['CMDBChangeOpCreate' => 'test'], $aCmdbChangeUserInfo);

		//delete ticket
		$this->DeleteTicketFromApi($iId);
	}

	/**
	 * array_key_first comes with PHP7.3
	 * itop should also work with previous PHP versions
	 */
	private function array_key_first($aTab){
		if (!is_array($aTab) || empty($aTab)){
			return false;
		}

		foreach ($aTab as $sKey => $sVal){
			return $sKey;
		}
	}

	/**
	 * @dataProvider BasicProvider
	 * @param bool $bPassJsonDataAsFile
	 */
	public function testUpdateApi($bPassJsonDataAsFile)
	{
		$this->bPassJsonDataAsFile = $bPassJsonDataAsFile;

		//create ticket
		$description = date('dmY H:i:s');
		$sOuputJson = $this->CreateTicketViaApi($description);
		$aJson = json_decode($sOuputJson, true);
		$this->assertContains("0", "".$aJson['code'], $sOuputJson);
		$sUserRequestKey = $this->array_key_first($aJson['objects']);
		$this->assertContains('UserRequest::', $sUserRequestKey);
		$iId = $aJson['objects'][$sUserRequestKey]['key'];

		//update ticket
		$description = date('Ymd H:i:s');
		$sExpectedJsonOuput=<<<JSON
{"objects":{"UserRequest::$iId":{"code":0,"message":"updated","class":"UserRequest","key":"$iId","fields":{"description":"<p>$description<\/p>"}}},"code":0,"message":null}
JSON;
		$this->assertEquals($sExpectedJsonOuput, $this->UpdateTicketViaApi($iId, $description));

		$aCmdbChangeUserInfo = $this->GetCmdbChangeUserInfo($iId);
		$this->assertEquals(['CMDBChangeOpCreate' => 'test', 'CMDBChangeOpSetAttributeHTML' => 'test'], $aCmdbChangeUserInfo);


		//delete ticket
		$this->DeleteTicketFromApi($iId);
	}
	/**
	 * @dataProvider BasicProvider
	 * @param bool $bPassJsonDataAsFile
	 */
	public function testDeleteApi($bPassJsonDataAsFile)
	{
		$this->bPassJsonDataAsFile = $bPassJsonDataAsFile;

		//create ticket
		$description = date('dmY H:i:s');

		$sOuputJson = $this->CreateTicketViaApi($description);
		$aJson = json_decode($sOuputJson, true);
		$this->assertContains("0", "".$aJson['code'], $sOuputJson);
		$sUserRequestKey = $this->array_key_first($aJson['objects']);
		$this->assertContains('UserRequest::', $sUserRequestKey);
		$iId = $aJson['objects'][$sUserRequestKey]['key'];

		//delete ticket
		$sExpectedJsonOuput=<<<JSON
{"objects":{"UserRequest::$iId"
JSON;
		$this->assertContains($sExpectedJsonOuput, $this->DeleteTicketFromApi($iId));

		$sExpectedJsonOuput=<<<JSON
{"objects":null,"code":0,"message":"Found: 0"}
JSON;
		$this->assertEquals($sExpectedJsonOuput, $this->GetTicketViaRest($iId));
	}

	private function GetTicketViaRest($iId){
		$sJsonGetContent = <<<JSON
{
   "operation": "core/get",
   "class": "UserRequest",
   "key": "SELECT UserRequest WHERE id=$iId",
   "output_fields": "id, description"
}
JSON;

		return $this->CallRestApi($sJsonGetContent);
	}

	public function BasicProvider(){
		return [
			'call rest call' => [ 'bCallApiViaFile' => false],
			//'pass json_data as file' => [ 'bCallApiViaFile' => true]
		];
	}

	private function UpdateTicketViaApi($iId, $description){
		$sJsonUpdateContent = <<<JSON
{"operation": "core/update","comment": "test","class": "UserRequest","key":"$iId","output_fields": "description","fields":{"description": "$description"}}
JSON;

		return $this->CallRestApi($sJsonUpdateContent);
	}

	private function CreateTicketViaApi($description){
		$sJsonCreateContent = <<<JSON
{
   "operation": "core/create",
   "comment": "test",
   "class": "UserRequest",
   "output_fields": "id",
   "fields":
   {
      "org_id": "SELECT Organization WHERE name = \"Demo\"",
      
      "title": "Houston, got a problem",
      "description": "$description"
   }
}
JSON;

		return $this->CallRestApi($sJsonCreateContent);
	}

	private function DeleteTicketFromApi($iId){
    	$sJson = <<<JSON
{
   "operation": "core/delete",
   "comment": "Cleanup",
   "class": "UserRequest",
   "key":$iId,
   "simulate": false
}
JSON;
		return $this->CallRestApi($sJson);

	}

	private function CallRestApi($sJsonDataContent){
		$ch = curl_init();
		$aPostFields = [
			'version' => '1.3',
			'auth_user' => $this->sLogin,
			'auth_pwd' => $this->sPassword,
		];

		if ($this->bPassJsonDataAsFile){
			$this->sTmpFile = tempnam(sys_get_temp_dir(), 'jsondata_');
			file_put_contents($this->sTmpFile, $sJsonDataContent);

			$oCurlFile = curl_file_create($this->sTmpFile);
			$aPostFields['json_data'] = $oCurlFile;
		}else{
			$aPostFields['json_data'] = $sJsonDataContent;
		}

		curl_setopt($ch, CURLOPT_URL, "$this->sUrl/webservices/rest.php");
		curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
		curl_setopt($ch, CURLOPT_POSTFIELDS, $aPostFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$sJson = curl_exec($ch);
		curl_close ($ch);

		return $sJson;
	}

	/**
	 * @param $iId
	 * Get CMDBChangeOp info to test
	 * @return array
	 */
	private function GetCmdbChangeUserInfo($iId){
		$sJsonGetContent = <<<JSON
{
   "operation": "core/get",
   "class": "CMDBChangeOp",
   "key": "SELECT CMDBChangeOp WHERE objclass='UserRequest' AND objkey=$iId",
   "output_fields": "userinfo"
}
JSON;

		$aUserInfo = [];
		$sOutput = $this->CallRestApi($sJsonGetContent);
		$aJson = json_decode($sOutput, true);
		if (is_array($aJson) && array_key_exists('objects', $aJson)){
			$aObjects = $aJson['objects'];
			if (!empty($aObjects)){
				foreach ($aObjects as $aObject){
					$sClass = $aObject['class'];
					$sUserInfo = $aObject['fields']['userinfo'];
					$aUserInfo[$sClass] = $sUserInfo;
				}
			}
		}
		return $aUserInfo;
	}
}
