<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest;

use CMDBSource;
use DeprecatedCallsLog;
use MySQLTransactionNotClosedException;
use PHPUnit\Framework\TestCase;
use SetupUtils;
use const DEBUG_BACKTRACE_IGNORE_ARGS;

/**
 * Class ItopTestCase
 *
 * Helper class to extend for tests that DO NOT need to access the DataModel or the Database
 *
 * @since 3.0.4 3.1.1 3.2.0 N°6658 move some setUp/tearDown code to the corresponding methods *BeforeClass to speed up tests process time.
 */
abstract class ItopTestCase extends TestCase
{
	public const TEST_LOG_DIR = 'test';

	/**
	 * @var bool
	 * @since 3.0.4 3.1.1 3.2.0 N°6976 Allow to enable/disable {@see DeprecatedCallsLog} error handler
	 */
	public const DISABLE_DEPRECATEDCALLSLOG_ERRORHANDLER = true;
	public static $DEBUG_UNIT_TEST = false;
	protected static $aBackupStaticProperties = [];

	/**
	 * @link https://docs.phpunit.de/en/9.6/annotations.html#preserveglobalstate PHPUnit `preserveGlobalState` annotation documentation
	 *
	 * @since 3.0.4 3.1.1 3.2.0 N°6658 Override default value creation so that we don't need to add the annotation on each test classes that have runInSeparateProcess.
	 *                    This parameter isn't used when test is run in the same process so ok to change it globally !
	 */
	protected $preserveGlobalState = false;

	/**
	 * This method is called before the first test of this test class is run (in the current process).
	 */
	public static function setUpBeforeClass(): void
	{
		parent::setUpBeforeClass();

		static::$DEBUG_UNIT_TEST = getenv('DEBUG_UNIT_TEST');

		require_once static::GetAppRoot() . 'approot.inc.php';

		if ((static::DISABLE_DEPRECATEDCALLSLOG_ERRORHANDLER)
			&& (false === defined(ITOP_PHPUNIT_RUNNING_CONSTANT_NAME))) {
			// setUp might be called multiple times, so protecting the define() call !
			define(ITOP_PHPUNIT_RUNNING_CONSTANT_NAME, true);
		}
	}

	/**
	 * This method is called after the last test of this test class is run (in the current process).
	 */
	public static function tearDownAfterClass(): void
	{
		parent::tearDownAfterClass();

		if (method_exists('utils', 'GetConfig')) {
			// Reset the config by forcing the load from disk
			$oConfig = \utils::GetConfig(true);
			if (method_exists('MetaModel', 'SetConfig')) {
				\MetaModel::SetConfig($oConfig);
			}
		}
		if (method_exists('Dict', 'SetUserLanguage')) {
			\Dict::SetUserLanguage();
		}
	}

	protected function setUp(): void {
		parent::setUp();

		$this->debug("\n----------\n---------- ".$this->getName()."\n----------\n");

		$this->LoadRequiredItopFiles();
		$this->LoadRequiredTestFiles();
	}

	/**
	 * @throws \MySQLTransactionNotClosedException see N°5538
	 *
	 * @since 2.7.8 3.0.3 3.1.0 N°5538
	 * @since 3.0.4 3.1.1 3.2.0 N°6658 if transaction not closed, we are now doing a rollback
	 */
	protected function tearDown(): void
	{
		parent::tearDown();

		if (CMDBSource::IsInsideTransaction()) {
			// Nested transactions were opened but not finished !
			// Rollback to avoid side effects on next tests
			while (CMDBSource::IsInsideTransaction()) {
				CMDBSource::Query('ROLLBACK');
			}
			throw new MySQLTransactionNotClosedException('Some DB transactions were opened but not closed ! Fix the code by adding ROLLBACK or COMMIT statements !', []);
		}
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
		require_once $this->GetAppRoot() . $sFileRelPath;
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
		if (static::$DEBUG_UNIT_TEST) {
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
	public function InvokeNonPublicStaticMethod($sObjectClass, $sMethodName, $aArgs = [])
	{
		return $this->InvokeNonPublicMethod($sObjectClass, $sMethodName, null, $aArgs);
	}

	/**
	 * @param string $sObjectClass for example DBObject::class
	 * @param string $sMethodName
	 * @param ?object $oObject
	 * @param array $aArgs
	 *
	 * @return mixed method result
	 *
	 * @throws \ReflectionException
	 *
	 * @since 2.7.4 3.0.0
	 */
	public function InvokeNonPublicMethod($sObjectClass, $sMethodName, $oObject, $aArgs = [])
	{
		$class = new \ReflectionClass($sObjectClass);
		$method = $class->getMethod($sMethodName);
		$method->setAccessible(true);

		return $method->invokeArgs($oObject, $aArgs);
	}

	/**
	 * @since 2.7.10 3.1.0
	 */
	public function GetNonPublicStaticProperty(string $sClass, string $sProperty)
	{
		/** @noinspection OneTimeUseVariablesInspection */
		$oProperty = $this->GetProperty($sClass, $sProperty);

		return $oProperty->getValue();
	}

	/**
	 * @param object $oObject
	 * @param string $sProperty
	 *
	 * @return mixed property
	 *
	 * @since 2.7.8 3.0.3 3.1.0
	 */
	public function GetNonPublicProperty(object $oObject, string $sProperty)
	{
		/** @noinspection OneTimeUseVariablesInspection */
		$oProperty = $this->GetProperty(get_class($oObject), $sProperty);

		return $oProperty->getValue($oObject);
	}

	/**
	 * Backup every static property of the class (even protected ones)
	 * @param string $sClass
	 *
	 * @return void
	 *
	 * @since 3.2.0
	 */
	public static function BackupStaticProperties($sClass)
	{
		$class = new \ReflectionClass($sClass);
		foreach ($class->getProperties() as $property) {
			if (!$property->isStatic()) continue;
			$property->setAccessible(true);
			static::$aBackupStaticProperties[$sClass][$property->getName()] = $property->getValue();
		}
	}

	/**
	 * Restore every static property of the class (even protected ones)
	 * @param string $sClass
	 *
	 * @return void
	 *
	 * @since 3.2.0
	 */
	public static function RestoreStaticProperties($sClass)
	{
		$class = new \ReflectionClass($sClass);
		foreach ($class->getProperties() as $property) {
			if (!$property->isStatic()) continue;
			$property->setAccessible(true);
			$property->setValue(static::$aBackupStaticProperties[$sClass][$property->getName()]);
		}
	}

	/**
	 * @since 2.7.10 3.1.0
	 */
	private function GetProperty(string $sClass, string $sProperty): \ReflectionProperty
	{
		$class = new \ReflectionClass($sClass);
		$property = $class->getProperty($sProperty);
		$property->setAccessible(true);

		return $property;
	}


	/**
	 * @param object $oObject
	 * @param string $sProperty
	 * @param $value
	 *
	 * @since 2.7.8 3.0.3 3.1.0
	 */
	public function SetNonPublicProperty(object $oObject, string $sProperty, $value)
	{
		$oProperty = $this->GetProperty(get_class($oObject), $sProperty);
		$oProperty->setValue($oObject, $value);
	}

	/**
	 * @since 2.7.10 3.1.0
	 */
	public function SetNonPublicStaticProperty(string $sClass, string $sProperty, $value)
	{
		$oProperty = $this->GetProperty($sClass, $sProperty);
		$oProperty->setValue($value);
	}

	public static function RecurseRmdir($dir)
	{
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (is_dir($dir.DIRECTORY_SEPARATOR.$object)) {
						static::RecurseRmdir($dir.DIRECTORY_SEPARATOR.$object);
					} else {
						unlink($dir.DIRECTORY_SEPARATOR.$object);
					}
				}
			}
			rmdir($dir);
		}
	}

	public static function CreateTmpdir() {
		$sTmpDir=tempnam(sys_get_temp_dir(),'');
		if (file_exists($sTmpDir))
		{
			unlink($sTmpDir);
		}
		mkdir($sTmpDir);
		if (is_dir($sTmpDir))
		{
			return $sTmpDir;
		}

		return sys_get_temp_dir();
	}

	public static function RecurseMkdir($sDir){
		if (strpos($sDir, DIRECTORY_SEPARATOR) === 0){
			$sPath = DIRECTORY_SEPARATOR;
		} else {
			$sPath = "";
		}

		foreach (explode(DIRECTORY_SEPARATOR, $sDir) as $sSubDir){
			if (($sSubDir === '..')) {
				break;
			}

			if (( trim($sSubDir) === '' ) || ( $sSubDir === '.' )) {
				continue;
			}

			$sPath .= $sSubDir . DIRECTORY_SEPARATOR;
			if (!is_dir($sPath)) {
				var_dump($sPath);
				@mkdir($sPath);
			}
		}

	}

	public static function RecurseCopy($src,$dst) {
		$dir = opendir($src);
		@mkdir($dst);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					static::RecurseCopy($src . DIRECTORY_SEPARATOR . $file,$dst . DIRECTORY_SEPARATOR . $file);
				}
				else {
					copy($src . DIRECTORY_SEPARATOR . $file,$dst . DIRECTORY_SEPARATOR . $file);
				}
			}
		}
		closedir($dir);
	}
}
