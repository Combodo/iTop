<?php
/**
 * Copyright (C) 2018 Dennis Lassiter
 *
 * This file is part of iTop.
 *
 *  iTop is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 * @covers utils
 */
class UtilsTest extends \Combodo\iTop\Test\UnitTest\ItopTestCase
{
	public function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'application/utils.inc.php');
	}

	public function testEndsWith()
	{
		$this->assertFalse(utils::EndsWith('a', 'bbbb'));
	}

	/**
	 * @dataProvider memoryLimitDataProvider
	 */
	public function testIsMemoryLimit($expected, $memoryLimit, $requiredMemory)
	{
		$this->assertSame($expected, utils::IsMemoryLimitOk($memoryLimit, $requiredMemory));
	}

	/**
	 * DataProvider for testIsMemoryLimitOk
	 *
	 * @return array
	 */
	public function memoryLimitDataProvider()
	{
		return [
			[true, '-1', 1024],
			[true, -1, 1024],
			[true, 1024, 1024],
			[true, 2048, 1024],
			[false, 1024, 2048],
		];
	}

	/**
	 * @dataProvider realPathDataProvider
	 * @covers       utils::RealPath()
	 */
	public function testRealPath($sPath, $sBasePath, $expected)
	{
		$this->assertSame($expected, utils::RealPath($sPath, $sBasePath), "utils::RealPath($sPath, $sBasePath) does not match $expected");
	}

	public function realPathDataProvider()
	{
		parent::setUp(); // if not called, APPROOT won't be defined :(

		$sSep = DIRECTORY_SEPARATOR;
		$sItopRootRealPath = realpath(APPROOT).$sSep;
		$sLicenseFileName = 'license.txt';
		if (!is_file(APPROOT.$sLicenseFileName))
		{
			$sLicenseFileName = 'LICENSE';
		}

		return [
			$sLicenseFileName => [APPROOT.$sLicenseFileName, APPROOT, $sItopRootRealPath.$sLicenseFileName],
			'unexisting file' => [APPROOT.'license_DOES_NOT_EXIST.txt', APPROOT, false],
			'/'.$sLicenseFileName => [APPROOT.$sSep.$sLicenseFileName, APPROOT, $sItopRootRealPath.$sLicenseFileName],
			'%2f'.$sLicenseFileName => [APPROOT.'%2f'. $sLicenseFileName, APPROOT, false],
			'../'.$sLicenseFileName => [APPROOT.'..'.$sSep.$sLicenseFileName, APPROOT, false],
			'%2e%2e%2f'.$sLicenseFileName => [APPROOT.'%2e%2e%2f'.$sLicenseFileName, APPROOT, false],
			'application/utils.inc.php with basepath=APPROOT' => [
				APPROOT.'application/utils.inc.php',
				APPROOT,
				$sItopRootRealPath.'application'.$sSep.'utils.inc.php',
			],
			'application/utils.inc.php with basepath=APPROOT/application' => [
				APPROOT.'application/utils.inc.php',
				APPROOT.'application',
				$sItopRootRealPath.'application'.$sSep.'utils.inc.php',
			],
			'basepath containing / and \\' => [
				APPROOT.'sources/Form/Form.php',
				APPROOT.'sources/Form\\Form.php',
				$sItopRootRealPath.'sources'.$sSep.'Form'.$sSep.'Form.php',
			],
		];
	}

	/**
	 * @dataProvider LocalPathProvider
	 *
	 * @param $sAbsolutePath
	 * @param $expected
	 */
	public function testLocalPath($sAbsolutePath, $expected)
	{
		$this->assertSame($expected, utils::LocalPath($sAbsolutePath));

	}

	public function LocalPathProvider()
	{
		return array(
			'index.php' => array(
				'sAbsolutePath' => APPROOT.'index.php',
				'expected' => 'index.php',
			),
			'non existing' => array(
				'sAbsolutePath' => APPROOT.'nonexisting/nonexisting',
				'expected' => false,
			),
			'outside' => array(
				'sAbsolutePath' => '/tmp',
				'expected' => false,
			),
			'application/cmdbabstract.class.inc.php' => array(
				'sAbsolutePath' => APPROOT.'application/cmdbabstract.class.inc.php',
				'expected' => 'application/cmdbabstract.class.inc.php',
			),
			'dir' => array(
				'sAbsolutePath' => APPROOT.'application/.',
				'expected' => 'application',
			),
			'root' => array(
				'sAbsolutePath' => APPROOT.'.',
				'expected' => '',
			),
		);
	}

	/**
	 * @dataProvider appRootUrlProvider
	 * @covers utils::GetAppRootUrl
	 */
	public function testGetAppRootUrl($sReturnValue, $sCurrentScript, $sAppRoot, $sAbsoluteUrl)
	{
		$this->assertEquals($sReturnValue, utils::GetAppRootUrl($sCurrentScript, $sAppRoot, $sAbsoluteUrl));
	}

	public function appRootUrlProvider()
	{
		return array(
			'Setup index (windows antislash)' => array('http://localhost/', 'C:\Dev\wamp64\www\itop-dev\setup\index.php', 'C:\Dev\wamp64\www\itop-dev', 'http://localhost/setup/'),
			'Setup index (windows slash)' => array('http://127.0.0.1/', 'C:/web/setup/index.php', 'C:/web', 'http://127.0.0.1/setup/'),
			'Setup index (windows slash, drive letter case difference)' => array('http://127.0.0.1/', 'c:/web/setup/index.php', 'C:/web', 'http://127.0.0.1/setup/'),
		);
	}

	public function GetAbsoluteUrlAppRootPersistency() {
		$this->setUp();

		return [
			'ForceTrustProxy 111' => [
				'bBehindReverseProxy' => false,
				'bForceTrustProxy1' => true,
				'sExpectedAppRootUrl1' => 'https://proxy.com:4443/',
				'bForceTrustProxy2' => true,
				'sExpectedAppRootUrl2' => 'https://proxy.com:4443/',
				'bForceTrustProxy3' => true,
				'sExpectedAppRootUrl3' => 'https://proxy.com:4443/',
			],
			'ForceTrustProxy 101' => [
				'bBehindReverseProxy' => false,
				'bForceTrustProxy1' => true,
				'sExpectedAppRootUrl1' => 'https://proxy.com:4443/',
				'bForceTrustProxy2' => false,
				'sExpectedAppRootUrl2' => 'https://proxy.com:4443/',
				'bForceTrustProxy3' => true,
				'sExpectedAppRootUrl3' => 'https://proxy.com:4443/',
			],
			'ForceTrustProxy 011' => [
				'bBehindReverseProxy' => false,
				'bForceTrustProxy1' => false,
				'sExpectedAppRootUrl1' => 'http://example.com/',
				'bForceTrustProxy2' => true,
				'sExpectedAppRootUrl2' => 'https://proxy.com:4443/',
				'bForceTrustProxy3' => true,
				'sExpectedAppRootUrl3' => 'https://proxy.com:4443/',
			],
			'ForceTrustProxy 110' => [
				'bBehindReverseProxy' => false,
				'bForceTrustProxy1' => true,
				'sExpectedAppRootUrl1' => 'https://proxy.com:4443/',
				'bForceTrustProxy2' => true,
				'sExpectedAppRootUrl2' => 'https://proxy.com:4443/',
				'bForceTrustProxy3' => false,
				'sExpectedAppRootUrl3' => 'https://proxy.com:4443/',
			],
			'ForceTrustProxy 010' => [
				'bBehindReverseProxy' => false,
				'bForceTrustProxy1' => false,
				'sExpectedAppRootUrl1' => 'http://example.com/',
				'bForceTrustProxy2' => true,
				'sExpectedAppRootUrl2' => 'https://proxy.com:4443/',
				'bForceTrustProxy3' => false,
				'sExpectedAppRootUrl3' => 'https://proxy.com:4443/',
			],
			'ForceTrustProxy 001' => [
				'bBehindReverseProxy' => false,
				'bForceTrustProxy1' => false,
				'sExpectedAppRootUrl1' => 'http://example.com/',
				'bForceTrustProxy2' => false,
				'sExpectedAppRootUrl2' => 'http://example.com/',
				'bForceTrustProxy3' => true,
				'sExpectedAppRootUrl3' => 'https://proxy.com:4443/',
			],
			'ForceTrustProxy 000' => [
				'bBehindReverseProxy' => false,
				'bForceTrustProxy1' => false,
				'sExpectedAppRootUrl1' => 'http://example.com/',
				'bForceTrustProxy2' => false,
				'sExpectedAppRootUrl2' => 'http://example.com/',
				'bForceTrustProxy3' => false,
				'sExpectedAppRootUrl3' => 'http://example.com/',
			],
			'BehindReverseProxy ForceTrustProxy 010' => [
				'bBehindReverseProxy' => true,
				'bForceTrustProxy1' => false,
				'sExpectedAppRootUrl1' => 'https://proxy.com:4443/',
				'bForceTrustProxy2' => true,
				'sExpectedAppRootUrl2' => 'https://proxy.com:4443/',
				'bForceTrustProxy3' => false,
				'sExpectedAppRootUrl3' => 'https://proxy.com:4443/',
			],
		];
	}

	/**
	 * @dataProvider GetAbsoluteUrlAppRootPersistency
	 */
	public function testGetAbsoluteUrlAppRootPersistency($bBehindReverseProxy,$bForceTrustProxy1 ,$sExpectedAppRootUrl1,$bForceTrustProxy2 , $sExpectedAppRootUrl2,$bForceTrustProxy3 , $sExpectedAppRootUrl3)
	{
		utils::GetConfig()->Set('behind_reverse_proxy', $bBehindReverseProxy);
		utils::GetConfig()->Set('app_root_url', '');

		//should match http://example.com/ when not trusting the proxy
		//should match https://proxy.com:4443/ when  trusting the proxy
		$_SERVER = [
			'REMOTE_ADDR' => '127.0.0.1', //is not set, disable IsProxyTrusted
			'SERVER_NAME' => 'example.com',
			'SERVER_PORT' => '80',
			'REQUEST_URI' => '/index.php?baz=1',
			'SCRIPT_NAME' => '/index.php',
			'SCRIPT_FILENAME' => APPROOT.'index.php',
			'QUERY_STRING' => 'baz=1',
			'HTTP_X_FORWARDED_HOST' => 'proxy.com',
			'HTTP_X_FORWARDED_PORT' => '4443',
			'HTTP_X_FORWARDED_PROTO' => 'https',
			'HTTPS' => null,
		];

		$this->assertEquals($sExpectedAppRootUrl1, utils::GetAbsoluteUrlAppRoot($bForceTrustProxy1));

		$this->assertEquals($sExpectedAppRootUrl2, utils::GetAbsoluteUrlAppRoot($bForceTrustProxy2));

		$this->assertEquals($sExpectedAppRootUrl3, utils::GetAbsoluteUrlAppRoot($bForceTrustProxy3));
	}


	/**
	 * @dataProvider GetDefaultUrlAppRootProvider
	 */
	public function testGetDefaultUrlAppRoot($bForceTrustProxy, $bConfTrustProxy, $aServerVars, $sExpectedAppRootUrl)
	{
		$_SERVER = $aServerVars;
		utils::GetConfig()->Set('behind_reverse_proxy', $bConfTrustProxy);
		$sAppRootUrl = utils::GetDefaultUrlAppRoot($bForceTrustProxy);
		$this->assertEquals($sExpectedAppRootUrl, $sAppRootUrl);
	}

	public function GetDefaultUrlAppRootProvider()
	{
		$this->setUp();

		$baseServerVar = [
			'REMOTE_ADDR' => '127.0.0.1', //is not set, disable IsProxyTrusted
			'SERVER_NAME' => 'example.com',
			'HTTP_X_FORWARDED_HOST' => null,
			'SERVER_PORT' => '80',
			'HTTP_X_FORWARDED_PORT' => null,
			'REQUEST_URI' => '/index.php?baz=1',
			'SCRIPT_NAME' => '/index.php',
			'SCRIPT_FILENAME' => APPROOT.'index.php',
			'QUERY_STRING' => 'baz=1',
			'HTTP_X_FORWARDED_PROTO' => null,
			'HTTP_X_FORWARDED_PROTOCOL' => null,
			'HTTPS' => null,
		];

		return [
			'no proxy, http' => [
				'bForceTrustProxy' => false,
				'bConfTrustProxy' => false,
				'aServerVars' => array_merge($baseServerVar, []),
				'sExpectedAppRootUrl' => 'http://example.com/',
			],
			'no proxy, subPath, http' => [
				'bForceTrustProxy' => false,
				'bConfTrustProxy' => false,
				'aServerVars' => array_merge($baseServerVar, [
					'REQUEST_URI' => '/foo/index.php?baz=1',
				]),
				'sExpectedAppRootUrl' => 'http://example.com/foo/',
			],
			'IIS lack REQUEST_URI' => [
				'bForceTrustProxy' => false,
				'bConfTrustProxy' => false,
				'aServerVars' => array_merge($baseServerVar, [
					'REQUEST_URI' => null,
					'SCRIPT_NAME' => '/foo/index.php',
				]),
				'sExpectedAppRootUrl' => 'http://example.com/foo/',
			],
			'no proxy, https' => [
				'bForceTrustProxy' => false,
				'bConfTrustProxy' => false,
				'aServerVars' => array_merge($baseServerVar, [
					'SERVER_PORT' => '443',
					'HTTPS' => 'on',
				]),
				'sExpectedAppRootUrl' => 'https://example.com/',
			],
			'no proxy, https on 4443' => [
				'bForceTrustProxy' => false,
				'bConfTrustProxy' => false,
				'aServerVars' => array_merge($baseServerVar, [
					'SERVER_PORT' => '4443',
					'HTTPS' => 'on',
				]),
				'sExpectedAppRootUrl' => 'https://example.com:4443/',
			],
			'with proxy, not enabled' => [
				'bForceTrustProxy' => false,
				'bConfTrustProxy' => false,
				'aServerVars' => array_merge($baseServerVar, [
					'HTTP_X_FORWARDED_HOST' => 'proxy.com',
					'HTTP_X_FORWARDED_PORT' => '4443',
					'HTTP_X_FORWARDED_PROTO' => 'https',
				]),
				'sExpectedAppRootUrl' => 'http://example.com/',
			],
			'with proxy, enabled HTTP_X_FORWARDED_PROTO' => [
				'bForceTrustProxy' => false,
				'bConfTrustProxy' => true,
				'aServerVars' => array_merge($baseServerVar, [
					'HTTP_X_FORWARDED_HOST' => 'proxy.com',
					'HTTP_X_FORWARDED_PORT' => '4443',
					'HTTP_X_FORWARDED_PROTO' => 'https',
				]),
				'sExpectedAppRootUrl' => 'https://proxy.com:4443/',
			],
			'with proxy, enabled - alt HTTP_X_FORWARDED_PROTO COL' => [
				'bForceTrustProxy' => false,
				'bConfTrustProxy' => true,
				'aServerVars' => array_merge($baseServerVar, [
					'HTTP_X_FORWARDED_HOST' => 'proxy.com',
					'HTTP_X_FORWARDED_PORT' => '4443',
					'HTTP_X_FORWARDED_PROTOCOL' => 'https',
				]),
				'sExpectedAppRootUrl' => 'https://proxy.com:4443/',
			],
			'with proxy, disabled, forced' => [
				'bForceTrustProxy' => true,
				'bConfTrustProxy' => false,
				'aServerVars' => array_merge($baseServerVar, [
					'HTTP_X_FORWARDED_HOST' => 'proxy.com',
					'HTTP_X_FORWARDED_PORT' => '4443',
					'HTTP_X_FORWARDED_PROTO' => 'https',
				]),
				'sExpectedAppRootUrl' => 'https://proxy.com:4443/',
			],
			'with proxy, enabled, forced' => [
				'bForceTrustProxy' => true,
				'bConfTrustProxy' => true,
				'aServerVars' => array_merge($baseServerVar, [
					'HTTP_X_FORWARDED_HOST' => 'proxy.com',
					'HTTP_X_FORWARDED_PORT' => '4443',
					'HTTP_X_FORWARDED_PROTO' => 'https',
				]),
				'sExpectedAppRootUrl' => 'https://proxy.com:4443/',
			],

			'with proxy, disabled, forced, no remote addr' => [
				'bForceTrustProxy' => true,
				'bConfTrustProxy' => false,
				'aServerVars' => array_merge($baseServerVar, [
					'REMOTE_ADDR' => null,
					'HTTP_X_FORWARDED_HOST' => 'proxy.com',
					'HTTP_X_FORWARDED_PORT' => '4443',
					'HTTP_X_FORWARDED_PROTO' => 'https',
				]),
				'sExpectedAppRootUrl' => 'https://proxy.com:4443/',
			],
			'with proxy, enabled, no remote addr' => [
				'bForceTrustProxy' => false,
				'bConfTrustProxy' => true,
				'aServerVars' => array_merge($baseServerVar, [
					'REMOTE_ADDR' => null,
					'HTTP_X_FORWARDED_HOST' => 'proxy.com',
					'HTTP_X_FORWARDED_PORT' => '4443',
					'HTTP_X_FORWARDED_PROTO' => 'https',
				]),
				'sExpectedAppRootUrl' => 'http://example.com/',
			],
		];
	}

	/**
	 * @dataProvider ToAcronymProvider
	 * @covers       utils::ToAcronym
	 *
	 * @param string $sInput
	 * @param string $sExceptedAcronym
	 */
	public function testToAcronym(string $sInput, string $sExceptedAcronym)
	{
		$sTestedAcronym = utils::ToAcronym($sInput);
		$this->assertEquals($sTestedAcronym, $sExceptedAcronym, "Acronym for '$sInput' doesn't match. Got '$sTestedAcronym', expected '$sExceptedAcronym'.");
	}

	/**
	 * @since 3.0.0
	 */
	public function ToAcronymProvider()
	{
		return [
			'One word, upper case letter' => [
				'Carrie',
				'C',
			],
			'One word, lower case letter' => [
				'carrie',
				'C',
			],
			'Application name' => [
				'iTop',
				'I',
			],
			'Several words, upper case letters' => [
				'Carrie Ann Moss',
				'CAM',
			],
			'Several words, mixed case letters' => [
				'My name My name',
				'MM',
			],
			'Several words, upper case letters, two first hyphened' => [
				'Lily-Rose Depp',
				'LRD',
			],
			'Several words, mixed case letters, two first hyphened' => [
				'Lily-rose Depp',
				'LD',
			],
			'Several words, upper case letetrs, two last hypened' => [
				'Jada Pinkett-Smith',
				'JPS',
			],
			'Several words, mixed case letters, two last hyphened' => [
				'Jada Pinkett-smith',
				'JP',
			],
		];
	}

	/**
	 * @dataProvider GetMentionedObjectsFromTextProvider
	 * @covers       utils::GetMentionedObjectsFromText
	 *
	 * @param string $sInput
	 * @param string $sFormat
	 * @param array $aExceptedMentionedObjects
	 *
	 * @throws \Exception
	 */
	public function testGetMentionedObjectsFromText(string $sInput, string $sFormat, array $aExceptedMentionedObjects)
	{
		$aTestedMentionedObjects = utils::GetMentionedObjectsFromText($sInput, $sFormat);

		$sExpectedAsString = print_r($aExceptedMentionedObjects, true);
		$sTestedAsString = print_r($aTestedMentionedObjects, true);

		$this->assertEquals($sTestedAsString, $sExpectedAsString, "Found mentioned objects don't match. Got: $sTestedAsString, expected $sExpectedAsString");
	}

	/**
	 * @since 3.0.0
	 */
	public function GetMentionedObjectsFromTextProvider(): array
	{
		$sAbsUrlAppRoot = utils::GetAbsoluteUrlAppRoot();

		return [
			'No object' => [
				"Begining
				Second line
				End",
				utils::ENUM_TEXT_FORMAT_HTML,
				[],
			],
			'1 UserRequest' => [
				"Begining
				Before link <a href=\"$sAbsUrlAppRoot/pages/UI.php&operation=details&class=UserRequest&id=12345&foo=bar\">R-012345</a> After link
				End",
				utils::ENUM_TEXT_FORMAT_HTML,
				[
					'UserRequest' => ['12345'],
				],
			],
			'2 UserRequests' => [
				"Begining
				Before link <a href=\"$sAbsUrlAppRoot/pages/UI.php&operation=details&class=UserRequest&id=12345&foo=bar\">R-012345</a> After link
				And <a href=\"$sAbsUrlAppRoot/pages/UI.php&operation=details&class=UserRequest&id=987654&foo=bar\">R-987654</a>
				End",
				utils::ENUM_TEXT_FORMAT_HTML,
				[
					'UserRequest' => ['12345', '987654'],
				],
			],
			'1 UserRequest, 1 Person' => [
				"Begining
				Before link <a href=\"$sAbsUrlAppRoot/pages/UI.php&operation=details&class=UserRequest&id=12345&foo=bar\">R-012345</a> After link
				And <a href=\"$sAbsUrlAppRoot/pages/UI.php&operation=details&class=Person&id=3&foo=bar\">Claude Monet</a>
				End",
				utils::ENUM_TEXT_FORMAT_HTML,
				[
					'UserRequest' => ['12345'],
					'Person' => ['3'],
				],
			],
		];
	}
}
