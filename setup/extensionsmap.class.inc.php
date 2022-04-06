<?php
require_once(APPROOT.'/setup/parameters.class.inc.php');
require_once(APPROOT.'/core/cmdbsource.class.inc.php');
require_once(APPROOT.'/setup/modulediscovery.class.inc.php');
require_once(APPROOT.'/setup/moduleinstaller.class.inc.php');
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
	 * @var bool
	 */
	public $bVisible;

	/**
	 * @var string[]
	 */
	public $aModules;

	/**
	 * @var string[]
	 */
	public $aModuleVersion;

	/**
	 * @var string[]
	 */
	public $aModuleInfo;

	/**
	 * @var string
	 */
	public $sSourceDir;

	/**
	 *
	 * @var string[]
	 */
	public $aMissingDependencies;
	
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
		$this->aModules = array();
		$this->aModuleVersion = array();
		$this->aModuleInfo = array();
		$this->sSourceDir = '';
		$this->bVisible = true;
		$this->aMissingDependencies = array();
	}
}

/**
 * Helper class to discover all available extensions on a given iTop system
 */
class iTopExtensionsMap
{
	/**
	 * The list of all discovered extensions
	 * @param string $sFromEnvironment The environment to scan
	 * @param bool $bNormailizeOldExtension true to "magically" convert some well-known old extensions (i.e. a set of modules) to the new iTopExtension format
	 * @return void
	 */
	protected $aExtensions;
	
	/**
	 * The list of directories browsed using the ReadDir method when building the map
	 * @var string[]
	 */
	protected $aScannedDirs;
	
	public function __construct($sFromEnvironment = 'production', $bNormalizeOldExtensions = true, $aExtraDirs = array())
	{
		$this->aExtensions = array();
		$this->aScannedDirs = array();
		$this->ScanDisk($sFromEnvironment);
		foreach($aExtraDirs as $sDir)
		{
		    $this->ReadDir($sDir, iTopExtension::SOURCE_REMOTE);
		}
		$this->CheckDependencies($sFromEnvironment);
		if ($bNormalizeOldExtensions)
		{
			$this->NormalizeOldExtensions();
		}
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
	 *
	 * @param string $sSearchDir The directory to scan
	 * @param string $sSource The 'source' value for the extensions found in this directory
	 * @param string|null $sParentExtensionId Not null if the directory is under a declared extension
	 *
	 * @return boolean false if we cannot open dir
	 */
	protected function ReadDir($sSearchDir, $sSource, $sParentExtensionId = null)
	{
		if (!is_readable($sSearchDir)) return false;
		$hDir = opendir($sSearchDir);
		if ($hDir !== false)
		{
		    if ($sParentExtensionId == null)
		    {
		        // We're not recursing, let's add the directory to the list of scanned dirs 
		        $this->aScannedDirs[] = $sSearchDir;
		    }
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
				$oExtension->sSource = $sSource;
				$oExtension->sSourceDir = $sSearchDir;
				
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
						
						if (($sParentExtensionId !== null) && (array_key_exists($sParentExtensionId, $this->aExtensions)) && ($this->aExtensions[$sParentExtensionId] instanceof iTopExtension)) {
							// Already inside an extension, let's add this module the list of modules belonging to this extension
							$this->aExtensions[$sParentExtensionId]->aModules[] = $sModuleName;
							$this->aExtensions[$sParentExtensionId]->aModuleVersion[$sModuleName] = $sModuleVersion;
							$this->aExtensions[$sParentExtensionId]->aModuleInfo[$sModuleName] = $aModuleInfo[2];
						}
						else
						{
							// Not already inside an folder containing an 'extension.xml' file
							
							// Ignore non-visible modules and auto-select ones, since these are never prompted
							// as a choice to the end-user
							$bVisible = true;
							if (!$aModuleInfo[2]['visible'] || isset($aModuleInfo[2]['auto_select']))
							{
							    $bVisible = false;
							}
							
							// Let's create a "fake" extension from this module (containing just this module) for backwards compatibility
							$oExtension = new iTopExtension();
							$oExtension->sCode = $sModuleName;
							$oExtension->sLabel = $aModuleInfo[2]['label'];
							$oExtension->sDescription = '';
							$oExtension->sVersion = $sModuleVersion;
							$oExtension->sSource = $sSource;
							$oExtension->bMandatory = $aModuleInfo[2]['mandatory'];
							$oExtension->sMoreInfoUrl = $aModuleInfo[2]['doc.more_information'];
							$oExtension->aModules = array($sModuleName);
							$oExtension->aModuleVersion[$sModuleName] = $sModuleVersion;
							$oExtension->aModuleInfo[$sModuleName] = $aModuleInfo[2];
							$oExtension->sSourceDir = $sSearchDir;
							$oExtension->bVisible = $bVisible;
							$this->AddExtension($oExtension);
						}

						closedir($hDir);

						return true; // we found a module, no more digging necessary !
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
	 * Check if some extension contains a module with missing dependencies...
	 * If so, populate the aMissingDepenencies array
	 * @param string $sFromEnvironment
	 * @return void
	 */
	protected function CheckDependencies($sFromEnvironment)
	{
		$aSearchDirs = array();
		
		if (is_dir(APPROOT.'/datamodels/2.x'))
		{
			$aSearchDirs[] = APPROOT.'/datamodels/2.x';
		}
		else if (is_dir(APPROOT.'/datamodels/1.x'))
		{
			$aSearchDirs[] = APPROOT.'/datamodels/1.x';
		}
		$aSearchDirs = array_merge($aSearchDirs, $this->aScannedDirs);
		
		try
		{
			$aAllModules = ModuleDiscovery::GetAvailableModules($aSearchDirs, true);
		}
		catch(MissingDependencyException $e)
		{
			// Some modules have missing dependencies
			// Let's check what is the impact at the "extensions" level
			foreach($this->aExtensions as $sKey => $oExtension)
			{
				foreach($oExtension->aModules as $sModuleName)
				{
					if (array_key_exists($sModuleName, $oExtension->aModuleVersion))
					{
						// This information is not available for pseudo modules defined in the installation wizard, but let's ignore them
						$sVersion = $oExtension->aModuleVersion[$sModuleName];
						$sModuleId = $sModuleName.'/'.$sVersion;
						
						if (array_key_exists($sModuleId, $e->aModulesInfo))
						{
							// The extension actually contains a module which has unmet dependencies
							$aModuleInfo = $e->aModulesInfo[$sModuleId];
							$this->aExtensions[$sKey]->aMissingDependencies = array_merge($oExtension->aMissingDependencies, $aModuleInfo['dependencies']);
						}
					}
				}
			}
		}
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
				SetupLog::Warning("Eval of $sModuleFile did  not return the expected information...");
			}
		}
		catch(ParseError $e)
		{
		    // Continue...
			SetupLog::Warning("Eval of $sModuleFile caused a parse error: ".$e->getMessage()." at line ".$e->getLine());
		}
		catch(Exception $e)
		{
			// Continue...
			SetupLog::Warning("Eval of $sModuleFile caused an exception: ".$e->getMessage());
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
		try
		{
			$aInstalledExtensions = array();
			if (CMDBSource::DBName() === null)
			{
				CMDBSource::InitFromConfig($oConfig);
			}
			$sLatestInstallationDate = CMDBSource::QueryToScalar("SELECT max(installed) FROM ".$oConfig->Get('db_subname')."priv_extension_install");
			$aInstalledExtensions = CMDBSource::QueryToArray("SELECT * FROM ".$oConfig->Get('db_subname')."priv_extension_install WHERE installed = '".$sLatestInstallationDate."'");
		}
		catch (MySQLException $e)
		{
			// No database or erroneous information
			return false;
		}
		
		foreach($aInstalledExtensions as $aDBInfo)
		{
			$this->MarkAsChosen($aDBInfo['code']);
			$this->SetInstalledVersion($aDBInfo['code'], $aDBInfo['version']);
		}
		return true;
	}
	
	/**
	 * Find is a single-module extension is contained within another extension
	 * @param iTopExtension $oExtension
	 * @return NULL|iTopExtension
	 */
	public function IsExtensionObsoletedByAnother(iTopExtension $oExtension)
	{
		// Complex extensions (more than 1 module) are never considered as obsolete
		if (count($oExtension->aModules) != 1) return null;
		
		foreach($this->GetAllExtensions() as $oOtherExtension)
		{
			if (($oOtherExtension->sSourceDir != $oExtension->sSourceDir) && ($oOtherExtension->sSource != iTopExtension::SOURCE_WIZARD))
			{
				if (array_key_exists($oExtension->sCode, $oOtherExtension->aModuleVersion) &&
					(version_compare($oOtherExtension->aModuleVersion[$oExtension->sCode], $oExtension->sVersion, '>=')) )
				{
					// Found another extension containing a more recent version of the extension/module
					return $oOtherExtension;
				}
			}
		}
		
		// No match at all
		return null;
		
	}
	
	/**
	 * Search for multi-module extensions that are NOT deployed as an extension (i.e. shipped with an extension.xml file)
	 * but as a bunch of un-related modules based on the signature of some well-known extensions. If such an extension is found,
	 * replace the stand-alone modules by an "extension" with the appropriate label/description/version containing the same modules.
	 * @param string $sInSourceOnly The source directory to scan (datamodel|extensions|data)
	 */
	public function NormalizeOldExtensions($sInSourceOnly = iTopExtension::SOURCE_MANUAL)
	{
	    $aSignatures = $this->GetOldExtensionsSignatures();
	    foreach($aSignatures as $sExtensionCode => $aExtensionSignatures)
	    {
	        $bFound = false;
	        foreach($aExtensionSignatures['versions'] as $sVersion => $aModules)
	        {
	            $bInstalled = true;
	            foreach($aModules as $sModuleId)
	            {
	                if(!$this->ModuleIsPresent($sModuleId, $sInSourceOnly))
	               {
	                   $bFound = false;
	                   break; // One missing module is enough to determine that the extension/version is not present
	               }
	               else
	               {
	                   $bInstalled = $bInstalled && (!$this->ModuleIsInstalled($sModuleId, $sInSourceOnly));
	                   $bFound = true;
	               }
	            }
	            if ($bFound) break; // The current version matches the signature
	        }
	        
	        if ($bFound)
	        {
	            $oExtension = new iTopExtension();
	            $oExtension->sCode = $sExtensionCode;
	            $oExtension->sLabel = $aExtensionSignatures['label'];
	            $oExtension->sSource = $sInSourceOnly;
	            $oExtension->sDescription = $aExtensionSignatures['description'];
	            $oExtension->sVersion = $sVersion;
	            $oExtension->aModules = array();
	            if ($bInstalled)
	            {
	                $oExtension->sInstalledVersion = $sVersion;
	                $oExtension->bMarkedAsChosen = true;
	            }
	            foreach($aModules as $sModuleId)
	            {
	                list($sModuleName, $sModuleVersion) = ModuleDiscovery::GetModuleName($sModuleId);
	                $oExtension->aModules[] = $sModuleName;
	            }
	            $this->ReplaceModulesByNormalizedExtension($aExtensionSignatures['versions'][$sVersion], $oExtension);
	        }
	    }
	}
	
	/**
	 * Check if the given module-code/version is present on the disk
	 * @param string $sModuleIdToFind The module ID (code/version) to search for
	 * @param string $sInSourceOnly The origin (=source) to search in (datamodel|extensions|data)
	 * @return boolean
	 */
	protected function ModuleIsPresent($sModuleIdToFind, $sInSourceOnly)
	{
	    return (array_key_exists($sModuleIdToFind, $this->aExtensions) && ($this->aExtensions[$sModuleIdToFind]->sSource == $sInSourceOnly));
	}
	
	/**
	 * Check if the given module-code/version is currently installed
	 * @param string $sModuleIdToFind The module ID (code/version) to search for
	 * @param string $sInSourceOnly The origin (=source) to search in (datamodel|extensions|data)
	 * @return boolean
	 */
	protected function ModuleIsInstalled($sModuleIdToFind, $sInSourceOnly)
	{
	    return (array_key_exists($sModuleIdToFind, $this->aExtensions) &&
	            ($this->aExtensions[$sModuleIdToFind]->sSource == $sInSourceOnly) &&
	            ($this->aExtensions[$sModuleIdToFind]->sInstalledVersion !== '') );
	}
	
	/**
	 * Tells if the given module name is "chosen" since it is part of a "chosen" extension (in the specified source dir)
	 * @param string $sModuleNameToFind
	 * @param string $sInSourceOnly
	 * @return boolean
	 */
	public function ModuleIsChosenAsPartOfAnExtension($sModuleNameToFind, $sInSourceOnly = iTopExtension::SOURCE_REMOTE)
	{
		$bChosen = false;
		
		foreach($this->GetAllExtensions() as $oExtension)
		{
			if (($oExtension->sSource == $sInSourceOnly) &&
				($oExtension->bMarkedAsChosen == true) &&
				(array_key_exists($sModuleNameToFind, $oExtension->aModuleVersion)))
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Replace a given set of stand-alone modules by one single "extension"
	 * @param string[] $aModules
	 * @param iTopExtension $oNewExtension
	 */
	protected function ReplaceModulesByNormalizedExtension($aModules, iTopExtension $oNewExtension)
	{
	    foreach($aModules as $sModuleId)
	    {
	        unset($this->aExtensions[$sModuleId]);
	    }
	    $this->AddExtension($oNewExtension);
	}
	
	/**
	 * Get the list of signatures of some well-known multi-module extensions without extension.xml file (should not exist anymore)
	 *
	 * @return string[][]|string[][][][]
	 */
	protected function GetOldExtensionsSignatures()
	{
	    // Generated by the Factory using the page export_component_versions_for_normalisation.php
	    return array (
	        'combodo-approval-process-light' =>
	        array (
	            'label' => 'Approval process light',
	            'description' => 'Approve a request via a simple email',
	            'versions' =>
	            array (
	                '1.0.1' =>
	                array (
	                    0 => 'approval-base/2.1.0',
	                    1 => 'combodo-approval-light/1.0.1',
	                ),
	                '1.0.2' =>
	                array (
	                    0 => 'approval-base/2.1.1',
	                    1 => 'combodo-approval-light/1.0.2',
	                ),
	                '1.0.3' =>
	                array (
	                    0 => 'approval-base/2.1.2',
	                    1 => 'combodo-approval-light/1.0.2',
	                ),
	                '1.1.0' =>
	                array (
	                    0 => 'approval-base/2.2.2',
	                    1 => 'combodo-approval-light/1.0.2',
	                ),
	                '1.1.1' =>
	                array (
	                    0 => 'approval-base/2.2.3',
	                    1 => 'combodo-approval-light/1.0.2',
	                ),
	                '1.1.2' =>
	                array (
	                    0 => 'approval-base/2.2.6',
	                    1 => 'combodo-approval-light/1.0.2',
	                ),
	                '1.1.3' =>
	                array (
	                    0 => 'approval-base/2.2.6',
	                    1 => 'combodo-approval-light/1.0.3',
	                ),
	                '1.2.0' =>
	                array (
	                    0 => 'approval-base/2.3.0',
	                    1 => 'combodo-approval-light/1.0.3',
	                ),
	                '1.2.1' =>
	                array (
	                    0 => 'approval-base/2.4.0',
	                    1 => 'combodo-approval-light/1.0.4',
	                ),
	                '1.3.0' =>
	                array (
	                    0 => 'approval-base/2.4.2',
	                    1 => 'combodo-approval-light/1.1.1',
	                ),
	                '1.3.1' =>
	                array (
	                    0 => 'approval-base/2.5.0',
	                    1 => 'combodo-approval-light/1.1.1',
	                ),
	                '1.3.2' =>
	                array (
	                    0 => 'approval-base/2.5.0',
	                    1 => 'combodo-approval-light/1.1.2',
	                ),
	                '1.2.2' =>
	                array (
	                    0 => 'approval-base/2.4.2',
	                    1 => 'combodo-approval-light/1.0.5',
	                ),
	                '1.3.3' =>
	                array (
	                    0 => 'approval-base/2.5.1',
	                    1 => 'combodo-approval-light/1.1.2',
	                ),
	                '1.3.4' =>
	                array (
	                    0 => 'approval-base/2.5.2',
	                    1 => 'combodo-approval-light/1.1.2',
	                ),
	                '1.3.5' =>
	                array (
	                    0 => 'approval-base/2.5.3',
	                    1 => 'combodo-approval-light/1.1.2',
	                ),
	                '1.4.0' =>
	                array (
	                    0 => 'approval-base/2.5.3',
	                    1 => 'combodo-approval-light/1.1.2',
	                    2 => 'itop-approval-portal/1.0.0',
	                ),
	            ),
	        ),
	        'combodo-approval-process-automation' =>
	        array (
	            'label' => 'Approval process automation',
	            'description' => 'Control your approval process with predefined rules based on service catalog',
	            'versions' =>
	            array (
	                '1.0.1' =>
	                array (
	                    0 => 'approval-base/2.1.0',
	                    1 => 'combodo-approval-extended/1.0.2',
	                ),
	                '1.0.2' =>
	                array (
	                    0 => 'approval-base/2.1.1',
	                    1 => 'combodo-approval-extended/1.0.4',
	                ),
	                '1.0.3' =>
	                array (
	                    0 => 'approval-base/2.1.2',
	                    1 => 'combodo-approval-extended/1.0.4',
	                ),
	                '1.1.0' =>
	                array (
	                    0 => 'approval-base/2.2.2',
	                    1 => 'combodo-approval-extended/1.0.4',
	                ),
	                '1.1.1' =>
	                array (
	                    0 => 'approval-base/2.2.3',
	                    1 => 'combodo-approval-extended/1.0.4',
	                ),
	                '1.1.2' =>
	                array (
	                    0 => 'approval-base/2.2.6',
	                    1 => 'combodo-approval-extended/1.0.5',
	                ),
	                '1.1.3' =>
	                array (
	                    0 => 'approval-base/2.2.6',
	                    1 => 'combodo-approval-extended/1.0.6',
	                ),
	                '1.2.0' =>
	                array (
	                    0 => 'approval-base/2.3.0',
	                    1 => 'combodo-approval-extended/1.0.7',
	                ),
	                '1.2.1' =>
	                array (
	                    0 => 'approval-base/2.4.0',
	                    1 => 'combodo-approval-extended/1.0.8',
	                ),
	                '1.3.0' =>
	                array (
	                    0 => 'approval-base/2.4.2',
	                    1 => 'combodo-approval-extended/1.2.1',
	                ),
	                '1.3.1' =>
	                array (
	                    0 => 'approval-base/2.5.0',
	                    1 => 'combodo-approval-extended/1.2.1',
	                ),
	                '1.3.2' =>
	                array (
	                    0 => 'approval-base/2.5.0',
	                    1 => 'combodo-approval-extended/1.2.2',
	                ),
	                '1.2.2' =>
	                array (
	                    0 => 'approval-base/2.4.2',
	                    1 => 'combodo-approval-extended/1.0.9',
	                ),
	                '1.3.3' =>
	                array (
	                    0 => 'approval-base/2.5.1',
	                    1 => 'combodo-approval-extended/1.2.3',
	                ),
	                '1.3.4' =>
	                array (
	                    0 => 'approval-base/2.5.2',
	                    1 => 'combodo-approval-extended/1.2.3',
	                ),
	                '1.3.5' =>
	                array (
	                    0 => 'approval-base/2.5.3',
	                    1 => 'combodo-approval-extended/1.2.3',
	                ),
	                '1.4.0' =>
	                array (
	                    0 => 'approval-base/2.5.3',
	                    1 => 'combodo-approval-extended/1.2.3',
	                    3 => 'itop-approval-portal/1.0.0',
	                ),
	            ),
	        ),
	        'combodo-predefined-response-models' =>
	        array (
	            'label' => 'Predefined response models',
	            'description' => 'Pick common answers from a list of predefined replies grouped by categories to update tickets log',
	            'versions' =>
	            array (
	                '1.0.0' =>
	                array (
	                    0 => 'precanned-replies/1.0.0',
	                    1 => 'precanned-replies-pro/1.0.0',
	                ),
	                '1.0.1' =>
	                array (
	                    0 => 'precanned-replies/1.0.1',
	                    1 => 'precanned-replies-pro/1.0.1',
	                ),
	                '1.0.2' =>
	                array (
	                    0 => 'precanned-replies/1.0.2',
	                    1 => 'precanned-replies-pro/1.0.1',
	                ),
	                '1.0.3' =>
	                array (
	                    0 => 'precanned-replies/1.0.3',
	                    1 => 'precanned-replies-pro/1.0.1',
	                ),
	                '1.0.4' =>
	                array (
	                    0 => 'precanned-replies/1.0.3',
	                    1 => 'precanned-replies-pro/1.0.2',
	                ),
	                '1.0.5' =>
	                array (
	                    0 => 'precanned-replies/1.0.4',
	                    1 => 'precanned-replies-pro/1.0.2',
	                ),
	                '1.1.0' =>
	                array (
	                    0 => 'precanned-replies/1.1.0',
	                    1 => 'precanned-replies-pro/1.0.2',
	                ),
	                '1.1.1' =>
	                array (
	                    0 => 'precanned-replies/1.1.1',
	                    1 => 'precanned-replies-pro/1.0.2',
	                ),
	            ),
	        ),
	        'combodo-customized-request-forms' =>
	        array (
	            'label' => 'Customized request forms',
	            'description' => 'Define personalized request forms based on the service catalog. Add extra fields for a given type of request.',
	            'versions' =>
	            array (
	                '1.0.1' =>
	                array (
	                    0 => 'templates-base/2.1.1',
	                    1 => 'itop-request-template/1.0.0',
	                ),
	                '1.0.2' =>
	                array (
	                    0 => 'templates-base/2.1.2',
	                    1 => 'itop-request-template/1.0.0',
	                ),
	                '1.0.3' =>
	                array (
	                    0 => 'templates-base/2.1.2',
	                    1 => 'itop-request-template/1.0.1',
	                ),
	                '1.0.4' =>
	                array (
	                    0 => 'templates-base/2.1.3',
	                    1 => 'itop-request-template/1.0.1',
	                ),
	                '1.0.5' =>
	                array (
	                    0 => 'templates-base/2.1.4',
	                    1 => 'itop-request-template/1.0.1',
	                ),
	                '2.0.0' =>
	                array (
	                    0 => 'templates-base/3.0.0',
	                    1 => 'itop-request-template/2.0.0',
	                    2 => 'itop-request-template-portal/1.0.0',
	                ),
	                '2.0.1' =>
	                array (
	                    0 => 'templates-base/3.0.1',
	                    1 => 'itop-request-template/2.0.0',
	                    2 => 'itop-request-template-portal/1.0.0',
	                ),
	                '2.0.2' =>
	                array (
	                    0 => 'templates-base/3.0.2',
	                    1 => 'itop-request-template/2.0.0',
	                    2 => 'itop-request-template-portal/1.0.0',
	                ),
	                '2.0.3' =>
	                array (
	                    0 => 'templates-base/3.0.4',
	                    1 => 'itop-request-template/2.0.0',
	                    2 => 'itop-request-template-portal/1.0.0',
	                ),
	                '2.0.4' =>
	                array (
	                    0 => 'templates-base/3.0.5',
	                    1 => 'itop-request-template/2.0.0',
	                    2 => 'itop-request-template-portal/1.0.0',
	                ),
	                '2.0.5' =>
	                array (
	                    0 => 'templates-base/3.0.6',
	                    1 => 'itop-request-template/2.0.0',
	                    2 => 'itop-request-template-portal/1.0.0',
	                ),
	                '2.0.6' =>
	                array (
	                    0 => 'templates-base/3.0.8',
	                    1 => 'itop-request-template/2.0.0',
	                    2 => 'itop-request-template-portal/1.0.0',
	                ),
	                '2.0.7' =>
	                array (
	                    0 => 'templates-base/3.0.9',
	                    1 => 'itop-request-template/2.0.0',
	                    2 => 'itop-request-template-portal/1.0.0',
	                ),
	                '2.0.8' =>
	                array (
	                    0 => 'templates-base/3.0.12',
	                    1 => 'itop-request-template/2.0.0',
	                    2 => 'itop-request-template-portal/1.0.0',
	                ),
	            ),
	        ),
	        'combodo-sla-considering-business-hours' =>
	        array (
	            'label' => 'SLA considering business hours',
	            'description' => 'Compute SLAs taking into account service coverage window and holidays',
	            'versions' =>
	            array (
	                '2.0.1' =>
	                array (
	                    0 => 'combodo-sla-computation/2.0.1',
	                    1 => 'combodo-coverage-windows-computation/2.0.0',
	                ),
	                '2.1.0' =>
	                array (
	                    0 => 'combodo-sla-computation/2.1.0',
	                    1 => 'combodo-coverage-windows-computation/2.0.0',
	                ),
	                '2.1.1' =>
	                array (
	                    0 => 'combodo-sla-computation/2.1.1',
	                    1 => 'combodo-coverage-windows-computation/2.0.0',
	                ),
	                '2.1.2' =>
	                array (
	                    0 => 'combodo-sla-computation/2.1.2',
	                    1 => 'combodo-coverage-windows-computation/2.0.0',
	                ),
	                '2.1.3' =>
	                array (
	                    0 => 'combodo-sla-computation/2.1.2',
	                    1 => 'combodo-coverage-windows-computation/2.0.1',
	                ),
	                '2.0.2' =>
	                array (
	                    0 => 'combodo-sla-computation/2.0.1',
	                    1 => 'combodo-coverage-windows-computation/2.0.1',
	                ),
	                '2.1.4' =>
	                array (
	                    0 => 'combodo-sla-computation/2.1.3',
	                    1 => 'combodo-coverage-windows-computation/2.0.1',
	                ),
	                '2.1.5' =>
	                array (
	                    0 => 'combodo-sla-computation/2.1.5',
	                    1 => 'combodo-coverage-windows-computation/2.0.1',
	                ),
	                '2.1.6' =>
	                array (
	                    0 => 'combodo-sla-computation/2.1.5',
	                    1 => 'combodo-coverage-windows-computation/2.0.2',
	                ),
	                '2.1.7' =>
	                array (
	                    0 => 'combodo-sla-computation/2.1.6',
	                    1 => 'combodo-coverage-windows-computation/2.0.2',
	                ),
	                '2.1.8' =>
	                array (
	                    0 => 'combodo-sla-computation/2.1.7',
	                    1 => 'combodo-coverage-windows-computation/2.0.2',
	                ),
	                '2.1.9' =>
	                array (
	                    0 => 'combodo-sla-computation/2.1.8',
	                    1 => 'combodo-coverage-windows-computation/2.0.2',
	                ),
	            ),
	        ),
	        'combodo-mail-to-ticket-automation' =>
	        array (
	            'label' => 'Mail to ticket automation',
	            'description' => 'Scan several mailboxes to create or update tickets.',
	            'versions' =>
	            array (
	                '2.6.0' =>
	                array (
	                    0 => 'combodo-email-synchro/2.6.0',
	                    1 => 'itop-standard-email-synchro/2.6.0',
	                ),
	                '2.6.1' =>
	                array (
	                    0 => 'combodo-email-synchro/2.6.1',
	                    1 => 'itop-standard-email-synchro/2.6.0',
	                ),
	                '2.6.2' =>
	                array (
	                    0 => 'combodo-email-synchro/2.6.2',
	                    1 => 'itop-standard-email-synchro/2.6.0',
	                ),
	                '2.6.3' =>
	                array (
	                    0 => 'combodo-email-synchro/2.6.2',
	                    1 => 'itop-standard-email-synchro/2.6.1',
	                ),
	                '2.6.4' =>
	                array (
	                    0 => 'combodo-email-synchro/2.6.3',
	                    1 => 'itop-standard-email-synchro/2.6.2',
	                ),
	                '2.6.5' =>
	                array (
	                    0 => 'combodo-email-synchro/2.6.4',
	                    1 => 'itop-standard-email-synchro/2.6.2',
	                ),
	                '2.6.6' =>
	                array (
	                    0 => 'combodo-email-synchro/2.6.5',
	                    1 => 'itop-standard-email-synchro/2.6.3',
	                ),
	                '2.6.7' =>
	                array (
	                    0 => 'combodo-email-synchro/2.6.6',
	                    1 => 'itop-standard-email-synchro/2.6.4',
	                ),
	                '2.6.8' =>
	                array (
	                    0 => 'combodo-email-synchro/2.6.7',
	                    1 => 'itop-standard-email-synchro/2.6.4',
	                ),
	                '2.6.9' =>
	                array (
	                    0 => 'combodo-email-synchro/2.6.8',
	                    1 => 'itop-standard-email-synchro/2.6.5',
	                ),
	                '2.6.10' =>
	                array (
	                    0 => 'combodo-email-synchro/2.6.9',
	                    1 => 'itop-standard-email-synchro/2.6.6',
	                ),
	                '2.6.11' =>
	                array (
	                    0 => 'combodo-email-synchro/2.6.10',
	                    1 => 'itop-standard-email-synchro/2.6.6',
	                ),
	                '2.6.12' =>
	                array (
	                    0 => 'combodo-email-synchro/2.6.11',
	                    1 => 'itop-standard-email-synchro/2.6.6',
	                ),
	                '3.0.0' =>
	                array (
	                    0 => 'combodo-email-synchro/3.0.0',
	                    1 => 'itop-standard-email-synchro/3.0.0',
	                ),
	                '3.0.1' =>
	                array (
	                    0 => 'combodo-email-synchro/3.0.1',
	                    1 => 'itop-standard-email-synchro/3.0.1',
	                ),
	                '3.0.2' =>
	                array (
	                    0 => 'combodo-email-synchro/3.0.2',
	                    1 => 'itop-standard-email-synchro/3.0.1',
	                ),
	                '3.0.3' =>
	                array (
	                    0 => 'combodo-email-synchro/3.0.3',
	                    1 => 'itop-standard-email-synchro/3.0.3',
	                ),
	                '3.0.4' =>
	                array (
	                    0 => 'combodo-email-synchro/3.0.3',
	                    1 => 'itop-standard-email-synchro/3.0.4',
	                ),
	                '3.0.5' =>
	                array (
	                    0 => 'combodo-email-synchro/3.0.4',
	                    1 => 'itop-standard-email-synchro/3.0.4',
	                ),
	                '3.0.6' =>
	                array (
	                    0 => 'combodo-email-synchro/3.0.5',
	                    1 => 'itop-standard-email-synchro/3.0.4',
	                ),
	                '3.0.7' =>
	                array (
	                    0 => 'combodo-email-synchro/3.0.5',
	                    1 => 'itop-standard-email-synchro/3.0.5',
	                ),
	            ),
	        ),
	        'combodo-configurator-for-automatic-object-creation' =>
	        array (
	            'label' => 'Configurator for automatic object creation',
	            'description' => 'Templating based on existing objects.',
	            'versions' =>
	            array (
	                '1.0.13' =>
	                array (
		                    1 => 'itop-stencils/1.0.6',
	                ),
	            ),
	        ),
	        'combodo-user-actions-configurator' =>
	        array (
	            'label' => 'User actions configurator',
	            'description' => 'Configure user actions to simplify and automate processes (e.g. create an incident from a CI).',
	            'versions' =>
	            array (
	                '1.0.0' =>
	                array (
	                    0 => 'itop-object-copier/1.0.0',
	                ),
	                '1.0.1' =>
	                array (
	                    0 => 'itop-object-copier/1.0.1',
	                ),
	                '1.0.2' =>
	                array (
	                    0 => 'itop-object-copier/1.0.2',
	                ),
	                '1.0.3' =>
	                array (
	                    0 => 'itop-object-copier/1.0.3',
	                ),
	                '1.1.0' =>
	                array (
	                    0 => 'itop-object-copier/1.1.0',
	                ),
	                '1.1.1' =>
	                array (
	                    0 => 'itop-object-copier/1.1.1',
	                ),
	                '1.1.2' =>
	                array (
	                    0 => 'itop-object-copier/1.1.2',
	                ),
	                '1.1.3' =>
	                array (
	                    0 => 'itop-object-copier/1.1.3',
	                ),
	                '1.1.4' =>
	                array (
	                    0 => 'itop-object-copier/1.1.4',
	                ),
	                '1.1.5' =>
	                array (
	                    0 => 'itop-object-copier/1.1.5',
	                ),
	                '1.1.6' =>
	                array (
	                    0 => 'itop-object-copier/1.1.6',
	                ),
	                '1.1.7' =>
	                array (
	                    0 => 'itop-object-copier/1.1.7',
	                ),
	                '1.1.8' =>
	                array (
	                    0 => 'itop-object-copier/1.1.8',
	                ),
	            ),
	        ),
	        'combodo-send-updates-by-email' =>
	        array (
	            'label' => 'Send updates by email',
	            'description' => 'Send an email to pre-configured contacts when a ticket log is updated.',
	            'versions' =>
	            array (
	                '1.0.1' =>
	                array (
	                    0 => 'email-reply/1.0.1',
	                ),
	                '1.0.3' =>
	                array (
	                    0 => 'email-reply/1.0.3',
	                ),
	                '1.1.1' =>
	                array (
	                    0 => 'email-reply/1.1.1',
	                ),
	                '1.1.2' =>
	                array (
	                    0 => 'email-reply/1.1.2',
	                ),
	                '1.1.3' =>
	                array (
	                    0 => 'email-reply/1.1.3',
	                ),
	                '1.1.4' =>
	                array (
	                    0 => 'email-reply/1.1.4',
	                ),
	                '1.1.5' =>
	                array (
	                    0 => 'email-reply/1.1.5',
	                ),
	                '1.1.6' =>
	                array (
	                    0 => 'email-reply/1.1.6',
	                ),
	                '1.1.7' =>
	                array (
	                    0 => 'email-reply/1.1.7',
	                ),
	                // 1.1.8 was never released
	                '1.1.9' =>
	                array (
	                    0 => 'email-reply/1.1.9',
	                ),
	                '1.1.10' =>
	                array (
	                    0 => 'email-reply/1.1.10',
	                ),
	            ),
	        ),
	    );
	}
}
