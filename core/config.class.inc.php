<?php
require_once('coreexception.class.inc.php');
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

	public function __construct($sConfigFile, $bLoadConfig = true)
	{
		$this->m_sFile = $sConfigFile;
		$this->m_aAppModules = array();
		$this->m_aDataModels = array();
		$this->m_aAddons = array();

		$this->m_sDBHost = '';
		$this->m_sDBUser = '';
		$this->m_sDBPwd = '';
		$this->m_sDBName = '';
		$this->m_sDBSubname = '';
		if ($bLoadConfig)
		{
			$this->Load($sConfigFile);
			$this->Verify();
		}
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

	public function SetDBHost($sDBHost)
	{
		$this->m_sDBHost = $sDBHost;
	}
	
	public function SetDBName($sDBName)
	{
		$this->m_sDBName = $sDBName;
	}

	public function SetDBSubname($sDBSubName)
	{
		$this->m_sDBSubname = $sDBSubName;
	}

	public function SetDBUser($sUser)
	{
		$this->m_sDBUser = $sUser;
	}

	public function SetDBPwd($sPwd)
	{
		$this->m_sDBPwd = $sPwd;
	}
	public function FileIsWritable()
	{
		return is_writable($this->m_sFile);
	}
	
	/**
	 * Write the configuration to a file (php format) that can be reloaded later
	 * By default write to the same file that was specified when constructing the object
	 * @param $sFileName string Name of the file to write to (emtpy to write to the same file)
	 * @return boolean True otherwise throws an Exception
	 */	 	 	 	 	
	public function WriteToFile($sFileName = '')
	{
		if (empty($sFileName))
		{
			$sFileName = $this->m_sFile;
		}
		$hFile = @fopen($sFileName, 'w');
		if ($hFile !== false)
		{
			fwrite($hFile, "<?php\n");
			fwrite($hFile, "\n/**\n");
			fwrite($hFile, " *\n");
			fwrite($hFile, " * phpMyORM configuration file, generated by the iTop configuration wizard\n");
			fwrite($hFile, " *\n");
			fwrite($hFile, " * The file is used in MetaModel::LoadConfig() which does all the necessary initialization job\n");
			fwrite($hFile, " *\n");
			fwrite($hFile, " */\n");
			fwrite($hFile, "\n");
			
			fwrite($hFile, "\$MySettings = array(\n");
			fwrite($hFile, "\t'db_host' => '{$this->m_sDBHost}',\n");
			fwrite($hFile, "\t'db_user' => '{$this->m_sDBUser}',\n");
			fwrite($hFile, "\t'db_pwd' => '{$this->m_sDBPwd}',\n");
			fwrite($hFile, "\t'db_name' => '{$this->m_sDBName}',\n");
			fwrite($hFile, "\t'db_subname' => '{$this->m_sDBSubname}',\n");
			fwrite($hFile, ");\n");
			
			fwrite($hFile, "\n/**\n");
			fwrite($hFile, " *\n");
			fwrite($hFile, " * Data model modules to be loaded. Names should be specified as absolute paths\n");
			fwrite($hFile, " *\n");
			fwrite($hFile, " */\n");
			fwrite($hFile, "\$MyModules = array(\n");
			fwrite($hFile, "\t'application' => array (\n");
			fwrite($hFile, "\t\t'../application/menunode.class.inc.php',\n");
			fwrite($hFile, "\t\t'../application/audit.rule.class.inc.php',\n");
			fwrite($hFile, "\t\t// to be continued...\n");
			fwrite($hFile, "\t),\n");
			fwrite($hFile, "\t'business' => array (\n");
			fwrite($hFile, "\t\t'../business/itop.business.class.inc.php'\n");
			fwrite($hFile, "\t\t// to be continued...\n");
			fwrite($hFile, "\t),\n");
			fwrite($hFile, "\t'addons' => array (\n");
			fwrite($hFile, "\t\t'user rights' => '../addons/userrights/userrightsprofile.class.inc.php',\n");
			fwrite($hFile, "\t\t// other modules to come later\n");
			fwrite($hFile, "\t)\n");
			fwrite($hFile, ");\n");
			fwrite($hFile, '?'.'>'); // Avoid perturbing the syntax highlighting !
			return fclose($hFile);
		}
		else
		{
			throw new ConfigException("Could not write to configuration file", array('file' => $sFileName));
		}
	}
}
?>
