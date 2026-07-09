<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use CheckResult;
use Combodo\iTop\Setup\FeatureRemoval\ModelReflectionSerializer;
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
	public const ERROR = 0;
	public const WARNING = 1;
	public const INFO = 2;
	public const TRACE = 3; // for log purposes : replace old SetupLog::Log calls

	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceItopFile('setup/setuputils.class.inc.php');
		$this->RequireOnceItopFile('setup/setuppage.class.inc.php');
	}

	/**
	 * @dataProvider CheckGraphvizProvider
	 */
	public function testCheckGraphviz($sScriptPath, $iSeverity, $sLabel)
	{
		/** @var \CheckResult $oCheck */
		$aCheck = SetupUtils::CheckGraphviz($sScriptPath);
		$bLabelFound = false;
		foreach ($aCheck as $oCheck) {
			$this->assertGreaterThanOrEqual($iSeverity, $oCheck->iSeverity);
			if (!$bLabelFound && (empty($sLabel) || strpos($oCheck->sLabel, $sLabel) !== false)) {
				$bLabelFound = true;
			}
		}
		$this->assertTrue($bLabelFound, "label '$sLabel' not found");
	}

	public function CheckGraphvizProvider()
	{
		if (substr(PHP_OS, 0, 3) === 'WIN') {
			return [];
		}

		return [
			"bash injection" => [
				"touch /tmp/toto",
				self::WARNING,
				"could not be executed: Please make sure it is installed and in the path",
			],
			"command ok" => [
				"/usr/bin/whereis",
				self::INFO,
				"",
			],
			"empty command => dot by default" => [
				"",
				self::INFO,
				"",
			],
			"command failed" => [
				"/bin/ls",
				self::WARNING,
				"dot could not be executed (retcode=2): Please make sure it is installed and in the path",
			],
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
			'10 exabytes'  => [
				10 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024,
				'11.53 EB',
			],
		];
	}

	/**
	 * @covers SetupUtils::PHP_MIN_VERSION
	 * @covers SetupUtils::PHP_NOT_VALIDATED_VERSION
	 * @covers composer.json
	 * @group composerJson
	 */
	public function testPhpMinVersionConsistency()
	{
		$sPHPMinVersion = SetupUtils::PHP_MIN_VERSION;
		$sPHPNotValidatedVersion = SetupUtils::PHP_NOT_VALIDATED_VERSION;

		// Ensure that not validated version is greater than min. supported version
		$this->assertTrue(version_compare($sPHPMinVersion, $sPHPNotValidatedVersion, '<'), "SetupUtils::PHP_MIN_VERSION ($sPHPMinVersion) is not strictly lower than SetupUtils::PHP_NOT_VALIDATED_VERSION ($sPHPNotValidatedVersion)");

		if (file_exists(APPROOT.'composer.json')) {
			$oComposerConfig = json_decode(file_get_contents(APPROOT.'composer.json'));
			// Platform/PHP must be set to the minimum to ensure dependancies are compatible with the min. version
			$sComposerPlatformPhp = $oComposerConfig->config->platform->php;
			$this->assertEquals($sPHPMinVersion, $oComposerConfig->config->platform->php, "SetupUtils::PHP_MIN_VERSION ($sPHPMinVersion) is not equals composer.json > config > platform ($sComposerPlatformPhp)");
			// Require/PHP must be set to the supported PHP versions range in order to keep our package constraints up-to-date
			$sComposerRequirePhp = $oComposerConfig->require->php;
			$this->assertEquals(
				">=$sPHPMinVersion <$sPHPNotValidatedVersion",
				$oComposerConfig->require->php,
				"SetupUtils::PHP_MIN_VERSION ($sPHPMinVersion) and SetupUtils::PHP_NOT_VALIDATED_VERSION ($sPHPNotValidatedVersion) is not equals composer.json > require > php ($sComposerRequirePhp)"
			);
		}
	}

	public function testCheckCliPhpVersionIsOk()
	{
		$this->RequireOnceItopFile('/setup/feature_removal/ModelReflectionSerializer.php');
		SetupUtils::CheckCliPhpVersionIsOk(ModelReflectionSerializer::ERROR_LABEL);
		$this->assertTrue(true);
	}

	public function CheckOKProvider()
	{
		return [
			["7.4 7.2 7.3.33"],
			["PHP 7.4.33 (cli) (built: Aug  2 2024 16:22:28) ( NTS )", "7.4.33"],
			["PHP 7.4.33 PHP 7.33.22", "7.4.33"],
			["version: 7.4.33 stable", "7.4.33"],
		];
	}

	/**
	 * @dataProvider CheckOKProvider
	 */
	public function testCheckCliPhpVersionFromOutputFail($sOuput, $sFoundVersion = '7.4')
	{
		$this->RequireOnceItopFile('/setup/feature_removal/ModelReflectionSerializer.php');

		$this->expectException(\CoreException::class);
		$sDetails = sprintf('The current CLI PHP Version (%s) is lower than the minimum version required to run %s, which is (%s)', $sFoundVersion, ITOP_APPLICATION, SetupUtils::PHP_MIN_VERSION);

		$this->expectExceptionMessage("Data consistency check failed: $sDetails");
		$this->InvokeNonPublicStaticMethod(SetupUtils::class, 'CheckCliPhpVersionFromOutput', [ModelReflectionSerializer::ERROR_LABEL, 'sPHPExec', [$sOuput]]);
	}

	public function testCheckCliPhpVersionFromOutputOK()
	{
		$sOuput = <<<OUTPUT
PHP 8.2.3 (cli) (built: Aug 2 2024 16:22:28) ( NTS )
OUTPUT;

		$this->RequireOnceItopFile('/setup/feature_removal/ModelReflectionSerializer.php');
		$this->InvokeNonPublicStaticMethod(SetupUtils::class, 'CheckCliPhpVersionFromOutput', [ModelReflectionSerializer::ERROR_LABEL, 'sPHPExec', [$sOuput]]);
		$this->assertTrue(true);
	}

	public function testCheckPhpVersionIsOK()
	{
		$aRes = [];
		$this->InvokeNonPublicStaticMethod(SetupUtils::class, 'CheckPhpVersion', [&$aRes]);
		$this->ValidateCheckResults([], $aRes);
	}

	public function testCheckPhpVersionIsNotValidatedYet()
	{
		$aRes = [];
		$this->InvokeNonPublicStaticMethod(SetupUtils::class, 'CheckPhpVersion', [&$aRes, '100.0']);
		$expected = [
			'The current PHP Version (100.0) is not yet validated by Combodo. You may experience some incompatibility issues.',
		];
		$this->ValidateCheckResults($expected, $aRes);
	}

	public function testCheckPhpVersionIsNOK()
	{
		$aRes = [];
		$this->InvokeNonPublicStaticMethod(SetupUtils::class, 'CheckPhpVersion', [&$aRes, '1.0']);
		$expected = [
			sprintf('Error: The current PHP Version (1.0) is lower than the minimum version required to run %s, which is (%s)', ITOP_APPLICATION, SetupUtils::PHP_MIN_VERSION),
		];
		$this->ValidateCheckResults($expected, $aRes);
	}

	private function ValidateCheckResults(array $expected, array $aActualCheckResults)
	{
		$aActual = [];
		foreach ($aActualCheckResults as $oCheckRes) {
			/** @var CheckResult $oCheckRes */
			if ($oCheckRes->iSeverity <= CheckResult::WARNING) {
				$aActual [] = $oCheckRes->sLabel;
			}
		}

		self::assertEquals($expected, $aActual);
	}

}
