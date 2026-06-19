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
	private $bMarkedAsChosen;
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

	public function __serialize(): array
	{
		return [
			'sCode' => $this->sCode,
			'sSource' => $this->sSource,
			'sVersion' => $this->sVersion,
			'aModules' => $this->aModules,
			'aModuleVersion' => $this->aModuleVersion,
			'aModuleInfo' => $this->aModuleInfo,
		];
	}

	public function __unserialize(array $aData): void
	{
		$this->sCode = $aData['sCode'] ?? '';
		$this->sSource = $aData['sSource'] ?? '';
		$this->sVersion = $aData['sVersion'] ?? '';
		$this->aModules = $aData['aModules'] ?? '';
		$this->aModuleVersion = $aData['aModuleVersion'] ?? '';
		$this->aModuleInfo = $aData['aModuleInfo'] ?? '';
	}

	public function __toString(): string
	{
		return json_encode($this->__serialize(), JSON_PRETTY_PRINT);
	}

	public function GetExtensionSourceLabel(): string
	{
		return match ($this->sSource) {
			self::SOURCE_MANUAL => 'Local extensions folder',
			self::SOURCE_REMOTE => (ITOP_APPLICATION == 'iTop') ? 'iTop Hub' : 'ITSM Designer',
			self::SOURCE_WIZARD => 'iTop package',
			default => '',
		};
	}

	public function IsRemote(): string
	{
		return $this->sSource === self::SOURCE_REMOTE;
	}

	public function IsMarkedAsChosen(): bool
	{
		return $this->bMarkedAsChosen;
	}

	public function MarkAsChosen(bool $bMarkedAsChosen = true): void
	{
		$this->bMarkedAsChosen = $bMarkedAsChosen;
	}
}
