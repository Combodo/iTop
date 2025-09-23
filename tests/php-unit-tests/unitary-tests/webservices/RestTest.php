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
 */
class RestTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;
	const CREATE_TEST_ORG = false;

	static private $sUrl;
	static private $sLogin;
	static private $sPassword = "Iuytrez9876543ç_è-(";

	/**
	 * This method is called before the first test of this test class is run (in the current process).
	 */
	public static function setUpBeforeClass(): void
	{
		parent::setUpBeforeClass();
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

		static::$sUrl = MetaModel::GetConfig()->Get('app_root_url');
		static::$sLogin = "rest-user-".date('dmYHis');

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
		$sJSONResult = $this->CallRestApi_HTTP($sJsonData);
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
		$sJSONPResult =  $this->CallRestApi_HTTP($sJsonData, $sCallbackName);
		$this->assertEquals($sCallbackName.'('.$sJSONResult.');', $sJSONPResult, 'JSONP response callback does not match expected result');
	}

	public function testMissingJSONData()
	{
		$sOutputJson = $this->CallRestApi_HTTP();
		$aJson = json_decode($sOutputJson, true);
		$this->assertEquals(3, $aJson['code'], $sOutputJson);
		$this->assertEquals("Error: Missing parameter 'json_data'", $aJson['message'], $sOutputJson);
	}

	public function testPostJSONDataAsCurlFile()
	{
		$sCallbackName = 'fooCallback';
		$sJsonData = '{"operation": "list_operations"}';

		// Test regular JSON result
		$sJSONResult = $this->CallRestApi_HTTP($sJsonData, null, true);
		$aJSONResultAsArray = json_decode($sJSONResult, true);
		$this->assertEquals(0, $aJSONResultAsArray['code'], $sJSONResult);
	}

	public function testCoreApiGet(){
		// Create ticket
		$description = date('dmY H:i:s');
		$oTicket = $this->CreateSampleTicket($description);
		$iId = $oTicket->GetKey();

		$sJSONOutput = $this->CallCoreRestApi_Internally(<<<JSON
{
   "operation": "core/get",
   "class": "UserRequest",
   "key": "SELECT UserRequest WHERE id=$iId",
   "output_fields": "id, description"
}
JSON);

		$sExpectedJsonOuput = <<<JSON
{
    "code": 0,
    "message": "Found: 1",
    "objects": {
        "UserRequest::$iId": {
            "class": "UserRequest",
            "code": 0,
            "fields": {
                "description": "<p>$description</p>",
                "id": "$iId"
            },
            "key": "$iId",
            "message": ""
        }
    }
}
JSON;
		$this->assertJsonStringEqualsJsonString($sExpectedJsonOuput, $sJSONOutput);
	}

	public function testCoreApiCreate()
	{
		// Create ticket
		$description = date('dmY H:i:s');
		$sOutputJson = $this->CallCoreRestApi_Internally(<<<JSON
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
JSON);

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

		$oObject = MetaModel::GetObject('UserRequest', $iId, false, true);
		$this->assertIsObject($oObject, "Object UserRequest::$iId not present in the database");
		$this->assertSame("<p>$description</p>", $oObject->Get('description'));

		$this->assertDBChangeOpCount('UserRequest', $iId, 1);

		// Delete ticket
		$oObject->DBDelete();
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
		$oTicket = $this->CreateSampleTicket($description);
		$iId = $oTicket->GetKey();

		// Update ticket
		$description = 'Update to '.date('Ymd H:i:s');
		$sJSONOutput = $this->CallCoreRestApi_Internally(<<<JSON
{"operation": "core/update","comment": "test","class": "UserRequest","key":"$iId","output_fields": "description","fields":{"description": "$description"}}
JSON);

		$sExpectedJsonOuput = <<<JSON
{"objects":{"UserRequest::$iId":{"code":0,"message":"updated","class":"UserRequest","key":"$iId","fields":{"description":"<p>$description<\/p>"}}},"code":0,"message":null}
JSON;
		$this->assertJsonStringEqualsJsonString($sExpectedJsonOuput, $sJSONOutput);

		$this->assertDBChangeOpCount('UserRequest', $iId, 2);
	}

	public function testCoreApiDelete()
	{
		// Create ticket
		$description = date('dmY H:i:s');
		$oTicket = $this->CreateSampleTicket($description);
		$iId = $oTicket->GetKey();

		// Delete ticket
		$sJSONOutput = $this->CallCoreRestApi_Internally(<<<JSON
{
   "operation": "core/delete",
   "comment": "Cleanup",
   "class": "UserRequest",
   "key":$iId,
   "simulate": false
}
JSON);
		$sExpectedJsonOuput = <<<JSON
"objects":{"UserRequest::$iId"
JSON;
		$this->assertStringContainsString($sExpectedJsonOuput, $sJSONOutput);

		$oObject = MetaModel::GetObject('UserRequest', $iId, false, true);
		$this->assertSame(null, $oObject, "Object UserRequest::$iId still present in the database");
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// Helpers
	//
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	private function CreateSampleTicket($description)
	{
		$oTicket = $this->createObject('UserRequest', [
			'org_id' => $this->getTestOrgId(),
			"title" => "Houston, got a problem",
			"description" => $description
		]);
		return $oTicket;
	}

	/**
	 * @param string|null $sJsonDataContent If null, then no data is posted and the service should reply with an error
	 * @param string|null $sCallbackName JSONP callback
	 * @param bool $bJSONDataAsFile Set to true to test with the data provided to curl as a file
	 *
	 * @return bool|string
	 */
	private function CallRestApi_HTTP(string $sJsonDataContent = null, string $sCallbackName = null, bool $bJSONDataAsFile = false)
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

	private function CallCoreRestApi_Internally(string $sJsonDataContent)
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
}
