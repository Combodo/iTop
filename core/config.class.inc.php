<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

define('ITOP_VERSION', '$ITOP_VERSION$');
define('ITOP_REVISION', '$WCREV$');
define('ITOP_BUILD_DATE', '$WCNOW$');

/**
 * Configuration read/write
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once('coreexception.class.inc.php');

class ConfigException extends CoreException
{
}

define ('DEFAULT_LOG_GLOBAL', true);
define ('DEFAULT_LOG_NOTIFICATION', true);
define ('DEFAULT_LOG_ISSUE', true);
define ('DEFAULT_LOG_WEB_SERVICE', true);

define ('DEFAULT_MIN_DISPLAY_LIMIT', 10);
define ('DEFAULT_MAX_DISPLAY_LIMIT', 15);
define ('DEFAULT_STANDARD_RELOAD_INTERVAL', 5*60);
define ('DEFAULT_FAST_RELOAD_INTERVAL', 1*60);
define ('DEFAULT_SECURE_CONNECTION_REQUIRED', false);
define ('DEFAULT_ALLOWED_LOGIN_TYPES', 'form|basic|external');
define ('DEFAULT_EXT_AUTH_VARIABLE', '$_SERVER[\'REMOTE_USER\']');

/**
 * Config
 * configuration data (this class cannot not be localized, because it is responsible for loading the dictionaries)
 *
 * @package     iTopORM
 */
class Config
{
	//protected $m_bIsLoaded = false;
	protected $m_sFile = '';

	protected $m_aAppModules;
	protected $m_aDataModels;
	protected $m_aAddons;
	protected $m_aDictionaries;

	protected $m_aModuleSettings;

	protected $m_sDBHost;
	protected $m_sDBUser;
	protected $m_sDBPwd;
	protected $m_sDBName;
	protected $m_sDBSubname;

	/**
	 * @var integer Event log options (see LOG_... definition)
	 */	 	
	protected $m_bLogGlobal;
	protected $m_bLogNotification;
	protected $m_bLogIssue;
	protected $m_bLogWebService;

	/**
	 * @var integer Number of elements to be displayed when there are more than m_iMaxDisplayLimit elements
	 */	 	
	protected $m_iMinDisplayLimit;
	/**
	 * @var integer Max number of elements before truncating the display
	 */	 	
	protected $m_iMaxDisplayLimit;

	/**
	 * @var integer Number of seconds between two reloads of the display (standard)
	 */	 	
	protected $m_iStandardReloadInterval;
	/**
	 * @var integer Number of seconds between two reloads of the display (fast)
	 */	 	
	protected $m_iFastReloadInterval;
	
	/**
	 * @var boolean Whether or not a secure connection is required for using the application
	 */	 	
	protected $m_bSecureConnectionRequired;

	/**
	 * @var string Langage code, default if the user language is undefined
	 */	 	
	protected $m_sDefaultLanguage;
	
	/**
	 * @var string Type of login process allowed: form|basic|url|external
	 */
	 protected $m_sAllowedLoginTypes;
	 
	/**
	 * @var string Name of the PHP variable in which external authentication information is passed by the web server
	 */
	 protected $m_sExtAuthVariable;

	public function __construct($sConfigFile, $bLoadConfig = true)
	{
		$this->m_sFile = $sConfigFile;
		$this->m_aAppModules = array(
			// Some default modules, always present can be move to an official iTop Module later if needed
			'../application/transaction.class.inc.php',
			'../application/menunode.class.inc.php',
			'../application/user.preferences.class.inc.php',
			'../application/audit.rule.class.inc.php',
// Romain - That's dirty, because those 3 classes are in fact part of the core
//          but I needed those classes to be derived from cmdbAbstractObject
//          (to be managed via the GUI) and this class in not really known from
//          the core, PLUS I needed the includes to be there also for the setup
//          to create the tables.
			'../core/event.class.inc.php',
			'../core/action.class.inc.php',
			'../core/trigger.class.inc.php',
		);
		$this->m_aDataModels = array();
		$this->m_aAddons = array(
			// Default AddOn, always present can be moved to an official iTop Module later if needed
			'user rights' => '../addons/userrights/userrightsprofile.class.inc.php',
		);
		$this->m_aDictionaries = array(
			// Default dictionaries, always present can be moved to an official iTop Module later if needed
			'../dictionaries/dictionary.itop.core.php',
			'../dictionaries/dictionary.itop.ui.php',		// Support for English
			'../dictionaries/fr.dictionary.itop.ui.php',	// Support for French
		);

		$this->m_sDBHost = '';
		$this->m_sDBUser = '';
		$this->m_sDBPwd = '';
		$this->m_sDBName = '';
		$this->m_sDBSubname = '';
		$this->m_bLogGlobal = DEFAULT_LOG_GLOBAL;
		$this->m_bLogNotification = DEFAULT_LOG_NOTIFICATION;
		$this->m_bLogIssue = DEFAULT_LOG_ISSUE;
		$this->m_bLogWebService = DEFAULT_LOG_WEB_SERVICE;
		$this->m_iMinDisplayLimit = DEFAULT_MIN_DISPLAY_LIMIT;
		$this->m_iMaxDisplayLimit = DEFAULT_MAX_DISPLAY_LIMIT;
		$this->m_iStandardReloadInterval = DEFAULT_STANDARD_RELOAD_INTERVAL;
		$this->m_iFastReloadInterval = DEFAULT_FAST_RELOAD_INTERVAL;
		$this->m_bSecureConnectionRequired = DEFAULT_SECURE_CONNECTION_REQUIRED;
		$this->m_sDefaultLanguage = 'EN US';
		$this->m_sAllowedLoginTypes = DEFAULT_ALLOWED_LOGIN_TYPES;
		$this->m_sExtAuthVariable = DEFAULT_EXT_AUTH_VARIABLE;
		
		$this->m_aModuleSettings = array();

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
			eval('?'.'>'.trim($sConfigCode));
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
			throw new ConfigException('Syntax error in configuration file', array('file' => $sConfigFile, 'error' => '<tt>'.htmlentities($sNoise).'</tt>'));
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
		if (!array_key_exists('dictionaries', $MyModules))
		{
			throw new ConfigException('Missing item in configuration file', array('file' => $sConfigFile, 'expected' => '$MyModules[\'dictionaries\']'));
		}
		$this->m_aAppModules = $MyModules['application'];
		$this->m_aDataModels = $MyModules['business'];
		$this->m_aAddons = $MyModules['addons'];
		$this->m_aDictionaries = $MyModules['dictionaries'];

		$this->m_sDBHost = trim($MySettings['db_host']);
		$this->m_sDBUser = trim($MySettings['db_user']);
		$this->m_sDBPwd = trim($MySettings['db_pwd']);
		$this->m_sDBName = trim($MySettings['db_name']);
		$this->m_sDBSubname = trim($MySettings['db_subname']);

		$this->m_bLogGlobal = isset($MySettings['log_global']) ? trim($MySettings['log_global']) : DEFAULT_LOG_GLOBAL;
		$this->m_bLogNotification = isset($MySettings['log_notification']) ? trim($MySettings['log_notification']) : DEFAULT_LOG_NOTIFICATION;
		$this->m_bLogIssue = isset($MySettings['log_issue']) ? trim($MySettings['log_issue']) : DEFAULT_LOG_ISSUE;
		$this->m_bLogWebService = isset($MySettings['log_web_service']) ? trim($MySettings['log_web_service']) : DEFAULT_LOG_WEB_SERVICE;
		$this->m_iMinDisplayLimit = isset($MySettings['min_display_limit']) ? trim($MySettings['min_display_limit']) : DEFAULT_MIN_DISPLAY_LIMIT;
		$this->m_iMaxDisplayLimit = isset($MySettings['max_display_limit']) ? trim($MySettings['max_display_limit']) : DEFAULT_MAX_DISPLAY_LIMIT;
		$this->m_iStandardReloadInterval = isset($MySettings['standard_reload_interval']) ? trim($MySettings['standard_reload_interval']) : DEFAULT_STANDARD_RELOAD_INTERVAL;
		$this->m_iFastReloadInterval = isset($MySettings['fast_reload_interval']) ? trim($MySettings['fast_reload_interval']) : DEFAULT_FAST_RELOAD_INTERVAL;
		$this->m_bSecureConnectionRequired = isset($MySettings['secure_connection_required']) ? trim($MySettings['secure_connection_required']) : DEFAULT_SECURE_CONNECTION_REQUIRED;

		$this->m_aModuleSettings = isset($MyModuleSettings) ?  $MyModuleSettings : array();

		$this->m_sDefaultLanguage = isset($MySettings['default_language']) ? trim($MySettings['default_language']) : 'EN US';
		$this->m_sAllowedLoginTypes = isset($MySettings['allowed_login_types']) ? trim($MySettings['allowed_login_types']) : DEFAULT_ALLOWED_LOGIN_TYPES;
		$this->m_sExtAuthVariable = isset($MySettings['ext_auth_variable']) ? trim($MySettings['ext_auth_variable']) : DEFAULT_EXT_AUTH_VARIABLE;
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
		foreach ($this->m_aDictionaries as $sModule => $sToInclude)
		{
			$this->CheckFile('dictionary', $sToInclude);
		}
	}

	public function GetModuleSetting($sModule, $sProperty, $defaultvalue = null)
	{
		if (isset($this->m_aModuleSettings[$sModule][$sProperty]))
		{
			return $this->m_aModuleSettings[$sModule][$sProperty];
		}
		return $defaultvalue;
	}

	public function SetModuleSetting($sModule, $sProperty, $value)
	{
		$this->m_aModuleSettings[$sModule][$sProperty] = $value;
	}

	public function GetAppModules()
	{
		return $this->m_aAppModules;
	}
	public function SetAppModules($aAppModules)
	{
		$this->m_aAppModules = $aAppModules;
	}

	public function GetDataModels()
	{
		return $this->m_aDataModels;
	}
	public function SetDataModels($aDataModels)
	{
		$this->m_aDataModels = $aDataModels;
	}

	public function GetAddons()
	{
		return $this->m_aAddons;
	}
	public function SetAddons($aAddons)
	{
		$this->m_aAddons = $aAddons;
	}

	public function GetDictionaries()
	{
		return $this->m_aDictionaries;
	}
	public function SetDictionaries($aDictionaries)
	{
		$this->m_aDictionaries = $aDictionaries;
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

	public function GetLogGlobal()
	{
		return $this->m_bLogGlobal;
	}

	public function GetLogNotification()
	{
		return $this->m_bLogNotification;
	}

	public function GetLogIssue()
	{
		return $this->m_bLogIssue;
	}

	public function GetLogWebService()
	{
		return $this->m_bLogWebService;
	}

	public function GetMinDisplayLimit()
	{
		return $this->m_iMinDisplayLimit;
	}

	public function GetMaxDisplayLimit()
	{
		return $this->m_iMaxDisplayLimit;
	}

	public function GetStandardReloadInterval()
	{
		return $this->m_iStandardReloadInterval;
	}

	public function GetFastReloadInterval()
	{
		return $this->m_iFastReloadInterval;
	}

	public function GetSecureConnectionRequired()
	{
		return $this->m_bSecureConnectionRequired;
	}

	public function GetDefaultLanguage()
	{
		return $this->m_sDefaultLanguage;
	}


	public function GetAllowedLoginTypes()
	{
		return explode('|', $this->m_sAllowedLoginTypes);
	}

	public function GetExternalAuthenticationVariable()
	{
		return $this->m_sExtAuthVariable;
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

	public function SetLogGlobal($iLogGlobal)
	{
		$this->m_iLogGlobal = $iLogGlobal;
	}

	public function SetLogNotification($iLogNotification)
	{
		$this->m_iLogNotification = $iLogNotification;
	}

	public function SetLogIssue($iLogIssue)
	{
		$this->m_iLogIssue = $iLogIssue;
	}

	public function SetLogWebService($iLogWebService)
	{
		$this->m_iLogWebService = $iLogWebService;
	}

	public function SetMinDisplayLimit($iMinDisplayLimit)
	{
		$this->m_iMinDisplayLimit = $iMinDisplayLimit;
	}

	public function SetMaxDisplayLimit($iMaxDisplayLimit)
	{
		$this->m_iMaxDisplayLimit = $iMaxDisplayLimit;
	}

	public function SetStandardReloadInterval($iStandardReloadInterval)
	{
		$this->m_iStandardReloadInterval = $iStandardReloadInterval;
	}

	public function SetFastReloadInterval($iFastReloadInterval)
	{
		$this->m_iFastReloadInterval = $iFastReloadInterval;
	}

	public function SetSecureConnectionRequired($bSecureConnectionRequired)
	{
		$this->m_bSecureConnectionRequired = $bSecureConnectionRequired;
	}

	public function SetDefaultLanguage($sLanguageCode)
	{
		$this->m_sDefaultLanguage = $sLanguageCode;
	}

	public function SetAllowedLoginTypes($aAllowedLoginTypes)
	{
		$this->m_sAllowedLoginTypes = implode('|', $aAllowedLoginTypes);
	}

	public function SetExternalAuthenticationVariable($sExtAuthVariable)
	{
		$this->m_sExtAuthVariable = $sExtAuthVariable;
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
			fwrite($hFile, "\t'db_pwd' => '".addslashes($this->m_sDBPwd)."',\n");
			fwrite($hFile, "\t'db_name' => '{$this->m_sDBName}',\n");
			fwrite($hFile, "\t'db_subname' => '{$this->m_sDBSubname}',\n");
			fwrite($hFile, "\n");
			fwrite($hFile, "\t'log_global' => {$this->m_bLogGlobal},\n");
			fwrite($hFile, "\t'log_notification' => {$this->m_bLogNotification},\n");
			fwrite($hFile, "\t'log_issue' => {$this->m_bLogIssue},\n");
			fwrite($hFile, "\t'log_web_service' => {$this->m_bLogWebService},\n");
			fwrite($hFile, "\t'min_display_limit' => {$this->m_iMinDisplayLimit},\n");
			fwrite($hFile, "\t'max_display_limit' => {$this->m_iMaxDisplayLimit},\n");
			fwrite($hFile, "\t'standard_reload_interval' => {$this->m_iStandardReloadInterval},\n");
			fwrite($hFile, "\t'fast_reload_interval' => {$this->m_iFastReloadInterval},\n");
			fwrite($hFile, "\t'secure_connection_required' => ".($this->m_bSecureConnectionRequired ? 'true' : 'false').",\n");
			fwrite($hFile, "\t'default_language' => '{$this->m_sDefaultLanguage}',\n");
			fwrite($hFile, "\t'allowed_login_types' => '{$this->m_sAllowedLoginTypes}',\n");
			fwrite($hFile, ");\n");

			fwrite($hFile, "\n");
			fwrite($hFile, "\$MyModuleSettings = array(\n");
			foreach ($this->m_aModuleSettings as $sModule => $aProperties)
			{
				fwrite($hFile, "\t'$sModule' => array (\n");
				foreach ($aProperties as $sProperty => $value)
				{
					$sExport = var_export($value, true);
					fwrite($hFile, "\t\t'$sProperty' => $sExport,\n");
				}
				fwrite($hFile, "\t),\n");
			}
			fwrite($hFile, ");\n");
			
			fwrite($hFile, "\n/**\n");
			fwrite($hFile, " *\n");
			fwrite($hFile, " * Data model modules to be loaded. Names should be specified as absolute paths\n");
			fwrite($hFile, " *\n");
			fwrite($hFile, " */\n");
			fwrite($hFile, "\$MyModules = array(\n");
			fwrite($hFile, "\t'application' => array (\n");
			foreach($this->m_aAppModules as $sFile)
			{
				fwrite($hFile, "\t\t'$sFile',\n");
			}
			fwrite($hFile, "\t),\n");
			fwrite($hFile, "\t'business' => array (\n");
			foreach($this->m_aDataModels as $sFile)
			{
				fwrite($hFile, "\t\t'$sFile',\n");
			}
			fwrite($hFile, "\t),\n");
			fwrite($hFile, "\t'addons' => array (\n");
			foreach($this->m_aAddons as $sKey => $sFile)
			{
				fwrite($hFile, "\t\t'$sKey' => '$sFile',\n");
			}
			fwrite($hFile, "\t),\n");
			fwrite($hFile, "\t'dictionaries' => array (\n");
			foreach($this->m_aDictionaries as $sFile)
			{
				fwrite($hFile, "\t\t'$sFile',\n");
			}
			fwrite($hFile, "\t),\n");
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
