<?php

namespace Combodo\iTop\Test\UnitTest\Webservices;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Config;
use Exception;
use MetaModel;

class CliResetSessionTest extends ItopDataTestCase
{
	public const USE_TRANSACTION = false;

	private $sCookieFile = "";
	private $sLogin;
	private $sPassword = "Iuytrez9876543ç_è-(";

	/**
	 * @throws Exception
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->BackupConfiguration();

		$this->sLogin = "rest-user-".date('dmYHis');
		$this->CreateTestOrganization();

		$this->sCookieFile = tempnam(sys_get_temp_dir(), 'jsondata_');

		$oRestProfile = \MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", ['name' => 'REST Services User'], true);
		$oAdminProfile = \MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", ['name' => 'Administrator'], true);

		if (is_object($oRestProfile) && is_object($oAdminProfile)) {
			$oUser = $this->CreateUser($this->sLogin, $oRestProfile->GetKey(), $this->sPassword);
			$this->AddProfileToUser($oUser, $oAdminProfile->GetKey());
		}
	}

	protected function tearDown(): void
	{
		parent::tearDown();

		if (!empty($this->sCookieFile)) {
			unlink($this->sCookieFile);
		}
	}

	protected function GivenConfigFileAllowedLoginTypes($aAllowedLoginTypes): void
	{
		@chmod(MetaModel::GetConfig()->GetLoadedFile(), 0770);
		MetaModel::GetConfig()->SetAllowedLoginTypes($aAllowedLoginTypes);
		MetaModel::GetConfig()->WriteToFile();
		@chmod(MetaModel::GetConfig()->GetLoadedFile(), 0444);
	}

	public function GivenAFirstQueryHasBeenSentWithCookiesEnabled(): void
	{
		$aPostFields = [
			'version'   => '1.2',
			'auth_user' => $this->sLogin,
			'auth_pwd'  => $this->sPassword,
			'json_data' => '{"operation": "core/get", "class": "User", "key": 99999, "output_fields": "id"}',
		];
		$sOutput = $this->SendHTTPRequestWithCookies('webservices/rest.php', $aPostFields);
		$this->assertStringStartsWith('{"code":0,"message":"Found: 0"', $sOutput, "Failed to establish the given: the first call should be successful (and set the session)");
	}

	public function LoginModesProvider()
	{
		return [
			'no login_mode specified' => [
				'sConfiguredLoginModes' => 'form|external|basic',
				'sForcedLoginMode' => null,
			],
			'form' => [
				'sConfiguredLoginModes' => 'form|external|basic',
				'sForcedLoginMode' => 'form',
			],
			'external' => [
				'sConfiguredLoginModes' => 'form|external|basic',
				'sForcedLoginMode' => 'external',
			],
			'basic' => [
				'sConfiguredLoginModes' => 'form|external|basic',
				'sForcedLoginMode' => 'basic',
			],
			'url' => [
				'sConfiguredLoginModes' => 'form|external|basic|url',
				'sForcedLoginMode' => 'url',
			],
			'cas' => [
				'sConfiguredLoginModes' => 'form|external|basic|cas',
				'sForcedLoginMode' => 'cas',
			],
		];
	}

	/**
	 * @dataProvider LoginModesProvider
	 */
	public function testVariousLoginModes($sAllowedLoginTypes, $sRequestedLoginMode)
	{
		$this->GivenConfigFileAllowedLoginTypes(explode('|', $sAllowedLoginTypes));
		$this->GivenAFirstQueryHasBeenSentWithCookiesEnabled();

		//2nd call to REST API made with previous session cookie
		$sOutput = $this->SendHTTPRequestWithCookies('webservices/rest.php', [], $sRequestedLoginMode);
		$this->assertStringContainsString('Invalid login', $sOutput, "Omitting auth_user/auth_pwd should not be allowed");
	}

	public function OtherWebServicesProvider()
	{
		return [
			'import' => [ 'webservices/import.php' ],
			'synchro_exec' => [ 'synchro/synchro_exec.php' ],
			'synchro_import' => [ 'synchro/synchro_import.php' ],
		];
	}

	/**
	 * @dataProvider OtherWebServicesProvider
	 */
	public function testVariousWebServices($sUri)
	{
		$this->GivenAFirstQueryHasBeenSentWithCookiesEnabled();

		$sOutput = $this->SendHTTPRequestWithCookies($sUri, []);
		$this->assertStringContainsString('Invalid login', $sOutput, "Omitting auth_user/auth_pwd should not be allowed");
	}

	/**
	 * @return array($iHttpCode, $sBody)
	 */
	private function SendHTTPRequestWithCookies($sUri, $aPostFields, $sForcedLoginMode = null): string
	{
		if (!is_null($sForcedLoginMode)) {
			$sUri .= "?login_mode=$sForcedLoginMode";
		}

		$aCurlOptions = [
			CURLOPT_COOKIEJAR => $this->sCookieFile,
			CURLOPT_COOKIEFILE => $this->sCookieFile,
			CURLOPT_HEADER => 1,
		];

		$sResponse = $this->CallItopUri($sUri, $aPostFields, $aCurlOptions);
		var_dump($this->aLastCurlGetInfo);
		/** $sResponse example
		 *  "HTTP/1.1 200 OK
		Date: Wed, 07 Jun 2023 05:00:40 GMT
		Server: Apache/2.4.29 (Ubuntu)
		Set-Cookie: itop-2e83d2e9b00e354fdc528621cac532ac=q7ldcjq0rvbn33ccr9q8u8e953; path=/
		 */
		//var_dump($sResponse);
		$iHeaderSize = $this->aLastCurlGetInfo['header_size'] ?? 0;
		$sBody = substr($sResponse, $iHeaderSize);

		//$iHttpCode = intval(curl_getinfo($ch, CURLINFO_HTTP_CODE));
		if (preg_match('/HTTP.* (\d*) /', $sResponse, $aMatches)) {
			$sHttpCode = $aMatches[1];
		} else {
			$sHttpCode = $this->aLastCurlGetInfo['http_code'] ?? -1;
		}

		$this->assertEquals(200, $sHttpCode, "The test logic assumes that the HTTP request is correctly handled");
		return $sBody;
	}
}
