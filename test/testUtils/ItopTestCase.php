<?php
/*
 * Copyright (C) 2013-2021 Combodo SARL
 * This file is part of iTop.
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Test\UnitTest;

use Combodo\iTop\Test\TestUtils\RunTimeEnvironment\RunTimeEnvironmentTest;
use PHPUnit\Framework\TestCase;
use SetupUtils;

define('DEBUG_UNIT_TEST', true);

class ItopTestCase extends TestCase
{
	const TEST_LOG_DIR = 'test';
	const TEST_ITOP_ENV_DEFAULT = null;

	const TEST_ITOP_ENV_PREFIX = 'test-';
	CONST TEST_TARGET_BASE_PATH = '/test/testUtils/conf/targets/';

    protected function setUp()
	{
		@include_once '../approot.inc.php';
        @include_once '../../approot.inc.php';
		@include_once '../../../approot.inc.php';
		@include_once '../../../../approot.inc.php';
		@include_once '../../../../../approot.inc.php';
		@include_once '../../../../../../approot.inc.php';
		@include_once '../../../../../../../approot.inc.php';
		@include_once '../../../../../../../../approot.inc.php';
		@include_once '../../../../../../../../../approot.inc.php';

        $this->debug("\n----------\n---------- ".$this->getName()."\n----------\n");
		$this->SetupItopEnv(static::TEST_ITOP_ENV_DEFAULT);
	}

	/**
	 * This method MUST be runned before the MetaModel startup
	 *
	 * NB: The startup is generally performed by `application/startup.inc.php`
	 *
	 */
	public function SetupItopEnv($sITopEnv)
	{
		if (defined('MODULESROOT')) {
			throw new \Exception('setupItopEnv must be called before the MetaModel startup!');
		}


		if ($sITopEnv != null) {
			if (empty($_SESSION)) {
				session_name('itop-'.md5(APPROOT));
				session_start();
				session_write_close();
			}
			$_SESSION['itop_env'] = ItopTestCase::TEST_ITOP_ENV_PREFIX."$sITopEnv";
			$_REQUEST['switch_env'] = ItopTestCase::TEST_ITOP_ENV_PREFIX."$sITopEnv";

			$this->BuildItopEnv($sITopEnv);
		}
	}



	private function BuildItopEnv($sITopEnv)
	{
		if (is_dir(APPROOT."/env-".ItopTestCase::TEST_ITOP_ENV_PREFIX.$sITopEnv)) {
			//The env has already been built
			return;
		}

		if (!is_dir(APPROOT.self::TEST_TARGET_BASE_PATH."/{$sITopEnv}")) {
			throw new \Exception("iTop env '{$sITopEnv}' not found");
		}

		$oRuntimeEnv = new RunTimeEnvironmentTest($sITopEnv);
		$oRuntimeEnv->PushDelta();
		$oRuntimeEnv->PushModules();
		$oRuntimeEnv->CheckDirectories();

		$oRuntimeEnv->SmartCompile();


		$oConfig = $oRuntimeEnv->MakeConfigFile($sITopEnv.' (built on '.date('Y-m-d').')');
		$oConfig->Set('access_mode', ACCESS_FULL);

		$sBdName = $oConfig->Get('db_name');
		assert(false !== strpos($sBdName, str_replace('-', '_',  static::TEST_ITOP_ENV_DEFAULT)), 'The DB contains the test env');
		$oRuntimeEnv->PrepareEmptyDatabase($sBdName);

		$oRuntimeEnv->WriteConfigFileSafe($oConfig);
		$oRuntimeEnv->InitDataModel($oConfig, true);

		// Safety check: check the inter dependencies, will throw an exception in case of inconsistency
		$aAvailableModules = $oRuntimeEnv->AnalyzeInstallation($oConfig, $oRuntimeEnv->GetBuildDir(), true);

		$oRuntimeEnv->CheckMetaModel(); // Will throw an exception if a problem is detected

		$oRuntimeEnv->Commit();

		$aSelectedModules = array();
		foreach ($aAvailableModules as $sModuleId => $aModule)
		{
			if (($sModuleId == ROOT_MODULE) || ($sModuleId == DATAMODEL_MODULE))
			{
				continue;
			}
			else
			{
				$aSelectedModules[] = $sModuleId;
			}
		}



		$oRuntimeEnv->CallInstallerHandlers($aAvailableModules, $aSelectedModules, 'BeforeDatabaseCreation');

		$oRuntimeEnv->CreateDatabaseStructure($oConfig, 'install');

		$oRuntimeEnv->CallInstallerHandlers($aAvailableModules, $aSelectedModules, 'AfterDatabaseCreation');

		$oRuntimeEnv->UpdatePredefinedObjects();

		$oRuntimeEnv->CallInstallerHandlers($aAvailableModules, $aSelectedModules, 'AfterDatabaseSetup');

		$oRuntimeEnv->LoadData($aAvailableModules, $aSelectedModules, false /* no sample data*/);

		$oRuntimeEnv->CallInstallerHandlers($aAvailableModules, $aSelectedModules, 'AfterDataLoad');

		// Record the installation so that the "about box" knows about the installed modules
		$sDataModelVersion = $oRuntimeEnv->GetCurrentDataModelVersion();

		$oExtensionsMap = new \iTopExtensionsMap();

		// Default choices = as before
		$oExtensionsMap->LoadChoicesFromDatabase($oConfig);
		foreach ($oExtensionsMap->GetAllExtensions() as $oExtension)
		{
			// Plus all "remote" extensions
			if ($oExtension->sSource == \iTopExtension::SOURCE_REMOTE)
			{
				$oExtensionsMap->MarkAsChosen($oExtension->sCode);
			}
		}
		$aSelectedExtensionCodes = array();
		foreach ($oExtensionsMap->GetChoices() as $oExtension)
		{
			$aSelectedExtensionCodes[] = $oExtension->sCode;
		}
		$aSelectedExtensions = $oExtensionsMap->GetChoices();
		$oRuntimeEnv->RecordInstallation($oConfig, $sDataModelVersion, $aSelectedModules, $aSelectedExtensionCodes, 'Done by ItopTEstCase');


	}

	protected function debug($sMsg)
    {
        if (DEBUG_UNIT_TEST)
        {
        	if (is_string($sMsg))
	        {
	        	echo "$sMsg\n";
	        }
	        else
	        {
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
	 */
	public function InvokeNonPublicMethod($sObjectClass, $sMethodName, $oObject, $aArgs)
	{
		$class = new \ReflectionClass($sObjectClass);
		$method = $class->getMethod($sMethodName);
		$method->setAccessible(true);

		return $method->invokeArgs($oObject, $aArgs);
	}
}