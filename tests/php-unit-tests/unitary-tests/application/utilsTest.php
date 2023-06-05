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

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use utils;

/**
 * @covers utils
 */
class utilsTest extends ItopTestCase
{
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
			'current -1, required 1024' => [true, -1, 1024],
			'current 1024, required 1024' => [true, 1024, 1024],
			'current 2048, required 1024' => [true, 2048, 1024],
			'current 1024, required 2048' => [false, 1024, 2048],
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
	 * @runInSeparateProcess
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
	 * @dataProvider StrftimeFormatToDateTimeFormatProvider
	 * @covers \utils::StrftimeFormatToDateTimeFormat
	 *
	 * @param string $sInput
	 * @param string $sExpectedFormat
	 *
	 * @return void
	 */
	public function testStrftimeFormatToDateTimeFormat(string $sInput, string $sExpectedFormat)
	{
		$sTestedFormat = utils::StrftimeFormatToDateTimeFormat($sInput);
		$this->assertEquals($sExpectedFormat, $sTestedFormat, "DateTime format transformation for '$sInput' doesn't match. Got '$sTestedFormat', expected '$sExpectedFormat'.");
	}

	public function StrftimeFormatToDateTimeFormatProvider(): array
	{
		return [
			'Standard date time' => [
				'%Y-%m-%d %H:%M:%S',
				'Y-m-d H:i:s',
			],
			'All placeholders' => [
				'%d | %m | %y | %Y | %H | %M | %S | %a | %A | %e | %j | %u | %w | %U | %V | %W | %b | %B | %h | %C | %g | %G | %k | %I | %l | %p | %P | %r | %R | %T | %X | %z | %Z | %c | %D | %F | %s | %x | %n | %t | %%',
				'd | m | y | Y | H | i | s | D | l | j | z | N | w | %U | W | %W | M | F | M | %C | y | Y | G | h | g | A | a | h:i:s A | H:i | H:i:s | %X | O | T | %c | m/d/y | Y-m-d | U | %x | %n | %t | %',
			],
		];
	}

	/**
	 * @dataProvider ToCamelCaseProvider
	 * @covers       utils::ToCamelCase
	 *
	 * @param string $sInput
	 * @param string $sExpectedOutput
	 *
	 * @return void
	 */
	public function testToCamelCase(string $sInput, string $sExpectedOutput)
	{
		$sTestedOutput = utils::ToCamelCase($sInput);
		$this->assertEquals($sExpectedOutput, $sTestedOutput, "Camel case transformation for '$sInput' doesn't match. Got '$sTestedOutput', expected '$sExpectedOutput'.");
	}

	/**
	 * @since 3.1.0
	 * @return \string[][]
	 */
	public function ToCamelCaseProvider(): array
	{
		return [
			'One word' => [
				'hello',
				'Hello',
			],
			'Two words separated with space' => [
				'hello world',
				'HelloWorld',
			],
			'Two words separated with underscore' => [
				'hello_world',
				'HelloWorld',
			],
			'Two words separated with dash' => [
				'hello-world',
				'HelloWorld',
			],
			'Two words separated with dot' => [
				'hello.world',
				'Hello.world',
			],
			'Three words separated with underscore and space' => [
				'hello_there world',
				'HelloThereWorld',
			],
		];
	}

	/**
	 * @dataProvider ToSnakeCaseProvider
	 * @covers       utils::ToSnakeCase
	 *
	 * @param string $sInput
	 * @param string $sExpectedOutput
	 *
	 * @return void
	 */
	public function testToSnakeCase(string $sInput, string $sExpectedOutput)
	{
		$sTestedOutput = utils::ToSnakeCase($sInput);
		$this->assertEquals($sExpectedOutput, $sTestedOutput, "Snake case transformation for '$sInput' doesn't match. Got '$sTestedOutput', expected '$sExpectedOutput'.");
	}

	/**
	 * @since 3.1.0
	 * @return \string[][]
	 */
	public function ToSnakeCaseProvider(): array
	{
		return [
			'One word lowercase' => [
				'hello',
				'hello',
			],
			'One word uppercase' => [
				'HELLO',
				'hello',
			],
			'One word capitalize' => [
				'Hello',
				'hello',
			],
			'Two words separated with space' => [
				'hello world',
				'hello_world',
			],
			'Two words separated with underscore' => [
				'hello_world',
				'hello_world',
			],
			'Two words separated with dash' => [
				'hello-world',
				'hello_world',
			],
			'Two words separated with dot' => [
				'hello.world',
				'hello_world',
			],
			'Two words camel cased' => [
				'HelloWorld',
				'hello_world',
			],
			'Two words camel cased with acronym' => [
				'HTMLWorld',
				'html_world',
			],
			'Three words separated with underscore and space' => [
				'hello_there world',
				'hello_there_world',
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
		$this->assertEquals($sExceptedAcronym, $sTestedAcronym, "Acronym for '$sInput' doesn't match. Got '$sTestedAcronym', expected '$sExceptedAcronym'.");
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
			'Several words, cyrillic alphabet' => [
				'Денис Александра',
				'ДА',
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

	/**
	 * @dataProvider FormatInitialsForMedallionProvider
	 * @covers utils::FormatInitialsForMedallion
	 *
	 * @param string $sInput
	 * @param string $sExpected
	 */
	public function testFormatInitialsForMedallion(string $sInput, string $sExpected)
	{
		$sTested = utils::FormatInitialsForMedallion($sInput);
		$this->assertEquals($sExpected, $sTested);
	}

	/**
	 * @since 3.0.1
	 */
	public function FormatInitialsForMedallionProvider()
	{
		return [
			'All letters kept (2)' => [
				'AB',
				'AB',
			],
			'All letters kept (3)' => [
				'ABC',
				'ABC',
			],
			'Only 3 first letters kept (4)' => [
				'ABCD',
				'ABC',
			],
		];
	}

	/**
	 * @param string $sExpressionToConvert
	 * @param int $iExpectedConvertedValue
	 *
	 * @dataProvider ConvertToBytesProvider
	 */
	public function testConvertToBytes($sExpressionToConvert, $iExpectedConvertedValue)
	{
		$iCurrentConvertedValue = utils::ConvertToBytes($sExpressionToConvert);
		self::assertEquals($iExpectedConvertedValue, $iCurrentConvertedValue, 'Converted value wasn\'t the one expected !');
		self::assertSame($iExpectedConvertedValue, $iCurrentConvertedValue, 'Value was converted but not of the expected type');
	}

	public function ConvertToBytesProvider()
	{
		return [
			'123 int value' => ['123', 123],
			'-1 no limit'   => ['-1', -1],
			'56k'           => ['56k', 56 * 1024],
			'512M'          => ['512M', 512 * 1024 * 1024],
			'2G'            => ['2G', 2 * 1024 * 1024 * 1024],
		];
	}

	/**
	 * @param string|null $sString
	 * @param int $iExpected
	 *
	 * @dataProvider StrLenProvider
	 */
	public function testStrLen(?string $sString, int $iExpected)
	{
		$iComputed = utils::StrLen($sString);
		self::assertEquals($iExpected, $iComputed, 'Length was not as expected');
	}

	public function StrLenProvider(): array
	{
		return [
			'null value' => [null, 0],
			'0 character' => ['', 0],
			'1 character' => ['a', 1],
			'5 characters' => ['abcde', 5],
		];
	}

	/**
	 * Test sanitizer.
	 *
	 * @param $type string type of sanitizer
	 * @param $valueToSanitize ? value to sanitize
	 * @param $expectedResult ? expected result
	 *
	 * @return void
	 *
	 * @dataProvider sanitizerDataProvider
	 */
	public function testSanitizer($type, $valueToSanitize, $expectedResult)
	{
		$this->assertEquals($expectedResult, utils::Sanitize($valueToSanitize, null, $type), 'url sanitize failed');
	}

	/**
	 * DataProvider for testSanitizer
	 *
	 * @return array
	 */
	public function sanitizerDataProvider()
	{
		return [
			'good integer'            => [utils::ENUM_SANITIZATION_FILTER_INTEGER, '2565', '2565'],
			'bad integer'             => [utils::ENUM_SANITIZATION_FILTER_INTEGER, 'a2656', '2656'],
			/**
			 * 'class' filter needs a loaded datamodel... and is only an indirection to \MetaModel::IsValidClass so might very important to test !
			 * If we switch this class to ItopDataTestCase then we are seeing :
			 *   - the class now takes 18s to process instead of... 459ms when using ItopTestCase !!!
			 *   - multiple errors are thrown in testGetAbsoluteUrlAppRootPersistency :(
			 * We decided it wasn't worse the effort to test the 'class' filter !
			 */
			//			'good class' => ['class', 'UserRequest', 'UserRequest'],
			//			'bad class' => ['class', 'MyUserRequest',null],
			'good string'             => [utils::ENUM_SANITIZATION_FILTER_STRING, 'Is Peter smart and funny?', 'Is Peter smart and funny?'],
			'bad string'              => [utils::ENUM_SANITIZATION_FILTER_STRING, 'Is Peter <smart> & funny?', 'Is Peter &#60;smart&#62; &#38; funny?'],
			'good transaction_id'     => [utils::ENUM_SANITIZATION_FILTER_TRANSACTION_ID, '8965.-dd', '8965.-dd'],
			'bad transaction_id'      => [utils::ENUM_SANITIZATION_FILTER_TRANSACTION_ID, '8965.-dd+', null],
			'good route'              => [utils::ENUM_SANITIZATION_FILTER_ROUTE, 'object.modify', 'object.modify'],
			'good route with underscore' => [utils::ENUM_SANITIZATION_FILTER_ROUTE, 'object.apply_modify', 'object.apply_modify'],
			'bad route with space'    => [utils::ENUM_SANITIZATION_FILTER_ROUTE, 'object modify', null],
			'good operation'          => [utils::ENUM_SANITIZATION_FILTER_OPERATION, 'modify', 'modify'],
			'good operation with underscore' => [utils::ENUM_SANITIZATION_FILTER_OPERATION, 'apply_modify', 'apply_modify'],
			'bad operation with space' => [utils::ENUM_SANITIZATION_FILTER_OPERATION, 'apply modify', null],
			'good parameter'          => [utils::ENUM_SANITIZATION_FILTER_PARAMETER, 'JU8965-dd=_', 'JU8965-dd=_'],
			'bad parameter'           => [utils::ENUM_SANITIZATION_FILTER_PARAMETER, '8965.-dd+', null],
			'good field_name'         => [utils::ENUM_SANITIZATION_FILTER_FIELD_NAME, 'Name->bUzz38', 'Name->bUzz38'],
			'bad field_name'          => [utils::ENUM_SANITIZATION_FILTER_FIELD_NAME, 'name-buzz', null],
			'good context_param'      => [utils::ENUM_SANITIZATION_FILTER_CONTEXT_PARAM, '%dssD25_=%:+-', '%dssD25_=%:+-'],
			'bad context_param'       => [utils::ENUM_SANITIZATION_FILTER_CONTEXT_PARAM, '%dssD,25_=%:+-', null],
			'good element_identifier' => [utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER, 'AD05nb', 'AD05nb'],
			'bad element_identifier' => [utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER, 'AD05nb+', 'AD05nb'],
			'good url' => [utils::ENUM_SANITIZATION_FILTER_URL, 'https://www.w3schools.com', 'https://www.w3schools.com'],
			'bad url' => [utils::ENUM_SANITIZATION_FILTER_URL, 'https://www.w3schoo��ls.co�m', null],
			'url with injection' => [utils::ENUM_SANITIZATION_FILTER_URL, 'https://demo.combodo.com/simple/pages/UI.php?operation=full_text&text=<img zzz src=x onerror=alert(1) //>', null],
			'raw_data' => ['raw_data', '<Test>\s😃😃😃', '<Test>\s😃😃😃'],
		];
	}

	/**
	 * @return void
	 *
	 * @dataProvider escapeHtmlProvider
	 */
	public function testEscapeHtml($sInput, $sExpectedEscaped)
	{
		if (is_null($sExpectedEscaped)) {
			$sExpectedEscaped = $sInput;
		}

		$sEscaped = utils::EscapeHtml($sInput);
		self::assertSame($sExpectedEscaped, $sEscaped);

		$sEscapedDecoded = utils::EscapedHtmlDecode($sEscaped);
		self::assertSame($sInput, $sEscapedDecoded);
	}

	public function escapeHtmlProvider()
	{
		return [
			'no escape' => ['abcdefghijklmnop', null],
			'&amp;'     => ['abcdefghijklmnop&0123456789', 'abcdefghijklmnop&amp;0123456789'],
			['"double quotes"', '&quot;double quotes&quot;'],
			["'simple quotes'", '&apos;simple quotes&apos;'],
		];
	}
}
