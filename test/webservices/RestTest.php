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
	const CREATE_TEST_ORG = true;
	const USE_TRANSACTION = true;

	private $sTmpFile = "";
	private $bCallApiViaFile = false;

	/**
     * @throws Exception
     */
    protected function setUp()
	{
		parent::setUp();

		if (!empty($this->sTmpFile)){
			unlink($this->sTmpFile);
		}
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

	public function BasicProvider(){
		return [
			'call rest call' => [ 'bCallApiViaFile' => false],
			'pass json_data as file' => [ 'bCallApiViaFile' => true]
			];
	}

	/**
	 * @dataProvider BasicProvider
	 */
	public function testBasic($bCallApiViaFile)
	{
		$this->bCallApiViaFile = $bCallApiViaFile;

		//create ticket
		$description = date('dmY H:i:s');

		$sOuputJson = $this->CreateTicketViaApi($description);
		$aJson = json_decode($sOuputJson, true);
		$this->assertContains("0", "".$aJson['code']);
		$sUserRequestKey = array_key_first($aJson['objects']);
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

		//update ticket
		$description = date('Ymd H:i:s');
		$sExpectedJsonOuput=<<<JSON
{"objects":{"UserRequest::$iId":{"code":0,"message":"updated","class":"UserRequest","key":"$iId","fields":{"description":"<p>$description<\/p>"}}},"code":0,"message":null}
JSON;
		$this->assertEquals($sExpectedJsonOuput, $this->UpdateTicketViaApi($iId, $description));

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

		private function CallRestApi($sJsonDataContent){
		$ch = curl_init();
		$aPostFields = [
			'version' => '1.3',
			'auth_user' => 'admin',
			'auth_pwd' => 'admin',
		];

		if ($this->bCallApiViaFile){
			$this->sTmpFile = tempnam(sys_get_temp_dir(), 'jsondata_');
			file_put_contents($this->sTmpFile, $sJsonDataContent);

			$oCurlFile = curl_file_create($this->sTmpFile);
			$aPostFields['json_data'] = $oCurlFile;
		}else{
			$aPostFields['json_data'] = $sJsonDataContent;
		}

		curl_setopt($ch, CURLOPT_URL, "http://localhost/iTop/webservices/rest.php");
		curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
		curl_setopt($ch, CURLOPT_POSTFIELDS, $aPostFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$sJson = curl_exec($ch);
		curl_close ($ch);

		return $sJson;
	}

}
