<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest;

use CMDBSource;
use MySQLTransactionNotClosedException;
use PHPUnit\Framework\TestCase;
use SetupUtils;
use const DEBUG_BACKTRACE_IGNORE_ARGS;

define('DEBUG_UNIT_TEST', true);

/**
 * Class ItopTestCase
 *
 * Helper class to extend for tests that DO NOT need to access the DataModel or the Database
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package Combodo\iTop\Test\UnitTest
 */
abstract class ItopTestCase extends TestCase
{
	const TEST_LOG_DIR = 'test';

	/** @noinspection UsingInclusionOnceReturnValueInspection avoid errors for approot includes */
	protected function setUp(): void
	{
		$sAppRootRelPath = 'approot.inc.php';
		$sDepthSeparator = '../';
		for ($iDepth = 0; $iDepth < 8; $iDepth++) {
			if (file_exists($sAppRootRelPath)) {
				require_once $sAppRootRelPath;
				break;
			}

			$sAppRootRelPath = $sDepthSeparator.$sAppRootRelPath;
		}

		$this->LoadRequiredItopFiles();
		$this->LoadRequiredTestFiles();
	}

	/**
	 * @throws \MySQLTransactionNotClosedException see N°5538
	 * @since 2.7.8 3.0.3 3.1.0 N°5538
	 */
	protected function tearDown(): void
	{
		parent::tearDown();

		if (CMDBSource::IsInsideTransaction()) {
			// Nested transactions were opened but not finished !
			throw new MySQLTransactionNotClosedException('Some DB transactions were opened but not closed ! Fix the code by adding ROLLBACK or COMMIT statements !', []);
		}
	}

	/**
	 * Overload this method to require necessary files through {@see \Combodo\iTop\Test\UnitTest\ItopTestCase::RequireOnceItopFile()}
	 *
	 * @return void
	 * @since 2.7.9 3.0.4 3.1.0
	 */
	protected function LoadRequiredItopFiles(): void
	{
		// Empty until we actually need to require some files in the class
	}

	/**
	 * Overload this method to require necessary files through {@see \Combodo\iTop\Test\UnitTest\ItopTestCase::RequireOnceUnitTestFile()}
	 *
	 * @return void
	 * @since 2.7.10 3.0.4 3.1.0
	 */
	protected function LoadRequiredTestFiles(): void
	{
		// Empty until we actually need to require some files in the class
	}

	/**
	 * Require once an iTop file (core or extension) from its relative path to the iTop root dir.
	 * This ensure to always use the right absolute path, especially in {@see \Combodo\iTop\Test\UnitTest\ItopTestCase::RequireOnceUnitTestFile()}
	 *
	 * @param string $sFileRelPath Rel. path (from iTop root dir) of the iTop file (core or extension) to require (eg. 'core/attributedef.class.inc.php' for <ITOP>/core/attributedef.class.inc.php)
	 *
	 * @return void
	 * @since 2.7.9 3.0.3 3.1.0 N°5608 Add method after PHPUnit directory moving
	 */
	protected function RequireOnceItopFile(string $sFileRelPath): void
	{
		require_once APPROOT . $sFileRelPath;
	}

	/**
	 * Helper to load a module file. The caller test must be in that module !
	 * Will browse dir up to find a module.*.php
	 *
	 * @param string $sFileRelPath for example 'portal/src/Helper/ApplicationHelper.php'
	 * @since 2.7.10 3.1.1 3.2.0 N°6709 method creation
	 */
	protected function RequireOnceCurrentModuleFile(string $sFileRelPath): void
	{
		$aStack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
		$sCallerFileFullPath = $aStack[0]['file'];
		$sCallerDir = dirname($sCallerFileFullPath);

		$sModuleRootPath = static::GetFirstDirUpContainingFile($sCallerDir, 'module.*.php');
		require_once $sModuleRootPath . $sFileRelPath;
	}

	/**
	 * Require once a unit test file (eg. a mock class) from its relative path from the *current* dir.
	 * This ensure that required files don't crash when unit tests dir is moved in the iTop structure (see N°5608)
	 *
	 * @param string $sFileRelPath Rel. path (from the *current* dir) of the unit test file to require (eg. './WeeklyScheduledProcessMockConfig.php' for <ITOP>/tests/php-unit-tests/unitary-tests/core/WeeklyScheduledProcessMockConfig.php in Combodo\iTop\Test\UnitTest\Core\WeeklyScheduledProcessTest)
	 *
	 * @return void
	 * @since 2.7.9 3.0.3 3.1.0 N°5608 Add method after PHPUnit directory moving
	 */
	protected function RequireOnceUnitTestFile(string $sFileRelPath): void
	{
		$aStack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
		$sCallerDirAbsPath = dirname($aStack[0]['file']);

		require_once $sCallerDirAbsPath . DIRECTORY_SEPARATOR . $sFileRelPath;
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

	protected function debug($sMsg)
	{
		if (DEBUG_UNIT_TEST) {
			if (is_string($sMsg)) {
				echo "$sMsg\n";
			} else {
		        /** @noinspection ForgottenDebugOutputInspection */
		        print_r($sMsg);
	        }
        }
    }

	public function GetMicroTime()
	{
		list($uSec, $sec) = explode(" ", microtime());
		return ((float)$uSec + (float)$sec);
	}

	public function WriteToCsvHeader($sFilename, $aHeader)
	{
		$sResultFile = APPROOT.'log/'.$sFilename;
		if (is_file($sResultFile))
		{
			@unlink($sResultFile);
		}
		SetupUtils::builddir(dirname($sResultFile));
		file_put_contents($sResultFile, implode(';', $aHeader)."\n");
	}

	public function WriteToCsvData($sFilename, $aData)
	{
		$sResultFile = APPROOT.'log/'.$sFilename;
		$file = fopen($sResultFile, 'a');
		fputs($file, implode(';', $aData)."\n");
		fclose($file);
	}

	public function GetTestId()
	{
		$sId = str_replace('"', '', $this->getName());
		$sId = str_replace(' ', '_', $sId);

		return $sId;
	}

	/**
	 * @since 2.7.4 3.0.0
	 */
	public function InvokeNonPublicStaticMethod($sObjectClass, $sMethodName, $aArgs)
	{
		return $this->InvokeNonPublicMethod($sObjectClass, $sMethodName, null, $aArgs);
	}

	/**
	 * @param string $sObjectClass for example DBObject::class
	 * @param string $sMethodName
	 * @param object $oObject
	 * @param array $aArgs
	 *
	 * @return mixed method result
	 *
	 * @throws \ReflectionException
	 *
	 * @since 2.7.4 3.0.0
	 */
	public function InvokeNonPublicMethod($sObjectClass, $sMethodName, $oObject, $aArgs)
	{
		$class = new \ReflectionClass($sObjectClass);
		$method = $class->getMethod($sMethodName);
		$method->setAccessible(true);

		return $method->invokeArgs($oObject, $aArgs);
	}

	/**
	 * @since 3.1.0 2.7.10
	 * @param string $sClass
	 * @param string $sProperty
	 *
	 * @return \ReflectionProperty
	 *
	 * @throws \ReflectionException
	 */
	private function GetProperty(string $sClass, string $sProperty)
	{
		$class = new \ReflectionClass($sClass);
		$property = $class->getProperty($sProperty);
		$property->setAccessible(true);

		return $property;
	}

	/**
	 * @param object $oObject
	 * @param string $sProperty
	 *
	 * @return mixed property
	 *
	 * @throws \ReflectionException
	 * @since 2.7.8 3.0.3 3.1.0
	 */
	public function GetNonPublicProperty(object $oObject, string $sProperty)
	{
		$property = $this->GetProperty(get_class($oObject), $sProperty);
		$property->setAccessible(true);

		return $property->getValue($oObject);
	}

	/**
	 * @param string $sClass
	 * @param string $sProperty
	 *
	 * @return mixed property
	 *
	 * @throws \ReflectionException
	 * @since 2.7.10 3.1.0
	 */
	public function GetNonPublicStaticProperty(string $sClass, string $sProperty)
	{
		$oProperty = $this->GetProperty($sClass, $sProperty);

		return $oProperty->getValue();
	}

	/**
	 * @param object $oObject
	 * @param string $sProperty
	 * @param $value
	 *
	 * @throws \ReflectionException
	 * @since 2.7.8 3.0.3 3.1.0
	 */
	public function SetNonPublicProperty(object $oObject, string $sProperty, $value)
	{
		$property = $this->GetProperty(get_class($oObject), $sProperty);
		$property->setAccessible(true);

		$property->setValue($oObject, $value);
	}

	/**
	 * @param string $sClass
	 * @param string $sProperty
	 * @param $value
	 *
	 * @throws \ReflectionException
	 * @since 2.7.10 3.1.0
	 */
	public function SetNonPublicStaticProperty(string $sClass, string $sProperty, $value)
	{
		$oProperty = $this->GetProperty($sClass, $sProperty);
		$oProperty->setValue($value);
	}
}
