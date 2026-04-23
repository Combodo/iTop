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
	 * @var array
	 */
	public $aModules;

	/**
	 * @var string[]
	 */
	public $aModuleVersion;

	/**
	 * @var array
	 */
	public $aModuleInfo;

	/**
	 * @var string
	 */
	public $sSourceDir;

	/**
	 *
	 * @var array
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
			if ($aModuleInfo['uninstallable'] !== 'yes') {
				return false;
			}
		}
		return true;
	}
}
