<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
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
	public function __construct($sEnvironment = 'production', $bAutoCommit = true)
	{
		parent::__construct($sEnvironment, $bAutoCommit);

		if ($sEnvironment != $this->sTargetEnv)
		{
			if (is_dir(APPROOT.'/env-'.$this->sTargetEnv))
			{
				SetupUtils::rrmdir(APPROOT.'/env-'.$this->sTargetEnv);
			}
			if (is_dir(APPROOT.'/data/'.$this->sTargetEnv.'-modules'))
			{
				SetupUtils::rrmdir(APPROOT.'/data/'.$this->sTargetEnv.'-modules');
			}
			SetupUtils::copydir(APPROOT.'/data/'.$sEnvironment.'-modules', APPROOT.'/data/'.$this->sTargetEnv.'-modules');
		}
	}

	/**
	 * @param $sTargetEnv
	 *
	 * @throws \Exception
	 */
	public function CheckDirectories($sTargetEnv)
	{
		$sTargetDir = APPROOT.'env-'.$sTargetEnv;
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
		$oConfig = clone($this->GetConfig('production'));

		$oConfig->UpdateIncludes('env-'.$this->sTargetEnv);

		if (is_null($sEnvironmentLabel))
		{
			$sEnvironmentLabel = $this->sTargetEnv;
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
		if (is_null($sEnvironment))
		{
			$sEnvironment = $this->sTargetEnv;
		}
		$sFile = APPCONF.$sEnvironment.'/'.ITOP_CONFIG_FILE;
		if (file_exists($sFile))
		{
			try
			{
				return new Config($sFile);
			}
			catch (Exception $e)
			{
			}
		}
		throw new Exception('No configuration file available');
	}
}
