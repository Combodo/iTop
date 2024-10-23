<?php
namespace Combodo\iTop\Test\UnitTest\HubConnector;

use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use PHPUnit\Framework\SkippedTestCase;

class AjaxPageTest extends ItopDataTestCase {
	const USE_TRANSACTION      = false;
	const AUTHENTICATION_TOKEN = '14b5da9d092f84044187421419a0347e7317bc8cd2b486fdda631be06b959269';
	const AUTHENTICATION_PASSWORD    = "tagada-Secret,007";

	protected function setUp(): void
	{
		$this->SkipIfModuleNotPresent('itop-hub-connector');
		parent::setUp();
	}

	public function testCompileOperation()
	{
		// Given
		static::RecurseMkdir(APPROOT.'data/hub');
		file_put_contents(APPROOT.'data/hub/compile_authent', self::AUTHENTICATION_TOKEN);
		$sLogin = $this->GivenUserInDB(self::AUTHENTICATION_PASSWORD, ['Administrator']);

		$iLastCompilation = filemtime(APPROOT.'env-production');

		// When
		$sOutput = $this->CallItopUrl(
			"/pages/exec.php?exec_module=itop-hub-connector&exec_page=ajax.php",
			[
				'auth_user' => $sLogin,
				'auth_pwd' => self::AUTHENTICATION_PASSWORD,
				'operation' => 	"compile",
				'authent' => self::AUTHENTICATION_TOKEN,
			]
		);

		// Then
		$aRes = json_decode($sOutput, true);
		$this->assertNotNull($aRes, "Response should be a valid json, found instead:" . PHP_EOL . $sOutput);
		$this->assertEquals(
			[
				'code' => 0,
			    'message' => 'Ok',
			    'fields' => []
			],
			$aRes
		);

		clearstatcache();
		$this->assertGreaterThan($iLastCompilation, filemtime(APPROOT.'env-production'), 'The env-production directory should have been rebuilt');
	}

	protected function CallItopUrl($sUri, ?array $aPostFields = null, bool $bXDebugEnabled = false)
	{
		$ch = curl_init();
		if ($bXDebugEnabled) {
			curl_setopt($ch, CURLOPT_COOKIE, 'XDEBUG_SESSION=phpstorm');
		}

		$sUrl = \MetaModel::GetConfig()->Get('app_root_url')."/$sUri";
		var_dump($sUrl);
		curl_setopt($ch, CURLOPT_URL, $sUrl);
		curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
		curl_setopt($ch, CURLOPT_POSTFIELDS, $aPostFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$sOutput = curl_exec($ch);
		//echo "$sUrl error code:".curl_error($ch);
		curl_close($ch);

		return $sOutput;
	}
}