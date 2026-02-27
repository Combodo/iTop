<?php

namespace Combodo\iTop\Test\UnitTest\Webservices;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Exception;
use iTopMutex;
use MetaModel;
use utils;

/**
 * @group itopRequestMgmt
 * @group restApi
 * @group defaultProfiles
 */
class CronTest extends ItopDataTestCase
{
	public const USE_TRANSACTION = false;
	public const CREATE_TEST_ORG = false;

	public static $sLogin;
	public static $sPassword = "Iuytrez9876543ç_è-(";

	/**
	 * @throws Exception
	 */
	protected function setUp(): void
	{
		parent::setUp();
		$this->BackupConfiguration();

		static::$sLogin = "rest-user-";//.date('dmYHis');

		$this->CreateTestOrganization();
	}

	public function testRestWithFormMode()
	{
		$this->AddLoginModeAndSaveConfiguration('form');
		$this->CreateUserWithProfiles([self::$aURP_Profiles['Administrator'], self::$aURP_Profiles['REST Services User']]);
		$aPostFields = [
			'version' => '1.3',
			'auth_user' => static::$sLogin,
			'auth_pwd' => static::$sPassword,
			'json_data' => '{"operation": "list_operations"}',
		];

		$sJSONResult = $this->CallItopUri("/webservices/rest.php", $aPostFields);

		$this->assertEquals($this->GetExpectedRestResponse(), $sJSONResult);
	}

	public function testRestWithBasicMode()
	{
		$this->AddLoginModeAndSaveConfiguration('basic');
		$this->CreateUserWithProfiles([self::$aURP_Profiles['Administrator'], self::$aURP_Profiles['REST Services User']]);
		$aPostFields = [
			'version' => '1.3',
			'json_data' => '{"operation": "list_operations"}',
		];

		$sToken = base64_encode(sprintf("%s:%s", static::$sLogin, static::$sPassword));
		$aCurlOptions = [
			CURLOPT_HTTPHEADER => ["Authorization: Basic $sToken"],
		];

		// Test regular JSON result
		$sJSONResult = $this->CallItopUri("/webservices/rest.php", $aPostFields, $aCurlOptions);

		$this->assertEquals($this->GetExpectedRestResponse(), $sJSONResult);
	}

	public function testRestWithUrlMode()
	{
		$this->AddLoginModeAndSaveConfiguration('url');
		$this->CreateUserWithProfiles([self::$aURP_Profiles['Administrator'], self::$aURP_Profiles['REST Services User']]);
		$aPostFields = [
			'version' => '1.3',
			'json_data' => '{"operation": "list_operations"}',
		];

		$aGetFields = [
			'auth_user' => static::$sLogin,
			'auth_pwd' => static::$sPassword,
		];
		$sJSONResult = $this->CallItopUri("/webservices/rest.php?".http_build_query($aGetFields), $aPostFields);

		$this->assertEquals($this->GetExpectedRestResponse(), $sJSONResult);
	}

	public function testLaunchCronWithFormModeFailWhenNotAdmin()
	{
		$this->AddLoginModeAndSaveConfiguration('form');
		$this->CreateUserWithProfiles([self::$aURP_Profiles['REST Services User']]);

		$sLogFileName = "crontest_".uniqid();
		$aPostFields = [
			'version' => '1.3',
			'auth_user' => static::$sLogin,
			'auth_pwd' => static::$sPassword,
			'verbose' => 1,
			'debug' => 1,
			'cron_log_file' => $sLogFileName,
		];

		$sJSONResult = $this->CallItopUri("/webservices/launch_cron_asynchronously.php", $aPostFields);

		$this->assertEquals($this->GetExpectedCronResponse(), $sJSONResult);
		$sLogFile = $this->CheckLogFileIsGeneratedAndGetFullPath($sLogFileName);
		$this->CheckAdminAccessIssueWithCron($sLogFile);
	}

	public function testLaunchCronWithBasicModeFailWhenNotAdmin()
	{
		$this->AddLoginModeAndSaveConfiguration('basic');
		$this->CreateUserWithProfiles([self::$aURP_Profiles['REST Services User']]);

		$sLogFileName = "crontest_".uniqid();
		$aPostFields = [
			'version' => '1.3',
			'verbose' => 1,
			'debug' => 1,
			'cron_log_file' => $sLogFileName,
		];

		$sToken = base64_encode(sprintf("%s:%s", static::$sLogin, static::$sPassword));
		$aCurlOptions = [
			CURLOPT_HTTPHEADER => ["Authorization: Basic $sToken"],
		];

		$sJSONResult = $this->CallItopUri("/webservices/launch_cron_asynchronously.php", $aPostFields, $aCurlOptions);

		$this->assertEquals($this->GetExpectedCronResponse(), $sJSONResult);
		$sLogFile = $this->CheckLogFileIsGeneratedAndGetFullPath($sLogFileName);
		$this->CheckAdminAccessIssueWithCron($sLogFile);
	}

	public function testLaunchCronWithUrlModeFailWhenNotAdmin()
	{
		$this->AddLoginModeAndSaveConfiguration('url');
		$this->CreateUserWithProfiles([self::$aURP_Profiles['REST Services User']]);

		$sLogFileName = "crontest_".uniqid();
		$aPostFields = [
			'version' => '1.3',
			'verbose' => 1,
			'debug' => 1,
			'cron_log_file' => $sLogFileName,
		];

		$aGetFields = [
			'auth_user' => static::$sLogin,
			'auth_pwd' => static::$sPassword,
		];

		$sJSONResult = $this->CallItopUri("/webservices/launch_cron_asynchronously.php?".http_build_query($aGetFields), $aPostFields);

		$this->assertEquals($this->GetExpectedCronResponse(), $sJSONResult);
		$sLogFile = $this->CheckLogFileIsGeneratedAndGetFullPath($sLogFileName);
		$this->CheckAdminAccessIssueWithCron($sLogFile);
		;
	}

	public function ModeProvider()
	{
		return [
			'form' => [ 'LoginForm' ],
			'basic' => [ 'LoginBasic'],
			'url' => [ 'LoginURL'],
		];
	}

	/**
	 * @dataProvider ModeProvider
	 */
	public function testGetUserLoginWithFormMode($sLoginModeClass)
	{
		$this->CreateUserWithProfiles([self::$aURP_Profiles['Administrator']]);

		$oLoginMode = new $sLoginModeClass;
		$sUserLogin = $oLoginMode->GetUserLogin([static::$sLogin, static::$sPassword]);
		$this->assertEquals(static::$sLogin, $sUserLogin);
	}

	private function CreateUserWithProfiles(array $aProfileIds): ?string
	{
		if (count($aProfileIds) > 0) {
			$oUser = null;
			foreach ($aProfileIds as $iProfileId) {
				if (is_null($oUser)) {
					$oUser = $this->CreateContactlessUser(static::$sLogin, $iProfileId, static::$sPassword);
				} else {
					$this->AddProfileToUser($oUser, $iProfileId);
				}
				$oUser->DBWrite();
			}

			return $oUser->GetKey();
		}

		return null;
	}

	private function GetExpectedRestResponse(): string
	{
		return <<<JSON
{"code":0,"message":"Operations: 7","version":"1.3","operations":[{"verb":"core\/create","description":"Create an object","extension":"CoreServices"},{"verb":"core\/update","description":"Update an object","extension":"CoreServices"},{"verb":"core\/apply_stimulus","description":"Apply a stimulus to change the state of an object","extension":"CoreServices"},{"verb":"core\/get","description":"Search for objects","extension":"CoreServices"},{"verb":"core\/delete","description":"Delete objects","extension":"CoreServices"},{"verb":"core\/get_related","description":"Get related objects through the specified relation","extension":"CoreServices"},{"verb":"core\/check_credentials","description":"Check user credentials","extension":"CoreServices"}]}
JSON;
	}

	private function GetExpectedCronResponse(): string
	{
		return '{"message":"OK"}';
	}

	private function CheckLogFileIsGeneratedAndGetFullPath(string $sLogFileName): string
	{
		$sLogFile = APPROOT."log/$sLogFileName";
		$this->assertTrue(is_file($sLogFile));
		$this->aFileToClean[] = $sLogFile;
		return $sLogFile;
	}

	private function CheckAdminAccessIssueWithCron(string $sLogFile)
	{
		$aLines = Utils::ReadTail($sLogFile);
		$sLastLine = array_shift($aLines);
		$this->assertMatchesRegularExpression('/^Access restricted to administrators/', $sLastLine, "@$sLastLine@");
	}
}
