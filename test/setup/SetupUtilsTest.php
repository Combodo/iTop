<?php


namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use SetupUtils;


/**
 * Class SetupUtilsTest
 *
 * @covers SetupUtils
 *
 * @since 2.7.4 N°3412
 * @package Combodo\iTop\Test\UnitTest\Setup
 */
class SetupUtilsTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		require_once APPROOT.'setup/setuputils.class.inc.php';
		require_once APPROOT.'setup/setuppage.class.inc.php';
	}

	/**
	 * @dataProvider CheckGraphvizProvider
	 */
	public function testCheckGraphviz($sScriptPath, $iSeverity, $sLabel){
		/** @var \CheckResult $oCheck */
		$oCheck = SetupUtils::CheckGraphviz($sScriptPath);
		$this->assertEquals($iSeverity, $oCheck->iSeverity);
		$this->assertContains($sLabel, $oCheck->sLabel);
	}

	public function CheckGraphvizProvider(){
		if (substr(PHP_OS,0,3) === 'WIN'){
			return [];
		}

		return [
			"bash injection" => [
				"touch /tmp/toto",
				1,
				"could not be executed: Please make sure it is installed and in the path",
			],
			"command ok" => [
				"/usr/bin/whereis",
				2,
				"",
			],
			"empty command => dot by default" => [
				"",
				2,
				"",
			],
			"command failed" => [
				"/bin/ls",
				1,
				"dot could not be executed (retcode=2): Please make sure it is installed and in the path",
			]
		];
	}


}
