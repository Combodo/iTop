<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\Service\UnitTestRunTimeEnvironment;
use Config;
use Exception;
use MetaModel;
use SetupUtils;
use utils;


/**
 * Class ItopCustomDatamodelTestCase
 *
 * Helper class to extend for tests needing a custom DataModel (eg. classes, attributes, etc conditions not available in the standard DM)
 * Usage:
 *   - Create a test case class extending this one
 *   - Override the {@see ItopCustomDatamodelTestCase::GetDatamodelDeltaAbsPath()} method to define where you XML delta is
 *   - Implement your test case methods as usual
 *
 * @since 2.7.9 3.0.4 3.1.0 N°6097
 */
abstract class ItopCustomDatamodelTestCase extends ItopDataTestCase
{
	/**
	 * @var UnitTestRunTimeEnvironment
     */
	protected $oEnvironment = null;

	/**
	 * @inheritDoc
	 * @since N°6097 Workaround to make the "runClassInSeparateProcess" directive work
	 */
	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);

		// Ensure that a test class derived from this one runs in a dedicated process as it changes the MetaModel / environment on the fly and
		// for now we have no way of switching environments properly in memory and it will result in other (regular) test classes to fail as they won't be on the expected environment.
		//
		// If we don't do this, we would have to add the `@runTestsInSeparateProcesses` on *each* test classes which we want to avoid for obvious possible mistakes.
		// Note that the `@runClassInSeparateProcess` don't work in PHPUnit yet.
		$this->setRunClassInSeparateProcess(true);
	}

    /**
	 * @return string Abs path to the XML delta to use for the tests of that class
	 */
	abstract public function GetDatamodelDeltaAbsPath(): string;

	protected function setUp(): void
	{
        static::LoadRequiredItopFiles();
        $this->oEnvironment = new UnitTestRunTimeEnvironment('production', $this->GetTestEnvironment());

		parent::setUp();
	}

	/**
	 * @inheritDoc
	 */
	protected function LoadRequiredItopFiles(): void
	{
		parent::LoadRequiredItopFiles();

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
	 * @warning This should ONLY be overloaded if your test case XML deltas are NOT compatible with the others, as it will create / compile another environment, increasing the global testing time.
	 */
	public function GetTestEnvironment(): string
	{
		return 'php-unit-tests';
	}

	/**
	 * @return string Absolute path to the {@see \Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase::GetTestEnvironment()} folder
	 */
	final protected function GetTestEnvironmentFolderAbsPath(): string
	{
		return APPROOT.'env-'.$this->GetTestEnvironment().'/';
	}

	/**
	 * @return bool True if the {@see \Combodo\iTop\Test\UnitTest\ItopDataTestCase::GetTestEnvironment()} is ready (compiled, up-to-date, but not necessarily started)
	 */
	final protected function IsEnvironmentReady(): bool
	{
		clearstatcache();
		if (false === file_exists($this->GetTestEnvironmentFolderAbsPath())) {
			return false;
		}
        return $this->oEnvironment->IsUpToDate();
    }

	/**
	 * @inheritDoc
	 */
	protected function PrepareEnvironment(): void
	{
		$sSourceEnv = $this->GetSourceEnvironment();
		$sTestEnv = $this->GetTestEnvironment();

		// Check if test env. is already set and only prepare it if it's not up-to-date
		//
		// Note: To improve performances, we compile all XML deltas from test cases derived from this class and make a single environment where everything will be ran at once.
		//       This requires XML deltas to be compatible, but it is a known and accepted trade-off. See PR #457
		if (false === $this->IsEnvironmentReady()) {

            $this->debug("Preparing custom environment '$sTestEnv' with the following datamodel files:");
            foreach ($this->oEnvironment->GetCustomDatamodelFiles() as $sCustomDatamodelFile) {
                $this->debug("  - $sCustomDatamodelFile");
            }

			//----------------------------------------------------
			// Clear any previous "$sTestEnv" environment
			//----------------------------------------------------

			// - Configuration file
			$sConfFile = utils::GetConfigFilePath($sTestEnv);
			$sConfFolder = dirname($sConfFile);
			if (is_file($sConfFile)) {
				SetupUtils::tidydir($sConfFolder);
			}

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
			$oEnvironment = new UnitTestRunTimeEnvironment($sSourceEnv, $sTestEnv);
			$oEnvironment->WriteConfigFileSafe($oTestConfig);
			$oEnvironment->CompileFrom($sSourceEnv);

			// - Force re-creating a fresh DB
			CMDBSource::InitFromConfig($oTestConfig);
			if (CMDBSource::IsDB($oTestConfig->Get('db_name'))) {
				CMDBSource::DropDB();
			}
			CMDBSource::CreateDB($oTestConfig->Get('db_name'));
			MetaModel::Startup($sConfFile, false /* $bModelOnly */, true /* $bAllowCache */, false /* $bTraceSourceFiles */, $sTestEnv);
            // N°7446 For some reason we need to create the DB schema before starting the MM, then only we can create the tables.
			MetaModel::DBCreate();

			$this->debug("Custom environment '$sTestEnv' is ready!");
		}

		parent::PrepareEnvironment();
	}
}
