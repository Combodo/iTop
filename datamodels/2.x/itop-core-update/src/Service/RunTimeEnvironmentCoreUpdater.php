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

class RunTimeEnvironmentCoreUpdater extends RunTimeEnvironment
{
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

	public function MakeConfigFile($sEnvironmentLabel = null)
	{
		$oConfig = $this->GetConfig();
		if (!is_null($oConfig))
		{
			// Return the existing one
			$oConfig->UpdateIncludes('env-'.$this->sTargetEnv);
		}
		else
		{
			// Clone the default 'production' config file
			//
			$oConfig = clone($this->GetConfig('production'));

			$oConfig->UpdateIncludes('env-'.$this->sTargetEnv);

			if (is_null($sEnvironmentLabel))
			{
				$sEnvironmentLabel = $this->sTargetEnv;
			}
			$oConfig->Set('app_env_label', $sEnvironmentLabel);
			if ($this->sFinalEnv !== 'production')
			{
				$oConfig->Set('db_name', $oConfig->Get('db_name').'_'.$this->sFinalEnv);
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
}
