<?php

use Combodo\iTop\Setup\ModuleDiscovery\ModuleFileReader;
use Combodo\iTop\Setup\ModuleDiscovery\ModuleFileReaderException;

require_once(APPROOT.'/setup/parameters.class.inc.php');
require_once(APPROOT.'/core/cmdbsource.class.inc.php');
require_once(APPROOT.'/setup/modulediscovery.class.inc.php');
require_once(APPROOT.'/setup/moduleinstaller.class.inc.php');
/**
 * Basic helper class to describe an extension, with some characteristics and a list of modules
 */
class iTopExtension
{
	public const SOURCE_WIZARD = 'datamodels';
	public const SOURCE_MANUAL = 'extensions';
	public const SOURCE_REMOTE = 'data';

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
	 * If null, check if at least one module cannot be uninstalled
	 * @var bool|null
	 */
	public ?bool $bCanBeUninstalled = null;

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
	/**
	 * @var bool
	 */
	public bool $bInstalled = false;
	/**
	 * @var bool
	 */
	public bool $bRemovedFromDisk = false;

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
		$this->aModules = [];
		$this->aModuleVersion = [];
		$this->aModuleInfo = [];
		$this->sSourceDir = '';
		$this->bVisible = true;
		$this->aMissingDependencies = [];
	}

	/**
	 * @since 3.3.0
	 * @return bool
	 */
	public function CanBeUninstalled(): bool
	{
		if (!is_null($this->bCanBeUninstalled)) {
			return $this->bCanBeUninstalled;
		}
		foreach ($this->aModuleInfo as $sModuleCode => $aModuleInfo) {
			$this->bCanBeUninstalled = $aModuleInfo['uninstallable'] === 'yes';
			return $this->bCanBeUninstalled;
		}
		return true;
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
	 * The list of all currently installed extensions
	 * @var array|null
	 */
	protected ?array $aInstalledExtensions = null;

	/**
	 * The list of directories browsed using the ReadDir method when building the map
	 * @var string[]
	 */
	protected $aScannedDirs;

	public function __construct($sFromEnvironment = 'production', $aExtraDirs = [])
	{
		$this->aExtensions = [];
		$this->aScannedDirs = [];
		$this->ScanDisk($sFromEnvironment);
		foreach ($aExtraDirs as $sDir) {
			$this->ReadDir($sDir, iTopExtension::SOURCE_REMOTE);
		}
		$this->CheckDependencies($sFromEnvironment);
	}

	/**
	 * Populate the list of available (pseudo)extensions by scanning the disk
	 * where the iTop files are located
	 * @param string $sEnvironment
	 * @return void
	 */
	protected function ScanDisk($sEnvironment)
	{
		if (!$this->ReadInstallationWizard(APPROOT.'/datamodels/2.x') && !$this->ReadInstallationWizard(APPROOT.'/datamodels/2.x')) {
			if (!$this->ReadDir(APPROOT.'/datamodels/2.x', iTopExtension::SOURCE_WIZARD)) {
				$this->ReadDir(APPROOT.'/datamodels/1.x', iTopExtension::SOURCE_WIZARD);
			}
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
		if (!is_readable($sDir.'/installation.xml')) {
			return false;
		}

		$oXml = new XMLParameters($sDir.'/installation.xml');
		foreach ($oXml->Get('steps') as $aStepInfo) {
			if (array_key_exists('options', $aStepInfo)) {
				$this->ProcessWizardChoices($aStepInfo['options']);
			}
			if (array_key_exists('alternatives', $aStepInfo)) {
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
		foreach ($aChoices as $aChoiceInfo) {
			if (array_key_exists('extension_code', $aChoiceInfo)) {
				$oExtension = new iTopExtension();
				$oExtension->sCode = $aChoiceInfo['extension_code'];
				$oExtension->sLabel = $aChoiceInfo['title'];
				$oExtension->sDescription = $aChoiceInfo['description'];
				if (array_key_exists('modules', $aChoiceInfo)) {
					// Some wizard choices are not associated with any module
					$oExtension->aModules = $aChoiceInfo['modules'];
				}
				if (array_key_exists('sub_options', $aChoiceInfo)) {
					if (array_key_exists('options', $aChoiceInfo['sub_options'])) {
						$this->ProcessWizardChoices($aChoiceInfo['sub_options']['options']);
					}
					if (array_key_exists('alternatives', $aChoiceInfo['sub_options'])) {
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
		foreach ($this->aExtensions as $key => $oExtension) {
			if ($oExtension->sCode == $oNewExtension->sCode) {
				if (version_compare($oNewExtension->sVersion, $oExtension->sVersion, '>')) {
					// This "new" extension is "newer" than the previous one, let's replace the previous one
					unset($this->aExtensions[$key]);
					$this->aExtensions[$oNewExtension->sCode.'/'.$oNewExtension->sVersion] = $oNewExtension;
					return;
				} else {
					// This "new" extension is not "newer" than the previous one, let's ignore it
					return;
				}
			}
		}
		// Finally it's not a duplicate, let's add it to the list
		$this->aExtensions[$oNewExtension->sCode.'/'.$oNewExtension->sVersion] = $oNewExtension;
	}

	/**
	 * @since 3.3.0
	 * @param string $sExtensionCode
	 *
	 * @return \iTopExtension|null
	 */
	public function GetFromExtensionCode(string $sExtensionCode): ?iTopExtension
	{
		foreach ($this->aExtensions as $oExtension) {
			if ($oExtension->sCode === $sExtensionCode) {
				return $oExtension;
			}
		}
		return null;
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
		if (!is_readable($sSearchDir)) {
			return false;
		}
		$hDir = opendir($sSearchDir);
		if ($hDir !== false) {
			if ($sParentExtensionId == null) {
				// We're not recursing, let's add the directory to the list of scanned dirs
				$this->aScannedDirs[] = $sSearchDir;
			}
			$sExtensionId = null;
			$aSubDirectories = [];

			// First check if there is an extension.xml file in this directory
			if (is_readable($sSearchDir.'/extension.xml')) {
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
			while (($sFile = readdir($hDir)) !== false) {
				if (($sFile !== '.') && ($sFile !== '..')) {
					$aMatches = [];
					if (is_dir($sSearchDir.'/'.$sFile)) {
						// Recurse after parsing all the regular files
						$aSubDirectories[] = $sSearchDir.'/'.$sFile;
					} elseif (preg_match('/^module\.(.*).php$/i', $sFile, $aMatches)) {
						// Found a module
						try {
							$aModuleInfo = ModuleFileReader::GetInstance()->ReadModuleFileInformation($sSearchDir.'/'.$sFile);
						} catch (ModuleFileReaderException $e) {
							continue;
						}
						// If we are not already inside a formal extension, then the module itself is considered
						// as an extension, otherwise, the module is just added to the list of modules belonging
						// to this extension
						$sModuleId = $aModuleInfo[ModuleFileReader::MODULE_INFO_ID];
						list($sModuleName, $sModuleVersion) = ModuleDiscovery::GetModuleName($sModuleId);
						if ($sModuleVersion == '') {
							// Provide a default module version since version is mandatory when recording ExtensionInstallation
							$sModuleVersion = '0.0.1';
						}
						$aModuleInfo[ModuleFileReader::MODULE_INFO_CONFIG]['uninstallable'] ??= 'yes';

						if (($sParentExtensionId !== null) && (array_key_exists($sParentExtensionId, $this->aExtensions)) && ($this->aExtensions[$sParentExtensionId] instanceof iTopExtension)) {
							// Already inside an extension, let's add this module the list of modules belonging to this extension
							$this->aExtensions[$sParentExtensionId]->aModules[] = $sModuleName;
							$this->aExtensions[$sParentExtensionId]->aModuleVersion[$sModuleName] = $sModuleVersion;
							$this->aExtensions[$sParentExtensionId]->aModuleInfo[$sModuleName] = $aModuleInfo[ModuleFileReader::MODULE_INFO_CONFIG];
						} else {
							// Not already inside a folder containing an 'extension.xml' file

							// Ignore non-visible modules and auto-select ones, since these are never prompted
							// as a choice to the end-user
							$bVisible = true;
							if (!$aModuleInfo[ModuleFileReader::MODULE_INFO_CONFIG]['visible'] || isset($aModuleInfo[ModuleFileReader::MODULE_INFO_CONFIG]['auto_select'])) {
								$bVisible = false;
							}

							// Let's create a "fake" extension from this module (containing just this module) for backwards compatibility
							$oExtension = new iTopExtension();
							$oExtension->sCode = $sModuleName;
							$oExtension->sLabel = $aModuleInfo[ModuleFileReader::MODULE_INFO_CONFIG]['label'];
							$oExtension->sDescription = '';
							$oExtension->sVersion = $sModuleVersion;
							$oExtension->sSource = $sSource;
							$oExtension->bMandatory = $aModuleInfo[ModuleFileReader::MODULE_INFO_CONFIG]['mandatory'];
							$oExtension->sMoreInfoUrl = $aModuleInfo[ModuleFileReader::MODULE_INFO_CONFIG]['doc.more_information'];
							$oExtension->aModules = [$sModuleName];
							$oExtension->aModuleVersion[$sModuleName] = $sModuleVersion;
							$oExtension->aModuleInfo[$sModuleName] = $aModuleInfo[ModuleFileReader::MODULE_INFO_CONFIG];
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
			foreach ($aSubDirectories as $sDir) {
				// Recurse inside the subdirectories
				$this->ReadDir($sDir, $sSource, $sExtensionId);
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
		$aSearchDirs = [];

		if (is_dir(APPROOT.'/datamodels/2.x')) {
			$aSearchDirs[] = APPROOT.'/datamodels/2.x';
		} elseif (is_dir(APPROOT.'/datamodels/1.x')) {
			$aSearchDirs[] = APPROOT.'/datamodels/1.x';
		}
		$aSearchDirs = array_merge($aSearchDirs, $this->aScannedDirs);

		try {
			$aAllModules = ModuleDiscovery::GetAvailableModules($aSearchDirs, true);
		} catch (MissingDependencyException $e) {
			// Some modules have missing dependencies
			// Let's check what is the impact at the "extensions" level
			foreach ($this->aExtensions as $sKey => $oExtension) {
				foreach ($oExtension->aModules as $sModuleName) {
					if (array_key_exists($sModuleName, $oExtension->aModuleVersion)) {
						// This information is not available for pseudo modules defined in the installation wizard, but let's ignore them
						$sVersion = $oExtension->aModuleVersion[$sModuleName];
						$sModuleId = $sModuleName.'/'.$sVersion;

						if (array_key_exists($sModuleId, $e->aModulesInfo)) {
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
	 * Get all available extensions
	 * @return iTopExtension[]
	 */
	public function GetAllExtensions()
	{
		return $this->aExtensions;
	}

	/**
	 * @return array All available extensions and extensions currently installed but not available due to files removal
	 */
	public function GetAllExtensionsWithPreviouslyInstalled(): array
	{
		return array_merge($this->aExtensions, $this->aInstalledExtensions ?? []);
	}

	/**
	 * Mark the given extension as chosen
	 * @param string $sExtensionCode The code of the extension (code without version number)
	 * @param bool $bMark The value to set for the bMarkAsChosen flag
	 * @return void
	 */
	public function MarkAsChosen($sExtensionCode, $bMark = true)
	{
		foreach ($this->aExtensions as $oExtension) {
			if ($oExtension->sCode == $sExtensionCode) {
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
		foreach ($this->aExtensions as $oExtension) {
			if ($oExtension->sCode == $sExtensionCode) {
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
		foreach ($this->aExtensions as $oExtension) {
			if ($oExtension->sCode == $sExtensionCode) {
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
		$aResult = [];
		foreach ($this->aExtensions as $oExtension) {
			if ($oExtension->bMarkedAsChosen) {
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
		foreach ($this->LoadInstalledExtensionsFromDatabase($oConfig) as $oExtension) {
			$this->MarkAsChosen($oExtension->sCode);
			$this->SetInstalledVersion($oExtension->sCode, $oExtension->sVersion);
		}
		return true;
	}

	protected function LoadInstalledExtensionsFromDatabase(Config $oConfig): array|false
	{
		try {
			if (CMDBSource::DBName() === null) {
				CMDBSource::InitFromConfig($oConfig);
			}
			$sLatestInstallationDate = CMDBSource::QueryToScalar("SELECT max(installed) FROM ".$oConfig->Get('db_subname')."priv_extension_install");
			$aDBInfo = CMDBSource::QueryToArray("SELECT * FROM ".$oConfig->Get('db_subname')."priv_extension_install WHERE installed = '".$sLatestInstallationDate."'");

			$this->aInstalledExtensions = [];
			foreach ($aDBInfo as $aExtensionInfo) {
				$oExtension = new iTopExtension();
				$oExtension->sCode = $aExtensionInfo['code'];
				$oExtension->sLabel = $aExtensionInfo['label'];
				$oExtension->sDescription = $aExtensionInfo['description'] ?? '';
				$oExtension->sVersion = $aExtensionInfo['version'];
				$oExtension->sSource = $aExtensionInfo['source'];
				$oExtension->bMandatory = false;
				$oExtension->sMoreInfoUrl = '';
				$oExtension->aModules = [];
				$oExtension->aModuleVersion = [];
				$oExtension->aModuleInfo = [];
				$oExtension->sSourceDir = '';
				$oExtension->bVisible = true;
				$oExtension->bInstalled = true;
				$oExtension->bCanBeUninstalled = !isset($aExtensionInfo['uninstallable']) || $aExtensionInfo['uninstallable'] === 'yes';
				$oChoice = $this->GetFromExtensionCode($oExtension->sCode);
				if ($oChoice) {
					$oChoice->bInstalled = true;
				} else {
					$oExtension->bRemovedFromDisk = true;
				}

				$this->aInstalledExtensions[$oExtension->sCode.'/'.$oExtension->sVersion] = $oExtension;
			}

			return $this->aInstalledExtensions;
		} catch (MySQLException $e) {
			// No database or erroneous information
			return false;
		}
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

		foreach ($this->GetAllExtensions() as $oExtension) {
			if (($oExtension->sSource == $sInSourceOnly) &&
				($oExtension->bMarkedAsChosen == true) &&
				(array_key_exists($sModuleNameToFind, $oExtension->aModuleVersion))) {
				return true;
			}
		}
		return false;
	}

}
