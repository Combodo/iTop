<?php

/**
 * Config
 * configuration data
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class ConfigException extends CoreException
{
}

class Config
{
	//protected $m_bIsLoaded = false;
	protected $m_sFile = '';

	protected $m_aAppModules;
	protected $m_aDataModels;
	protected $m_aAddons;

	protected $m_sDBHost;
	protected $m_sDBUser;
	protected $m_sDBPwd;
	protected $m_sDBName;
	protected $m_sDBSubname;

	public function __construct($sConfigFile)
	{
		$this->m_sFile = $sConfigFile;
		$this->Load($sConfigFile);
		$this->Verify();
	}

	protected function CheckFile($sPurpose, $sFileName)
	{
		if (!file_exists($sFileName))
		{
			throw new ConfigException("Could not find $sPurpose file", array('file' => $sFileName));
		}
	}

	protected function Load($sConfigFile)
	{
		$this->CheckFile('configuration', $sConfigFile);

		$sConfigCode = trim(file_get_contents($sConfigFile));

		// This does not work on several lines
		// preg_match('/^<\\?php(.*)\\?'.'>$/', $sConfigCode, $aMatches)...
		// So, I've implemented a solution suggested in the PHP doc (search for phpWrapper)
		try
		{
			ob_start();
			$sCode = str_replace('<'.'?php','<'.'?', $sConfigCode);
			eval('?'.'>'.trim($sCode).'<'.'?');
			$sNoise = trim(ob_get_contents());
			ob_end_clean();
		}
		catch (Exception $e)
		{
			// well, never reach in case of parsing error :-(
			// will be improved in PHP 6 ?
			throw new ConfigException('Error in configuration file', array('file' => $sConfigFile, 'error' => $e->getMessage()));
		}
		if (strlen($sNoise) > 0)
		{
			// Note: sNoise is an html output, but so far it was ok for me (e.g. showing the entire call stack) 
			throw new ConfigException('Syntax error in configuration file', array('file' => $sConfigFile, 'error' => $sNoise));
		}

		if (!isset($MySettings) || !is_array($MySettings))
		{
			throw new ConfigException('Missing array in configuration file', array('file' => $sConfigFile, 'expected' => '$MySettings'));
		}
		if (!isset($MyModules) || !is_array($MyModules))
		{
			throw new ConfigException('Missing item in configuration file', array('file' => $sConfigFile, 'expected' => '$MyModules'));
		}
		if (!array_key_exists('application', $MyModules))
		{
			throw new ConfigException('Missing item in configuration file', array('file' => $sConfigFile, 'expected' => '$MyModules[\'application\']'));
		}
		if (!array_key_exists('business', $MyModules))
		{
			throw new ConfigException('Missing item in configuration file', array('file' => $sConfigFile, 'expected' => '$MyModules[\'business\']'));
		}
		if (!array_key_exists('addons', $MyModules))
		{
			throw new ConfigException('Missing item in configuration file', array('file' => $sConfigFile, 'expected' => '$MyModules[\'addons\']'));
		}
		if (!array_key_exists('user rights', $MyModules['addons']))
		{
			$MyModules['addons']['user rights'] = '../addons/userrights/userrightsnull.class.inc.php';
		}
		$this->m_aAppModules = $MyModules['application'];
		$this->m_aDataModels = $MyModules['business'];
		$this->m_aAddons = $MyModules['addons'];

		$this->m_sDBHost = trim($MySettings['db_host']);
		$this->m_sDBUser = trim($MySettings['db_user']);
		$this->m_sDBPwd = trim($MySettings['db_pwd']);
		$this->m_sDBName = trim($MySettings['db_name']);
		$this->m_sDBSubname = trim($MySettings['db_subname']);
	}

	protected function Verify()
	{
		foreach ($this->m_aAppModules as $sModule => $sToInclude)
		{
			$this->CheckFile('application module', $sToInclude);
		}
		foreach ($this->m_aDataModels as $sModule => $sToInclude)
		{
			$this->CheckFile('business model', $sToInclude);
		}
		foreach ($this->m_aAddons as $sModule => $sToInclude)
		{
			$this->CheckFile('addon module', $sToInclude);
		}
	}

	public function GetAppModules()
	{
		return $this->m_aAppModules;
	}

	public function GetDataModels()
	{
		return $this->m_aDataModels;
	}

	public function GetAddons()
	{
		return $this->m_aAddons;
	}

	public function GetDBHost()
	{
		return $this->m_sDBHost;
	}


	public function GetDBName()
	{
		return $this->m_sDBName;
	}

	public function GetDBSubname()
	{
		return $this->m_sDBSubname;
	}

	public function GetDBUser()
	{
		return $this->m_sDBUser;
	}

	public function GetDBPwd()
	{
		return $this->m_sDBPwd;
	}
}
?>
