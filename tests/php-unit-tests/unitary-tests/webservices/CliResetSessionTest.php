<?php

namespace Combodo\iTop\Test\UnitTest\Webservices;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Config;
use Exception;
use MetaModel;


class CliResetSessionTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;

	private $sCookieFile = "";
	private $sUrl;
	private $sLogin;
	private $sPassword = "Iuytrez9876543ç_è-(";
	protected $sConfigTmpBackupFile;


	/**
     * @throws Exception
     */
    protected function setUp(): void
    {
	    parent::setUp();

		$this->sConfigTmpBackupFile = tempnam(sys_get_temp_dir(), "config_");
	    MetaModel::GetConfig()->WriteToFile($this->sConfigTmpBackupFile);

	    $this->sLogin = "rest-user-".date('dmYHis');
	    $this->CreateTestOrganization();

	    $this->sCookieFile = tempnam(sys_get_temp_dir(), 'jsondata_');

	    $this->sUrl = \MetaModel::GetConfig()->Get('app_root_url');

	    $oRestProfile = \MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'REST Services User'), true);
	    $oAdminProfile = \MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'Administrator'), true);

	    if (is_object($oRestProfile) && is_object($oAdminProfile)) {
		    $oUser = $this->CreateUser($this->sLogin, $oRestProfile->GetKey(), $this->sPassword);
		    $this->AddProfileToUser($oUser, $oAdminProfile->GetKey());
	    }
    }

	protected function tearDown(): void
	{
		parent::tearDown();

		if (! is_null($this->sConfigTmpBackupFile) && is_file($this->sConfigTmpBackupFile)){
			//put config back
			$sConfigPath = MetaModel::GetConfig()->GetLoadedFile();
			@chmod($sConfigPath, 0770);
			$oConfig = new Config($this->sConfigTmpBackupFile);
			$oConfig->WriteToFile($sConfigPath);
			@chmod($sConfigPath, 0444);
			unlink($this->sConfigTmpBackupFile);
		}

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
		$ch = curl_init();

		curl_setopt ($ch, CURLOPT_COOKIEJAR, $this->sCookieFile);
		curl_setopt ($ch, CURLOPT_COOKIEFILE, $this->sCookieFile);

		$sUrl = "$this->sUrl/$sUri";
		if (!is_null($sForcedLoginMode)){
			$sUrl .= "?login_mode=$sForcedLoginMode";
		}
		curl_setopt($ch, CURLOPT_URL, $sUrl);
		curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
		curl_setopt($ch, CURLOPT_POSTFIELDS, $aPostFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		// Force disable of certificate check as most of dev / test env have a self-signed certificate
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

		$sResponse = curl_exec($ch);
		/** $sResponse example
		 *  "HTTP/1.1 200 OK
		Date: Wed, 07 Jun 2023 05:00:40 GMT
		Server: Apache/2.4.29 (Ubuntu)
		Set-Cookie: itop-2e83d2e9b00e354fdc528621cac532ac=q7ldcjq0rvbn33ccr9q8u8e953; path=/
		 */
		//var_dump($sResponse);
		$iHeaderSize = curl_getinfo($ch,CURLINFO_HEADER_SIZE);
		$sBody = substr($sResponse, $iHeaderSize);

		//$iHttpCode = intval(curl_getinfo($ch, CURLINFO_HTTP_CODE));
		if (preg_match('/HTTP.* (\d*) /', $sResponse, $aMatches)){
			$sHttpCode = $aMatches[1];
		} else {
			$sHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		}
		curl_close ($ch);

		$this->assertEquals(200, $sHttpCode, "The test logic assumes that the HTTP request is correctly handled");
		return $sBody;
	}
}
