<?php
require_once(APPROOT.'/setup/parameters.class.inc.php');
require_once(APPROOT.'/core/cmdbsource.class.inc.php');

/**
 * Basic helper class to describe an extension, with some characteristics and a list of modules
 */
class iTopExtension
{
	const SOURCE_WIZARD = 'datamodels';
	const SOURCE_MANUAL = 'extensions';
	const SOURCE_REMOTE = 'data';
	
	/**
	 * @var string
	 */
	public $sCode;
	
	/**
	 * @var string
	 */
	public $sVersion;
	
		/**
	 * @var string
	 */
	public $sInstalledVersion;
	
/**
	 * @var string
	 */
	public $sLabel;
	
	/**
	 * @var string
	 */
	public $sDescription;
	
	/**
	 * @var string
	 */
	public $sSource;
	
	/**
	 * @var bool
	 */
	public $bMandatory;
	
	/**
	 * @var string
	 */
	public $sMoreInfoUrl;
	
	/**
	 * @var bool
	 */
	public $bMarkedAsChosen;
	
	
	/**
	 * @var string[]
	 */
	public $aModules;
	
	public function __construct()
	{
		$this->sCode = '';
		$this->sLabel = '';
		$this->sDescription = '';
		$this->sSource = self::SOURCE_WIZARD;
		$this->bMandatory = false;
		$this->sMoreInfoUrl = '';
		$this->bMarkedAsChosen = false;
		$this->sVersion = ITOP_VERSION;
		$this->sInstalledVersion = '';
	}
}

/**
 * Helper class to discover all available extensions on a given iTop system
 */
class iTopExtensionsMap
{
	/**
	 * The list of all discovered extensions
	 * @var iTopExtension[]
	 */
	protected $aExtensions;
	
	public function __construct($sFromEnvironment = 'production')
	{
		$this->aExtensions = array();
		$this->ScanDisk($sFromEnvironment);
	}
	
	/**
	 * Populate the list of available (pseudo)extensions by scanning the disk
	 * where the iTop files are located
	 * @param string $sEnvironment
	 * @return void
	 */
	protected function ScanDisk($sEnvironment)
	{
		if (!$this->ReadInstallationWizard(APPROOT.'/datamodels/2.x') && !$this->ReadInstallationWizard(APPROOT.'/datamodels/2.x'))
		{
			if(!$this->ReadDir(APPROOT.'/datamodels/2.x', iTopExtension::SOURCE_WIZARD)) $this->ReadDir(APPROOT.'/datamodels/1.x', iTopExtension::SOURCE_WIZARD);
		}
		$this->ReadDir(APPROOT.'/extensions', iTopExtension::SOURCE_MANUAL);
		$this->ReadDir(APPROOT.'/data/'.$sEnvironment.'-modules', iTopExtension::SOURCE_REMOTE);
	}
	
	/**
	 * Read the information contained in the "installation.xml" file in the given directory
	 * and create pseudo extensions from the list of choices described in this file
	 * @param string $sDir
	 * @return boolean Return true if the installation.xml file exists and is readable
	 */
	protected function ReadInstallationWizard($sDir)
	{
		if (!is_readable($sDir.'/installation.xml')) return false;
		
		$oXml = new XMLParameters($sDir.'/installation.xml');
		foreach($oXml->Get('steps') as $aStepInfo)
		{
			if (array_key_exists('options', $aStepInfo))
			{
				$this->ProcessWizardChoices($aStepInfo['options']);
			}
			if (array_key_exists('alternatives', $aStepInfo))
			{
				$this->ProcessWizardChoices($aStepInfo['alternatives']);
			}
		}
		return true;
	}
	
	/**
	 * Helper to process a "choice" array read from the installation.xml file
	 * @param array $aChoices
	 * @return void
	 */
	protected function ProcessWizardChoices($aChoices)
	{
		foreach($aChoices as $aChoiceInfo)
		{
			if (array_key_exists('extension_code', $aChoiceInfo))
			{
				$oExtension = new iTopExtension();
				$oExtension->sCode = $aChoiceInfo['extension_code'];
				$oExtension->sLabel = $aChoiceInfo['title'];
				if (array_key_exists('modules', $aChoiceInfo))
				{
					// Some wizard choices are not associated with any module
					$oExtension->aModules = $aChoiceInfo['modules'];
				}
				if (array_key_exists('sub_options', $aChoiceInfo))
				{
					if (array_key_exists('options', $aChoiceInfo['sub_options']))
					{
						$this->ProcessWizardChoices($aChoiceInfo['sub_options']['options']);
					}
					if (array_key_exists('alternatives', $aChoiceInfo['sub_options']))
					{
						$this->ProcessWizardChoices($aChoiceInfo['sub_options']['alternatives']);
					}
				}
				$this->AddExtension($oExtension);
			}
		}
	}
	
	/**
	 * Add an extension to the list of existing extensions, taking care of removing duplicates
	 * (only the latest/greatest version is kept)
	 * @param iTopExtension $oNewExtension
	 * @return void
	 */
	protected function AddExtension(iTopExtension $oNewExtension)
	{
		foreach($this->aExtensions as $key => $oExtension)
		{
			if ($oExtension->sCode == $oNewExtension->sCode)
			{
				if (version_compare($oNewExtension->sVersion, $oExtension->sVersion, '>'))
				{
					// This "new" extension is "newer" than the previous one, let's replace the previous one
					unset($this->aExtensions[$key]);
					$this->aExtensions[$oNewExtension->sCode.'/'.$oNewExtension->sVersion] = $oNewExtension;
					return;
				}
				else
				{
					// This "new" extension is not "newer" than the previous one, let's ignore it
					return;
				}
			}
		}
		// Finally it's not a duplicate, let's add it to the list
		$this->aExtensions[$oNewExtension->sCode.'/'.$oNewExtension->sVersion] = $oNewExtension;
	}
	
	/**
	 * Read (recursively) a directory to find if it contains extensions (or modules) 
	 * @param string $sSearchDir The directory to scan
	 * @param string $sSource The 'source' value for the extensions found in this directory
	 * @param string|null $sParentExtensionId Not null if the directory is under a declared extension
	 * @return boolean
	 */
	public function ReadDir($sSearchDir, $sSource, $sParentExtensionId = null)
	{
		if (!is_readable($sSearchDir)) return false;
		$hDir = opendir($sSearchDir);
		if ($hDir !== false)
		{
			$sExtensionId = null;
			$aSubDirectories = array();

			// First check if there is an extension.xml file in this directory
			if (is_readable($sSearchDir.'/extension.xml'))
			{
				$oXml = new XMLParameters($sSearchDir.'/extension.xml');
				$oExtension = new iTopExtension();
				$oExtension->sCode = $oXml->Get('extension_code');
				$oExtension->sLabel = $oXml->Get('label');
				$oExtension->sDescription = $oXml->Get('description');
				$oExtension->sVersion = $oXml->Get('version');
				$oExtension->bMandatory = ($oXml->Get('mandatory') == 'true');
				$oExtension->sMoreInfoUrl = $oXml->Get('more_info_url');
				$oExtension->sVersion = $oXml->Get('version');
				$oExtension->sSource = $sSource;
				
				$sParentExtensionId = $sExtensionId = $oExtension->sCode.'/'.$oExtension->sVersion;
				$this->AddExtension($oExtension);
			}
			// Then scan the other files and subdirectories
			while (($sFile = readdir($hDir)) !== false)
			{
				if (($sFile !== '.') && ($sFile !== '..'))
				{
					$aMatches = array();
					if (is_dir($sSearchDir.'/'.$sFile))
					{
						// Recurse after parsing all the regular files
						$aSubDirectories[] = $sSearchDir.'/'.$sFile;
					}
					else if (preg_match('/^module\.(.*).php$/i', $sFile, $aMatches))
					{
						// Found a module
						$aModuleInfo = $this->GetModuleInfo($sSearchDir.'/'.$sFile);
						// If we are not already inside a formal extension, then the module itself is considered
						// as an extension, otherwise, the module is just added to the list of modules belonging
						// to this extension
						$sModuleId = $aModuleInfo[1];
						list($sModuleName, $sModuleVersion) = ModuleDiscovery::GetModuleName($sModuleId);
						if ($sModuleVersion == '')
						{
							// Provide a default module version since version is mandatory when recording ExtensionInstallation
							$sModuleVersion = '0.0.1';
						}
						
						if ($sParentExtensionId !== null)
						{
							// Already inside an extension, let's add this module the list of modules belonging to this extension
							$this->aExtensions[$sParentExtensionId]->aModules[] = $sModuleName;
						}
						else
						{
							// Not already inside an folder containing an 'extension.xml' file
							
							// Ignore non-visible modules and auto-select ones, since these are never prompted
							// as a choice to the end-user
							if (!$aModuleInfo[2]['visible'] || isset($aModuleInfo[2]['auto_select'])) continue;
							
							// Let's create a "fake" extension from this module (containing just this module) for backwards compatibility
							$sExtensionId = $sModuleId;
							
							$oExtension = new iTopExtension();
							$oExtension->sCode = $sModuleName;
							$oExtension->sLabel = $aModuleInfo[2]['label'];
							$oExtension->sDescription = '';
							$oExtension->sVersion = $sModuleVersion;
							$oExtension->sSource = $sSource;
							$oExtension->bMandatory = $aModuleInfo[2]['mandatory'];
							$oExtension->sMoreInfoUrl = $aModuleInfo[2]['doc.more_information'];
							$oExtension->aModules = array($sModuleName);
							
							$this->AddExtension($oExtension);
							
						}
					}
				}
			}
			closedir($hDir);
			foreach($aSubDirectories as $sDir)
			{
				// Recurse inside the subdirectories
				$this->ReadDir($sDir,  $sSource, $sExtensionId);
			}
			return true;
		}
		return false;
	}
	
	/**
	 * Read the information from a module file (module.xxx.php)
	 * Closely inspired (almost copied/pasted !!) from ModuleDiscovery::ListModuleFiles
	 * @param string $sModuleFile
	 * @return array
	 */
	protected function GetModuleInfo($sModuleFile)
	{
		static $iDummyClassIndex = 0;
		
		$aModuleInfo = array(); // will be filled by the "eval" line below...
		try
		{
			$aMatches = array();
			$sModuleFileContents = file_get_contents($sModuleFile);
			$sModuleFileContents = str_replace(array('<?php', '?>'), '', $sModuleFileContents);
			$sModuleFileContents = str_replace('__FILE__', "'".addslashes($sModuleFile)."'", $sModuleFileContents);
			preg_match_all('/class ([A-Za-z0-9_]+) extends ([A-Za-z0-9_]+)/', $sModuleFileContents, $aMatches);
			//print_r($aMatches);
			$idx = 0;
			foreach($aMatches[1] as $sClassName)
			{
				if (class_exists($sClassName))
				{
					// rename any class declaration inside the code to prevent a "duplicate class" declaration
					// and change its parent class as well so that nobody will find it and try to execute it
					// Note: don't use the same naming scheme as ModuleDiscovery otherwise you 'll have the duplicate class error again !!
					$sModuleFileContents = str_replace($sClassName.' extends '.$aMatches[2][$idx], $sClassName.'_Ext_'.($iDummyClassIndex++).' extends DummyHandler', $sModuleFileContents);
				}
				$idx++;
			}
			// Replace the main function call by an assignment to a variable, as an array...
			$sModuleFileContents = str_replace(array('SetupWebPage::AddModule', 'ModuleDiscovery::AddModule'), '$aModuleInfo = array', $sModuleFileContents);	
			
			eval($sModuleFileContents); // Assigns $aModuleInfo
			
			if (count($aModuleInfo) === 0)
			{
				SetupPage::log_warning("Eval of $sModuleFile did  not return the expected information...");
			}
		}
		catch(Exception $e)
		{
			// Continue...
			SetupPage::log_warning("Eval of $sModuleFile caused an exception: ".$e->getMessage());
		}
		return $aModuleInfo;
	}
	
	/**
	 * Get all available extensions
	 * @return iTopExtension[]
	 */
	public function GetAllExtensions()
	{
		return $this->aExtensions;
	}
	
	/**
	 * Mark the given extension as chosen
	 * @param string $sExtensionCode The code of the extension (code without verison number)
	 * @param bool $bMark The value to set for the bmarkAschosen flag
	 * @return void
	 */
	public function MarkAsChosen($sExtensionCode, $bMark = true)
	{
		foreach($this->aExtensions as $oExtension)
		{
			if ($oExtension->sCode == $sExtensionCode)
			{
				$oExtension->bMarkedAsChosen = $bMark;
				break;
			}
		}
	}
	
	/**
	 * Tells if a given extension(code) is marked as chosen
	 * @param string $sExtensionCode
	 * @return boolean
	 */
	public function IsMarkedAsChosen($sExtensionCode)
	{
		foreach($this->aExtensions as $oExtension)
		{
			if ($oExtension->sCode == $sExtensionCode)
			{
				return $oExtension->bMarkedAsChosen;
			}
		}
		return false;
	}

	/**
	 * Set the 'installed_version' of the given extension(code)
	 * @param string $sExtensionCode
	 * @param string $sInstalledVersion
	 * @return void
	 */
	protected function SetInstalledVersion($sExtensionCode, $sInstalledVersion)
	{
		foreach($this->aExtensions as $oExtension)
		{
			if ($oExtension->sCode == $sExtensionCode)
			{
				$oExtension->sInstalledVersion = $sInstalledVersion;
				break;
			}
		}
	}
	
	/**
	 * Get the list of the "chosen" extensions
	 * @return iTopExtension[]
	 */
	public function GetChoices()
	{
		$aResult = array();
		foreach($this->aExtensions as $oExtension)
		{
			if ($oExtension->bMarkedAsChosen)
			{
				$aResult[] = $oExtension;
			}
		}
		return $aResult;
	}
	
	/**
	 * Load the choices (i.e. MarkedAsChosen) from the database defined in the supplied Config
	 * @param Config $oConfig
	 * @return bool
	 */
	public function LoadChoicesFromDatabase(Config $oConfig)
	{
		$aInstalledExtensions = array();
		try
		{
			if (CMDBSource::DBName() === null)
			{
				CMDBSource::Init($oConfig->GetDBHost(), $oConfig->GetDBUser(), $oConfig->GetDBPwd(), $oConfig->GetDBName());
				CMDBSource::SetCharacterSet($oConfig->GetDBCharacterSet(), $oConfig->GetDBCollation());
			}
			$sLatestInstallationDate = CMDBSource::QueryToScalar("SELECT max(installed) FROM ".$oConfig->GetDBSubname()."priv_extension_install");
			$aInstalledExtensions = CMDBSource::QueryToArray("SELECT * FROM ".$oConfig->GetDBSubname()."priv_extension_install WHERE installed = '".$sLatestInstallationDate."'");
		}
		catch (MySQLException $e)
		{
			// No database or erroneous information
			$aInstalledExtensions = array();
			return false;
		}
		
		foreach($aInstalledExtensions as $aDBInfo)
		{
			$this->MarkAsChosen($aDBInfo['code']);
			$this->SetInstalledVersion($aDBInfo['code'], $aDBInfo['version']);
		}
		return true;
	}
}