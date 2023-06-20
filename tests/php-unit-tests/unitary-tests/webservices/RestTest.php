<?php

namespace Combodo\iTop\Test\UnitTest\Webservices;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Exception;
use MetaModel;
use utils;


/**
 * @group itopRequestMgmt
 * @group restApi
 * @group defaultProfiles
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class RestTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;

	const ENUM_JSONDATA_AS_STRING = 0;
	const ENUM_JSONDATA_AS_FILE = 1;
	const ENUM_JSONDATA_NONE = 2;

	private $sTmpFile = "";
	private $sUrl;
	private $sLogin;
	private $sPassword = "Iuytrez9876543ç_è-(";
	/** @var int $iJsonDataMode */
	private int $iJsonDataMode = self::ENUM_JSONDATA_AS_STRING;

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

	    $this->sUrl = MetaModel::GetConfig()->Get('app_root_url');

	    $oRestProfile = MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'REST Services User'), true);
	    $oAdminProfile = MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'Administrator'), true);

	    if (is_object($oRestProfile) && is_object($oAdminProfile)) {
		    $oUser = $this->CreateUser($this->sLogin, $oRestProfile->GetKey(), $this->sPassword);
		    $this->AddProfileToUser($oUser, $oAdminProfile->GetKey());
	    }
    }

	public function testJSONPCallback()
	{
		$sCallbackName = 'fooCallback';
		$sJsonData = <<<JSON
{
   "operation": "list_operations"   
}
JSON;

		// Test regular JSON result
		$sJSONResult =  $this->CallRestApi($sJsonData);
		// - Try to decode JSON to array to check if it is well-formed
		$aJSONResultAsArray = json_decode($sJSONResult, true);
		if (false === is_array($aJSONResultAsArray)) {
			$this->fail('JSON result could not be decoded as array, it might be malformed');
		}

		// Test JSONP with callback by checking that it is the same as the regular JSON but within the JS callback
		$sJSONPResult =  $this->CallRestApi($sJsonData, $sCallbackName);
		$this->assertEquals($sCallbackName.'('.$sJSONResult.');', $sJSONPResult, 'JSONP response callback does not match expected result');
	}

	/**
	 * @dataProvider BasicProvider
	 * @param int $iJsonDataMode
	 */
	public function testCreateApi($iJsonDataMode)
	{
		$this->iJsonDataMode = $iJsonDataMode;

		// Create ticket
		$description = date('dmY H:i:s');
		$sOutputJson = $this->CreateTicketViaApi($description);
		$this->debug("Output: '$sOutputJson'");
		$aJson = json_decode($sOutputJson, true);
		$this->assertNotNull($aJson, "Cannot decode returned JSON : $sOutputJson");

		if ($this->iJsonDataMode === static::ENUM_JSONDATA_NONE){
			$this->assertStringContainsString("3", "".$aJson['code'], $sOutputJson);
			$this->assertStringContainsString("Error: Missing parameter 'json_data'", "".$aJson['message'], $sOutputJson);
			return;
		}

		$this->assertStringContainsString("0", "".$aJson['code'], $sOutputJson);
		$sUserRequestKey = $this->array_key_first($aJson['objects']);
		$this->assertStringContainsString('UserRequest::', $sUserRequestKey);
		$iId = $aJson['objects'][$sUserRequestKey]['key'];

		$sExpectedJsonOuput = <<<JSON
{"objects":{"UserRequest::$iId":{"code":0,"message":"created","class":"UserRequest","key":"$iId","fields":{"id":"$iId"}}},"code":0,"message":null}
JSON;
		$this->assertJsonStringEqualsJsonString($sExpectedJsonOuput, $sOutputJson);

		$sExpectedJsonOuput = <<<JSON
{"objects":{"UserRequest::$iId":{"code":0,"message":"","class":"UserRequest","key":"$iId","fields":{"id":"$iId","description":"<p>$description<\/p>"}}},"code":0,"message":"Found: 1"}
JSON;
		$this->assertJsonStringEqualsJsonString($sExpectedJsonOuput, $this->GetTicketViaRest($iId));

		$aCmdbChangeUserInfo = $this->GetCmdbChangeUserInfo($iId);
		$this->assertEquals(['CMDBChangeOpCreate' => 'test'], $aCmdbChangeUserInfo);

		// Delete ticket
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
	 * @param int $iJsonDataMode
	 */
	public function testUpdateApi($iJsonDataMode)
	{
		$this->iJsonDataMode = $iJsonDataMode;

		//create ticket
		$description = date('dmY H:i:s');
		$sOuputJson = $this->CreateTicketViaApi($description);
		$aJson = json_decode($sOuputJson, true);
		$this->assertNotNull($aJson, 'json_decode() on the REST API response returned null :(');

		if ($this->iJsonDataMode === static::ENUM_JSONDATA_NONE){
			$this->assertStringContainsString("3", "".$aJson['code'], $sOuputJson);
			$this->assertStringContainsString("Error: Missing parameter 'json_data'", "".$aJson['message'], $sOuputJson);
			return;
		}

		$this->assertStringContainsString("0", "".$aJson['code'], $sOuputJson);
		$sUserRequestKey = $this->array_key_first($aJson['objects']);
		$this->assertStringContainsString('UserRequest::', $sUserRequestKey);
		$iId = $aJson['objects'][$sUserRequestKey]['key'];

		// Update ticket
		$description = date('Ymd H:i:s');
		$sExpectedJsonOuput = <<<JSON
{"objects":{"UserRequest::$iId":{"code":0,"message":"updated","class":"UserRequest","key":"$iId","fields":{"description":"<p>$description<\/p>"}}},"code":0,"message":null}
JSON;
		$this->assertJsonStringEqualsJsonString($sExpectedJsonOuput, $this->UpdateTicketViaApi($iId, $description));

		$aCmdbChangeUserInfo = $this->GetCmdbChangeUserInfo($iId);
		$this->assertEquals(['CMDBChangeOpCreate' => 'test', 'CMDBChangeOpSetAttributeHTML' => 'test'], $aCmdbChangeUserInfo);


		// Delete ticket
		$this->DeleteTicketFromApi($iId);
	}

	/**
	 * @dataProvider BasicProvider
	 * @param int $iJsonDataMode
	 */
	public function testDeleteApi($iJsonDataMode)
	{
		$this->iJsonDataMode = $iJsonDataMode;

		// Create ticket
		$description = date('dmY H:i:s');

		$sOuputJson = $this->CreateTicketViaApi($description);
		$aJson = json_decode($sOuputJson, true);
		$this->assertNotNull($aJson, 'json_decode() on the REST API response returned null :(');

		if ($this->iJsonDataMode === static::ENUM_JSONDATA_NONE){
			$this->assertStringContainsString("3", "".$aJson['code'], $sOuputJson);
			$this->assertStringContainsString("Error: Missing parameter 'json_data'", "".$aJson['message'], $sOuputJson);
			return;
		}

		$this->assertStringContainsString("0", "".$aJson['code'], $sOuputJson);
		$sUserRequestKey = $this->array_key_first($aJson['objects']);
		$this->assertStringContainsString('UserRequest::', $sUserRequestKey);
		$iId = $aJson['objects'][$sUserRequestKey]['key'];

		// Delete ticket
		$sExpectedJsonOuput = <<<JSON
"objects":{"UserRequest::$iId"
JSON;
		$this->assertStringContainsString($sExpectedJsonOuput, $this->DeleteTicketFromApi($iId));

		$sExpectedJsonOuput = <<<JSON
{"objects":null,"code":0,"message":"Found: 0"}
JSON;
		$this->assertJsonStringEqualsJsonString($sExpectedJsonOuput, $this->GetTicketViaRest($iId));
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
			'call rest call' => [ 'sJsonDataMode' => static::ENUM_JSONDATA_AS_STRING],
			'pass json_data as file' => [ 'sJsonDataMode' => static::ENUM_JSONDATA_AS_FILE],
			'no json data' => [ 'sJsonDataMode' => static::ENUM_JSONDATA_NONE]
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

	private function CallRestApi(string $sJsonDataContent, string $sCallbackName = null){
		$ch = curl_init();
		$aPostFields = [
			'version' => '1.3',
			'auth_user' => $this->sLogin,
			'auth_pwd' => $this->sPassword,
		];

		if ($this->iJsonDataMode === static::ENUM_JSONDATA_AS_STRING) {
			$this->sTmpFile = tempnam(sys_get_temp_dir(), 'jsondata_');
			file_put_contents($this->sTmpFile, $sJsonDataContent);

			$oCurlFile = curl_file_create($this->sTmpFile);
			$aPostFields['json_data'] = $oCurlFile;
		} else if ($this->iJsonDataMode === static::ENUM_JSONDATA_AS_FILE) {
			$aPostFields['json_data'] = $sJsonDataContent;
		}

		if (utils::IsNotNullOrEmptyString($sCallbackName)) {
			$aPostFields['callback'] = $sCallbackName;
		}

		curl_setopt($ch, CURLOPT_URL, "$this->sUrl/webservices/rest.php");
		curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
		curl_setopt($ch, CURLOPT_POSTFIELDS, $aPostFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Force disable of certificate check as most of dev / test env have a self-signed certificate
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

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
		$this->assertNotNull($aJson, 'json_decode() on the REST API response returned null :(');

		if (is_array($aJson) && array_key_exists('objects', $aJson)) {
			$aObjects = $aJson['objects'];
			if (!empty($aObjects)) {
				foreach ($aObjects as $aObject) {
					$sClass = $aObject['class'];
					$sUserInfo = $aObject['fields']['userinfo'];
					$aUserInfo[$sClass] = $sUserInfo;
				}
			}
		}
		return $aUserInfo;
	}
}
