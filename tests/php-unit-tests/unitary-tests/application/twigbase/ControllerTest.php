<?php
namespace Combodo\iTop\Application\TwigBase\Controller;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

class ControllerTest extends ItopDataTestCase {
	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceUnitTestFile('FakeController.php');
	}

	public function CheckAccessProvider() {
		return [
			'simple token access OK' => [
				'access_token' => 'toto123',
				'http_access_token' => 'toto123',
				'bSuccess' => true,
			],
			'simple token access OK sent by POST' => [
				'access_token' => 'toto123',
				'http_access_token' => 'toto123',
				'bSuccess' => true,
				'bPost' => true,
			],
			'simple token access FAILED' => [
				'access_token' => 'toto123',
				'http_access_token' => 'toto124',
				'bSuccess' => false,
			],
			'url encoded token access OK' => [
				'access_token' => 'rfb4j"E?7}-ZJq4T^B*26pk8{;zxem',
				'http_access_token' => 'rfb4j%22E%3F7%7D-ZJq4T%5EB%2A26pk8%7B%3Bzxem',
				'bSuccess' => true,
			],
		];
	}

	/**
	 * Fix N°7147
	 * @dataProvider CheckAccessProvider
	 */
	public function testCheckAccess($sConfiguredAccessToken, $sHttpAccessToken, $bSuccess, $bPost=false){
		$sModuleName = "MyModule";
		$sTokenParamName = "access_token_conf_param";

		$_SESSION = [];
		$_POST = [];
		$_REQUEST = [];

		$_REQUEST['exec_module'] = $sModuleName;
		if ($bPost){
			$_POST[$sTokenParamName] = $sHttpAccessToken;
		} else {
			$_REQUEST[$sTokenParamName] = $sHttpAccessToken;
		}

		$oController = new FakeController();
		$oController->SetAccessTokenConfigParamId($sTokenParamName);

		MetaModel::GetConfig()->SetModuleSetting($sModuleName, $sTokenParamName, $sConfiguredAccessToken);

		if (! $bSuccess){
			$this->expectExceptionMessage("Invalid token");
		}

		$this->InvokeNonPublicMethod(FakeController::class, "CheckAccess", $oController);

		if ($bSuccess){
			$this->assertTrue(true, "no issue encountered");
		}
	}
}
