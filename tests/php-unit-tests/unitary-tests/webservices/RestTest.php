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
 * @runClassInSeparateProcess
 */
class RestTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;

	static private $sUrl;
	static private $sLogin;
	static private $sPassword = "Iuytrez9876543ç_è-(";

	/**
	 * This method is called before the first test of this test class is run (in the current process).
	 */
	public static function setUpBeforeClass(): void
	{
		parent::setUpBeforeClass();

		static::$sUrl = MetaModel::GetConfig()->Get('app_root_url');
		static::$sLogin = "rest-user-".date('dmYHis');
	}

	/**
	 * This method is called after the last test of this test class is run (in the current process).
	 */
	public static function tearDownAfterClass(): void
	{
		parent::tearDownAfterClass();
	}

	/**
     * @throws Exception
     */
    protected function setUp(): void
    {
	    parent::setUp();

	    $this->CreateTestOrganization();

	    $oRestProfile = MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'REST Services User'), true);
	    $oAdminProfile = MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'Administrator'), true);

	    if (is_object($oRestProfile) && is_object($oAdminProfile)) {
		    $oUser = $this->CreateUser(static::$sLogin, $oRestProfile->GetKey(), static::$sPassword);
		    $this->AddProfileToUser($oUser, $oAdminProfile->GetKey());
	    }
    }

	public function testListOperationsAndJSONPCallback()
	{
		$sCallbackName = 'fooCallback';
		$sJsonData = '{"operation": "list_operations"}';

		// Test regular JSON result
		$sJSONResult = $this->CallRestApi($sJsonData);
		// - Try to decode JSON to array to check if it is well-formed
		$aJSONResultAsArray = json_decode($sJSONResult, true);
		$this->assertArrayHasKey('version', $aJSONResultAsArray);
		$this->assertArrayHasKey('operations', $aJSONResultAsArray);
		$this->assertArrayHasKey('code', $aJSONResultAsArray);
		$this->assertArrayHasKey('message', $aJSONResultAsArray);
		$this->assertEquals(0, $aJSONResultAsArray['code']);
		$this->assertTrue(count($aJSONResultAsArray['operations']) >= 7, 'Expecting at least 7 operations from Core Services');
		foreach ($aJSONResultAsArray['operations'] as $aOperationData) {
			$this->assertCount(3, $aOperationData);
			$this->assertArrayHasKey('verb', $aOperationData);
			$this->assertArrayHasKey('description', $aOperationData);
			$this->assertArrayHasKey('extension', $aOperationData);
			$this->assertNotEmpty($aOperationData['verb']);
		}

		// Test JSONP with callback by checking that it is the same as the regular JSON but within the JS callback
		$sJSONPResult =  $this->CallRestApi($sJsonData, $sCallbackName);
		$this->assertEquals($sCallbackName.'('.$sJSONResult.');', $sJSONPResult, 'JSONP response callback does not match expected result');
	}

	public function testMissingJSONData()
	{
		$sOutputJson = $this->CallRestApi();
		$aJson = json_decode($sOutputJson, true);
		$this->assertEquals(3, $aJson['code'], $sOutputJson);
		$this->assertEquals("Error: Missing parameter 'json_data'", $aJson['message'], $sOutputJson);
	}

	public function testPostJSONDataAsCurlFile()
	{
		$sCallbackName = 'fooCallback';
		$sJsonData = '{"operation": "list_operations"}';

		// Test regular JSON result
		$sJSONResult = $this->CallRestApi($sJsonData, null, true);
		$aJSONResultAsArray = json_decode($sJSONResult, true);
		$this->assertEquals(0, $aJSONResultAsArray['code'], $sJSONResult);
	}

	public function testCoreApiCreate()
	{
		// Create ticket
		$description = date('dmY H:i:s');
		$sOutputJson = $this->CreateTicketViaApi($description);
		$this->debug("Output: '$sOutputJson'");
		$aJson = json_decode($sOutputJson, true);
		$this->assertNotNull($aJson, "Cannot decode returned JSON : $sOutputJson");

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
		$this->assertCount(1, $aCmdbChangeUserInfo);

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

	public function testCoreApiUpdate()
	{
		//create ticket
		$description = date('dmY H:i:s');
		$sOuputJson = $this->CreateTicketViaApi($description);
		$aJson = json_decode($sOuputJson, true);
		$this->assertNotNull($aJson, 'json_decode() on the REST API response returned null :(');

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
		$this->assertCount(2, $aCmdbChangeUserInfo);

		// Delete ticket
		$this->DeleteTicketFromApi($iId);
	}

	public function testCoreApiDelete()
	{
		// Create ticket
		$description = date('dmY H:i:s');

		$sOuputJson = $this->CreateTicketViaApi($description);
		$aJson = json_decode($sOuputJson, true);
		$this->assertNotNull($aJson, 'json_decode() on the REST API response returned null :(');

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

		return $this->CallCoreRestApiInternally($sJsonGetContent);
	}

	private function UpdateTicketViaApi($iId, $description){
		$sJsonUpdateContent = <<<JSON
{"operation": "core/update","comment": "test","class": "UserRequest","key":"$iId","output_fields": "description","fields":{"description": "$description"}}
JSON;

		return $this->CallCoreRestApiInternally($sJsonUpdateContent);
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

		return $this->CallCoreRestApiInternally($sJsonCreateContent);
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
		return $this->CallCoreRestApiInternally($sJson);

	}

	/**
	 * @param string|null $sJsonDataContent If null, then no data is posted and the service should reply with an error
	 * @param string|null $sCallbackName JSONP callback
	 * @param bool $bJSONDataAsFile Set to true to test with the data provided to curl as a file
	 *
	 * @return bool|string
	 */
	private function CallRestApi(string $sJsonDataContent = null, string $sCallbackName = null, bool $bJSONDataAsFile = false)
	{
		$ch = curl_init();
		$aPostFields = [
			'version' => '1.3',
			'auth_user' => static::$sLogin,
			'auth_pwd' => static::$sPassword,
		];

		$sTmpFile = null;
		if (!is_null($sJsonDataContent)) {
			if ($bJSONDataAsFile) {
				$sTmpFile = tempnam(sys_get_temp_dir(), 'jsondata_');
				file_put_contents($sTmpFile, $sJsonDataContent);

				$oCurlFile = curl_file_create($sTmpFile);
				$aPostFields['json_data'] = $oCurlFile;
			}
			else {
				$aPostFields['json_data'] = $sJsonDataContent;
			}
		}

		if (utils::IsNotNullOrEmptyString($sCallbackName)) {
			$aPostFields['callback'] = $sCallbackName;
		}

		curl_setopt($ch, CURLOPT_URL, static::$sUrl."/webservices/rest.php");
		curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
		curl_setopt($ch, CURLOPT_POSTFIELDS, $aPostFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Force disable of certificate check as most of dev / test env have a self-signed certificate
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

		$sJson = curl_exec($ch);
		curl_close ($ch);

		if (!is_null($sTmpFile)) {
			unlink($sTmpFile);
		}

		return $sJson;
	}

	private function CallCoreRestApiInternally(string $sJsonDataContent)
	{
		$oJsonData = json_decode($sJsonDataContent);
		$sOperation = $oJsonData->operation;

		//\UserRights::Login(static::$sLogin);
		\CMDBObject::SetTrackOrigin('webservice-rest');
		\CMDBObject::SetTrackInfo('test');

		$oRestSP = new \CoreServices();
		$oResult = $oRestSP->ExecOperation('1.3', $sOperation, $oJsonData);

		//\UserRights::Logoff();

		return json_encode($oResult);
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
		$sOutput = $this->CallCoreRestApiInternally($sJsonGetContent);
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
