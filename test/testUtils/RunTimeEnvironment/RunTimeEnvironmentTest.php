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

namespace Combodo\iTop\Test\TestUtils\RunTimeEnvironment;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Config;
use Exception;
use iTopExtension;
use MFCompiler;
use MFCoreModule;
use MFDeltaModule;
use MFDictModule;
use ModelFactory;
use MySQLException;
use MySQLHasGoneAwayException;
use SetupInfo;
use SetupUtils;
use utils;

require_once APPROOT.'setup/runtimeenv.class.inc.php';
require_once APPROOT.'setup/xmldataloader.class.inc.php';



class RunTimeEnvironmentTest extends \RunTimeEnvironment
{
	protected $sEnvironmentSuffixe;
	private $sTargetPath;
	private $aTargetConfig;

	public function __construct($sEnvironment)
	{
		$this->sEnvironmentSuffixe = $sEnvironment;
		$this->sTargetPath = APPROOT."/test/testUtils/conf/targets/{$this->sEnvironmentSuffixe}/";
		$sRealEnvironment = ItopTestCase::TEST_ITOP_ENV_PREFIX.$this->sEnvironmentSuffixe;

		mkdir(APPROOT.'/env-'.$sRealEnvironment);

		$config = "{$this->sTargetPath}/target.ini";
		if (! is_file($config)) {
			$this->aTargetConfig = [];
		} else {
			$this->aTargetConfig = parse_ini_file($config);
		}

		parent::__construct($sRealEnvironment);
	}

	private function GetTargetConfig($key, $default = null)
	{
		if (!isset($this->aTargetConfig[$key])) {
			return $default;
		}

		$search = [
			'$APPROOT$',
		];
		$replace = [
			APPROOT,
		];

		if (is_string($this->aTargetConfig[$key])) {
			return str_replace($search, $replace, $this->aTargetConfig[$key]);
		}

		if (is_array($this->aTargetConfig[$key])) {
			$aResultReplaced = $this->aTargetConfig[$key];
			foreach ($aResultReplaced as $i => $sVal) {
				if (is_string($sVal)) {
					$aResultReplaced[$i] = str_replace($search, $replace, $sVal);
				}
			}

			return $aResultReplaced;
		}

		return $this->aTargetConfig[$key];
	}

	public function PushDelta()
	{
		// This is the real standard, that will be taken into account by the compiler + backup/restore
		$sDeltaFile = APPROOT.'data/'.$this->sTargetEnv.'.delta.xml';
		$sPreviousDeltaFile = APPROOT.'data/'.$this->sTargetEnv.'.delta.prev.xml';

		if (!file_exists(APPROOT.'data/test'))
		{
			mkdir(APPROOT.'data/test');
		}
		if (!file_exists(APPROOT.'data/test/'.$this->sTargetEnv))
		{
			mkdir(APPROOT.'data/test/'.$this->sTargetEnv);
		}

		if (file_exists($sDeltaFile))
		{
			// to be restored in case an issue is encountered later on
			copy($sDeltaFile, $sPreviousDeltaFile);
		}

		$sDeltaPath = "{$this->sTargetPath}/delta.xml";

//		fwrite(STDERR, __METHOD__.':'.var_export([
//				'$sDeltaPath' => $sDeltaPath,
//				'is_file($sDeltaPath)' => is_file($sDeltaPath),
//				'$sDeltaFile' => $sDeltaFile,
//			], true));

		if (is_file($sDeltaPath)) {
			copy($sDeltaPath, $sDeltaFile);
		} elseif (is_file($sDeltaFile)) {
			unlink($sDeltaFile);
		}
	}

	public function RestorePreviousDelta()
	{
		$sDeltaFile = APPROOT.'data/'.$this->sTargetEnv.'.delta.xml';
		$sPreviousDeltaFile = APPROOT.'data/'.$this->sTargetEnv.'.delta.prev.xml';
		unlink($sDeltaFile);
		if (file_exists($sPreviousDeltaFile))
		{
			rename($sPreviousDeltaFile, $sDeltaFile);
		}
	}

	public function PushModules()
	{
		$sSourceDir = "{$this->sTargetPath}/{$this->sFinalEnv}/modules/";

		$sModulesDir = APPROOT.'data/'.$this->sTargetEnv.'-modules/';
		self::MakeDirSafe($sModulesDir);
		SetupUtils::tidydir($sModulesDir);
		SetupUtils::copydir($sSourceDir, $sModulesDir);
	}

	public function IsInstalled()
	{
		$sConfig = APPCONF.$this->sTargetEnv.'/'.ITOP_CONFIG_FILE;
		if (file_exists($sConfig))
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	public function GetInstalledModules($sSourceEnv, $sSourceDir)
	{
		return parent::GetMFModulesToCompile($sSourceEnv, $sSourceDir);
	}

	public function MakeConfigFile($sEnvironmentLabel = null)
	{
		$oConfig = $this->GetConfig();
		if (!is_null($oConfig))
		{
			// Return the existing one
			$oConfig->UpdateIncludes($this->sTargetEnv);
		}
		else
		{
			// Clone the default 'production' config file
			//
			$oConfig = clone($this->GetConfig('production'));

			$oConfig->UpdateIncludes($this->sTargetEnv);

			if (is_null($sEnvironmentLabel))
			{
				$sEnvironmentLabel = $this->sTargetEnv;
			}
			$oConfig->Set('app_env_label', $sEnvironmentLabel, 'test');
			if ($this->sFinalEnv !== 'production')
			{
				$oConfig->Set('db_name', $oConfig->Get('db_name').'_'.str_replace('-', '_', $this->sFinalEnv));
			}
		}

		return $oConfig;
	}

	protected function GetConfig($sEnvironment = null)
	{
		if (is_null($sEnvironment))
		{
			$sEnvironment = $this->sTargetEnv;
		}
		$sFile = APPCONF.$sEnvironment.'/'.ITOP_CONFIG_FILE;
		if (file_exists($sFile))
		{
			$oConfig = new Config($sFile);
			return $oConfig;
		}
		else
		{
			return null;
		}
	}

	public function PrepareEmptyDatabase($sBdName)
	{
		try {
			\CMDBSource::DropDB($sBdName);
		} catch (\MySQLException $e) {
			//mysql errno 1008: Can't drop database 'xxx'; database doesn't exist
			if ($e->getCode() != 1008) {
				throw $e;
			}
		}
		\CMDBSource::CreateDB($sBdName);
		\CMDBSource::SelectDB($sBdName);
	}

	public function CloneDatabase($sSourceEnv = 'production')
	{
		if ($sSourceEnv == $this->sTargetEnv)
		{
			throw new Exception("Attempting to clone the DB from the environment '$sSourceEnv' into itself!");
		}
		$oSourceConfig = $this->GetConfig($sSourceEnv);
		// Copy the 'production' database to the target environment (new_db_name)
		//$oP = new ajax_page('');

		$sOldDBName = $oSourceConfig->Get('db_name');
		$sPrefix = $oSourceConfig->Get('db_subname');

		// No need to specify the DB to use, the name will be used in each command
		CMDBSource::InitFromConfig($oSourceConfig);

		$sNewDBName = $oSourceConfig->Get('db_name').'_'.$this->sFinalEnv;
		try
		{
			CMDBSource::DropDB($sNewDBName);
		}
		catch(MySQLException $e)
		{
			// Database may not already exist, never mind...
			// at least it will be clean !!
		}

		try
		{
			CMDBSource::CreateDB($sNewDBName);
		}
		catch(MySQLException $e)
		{
			// Database may already exist, never mind...
		}

//Parcourir la liste des tables utilisées par iTop (charger le data model de la prod, ou considérer l'ensemble des tables préfixées ????, ou voir le XML)
		// MySQL 5.0.2 will support this:
		// "SHOW FULL TABLES FROM `$sOldDBName` LIKE '$sPrefix%' WHERE Table_type = 'BASE TABLE'"
		$aTables = CMDBSource::QueryToArray("SHOW TABLES FROM `$sOldDBName` LIKE '$sPrefix%'");
		$sViewPrefix = $sPrefix.'view_';
		foreach ($aTables as $aRow)
		{
			$sTableName = $aRow[0];
			if (substr($sTableName, 0, strlen($sViewPrefix)) != $sViewPrefix)
			{
				// do not copy views
				try
				{
					CMDBSource::Query("DROP TABLE IF EXISTS `$sNewDBName`.`$sTableName`");
					CMDBSource::Query("CREATE TABLE `$sNewDBName`.`$sTableName` ENGINE=".MYSQL_ENGINE." DEFAULT CHARSET=".DEFAULT_CHARACTER_SET." COLLATE=".DEFAULT_COLLATION." SELECT * FROM `$sOldDBName`.`$sTableName`");
				} catch (MySQLHasGoneAwayException $e)
				{
					throw new Exception("Failed to clone the DB for table '$sTableName'. The database parameter 'max_allowed_paquet' may be too small.");
				} catch (Exception $e)
				{
					throw new Exception("Failed to clone the DB for table '$sTableName'");
				}
			}
		}
	}

	public function JumpInto($oPage = null)
	{
		$sTargetUrl = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?switch_env='.$this->sFinalEnv;
		if (is_null($oPage))
		{
			header('Location: '.$sTargetUrl);
			exit;
		}
		else
		{
			$oPage->add_ready_script("window.location.href='$sTargetUrl';");
		}
	}

	public function CheckDirectories()
	{
		$sTargetDir = APPROOT.$this->sFinalEnv;
		$sBuildDir = $sTargetDir.'-build';

		self::CheckDirectory($sTargetDir);
		self::CheckDirectory($sBuildDir);
	}

	/**
	 * @param $sDir
	 * @throws Exception
	 */
	public static function CheckDirectory($sDir)
	{
		if (!is_dir($sDir))
		{
			if (!@mkdir($sDir,0770))
			{
				throw new Exception('Creating directory '.$sDir.' is denied (Check access rights)');
			}
		}
		// Try create a file
		$sTempFile = $sDir.'/__itop_temp_file__';
		if (!@touch($sTempFile))
		{
			throw new Exception('Write access to '.$sDir.' is denied (Check access rights)');
		}
		@unlink($sTempFile);
	}

	public function SmartCompile()
	{
		$sCompileFrom = $this->GetTargetConfig('compileFrom');
		$bUseSymLinks = false;

		$sTargetDir = APPROOT.'env-'.$this->sTargetEnv;
		$bSkipTempDir = ($this->sFinalEnv != $this->sTargetEnv); // No need for a temporary directory if sTargetEnv is already a temporary directory

		$sConfigPath = $this->compileGetBaseConfigPath();
		$oSourceConfig = new Config($sConfigPath);

		if (null == $sCompileFrom) {
			$sSourceDir = null;
		} else {
			$sSourceDir = $oSourceConfig->Get('source_dir');
		}

		$sSourceDirFull = APPROOT.$oSourceConfig->Get('source_dir');

		// Do load the required modules
		//
		$oFactory = new ModelFactory($sSourceDirFull);
		$aModulesToCompile = $this->GetMFModulesToCompile($sCompileFrom, $sSourceDir);
		foreach($aModulesToCompile as $oModule)
		{
			if ($oModule instanceof MFDeltaModule)
			{
				// Just before loading the delta, let's save an image of the datamodel
				// in case there is no delta the operation will be done after the end of the loop
				$oFactory->SaveToFile(APPROOT.'data/datamodel-'.$this->sTargetEnv.'.xml');
			}
			$oFactory->LoadModule($oModule);
		}

		if ($oModule instanceof MFDeltaModule)
		{
			// A delta was loaded, let's save a second copy of the datamodel
			$oFactory->SaveToFile(APPROOT.'data/datamodel-'.$this->sTargetEnv.'-with-delta.xml');
		}
		else
		{
			// No delta was loaded, let's save the datamodel now
			$oFactory->SaveToFile(APPROOT.'data/datamodel-'.$this->sTargetEnv.'.xml');
		}


//		fwrite(STDERR, 'Compile:'.var_export([
//				'$this->sTargetEnv' => $this->sTargetEnv,
//				'$this->sFinalEnv' => $this->sFinalEnv,
//				'$sCompileFrom' => $sCompileFrom,
//				'$bUseSymLinks' => $bUseSymLinks,
//				'$sConfigPath' => $sConfigPath,
//				'$sSourceDirFull' => $sSourceDirFull,
//				'array_keys($aModulesToCompile)' => array_keys($aModulesToCompile),
////				'$aModulesToCompile[""]-> GetDataModelFiles()' => isset($aModulesToCompile['']) ? $aModulesToCompile['']-> GetDataModelFiles() : null,
//				'get_class(end($aModulesToCompile))' => get_class(end($aModulesToCompile)),
//				'end($aModulesToCompile)->GetDataModelFiles()' => end($aModulesToCompile)->GetDataModelFiles(),
//			], true));

		self::MakeDirSafe($sTargetDir);

		$oMFCompiler = new MFCompiler($oFactory, $this->sFinalEnv);
		$oMFCompiler->Compile($sTargetDir, null, $bUseSymLinks, $bSkipTempDir);

		$sCacheDir = APPROOT.'data/cache-'.$this->sTargetEnv;
		SetupUtils::builddir($sCacheDir);
		SetupUtils::tidydir($sCacheDir);

		\MetaModel::ResetCache(md5(APPROOT).'-'.$this->sTargetEnv);



		return array_keys($aModulesToCompile);
	}

	private function compileGetBaseConfigPath()
	{
		$sCompileFrom = $this->GetTargetConfig('baseConfig');
		if (null != $sCompileFrom) {
			return $sCompileFrom;
		}

		$sConfigPath = "{$this->sTargetPath}/config-itop.php";
		if (is_file($sConfigPath)) {
			return $sConfigPath;
		}

		$sCompileFrom = $this->GetTargetConfig('compileFrom');
		if (null != $sCompileFrom) {
			return utils::GetConfigFilePath($sCompileFrom);
		}

	}



	protected function GetMFModulesToCompile($sCompileFrom, $sSourceDir)
	{
		$aDirsToCompile = $this->GetTargetModules();
		$aModulesSelected = $this->GetTargetConfig('module_select', []);
		$sExtraDir = "{$this->sTargetPath}/modules";
		if (is_dir($sExtraDir))
		{
			$aDirsToCompile[] = $sExtraDir;
		}

		if (null != $sSourceDir) {
			$sSourceDirFull = APPROOT.$sSourceDir;
			if (is_dir($sSourceDirFull))
			{
				$aDirsToCompile[] = $sSourceDirFull;
			}
		}

		if (is_dir(APPROOT.'extensions'))
		{
			$aDirsToCompile[] = APPROOT.'extensions';
		}

		$aExtraDirs = $this->GetExtraDirsToScan($aDirsToCompile);
		$aDirsToCompile = array_merge($aDirsToCompile, $aExtraDirs);

		$aRet = array();


		// Actually read the modules available for the target environment,
		// but get the selection from the source environment and finally
		// mark as (automatically) chosen all the "remote" modules present in the
		// target environment (data/<target-env>-modules)
		// The actual choices will be recorded by RecordInstallation below
		$this->oExtensionsMap = new \iTopExtensionsMap($this->sTargetEnv, true, $aExtraDirs);

		// Determine the installed modules and extensions
		//

		$sConfigPath = $this->compileGetBaseConfigPath();
		$oSourceConfig = new Config($sConfigPath);

		$this->oExtensionsMap->LoadChoicesFromDatabase($oSourceConfig);
		foreach($this->oExtensionsMap->GetAllExtensions() as $oExtension)
		{
			if($this->IsExtensionSelected($oExtension))
			{
				$this->oExtensionsMap->MarkAsChosen($oExtension->sCode);
			}
		}
		// Do load the required modules
		//
		$oDictModule = new MFDictModule('dictionaries', 'iTop Dictionaries', APPROOT.'dictionaries');
		$aRet[$oDictModule->GetName()] = $oDictModule;

		$oFactory = new ModelFactory($aDirsToCompile);
		$sDeltaFile = APPROOT.'core/datamodel.core.xml';
		if (file_exists($sDeltaFile))
		{
			$oCoreModule = new MFCoreModule('core', 'Core Module', $sDeltaFile);
			$aRet[$oCoreModule->GetName()] = $oCoreModule;
		}
		$sDeltaFile = APPROOT.'application/datamodel.application.xml';
		if (file_exists($sDeltaFile))
		{
			$oApplicationModule = new MFCoreModule('application', 'Application Module', $sDeltaFile);
			$aRet[$oApplicationModule->GetName()] = $oApplicationModule;
		}

		//here, it seem, that the source's RunTimeEnvironment has to be launch in order to acquire it's `$aAvailableModules` via an `AnalyzeInstallation`
		if (null != $sCompileFrom) {
			$oSourceEnv = new \RunTimeEnvironment($sCompileFrom);
			$aAvailableModules = $oSourceEnv->AnalyzeInstallation($oSourceConfig, $aDirsToCompile);
		} else {
			$aAvailableModules = [];
		}

		$aModules = $oFactory->FindModules();
		foreach($aModules as $oModule)
		{
			$sModule = $oModule->GetName();
			$sModuleRootDir = $oModule->GetRootDir();
			$bIsExtra = $this->oExtensionsMap->ModuleIsChosenAsPartOfAnExtension($sModule, iTopExtension::SOURCE_REMOTE);

			if (in_array($sModule, $aModulesSelected)) {
				$aRet[$oModule->GetName()] = $oModule;
			} elseif (array_key_exists($sModule, $aAvailableModules))
			{
				if (($aAvailableModules[$sModule]['version_db'] != '') ||  $bIsExtra && !$oModule->IsAutoSelect()) //Extra modules are always unless they are 'AutoSelect'
				{
					$aRet[$oModule->GetName()] = $oModule;
				}
			}
		}

		// Now process the 'AutoSelect' modules
		do
		{
			// Loop while new modules are added...
			$bModuleAdded = false;
			foreach($aModules as $oModule)
			{
				if (!array_key_exists($oModule->GetName(), $aRet) && $oModule->IsAutoSelect())
				{
					try
					{
						$bSelected = false;
						SetupInfo::SetSelectedModules($aRet);
						eval('$bSelected = ('.$oModule->GetAutoSelect().');');
					}
					catch(Exception $e)
					{
						$bSelected = false;
					}
					if ($bSelected)
					{
						$aRet[$oModule->GetName()] = $oModule; // store the Id of the selected module
						$bModuleAdded  = true;
					}
				}
			}
		}
		while($bModuleAdded);

		$sDeltaFile = APPROOT.'data/'.$this->sTargetEnv.'.delta.xml';
		if (file_exists($sDeltaFile))
		{
			$oDelta = new MFDeltaModule($sDeltaFile);
			$aRet[$oDelta->GetName()] = $oDelta;
		}

//		fwrite(STDERR, __METHOD__.':'.var_export([
//				'$this->sTargetEnv' => $this->sTargetEnv,
//				'$sCompileFrom' => $sCompileFrom,
//				'$sConfigPath' => $sConfigPath,
//				'$sSourceDir' => $sSourceDir,
//				'$aDirsToCompile' => $aDirsToCompile,
//				'array_keys($aRet)' => array_keys($aRet),
//				'$aRet[""]-> GetDataModelFiles()' => isset($aRet['']) ? $aRet['']-> GetDataModelFiles() : null,
//			], true));

		return $aRet;
	}

	/**
	 * @param Config $oConfig
	 *
	 * @throws \Exception
	 */
	public function WriteConfigFileSafe($oConfig)
	{
		self::MakeDirSafe(APPCONF);
		self::MakeDirSafe(APPCONF.$this->sTargetEnv);

		$sTargetConfigFile = APPCONF.$this->sTargetEnv.'/'.ITOP_CONFIG_FILE;

		// Write the config file
		@chmod($sTargetConfigFile, 0770); // In case it exists: RWX for owner and group, nothing for others
		$oConfig->WriteToFile($sTargetConfigFile);
		@chmod($sTargetConfigFile, 0660); // let be less extremist since we will need to remove it on each testSuite launch
	}

	/**
	 * Wrappers for logging
	 */
	protected $aLog = array();



	protected function log_error($sText)
	{
		$this->aLog[] = "Error: $sText";
	}
	protected function log_warning($sText)
	{
		$this->aLog[] = "Warning: $sText";
	}
	protected function log_info($sText)
	{
		$this->aLog[] = "Info: $sText";
	}
	protected function log_ok($sText)
	{
		$this->aLog[] = "OK: $sText";
	}

	/**
	 * @return array
	 */
	protected function GetTargetModules()
	{
		$aTargetModules = [];

		$aModulePath = $this->GetTargetConfig('module_path', []);
		foreach ($aModulePath as $sModulePath) {
			$aTargetModules[] = $sModulePath;
		}

		return $aTargetModules;
	}
}