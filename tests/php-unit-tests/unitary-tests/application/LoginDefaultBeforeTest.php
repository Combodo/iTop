<?php

use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @runClassInSeparateProcess Required because PHPUnit outputs something earlier, thus causing the headers to be sent
 */
class LoginDefaultBeforeTest extends ItopDataTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		Session::$bAllowCLI = true;
		Session::Start();
		$_REQUEST = [];
		$this->RequireOnceItopFile('application/logindefault.class.inc.php');
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		Session::$bAllowCLI = false;
	}

	public function testOnStart_NoLoginModeRequiredNorStoredInSession()
	{
		$this->CallStartAndCheckLoginModeStored(null, null);
	}

	public function testOnStart_StoreLoginModeRequiredWhenConfigured()
	{
		MetaModel::GetConfig()->SetAllowedLoginTypes(["SAML"]);
		$_REQUEST['login_mode'] = "SAML";

		$this->CallStartAndCheckLoginModeStored("SAML", null);
	}

	public function testOnStart_SwitchLoginModeWhenNewOneRequiredIsConfigured()
	{
		MetaModel::GetConfig()->SetAllowedLoginTypes(["SAML"]);
		$_REQUEST['login_mode'] = "SAML";

		$this->CallStartAndCheckLoginModeStored("SAML");
	}

	public function testOnStart_PreserveCurrentLoginModeInSessionWhenNewOneRequiredIsNotConfigured()
	{
		$_REQUEST['login_mode'] = "SAML";

		$this->CallStartAndCheckLoginModeStored("previous_mode");
	}

	public function testOnStart_PreserveCurrentLoginModeInSessionWhenNoOtherRequired()
	{
		$this->CallStartAndCheckLoginModeStored('previous_mode');
	}

	private function CallStartAndCheckLoginModeStored($expected, ?string $sPreviousLoginMode = 'previous_mode')
	{
		Session::Set('login_mode', $sPreviousLoginMode);

		$iErrorCode = 666;
		$res  = $this->InvokeNonPublicMethod(LoginDefaultBefore::class, 'OnStart', new LoginDefaultBefore(), [&$iErrorCode]);
		$this->assertEquals(LoginWebPage::EXIT_CODE_OK, $iErrorCode);
		$this->assertEquals(LoginWebPage::LOGIN_FSM_CONTINUE, $res);
		$this->assertEquals($expected, Session::Get('login_mode'));
	}
}
