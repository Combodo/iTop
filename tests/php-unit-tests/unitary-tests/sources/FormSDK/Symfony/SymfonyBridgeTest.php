<?php

namespace Combodo\iTop\Test\UnitTest\FormSDK;

use Combodo\iTop\FormImplementation\Helper\SelectDataProvider;
use Combodo\iTop\FormSDK\Field\FormFieldDescription;
use Combodo\iTop\FormSDK\Field\FormFieldTypeEnumeration;
use Combodo\iTop\FormSDK\Service\FormManager;
use Combodo\iTop\FormSDK\Symfony\SymfonyBridge;
use Combodo\iTop\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SymfonyBridgeTest extends KernelTestCase {


	/**
	 * @param string $sName
	 * @param \Combodo\iTop\FormSDK\Field\FormFieldTypeEnumeration $oType
	 * @param array $aOptions
	 * @param array $aExpectedSymfonyTypeDescription
	 * @dataProvider ToSymfonyFormTypeProvider
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testToSymfonyFormType(string $sName, FormFieldTypeEnumeration $oType, array $aOptions, array $aExpectedSymfonyTypeDescription) : void
	{
		self::bootKernel();

		$oSymfonyBridge = $this->getContainer()->get(SymfonyBridge::class);

		$oFormFieldDescription = new FormFieldDescription($sName, $oType, $aOptions);
		$aSymfonyTypeDescription = $oSymfonyBridge->ToSymfonyFormType($oFormFieldDescription);

		$this->assertIsArray($aSymfonyTypeDescription);
	}

	/**
	 * Helper than can be called in the context of a data provider
	 *
	 * @since 3.0.4 3.1.1 3.2.0 N°6658 method creation
	 */
	public static function GetAppRoot()
	{
		if (defined('APPROOT')) {
			return APPROOT;
		}

		$sAppRootPath = static::GetFirstDirUpContainingFile(__DIR__, 'approot.inc.php');

		return $sAppRootPath . '/';
	}

	private static function GetFirstDirUpContainingFile(string $sSearchPath, string $sFileToFindGlobPattern): ?string
	{
		for ($iDepth = 0; $iDepth < 8; $iDepth++) {
			$aGlobFiles = glob($sSearchPath . '/' . $sFileToFindGlobPattern);
			if (is_array($aGlobFiles) && (count($aGlobFiles) > 0)) {
				return $sSearchPath . '/';
			}
			$iOffsetSep = strrpos($sSearchPath, '/');
			if ($iOffsetSep === false) {
				$iOffsetSep = strrpos($sSearchPath, '\\');
				if ($iOffsetSep === false) {
					// Do not throw an exception here as PHPUnit will not show it clearly when determing the list of test to perform
					return 'Could not find the approot file in ' . $sSearchPath;
				}
			}
			$sSearchPath = substr($sSearchPath, 0, $iOffsetSep);
		}
		return null;
	}

	protected function RequireOnceUnitTestFile(string $sFileRelPath): void
	{
		$aStack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
		$sCallerDirAbsPath = dirname($aStack[0]['file']);

		require_once $sCallerDirAbsPath . DIRECTORY_SEPARATOR . $sFileRelPath;
	}

	protected function RequireOnceItopFile(string $sFileRelPath): void
	{
		require_once $this->GetAppRoot() . $sFileRelPath;
	}

	public function ToSymfonyFormTypeProvider()
	{
		$this->RequireOnceItopFile( 'sources/FormSDK/Field/FormFieldTypeEnumeration.php');

		return [
			'Test 1' => [
				'language',
				FormFieldTypeEnumeration::SELECT,
				[
					'label'   => 'Ma langue',
					'choices' => [
						'French' => 'FR FR',
						'English' => 'EN EN',
					]
				],
				[
					'language',
					ChoiceType::class,
					[
						'label'   => 'Ma langue',
						'French' => 'FR FR',
						'English' => 'EN EN',
					]
				]
			]
		];
	}



	public function testCreateLayoutTypes(): void
	{

	}




}
