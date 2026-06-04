<?php

/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\CoreUpdate\Service;

require_once(APPROOT."setup/runtimeenv.class.inc.php");

use Config;
use Exception;
use RunTimeEnvironment;
use SetupUtils;

class RunTimeEnvironmentCoreUpdater extends RunTimeEnvironment
{
	/**
	 * Constructor
	 *
	 * @param string $sEnvironment
	 * @param bool $bAutoCommit
	 *
	 * @throws \Exception
	 */
	public function __construct($sEnvironment = ITOP_DEFAULT_ENV, $bAutoCommit = true)
	{
		parent::__construct($sEnvironment, $bAutoCommit);

		if ($sEnvironment != $this->sBuildEnv) {
			if (is_dir(APPROOT.'/env-'.$this->sBuildEnv)) {
				SetupUtils::rrmdir(APPROOT.'/env-'.$this->sBuildEnv);
			}
			if (is_dir(APPROOT.'/data/'.$this->sBuildEnv.'-modules')) {
				SetupUtils::rrmdir(APPROOT.'/data/'.$this->sBuildEnv.'-modules');
			}
			SetupUtils::copydir(APPROOT.'/data/'.$sEnvironment.'-modules', APPROOT.'/data/'.$this->sBuildEnv.'-modules');
		}
	}

	/**
	 * @param $sBuildEnv
	 *
	 * @throws \Exception
	 */
	public function CheckDirectories($sBuildEnv)
	{
		$sCurrentEnvDir = APPROOT.'env-'.$sBuildEnv;
		self::CheckDirectory($sCurrentEnvDir);
		self::CheckDirectory($sCurrentEnvDir.'-build');
	}

	/**
	 * @param $sDir
	 * @throws Exception
	 */
	public static function CheckDirectory($sDir)
	{
		if (!is_dir($sDir)) {
			if (!@mkdir($sDir, 0770)) {
				throw new Exception('Creating directory '.$sDir.' is denied (Check access rights)');
			}
		}
		// Try create a file
		$sTempFile = $sDir.'/__itop_temp_file__';
		if (!@touch($sTempFile)) {
			throw new Exception('Write access to '.$sDir.' is denied (Check access rights)');
		}
		@unlink($sTempFile);
	}

	/**
	 * @param null $sEnvironmentLabel
	 *
	 * @return \Config
	 * @throws \CoreException
	 */
	public function MakeConfigFile($sEnvironmentLabel = null)
	{
		// Clone the default 'production' config file
		//
		$oConfig = clone($this->GetConfig(ITOP_DEFAULT_ENV));

		$oConfig->UpdateIncludes('env-'.$this->sBuildEnv);

		if (is_null($sEnvironmentLabel)) {
			$sEnvironmentLabel = $this->sBuildEnv;
		}
		$oConfig->Set('app_env_label', $sEnvironmentLabel, 'application updater');

		return $oConfig;
	}

	/**
	 * @param null $sEnvironment
	 *
	 * @return \Config
	 * @throws \Exception
	 */
	protected function GetConfig($sEnvironment = null)
	{
		if (is_null($sEnvironment)) {
			$sEnvironment = $this->sBuildEnv;
		}
		$sFile = APPCONF.$sEnvironment.'/'.ITOP_CONFIG_FILE;
		if (file_exists($sFile)) {
			try {
				return new Config($sFile);
			} catch (Exception $e) {
			}
		}
		throw new Exception('No configuration file available');
	}
}
