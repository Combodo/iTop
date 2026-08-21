<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application\LoginFSM;

use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use LoginBasic;
use LoginWebPage;

class LoginBasicTest extends ItopDataTestCase
{
	public function testListSupportedLoginModes()
	{
		// Given
		$oLoginBasic = new LoginBasic();

		// When
		$aActual = $oLoginBasic->ListSupportedLoginModes();

		// Then
		$this->assertEquals(['basic'], $aActual);
	}

	public function testOnModeDetectionForLoginPass()
	{
		// Given
		$oLoginBasic = new LoginBasic();
		$_SERVER['HTTP_AUTHORIZATION'] = 'Basic '.base64_encode("Login:BasicTest");

		// When
		$_SESSION = [];
		$iErrorCode = 0;
		$sActualRes = $oLoginBasic->LoginAction(LoginWebPage::LOGIN_STATE_MODE_DETECTION, $iErrorCode);

		// Then
		$this->assertEquals('basic', $_SESSION['login_mode'], 'Login mode should have detected basic mode');
		$this->assertEquals(LoginWebPage::LOGIN_FSM_CONTINUE, $sActualRes, 'No Login FSM error should have been detected');
	}

	public function testOnModeDetectionForRedirect()
	{
		// Given
		$oLoginBasic = new LoginBasic();
		$_SERVER['REDIRECT_HTTP_AUTHORIZATION'] = 'Basic '.base64_encode('Login:BasicTest');

		// When
		$_SESSION = [];
		$iErrorCode = 0;
		$sActualRes = $oLoginBasic->LoginAction(LoginWebPage::LOGIN_STATE_MODE_DETECTION, $iErrorCode);

		// Then
		$this->assertEquals('basic', $_SESSION['login_mode'], 'Login mode should have detected basic mode');
		$this->assertEquals(LoginWebPage::LOGIN_FSM_CONTINUE, $sActualRes, 'No Login FSM error should have been detected');
	}

	public function testOnModeDetectionForBasicLoginPassword()
	{
		// Given
		$oLoginBasic = new LoginBasic();
		$_SERVER['REDIRECT_HTTP_AUTHORIZATION'] = 'Basic '.base64_encode('Login:BasicTest');

		// When
		$_SESSION = [];
		$iErrorCode = 0;
		$sActualRes = $oLoginBasic->LoginAction(LoginWebPage::LOGIN_STATE_MODE_DETECTION, $iErrorCode);

		// Then
		$this->assertEquals('basic', $_SESSION['login_mode'], 'Login mode should have detected basic mode');
		$this->assertEquals(LoginWebPage::LOGIN_FSM_CONTINUE, $sActualRes, 'No Login FSM error should have been detected');
	}

	public function testOnModeDetectionForBearerToken()
	{
		// Given
		$oLoginBasic = new LoginBasic();
		$_SERVER['HTTP_AUTHORIZATION'] = 'Bearer '.base64_encode('LoginBasicTest');

		// When
		$_SESSION = [];
		$iErrorCode = 0;
		$sActualRes = $oLoginBasic->LoginAction(LoginWebPage::LOGIN_STATE_MODE_DETECTION, $iErrorCode);

		// Then
		$this->assertFalse(Session::IsSet('login_mode'), 'No basic login mode should have been detected');
		$this->assertEquals(LoginWebPage::LOGIN_FSM_CONTINUE, $sActualRes, 'No Login FSM error should have been detected');
	}
}
