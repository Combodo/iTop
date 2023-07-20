<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest;

use Combodo\iTop\Test\UnitTest\Service\UnitTestRunTimeEnvironment;
use Config;
use Exception;
use IssueLog;
use SetupUtils;
use utils;


/**
 * Class ItopCustomDatamodelTestCase
 *
 * Helper class to extend for tests needing a custom DataModel access to iTop's metamodel
 *
 * **⚠ Warning** Each class extending this one needs to NOT have @runTestsInSeparateProcesses annotation; otherwise the test env. will be re-compiled each time.
 *
 * @runTestsInSeparateProcesseszzz
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 *
 * @since 2.7.9 3.0.4 3.1.0
 */
abstract class ItopCustomDatamodelTestCase extends ItopDataTestCase
{
	/**
	 * @var bool
	 * @since N°6097 2.7.10 3.0.4 3.1.1 3.2.0
	 *
	 * @note If we change this to an array (with {@see \Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase::GetTestEnvironment()} as the key), we could eventually have several environments in // to test incompatible DMs / deltas.
	 */
	protected static $bIsCustomEnvironmentReady = false;

	/**
	 * @inheritDoc
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$sLogFileAbsPath = APPROOT.'log/php_unit_tests_-_custom_datamodel_for_env_-_'.$this->GetTestEnvironment().'.log';
		IssueLog::Enable($sLogFileAbsPath);
	}


	/**
	 * @return string Abs path to the XML delta to use for the tests of that class
	 */
	abstract public function GetDatamodelDeltaAbsPath(): string;

	/**
	 * @inheritDoc
	 */
	protected function LoadRequiredFiles(): void
	{
		$this->RequireOnceItopFile('setup/setuputils.class.inc.php');
		$this->RequireOnceItopFile('setup/runtimeenv.class.inc.php');
	}

	/**
	 * @return string Environment used as a base (conf. file, modules, DB, ...) to prepare the test environment
	 */
	protected function GetSourceEnvironment(): string
	{
		return 'production';
	}

	/**
	 * @inheritDoc
	 *
	 * This is final for now as we don't support yet to have several environments in // to test incompatible DMs / deltas.
	 * When / if we do this, keep in mind that should ONLY be overloaded if your test case XML deltas are NOT compatible with the others, as it will create / compile another environment, increasing the global test time.
	 */
	final public function GetTestEnvironment(): string
	{
		return 'php-unit-tests';
	}

	/**
	 * @inheritDoc
	 */
	protected function PrepareEnvironment(): void
	{
		$sSourceEnv = $this->GetSourceEnvironment();
		$sTestEnv = $this->GetTestEnvironment();

		// Check if test env. if already set and only prepare it if it doesn't already exist
		//
		// Note: To improve performances, we compile all XML deltas from test cases derived from this class and make a single environment where everything will be ran at once.
		//       This requires XML deltas to be compatible, but it is a known and accepted trade-off. See PR #457
		if (false === static::$bIsCustomEnvironmentReady) {
			//----------------------------------------------------
			// Clear any previous "$sTestEnv" environment
			//----------------------------------------------------

			// - Configuration file
			$sConfFile = utils::GetConfigFilePath($sTestEnv);
			$sConfFolder = dirname($sConfFile);
			if (is_file($sConfFile)) {
				chmod($sConfFile, 0777);
				SetupUtils::tidydir($sConfFolder);
			}

			// - Datamodel delta files
			// - Cache folder
			// - Compiled folder
			// We don't need to clean them as they are already by the compilation

			// - Drop database
			// We don't do that now, it will be done before re-creating the DB, once the metamodel is started

			//----------------------------------------------------
			// Prepare "$sTestEnv" environment
			//----------------------------------------------------

			// All the following is greatly inspired by the toolkit's sandbox script
			// - Prepare config file
			$oSourceConf = new Config(utils::GetConfigFilePath($sSourceEnv));
			if ($oSourceConf->Get('source_dir') === '') {
				throw new Exception('Missing entry source_dir from the config file');
			}

			$oTestConfig = clone($oSourceConf);
			$oTestConfig->ChangeModulesPath($sSourceEnv, $sTestEnv);
			// - Switch DB name to a dedicated one so we don't mess with the original one
			$sTestEnvSanitizedForDBName = preg_replace('/[^\d\w]/', '', $sTestEnv);
			$oTestConfig->Set('db_name', $oTestConfig->Get('db_name').'_'.$sTestEnvSanitizedForDBName);

			// - Compile env. based on the existing 'production' env.
			$oEnvironment = new UnitTestRunTimeEnvironment($sTestEnv);
			$oEnvironment->WriteConfigFileSafe($oTestConfig);
			$oEnvironment->CompileFrom($sSourceEnv, false);

			// - Force re-creating of the DB
//			// TODO: Create tmp DB
			// But how to use it now when the metamodel is not started yet ??
//			MetaModel::LoadConfig($oTestConfig);
//			if (MetaModel::DBExists()) {
//				MetaModel::DBDrop();
//			}
//			MetaModel::DBCreate();

			static::$bIsCustomEnvironmentReady = true;
		}

		parent::PrepareEnvironment();
	}
}
