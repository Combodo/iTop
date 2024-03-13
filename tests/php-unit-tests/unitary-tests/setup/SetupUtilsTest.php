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

		$this->RequireOnceItopFile('setup/setuputils.class.inc.php');
		$this->RequireOnceItopFile('setup/setuppage.class.inc.php');
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

	public function CheckGraphvizProvider() {
		if (substr(PHP_OS, 0, 3) === 'WIN') {
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

	/**
	 * @dataProvider HumanReadableSizeProvider
	 */
	public function testHumanReadableSize($fBytes, $sExpected)
	{
		$sOutput = SetupUtils::HumanReadableSize($fBytes);
		$this->assertEquals($sExpected, $sOutput);
	}

	public function HumanReadableSizeProvider(): array
	{
		return [
			'10 bytes' => [
				10,
				'10 bytes',
			],
			'10 kilobytes' => [
				10 * 1024,
				'10.24 kB',
			],
			'10 megabytes' => [
				10 * 1024 * 1024,
				'10.49 MB',
			],
			'10 gigabytes' => [
				10 * 1024 * 1024 * 1024,
				'10.74 GB',
			],
			'10 terabytes' => [
				10 * 1024 * 1024 * 1024 * 1024,
				'11.00 TB',
			],
			'10 petabytes' => [
				10 * 1024 * 1024 * 1024 * 1024 * 1024,
				'11.26 PB',
			],
			'10 exabytes' => [
				10 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024,
				'11.53 EB',
			],
		];
	}
}
