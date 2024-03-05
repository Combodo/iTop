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
	 * @covers \Combodo\iTop\Application\WebPage\WebPage::LinkScript()
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

		$aLinkedScript = $this->GetNonPublicProperty($oPage, "a_linked_scripts");
		$this->assertEquals($iExpectedCount, count($aLinkedScript), "Linked scripts count should be $iExpectedCount");
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
		];
	}
}