<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application\WebPage;

use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @covers \Combodo\iTop\Application\WebPage\WebPage
 */
class WebPageTest extends ItopDataTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceUnitTestFile("./WebPageMock.php");
	}

	/**
	 * @dataProvider LinkScriptMethodsProvider
	 * @covers       \Combodo\iTop\Application\WebPage\WebPage::LinkScriptFromAppRoot()
	 * @covers       \Combodo\iTop\Application\WebPage\WebPage::LinkScriptFromModule()
	 * @covers       \Combodo\iTop\Application\WebPage\WebPage::LinkScriptFromURI()
	 *
	 * @param string $sMethodName
	 * @param string $sInputURI
	 * @param int $iExpectedCount
	 *
	 * @return void
	 * @throws \ReflectionException
	 */
	public function testLinkScriptMethods(string $sMethodName, string $sInputURI, int $iExpectedCount): void
	{
		$oPage = new WebPageMock('');

		$this->InvokeNonPublicMethod(WebPage::class, "EmptyLinkedScripts", $oPage);
		$this->InvokeNonPublicMethod(WebPage::class, $sMethodName, $oPage, [$sInputURI]);

		$aLinkedScripts = $this->GetNonPublicProperty($oPage, "a_linked_scripts");
		$this->assertEquals($iExpectedCount, count($aLinkedScripts), "Linked scripts count should be $iExpectedCount");
	}

	public function LinkScriptMethodsProvider(): array
	{
		return [
			// LinkScriptFromAppRoot
			"LinkScriptFromAppRoot: Empty URI should be ignored" => [
				"LinkScriptFromAppRoot",
				"",
				0,
			],
			"LinkScriptFromAppRoot: Relative URI of existing file should be completed / added" => [
				"LinkScriptFromAppRoot",
				"js/utils.js",
				1,
			],
			"LinkScriptFromAppRoot: Relative URI of existing file with query params should be completed / added" => [
				"LinkScriptFromAppRoot",
				"js/utils.js?foo=bar",
				1,
			],
			"LinkScriptFromAppRoot: Relative URI of NON existing file should be ignored" => [
				"LinkScriptFromAppRoot",
				"js/some-file.js",
				0,
			],
			"LinkScriptFromAppRoot: Absolute URI should be ignored" => [
				"LinkScriptFromAppRoot",
				"https://external.server/file.js",
				0,
			],

			// LinkScriptFromModule
			"LinkScriptFromModule: Empty URI should be ignored" => [
				"LinkScriptFromModule",
				"",
				0,
			],
			"LinkScriptFromModule: Relative URI of existing file should be completed / added" => [
				"LinkScriptFromModule",
				"itop-portal-base/portal/public/js/toolbox.js",
				1,
			],
			"LinkScriptFromModule: Relative URI of existing file with query params should be completed / added" => [
				"LinkScriptFromModule",
				"itop-portal-base/portal/public/js/toolbox.js?foo=bar",
				1,
			],
			"LinkScriptFromModule: Relative URI of NON existing file should be completed / added" => [
				"LinkScriptFromModule",
				"some-module/asset/js/some-file.js",
				0,
			],
			"LinkScriptFromModule: Absolute URI should be ignored" => [
				"LinkScriptFromModule",
				"https://external.server/file.js",
				0,
			],

			// LinkScriptFromURI
			"LinkScriptFromURI: Empty URI should be ignored" => [
				"LinkScriptFromURI",
				"",
				0,
			],
			"LinkScriptFromURI: Relative URI should be ignored" => [
				"LinkScriptFromURI",
				"js/utils.js",
				0,
			],
			"LinkScriptFromURI: Absolute URI should be added" => [
				"LinkScriptFromURI",
				"https://external.server/file.js",
				1,
			],
			"LinkScriptFromURI: Absolute URI with query params should be added" => [
				"LinkScriptFromURI",
				"https://external.server/file.js?foo=bar",
				1,
			],
		];
	}

	/**
	 * @dataProvider LinkStylesheetMethodsProvider
	 * @covers       \Combodo\iTop\Application\WebPage\WebPage::LinkStylesheetFromAppRoot()
	 * @covers       \Combodo\iTop\Application\WebPage\WebPage::LinkStylesheetFromModule()
	 * @covers       \Combodo\iTop\Application\WebPage\WebPage::LinkStylesheetFromURI()
	 *
	 * @param string $sMethodName
	 * @param string $sInputURI
	 * @param int $iExpectedCount
	 *
	 * @return void
	 * @throws \ReflectionException
	 */
	public function testLinkStylesheetMethods(string $sMethodName, string $sInputURI, int $iExpectedCount): void
	{
		$oPage = new WebPageMock('');

		$this->InvokeNonPublicMethod(WebPage::class, "EmptyLinkedStylesheets", $oPage);
		$this->InvokeNonPublicMethod(WebPage::class, $sMethodName, $oPage, [$sInputURI]);

		$aLinkedStylesheets = $this->GetNonPublicProperty($oPage, "a_linked_stylesheets");
		$this->assertEquals($iExpectedCount, count($aLinkedStylesheets), "Linked stylesheets count should be $iExpectedCount");
	}

	public function LinkStylesheetMethodsProvider(): array
	{
		return [
			// LinkStylesheetFromAppRoot
			"LinkStylesheetFromAppRoot: Empty URI should be ignored" => [
				"LinkStylesheetFromAppRoot",
				"",
				0,
			],
			"LinkStylesheetFromAppRoot: Relative URI of existing file should be completed / added" => [
				"LinkStylesheetFromAppRoot",
				"css/login.css",
				1,
			],
			"LinkStylesheetFromAppRoot: Relative URI of existing file with query params should be completed / added" => [
				"LinkStylesheetFromAppRoot",
				"css/login.css?foo=bar",
				1,
			],
			"LinkStylesheetFromAppRoot: Relative URI of NON existing file should be ignored" => [
				"LinkStylesheetFromAppRoot",
				"css/some-file.css",
				0,
			],
			"LinkStylesheetFromAppRoot: Absolute URI should be ignored" => [
				"LinkStylesheetFromAppRoot",
				"https://external.server/file.css",
				0,
			],

			// LinkStylesheetFromModule
			"LinkStylesheetFromModule: Empty URI should be ignored" => [
				"LinkStylesheetFromModule",
				"",
				0,
			],
			"LinkStylesheetFromModule: Relative URI of existing file should be completed / added" => [
				"LinkStylesheetFromModule",
				"itop-portal-base/portal/public/css/portal.css",
				1,
			],
			"LinkStylesheetFromModule: Relative URI of existing file with query params should be completed / added" => [
				"LinkStylesheetFromModule",
				"itop-portal-base/portal/public/css/portal.css?foo=bar",
				1,
			],
			"LinkStylesheetFromModule: Relative URI of NON existing file should be completed / added" => [
				"LinkStylesheetFromModule",
				"some-module/asset/js/some-file.js",
				0,
			],
			"LinkStylesheetFromModule: Absolute URI should be ignored" => [
				"LinkStylesheetFromModule",
				"https://external.server/file.js",
				0,
			],

			// LinkStylesheetFromURI
			"LinkStylesheetFromURI: Empty URI should be ignored" => [
				"LinkStylesheetFromURI",
				"",
				0,
			],
			"LinkStylesheetFromURI: Relative URI should be ignored" => [
				"LinkStylesheetFromURI",
				"js/login.css",
				0,
			],
			"LinkStylesheetFromURI: Absolute URI should be added" => [
				"LinkStylesheetFromURI",
				"https://external.server/file.css",
				1,
			],
			"LinkStylesheetFromURI: Absolute URI with query params should be added" => [
				"LinkStylesheetFromURI",
				"https://external.server/file.css?foo=bar",
				1,
			],
		];
	}
}