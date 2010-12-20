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

define('ACCESS_USER_WRITE', 1);
define('ACCESS_ADMIN_WRITE', 2);
define('ACCESS_FULL', ACCESS_USER_WRITE | ACCESS_ADMIN_WRITE);
define('ACCESS_READONLY', 0);

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

define ('DEFAULT_CHARACTER_SET', 'utf8');
define ('DEFAULT_COLLATION', 'utf8_general_ci');

define ('DEFAULT_LOG_GLOBAL', true);
define ('DEFAULT_LOG_NOTIFICATION', true);
define ('DEFAULT_LOG_ISSUE', true);
define ('DEFAULT_LOG_WEB_SERVICE', true);
define ('DEFAULT_LOG_KPI_DURATION', false);
define ('DEFAULT_LOG_KPI_MEMORY', false);
define ('DEFAULT_DEBUG_QUERIES', false);

define ('DEFAULT_QUERY_CACHE_ENABLED', true);


define ('DEFAULT_MIN_DISPLAY_LIMIT', 10);
define ('DEFAULT_MAX_DISPLAY_LIMIT', 15);
define ('DEFAULT_STANDARD_RELOAD_INTERVAL', 5*60);
define ('DEFAULT_FAST_RELOAD_INTERVAL', 1*60);
define ('DEFAULT_SECURE_CONNECTION_REQUIRED', false);
define ('DEFAULT_HTTPS_HYPERLINKS', false);
define ('DEFAULT_ALLOWED_LOGIN_TYPES', 'form|basic|external');
define ('DEFAULT_EXT_AUTH_VARIABLE', '$_SERVER[\'REMOTE_USER\']');
define ('DEFAULT_ENCRYPTION_KEY', '@iT0pEncr1pti0n!'); // We'll use a random value, later...

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
	protected $m_aWebServiceCategories;
	protected $m_aAddons;
	protected $m_aDictionaries;

	protected $m_aModuleSettings;

	// New way to store the settings !
	//
	protected $m_aSettings = array(
		'skip_check_to_write' => array(
			'type' => 'bool',
			'description' => 'Disable data format and integrity checks to boost up data load (insert or update)',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'skip_check_ext_keys' => array(
			'type' => 'bool',
			'description' => 'Disable external key check when checking the value of attribtutes',
			'default' => false,
			'value' => false,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'skip_strong_security' => array(
			'type' => 'bool',
			'description' => 'Disable strong security - TEMPORY: this flag should be removed when we are more confident in the recent change in security',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'graphviz_path' => array(
			'type' => 'string',
			'description' => 'Path to the Graphviz "dot" executable for graphing objects lifecycle',
			'default' => '/usr/bin/dot',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'session_name' => array(
			'type' => 'string',
			'description' => 'The name of the cookie used to store the PHP session id',
			'default' => 'iTop',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'max_combo_length' => array(
			'type' => 'int',
			'description' => 'The maximum number of elements in a drop-down list. If more then an autocomplete will be used',
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'min_autocomplete_chars' => array(
			'type' => 'int',
			'description' => 'The minimum number of characters to type in order to trigger the "autocomplete" behavior',
			'default' => 3,
			'value' => 3,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'allow_target_creation' => array(
			'type' => 'bool',
			'description' => 'Displays the + button on external keys to create target objects',
			'default' => true,
			'value' => true,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		// Levels that trigger a confirmation in the CSV import/synchro wizard
		'csv_import_min_object_confirmation' => array(
			'type' => 'integer',
			'description' => 'Minimum number of objects to check for the confirmation percentages',
			'default' => 3,
			'value' => 3,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'csv_import_errors_percentage' => array(
			'type' => 'integer',
			'description' => 'Percentage of errors that trigger a confirmation in the CSV import',
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'csv_import_modifications_percentage' => array(
			'type' => 'integer',
			'description' => 'Percentage of modifications that trigger a confirmation in the CSV import',
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'csv_import_creations_percentage' => array(
			'type' => 'integer',
			'description' => 'Percentage of creations that trigger a confirmation in the CSV import',
			'default' => 50,
			'value' => 50,
			'source_of_value' => '',
			'show_in_conf_sample' => false,
		),
		'access_mode' => array(
			'type' => 'integer',
			'description' => 'Combination of flags (ACCESS_USER_WRITE | ACCESS_ADMIN_WRITE, or ACCESS_FULL)',
			'default' => ACCESS_FULL,
			'value' => ACCESS_FULL,
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'access_message' => array(
			'type' => 'string',
			'description' => 'Message displayed to the users when there is any access restriction',
			'default' => 'iTop is temporarily frozen, please wait... (the admin team)',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
		'online_help' => array(
			'type' => 'string',
			'description' => 'Hyperlink to the online-help web page',
			'default' => 'http://www.combodo.com/itop-help',
			'value' => '',
			'source_of_value' => '',
			'show_in_conf_sample' => true,
		),
	);

	public function IsProperty($sPropCode)
	{
		return (array_key_exists($sPropCode, $this->m_aSettings));
	}

	public function Set($sPropCode, $value, $sSourceDesc = 'unknown')
	{
		$sType = $this->m_aSettings[$sPropCode]['type'];
		switch($sType)
		{
		case 'bool':
			$value = (bool) $value;
			break;
		case 'string':
			$value = (string) $value;
			break;
		case 'integer':
			$value = (integer) $value;
			break;
		case 'float':
			$value = (float) $value;
			break;
		default:
			throw new CoreException('Unknown type for setting', array('property' => $sPropCode, 'type' => $sType));
		}
		$this->m_aSettings[$sPropCode]['value'] = $value;
		$this->m_aSettings[$sPropCode]['source_of_value'] = $sSourceDesc;

	}

	public function Get($sPropCode)
	{
		return $this->m_aSettings[$sPropCode]['value'];
	}

	// Those variables will be deprecated later, when the transition to ...Get('my_setting') will be done
	protected $m_sDBHost;
	protected $m_sDBUser;
	protected $m_sDBPwd;
	protected $m_sDBName;
	protected $m_sDBSubname;
	protected $m_sDBCharacterSet;
	protected $m_sDBCollation;

	/**
	 * Event log options (see LOG_... definition)
	 */	 	
	// Those variables will be deprecated later, when the transition to ...Get('my_setting') will be done
	protected $m_bLogGlobal;
	protected $m_bLogNotification;
	protected $m_bLogIssue;
	protected $m_bLogWebService;
	protected $m_bLogKpiDuration; // private setting
	protected $m_bLogKpiMemory; // private setting
	protected $m_bDebugQueries; // private setting
	protected $m_bQueryCacheEnabled; // private setting

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
	 * @var boolean Whether or not a secure connection is required for using the application.
	 *              If set, any attempt to connect to an iTop page with http:// will be redirected
	 *              to https://
	 */	 	
	protected $m_bSecureConnectionRequired;

	/**
	 * @var boolean Forces iTop to output hyperlinks starting with https:// even
	 *              if the current page is not using https. This can be useful when
	 *              the application runs behind a SSL gateway
	 */	 	
	protected $m_bHttpsHyperlinks;

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

	/**
	 * @var string Encryption key used for all attributes of type "encrypted string". Can be set to a random value
	 *             unless you want to import a database from another iTop instance, in which case you must use
	 *             the same encryption key in order to properly decode the encrypted fields
	 */
	 protected $m_sEncryptionKey;

	/**
	 * @var array Additional character sets to be supported by the interactive CSV import
	 *            'iconv_code' => 'display name'
	 */
	 protected $m_aCharsets;

	public function __construct($sConfigFile, $bLoadConfig = true)
	{
		$this->m_sFile = $sConfigFile;
		$this->m_aAppModules = array(
			// Some default modules, always present can be move to an official iTop Module later if needed
			'application/transaction.class.inc.php',
			'application/menunode.class.inc.php',
			'application/user.preferences.class.inc.php',
			'application/audit.rule.class.inc.php',
// Romain - That's dirty, because those 3 classes are in fact part of the core
//          but I needed those classes to be derived from cmdbAbstractObject
//          (to be managed via the GUI) and this class in not really known from
//          the core, PLUS I needed the includes to be there also for the setup
//          to create the tables.
			'core/event.class.inc.php',
			'core/action.class.inc.php',
			'core/trigger.class.inc.php',
		);
		$this->m_aDataModels = array();
		$this->m_aWebServiceCategories = array(
			'webservices/webservices.basic.php',
		);
		$this->m_aAddons = array(
			// Default AddOn, always present can be moved to an official iTop Module later if needed
			'user rights' => 'addons/userrights/userrightsprofile.class.inc.php',
		);
		$this->m_aDictionaries = array(
			// Default dictionaries, always present can be moved to an official iTop Module later if needed
			'dictionaries/dictionary.itop.core.php',
			'dictionaries/dictionary.itop.ui.php',		// Support for English
			'dictionaries/fr.dictionary.itop.ui.php',	// Support for French
			'dictionaries/fr.dictionary.itop.core.php',	// Support for French
			'dictionaries/es_cr.dictionary.itop.ui.php',	// Support for Spanish (from Costa Rica)
			'dictionaries/es_cr.dictionary.itop.core.php',	// Support for Spanish (from Costa Rica)
			'dictionaries/de.dictionary.itop.ui.php',	// Support for German
			'dictionaries/de.dictionary.itop.core.php',	// Support for German
			'dictionaries/pt_br.dictionary.itop.ui.php',	// Support for Brazilian Portuguese
			'dictionaries/pt_br.dictionary.itop.core.php',	// Support for Brazilian Portuguese
			'dictionaries/ru.dictionary.itop.ui.php',	// Support for Russian
			'dictionaries/ru.dictionary.itop.core.php',	// Support for Russian
		);
		foreach($this->m_aSettings as $sPropCode => $aSettingInfo)
		{
			$this->m_aSettings[$sPropCode]['value'] = $aSettingInfo['default'];
		}

		$this->m_sDBHost = '';
		$this->m_sDBUser = '';
		$this->m_sDBPwd = '';
		$this->m_sDBName = '';
		$this->m_sDBSubname = '';
		$this->m_sDBCharacterSet = DEFAULT_CHARACTER_SET;
		$this->m_sDBCollation = DEFAULT_COLLATION;
		$this->m_bLogGlobal = DEFAULT_LOG_GLOBAL;
		$this->m_bLogNotification = DEFAULT_LOG_NOTIFICATION;
		$this->m_bLogIssue = DEFAULT_LOG_ISSUE;
		$this->m_bLogWebService = DEFAULT_LOG_WEB_SERVICE;
		$this->m_bLogKPIDuration = DEFAULT_LOG_KPI_DURATION;
		$this->m_bLogKPIDuration = DEFAULT_LOG_KPI_DURATION;
		$this->m_iMinDisplayLimit = DEFAULT_MIN_DISPLAY_LIMIT;
		$this->m_iMaxDisplayLimit = DEFAULT_MAX_DISPLAY_LIMIT;
		$this->m_iStandardReloadInterval = DEFAULT_STANDARD_RELOAD_INTERVAL;
		$this->m_iFastReloadInterval = DEFAULT_FAST_RELOAD_INTERVAL;
		$this->m_bSecureConnectionRequired = DEFAULT_SECURE_CONNECTION_REQUIRED;
		$this->m_bHttpsHyperlinks = DEFAULT_HTTPS_HYPERLINKS;
		$this->m_sDefaultLanguage = 'EN US';
		$this->m_sAllowedLoginTypes = DEFAULT_ALLOWED_LOGIN_TYPES;
		$this->m_sExtAuthVariable = DEFAULT_EXT_AUTH_VARIABLE;
		$this->m_sEncryptionKey = DEFAULT_ENCRYPTION_KEY;
		$this->m_aCharsets = array();
		
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
			// Add one, by default
			$MyModules['addons']['user rights'] = '/addons/userrights/userrightsnull.class.inc.php';
		}
		if (!array_key_exists('dictionaries', $MyModules))
		{
			throw new ConfigException('Missing item in configuration file', array('file' => $sConfigFile, 'expected' => '$MyModules[\'dictionaries\']'));
		}
		$this->m_aAppModules = $MyModules['application'];
		$this->m_aDataModels = $MyModules['business'];
		if (isset($MyModules['webservices']))
		{
			$this->m_aWebServiceCategories = $MyModules['webservices'];
		}
		$this->m_aAddons = $MyModules['addons'];
		$this->m_aDictionaries = $MyModules['dictionaries'];

		foreach($MySettings as $sPropCode => $rawvalue)
		{
			if ($this->IsProperty($sPropCode))
			{
				$value = trim($rawvalue);
				$this->Set($sPropCode, $value, $sConfigFile);
			}
		}

		$this->m_sDBHost = trim($MySettings['db_host']);
		$this->m_sDBUser = trim($MySettings['db_user']);
		$this->m_sDBPwd = trim($MySettings['db_pwd']);
		$this->m_sDBName = trim($MySettings['db_name']);
		$this->m_sDBSubname = trim($MySettings['db_subname']);

		$this->m_sDBCharacterSet = isset($MySettings['db_character_set']) ? trim($MySettings['db_character_set']) : DEFAULT_CHARACTER_SET;
		$this->m_sDBCollation = isset($MySettings['db_collation']) ? trim($MySettings['db_collation']) : DEFAULT_COLLATION;

		$this->m_bLogGlobal = isset($MySettings['log_global']) ? (bool) trim($MySettings['log_global']) : DEFAULT_LOG_GLOBAL;
		$this->m_bLogNotification = isset($MySettings['log_notification']) ? (bool) trim($MySettings['log_notification']) : DEFAULT_LOG_NOTIFICATION;
		$this->m_bLogIssue = isset($MySettings['log_issue']) ? (bool) trim($MySettings['log_issue']) : DEFAULT_LOG_ISSUE;
		$this->m_bLogWebService = isset($MySettings['log_web_service']) ? (bool) trim($MySettings['log_web_service']) : DEFAULT_LOG_WEB_SERVICE;
		$this->m_bLogKPIDuration = isset($MySettings['log_kpi_duration']) ? (bool) trim($MySettings['log_kpi_duration']) : DEFAULT_LOG_KPI_DURATION;
		$this->m_bLogKPIMemory = isset($MySettings['log_kpi_memory']) ? (bool) trim($MySettings['log_kpi_memory']) : DEFAULT_LOG_KPI_MEMORY;
		$this->m_bDebugQueries = isset($MySettings['debug_queries']) ? (bool) trim($MySettings['debug_queries']) : DEFAULT_DEBUG_QUERIES;
		$this->m_bQueryCacheEnabled = isset($MySettings['query_cache_enabled']) ? (bool) trim($MySettings['query_cache_enabled']) : DEFAULT_QUERY_CACHE_ENABLED;

		$this->m_iMinDisplayLimit = isset($MySettings['min_display_limit']) ? trim($MySettings['min_display_limit']) : DEFAULT_MIN_DISPLAY_LIMIT;
		$this->m_iMaxDisplayLimit = isset($MySettings['max_display_limit']) ? trim($MySettings['max_display_limit']) : DEFAULT_MAX_DISPLAY_LIMIT;
		$this->m_iStandardReloadInterval = isset($MySettings['standard_reload_interval']) ? trim($MySettings['standard_reload_interval']) : DEFAULT_STANDARD_RELOAD_INTERVAL;
		$this->m_iFastReloadInterval = isset($MySettings['fast_reload_interval']) ? trim($MySettings['fast_reload_interval']) : DEFAULT_FAST_RELOAD_INTERVAL;
		$this->m_bSecureConnectionRequired = isset($MySettings['secure_connection_required']) ? (bool) trim($MySettings['secure_connection_required']) : DEFAULT_SECURE_CONNECTION_REQUIRED;
		$this->m_bHttpsHyperlinks = isset($MySettings['https_hyperlinks']) ? (bool) trim($MySettings['https_hyperlinks']) : DEFAULT_HTTPS_HYPERLINKS;

		$this->m_aModuleSettings = isset($MyModuleSettings) ?  $MyModuleSettings : array();

		$this->m_sDefaultLanguage = isset($MySettings['default_language']) ? trim($MySettings['default_language']) : 'EN US';
		$this->m_sAllowedLoginTypes = isset($MySettings['allowed_login_types']) ? trim($MySettings['allowed_login_types']) : DEFAULT_ALLOWED_LOGIN_TYPES;
		$this->m_sExtAuthVariable = isset($MySettings['ext_auth_variable']) ? trim($MySettings['ext_auth_variable']) : DEFAULT_EXT_AUTH_VARIABLE;
		$this->m_sEncryptionKey = isset($MySettings['encryption_key']) ? trim($MySettings['encryption_key']) : DEFAULT_ENCRYPTION_KEY;
		$this->m_aCharsets = isset($MySettings['csv_import_charsets']) ? $MySettings['csv_import_charsets'] : array();
	}

	protected function Verify()
	{
		// Files are verified later on, just before using them -see MetaModel::Plugin()
		// (we have their final path at that point)
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

	public function GetWebServiceCategories()
	{
		return $this->m_aWebServiceCategories;
	}
	public function SetWebServiceCategories($aWebServiceCategories)
	{
		$this->m_aWebServiceCategories = $aWebServiceCategories;
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

	public function GetDBCharacterSet()
	{
		return $this->m_sDBCharacterSet;
	}

	public function GetDBCollation()
	{
		return $this->m_sDBCollation;
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

	public function GetLogKPIDuration()
	{
		return $this->m_bLogKPIDuration;
	}

	public function GetLogKPIMemory()
	{
		return $this->m_bLogKPIMemory;
	}

	public function GetDebugQueries()
	{
		return $this->m_bDebugQueries;
	}

	public function GetQueryCacheEnabled()
	{
		return $this->m_bQueryCacheEnabled;
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

	public function GetHttpsHyperlinks()
	{
		return $this->m_bHttpsHyperlinks;
	}

	public function GetDefaultLanguage()
	{
		return $this->m_sDefaultLanguage;
	}

	public function GetEncryptionKey()
	{
		return $this->m_sEncryptionKey;
	}

	public function GetAllowedLoginTypes()
	{
		return explode('|', $this->m_sAllowedLoginTypes);
	}

	public function GetExternalAuthenticationVariable()
	{
		return $this->m_sExtAuthVariable;
	}

	public function GetCSVImportCharsets()
	{
		return $this->m_aCharsets;
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

	public function SetDBCharacterSet($sDBCharacterSet)
	{
		$this->m_sDBCharacterSet = $sDBCharacterSet;
	}

	public function SetDBCollation($sDBCollation)
	{
		$this->m_sDBCollation = $sDBCollation;
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

	public function SetHttpsHyperlinks($bHttpsHyperlinks)
	{
		$this->m_bHttpsHyperlinks = $bHttpsHyperlinks;
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

	public function SetEncryptionKey($sKey)
	{
		$this->m_sEncryptionKey = $sKey;
	}

	public function SetCSVImportCharsets($aCharsets)
	{
		$this->m_aCharsets = $aCharsets;
	}

	public function AddCSVImportCharset($sIconvCode, $sDisplayName)
	{
		$this->m_aCharsets[$sIconvCode] = $sDisplayName;
	}	
	public function FileIsWritable()
	{
		return is_writable($this->m_sFile);
	}
	public function GetLoadedFile()
	{
		return $this->m_sFile;
	}
	
	/**
	 * Render the configuration as an associative array
	 * @return boolean True otherwise throws an Exception
	 */	 	 	 	 	
	public function ToArray()
	{
		$aSettings = array();
		foreach($this->m_aSettings as $sPropCode => $aSettingInfo)
		{
			$aSettings[$sPropCode] = $aSettingInfo['value'];
		}
		$aSettings['db_host'] = $this->m_sDBHost;
		$aSettings['db_user'] = $this->m_sDBUser;
		$aSettings['db_pwd'] = $this->m_sDBPwd;
		$aSettings['db_name'] = $this->m_sDBName;
		$aSettings['db_subname'] = $this->m_sDBSubname;
		$aSettings['db_character_set'] = $this->m_sDBCharacterSet;
		$aSettings['db_collation'] = $this->m_sDBCollation;
		$aSettings['log_global'] = $this->m_bLogGlobal;
		$aSettings['log_notification'] = $this->m_bLogNotification;
		$aSettings['log_issue'] = $this->m_bLogIssue;
		$aSettings['log_web_service'] = $this->m_bLogWebService;
		$aSettings['min_display_limit'] = $this->m_iMinDisplayLimit;
		$aSettings['max_display_limit'] = $this->m_iMaxDisplayLimit;
		$aSettings['standard_reload_interval'] = $this->m_iStandardReloadInterval;
		$aSettings['fast_reload_interval'] = $this->m_iFastReloadInterval;
		$aSettings['secure_connection_required'] = $this->m_bSecureConnectionRequired;
		$aSettings['https_hyperlinks'] = $this->m_bHttpsHyperlinks;
		$aSettings['default_language'] = $this->m_sDefaultLanguage;
		$aSettings['allowed_login_types'] = $this->m_sAllowedLoginTypes;
		$aSettings['encryption_key'] = $this->m_sEncryptionKey;
		$aSettings['csv_import_charsets'] = $this->m_aCharsets;

		foreach ($this->m_aModuleSettings as $sModule => $aProperties)
		{
			foreach ($aProperties as $sProperty => $value)
			{
				$aSettings['module_settings'][$sModule][$sProperty] = $value;
			}
		}
		foreach($this->m_aAppModules as $sFile)
		{
			$aSettings['application_list'][] = $sFile;
		}
		foreach($this->m_aDataModels as $sFile)
		{
			$aSettings['datamodel_list'][] = $sFile;
		}
		foreach($this->m_aWebServiceCategories as $sFile)
		{
			$aSettings['webservice_list'][] = $sFile;
		}
		foreach($this->m_aAddons as $sKey => $sFile)
		{
			$aSettings['addon_list'][] = $sFile;
		}
		foreach($this->m_aDictionaries as $sFile)
		{
			$aSettings['dictionary_list'][] = $sFile;
		}
		return $aSettings;
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
			foreach($this->m_aSettings as $sPropCode => $aSettingInfo)
			{
				if ($aSettingInfo['show_in_conf_sample'])
				{
					$sType = $this->m_aSettings[$sPropCode]['type'];
					switch($sType)
					{
					case 'bool':
						$sSeenAs = $aSettingInfo['value'] ? '1' : '0';
						break;
					default:
						$sSeenAs = "'".$aSettingInfo['value']."'";
					}
					fwrite($hFile, "\t'$sPropCode' => $sSeenAs,\n");
				}
			}
			fwrite($hFile, "\t'db_host' => '{$this->m_sDBHost}',\n");
			fwrite($hFile, "\t'db_user' => '{$this->m_sDBUser}',\n");
			fwrite($hFile, "\t'db_pwd' => '".addslashes($this->m_sDBPwd)."',\n");
			fwrite($hFile, "\t'db_name' => '{$this->m_sDBName}',\n");
			fwrite($hFile, "\t'db_subname' => '{$this->m_sDBSubname}',\n");
			fwrite($hFile, "\t'db_character_set' => '{$this->m_sDBCharacterSet}',\n");
			fwrite($hFile, "\t'db_collation' => '{$this->m_sDBCollation}',\n");
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
			fwrite($hFile, "\t'https_hyperlinks' => ".($this->m_bHttpsHyperlinks ? 'true' : 'false').",\n");
			fwrite($hFile, "\t'default_language' => '{$this->m_sDefaultLanguage}',\n");
			fwrite($hFile, "\t'allowed_login_types' => '{$this->m_sAllowedLoginTypes}',\n");
			fwrite($hFile, "\t'encryption_key' => '{$this->m_sEncryptionKey}',\n");
			$sExport = var_export($this->m_aCharsets, true);
			fwrite($hFile, "\t'csv_import_charsets' => $sExport,\n");

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
			fwrite($hFile, "\t'webservices' => array (\n");
			foreach($this->m_aWebServiceCategories as $sFile)
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
