<?php

/**
 * @since 3.1 N°4762
 */
class ParentMenuNodeCompiler
{
	const COMPILED = 1;
	const COMPILING = 2;

	public static $bUseLegacyMenuCompilation = false;

	/**
	 * @var MFCompiler
	 */
	private $oMFCompiler;

	/**
	 * admin menus declaration lines: result of module menu compilation
	 * @var array
	 */
	private $aMenuLinesForAdmins = [];

	/**
	 * non-admin menus declaration lines: result of module menu compilation
	 * @var array
	 */
	private $aMenuLinesForAll = [];

	/**
	 * use to handle menu group compilation recurring algorithm
	 * @var array
	 */
	private $aMenuProcessStatus = [];

	/**
	 * @var array
	 */
	private $aMenuNodes = [];

	/**
	 * @var array
	 */
	private $aMenusByModule = [];

	/**
	 * @var array
	 */
	private $aMenusToLoadByModule = [];

	/**
	 * @var array
	 */
	private $aParentMenusByModule = [];

	/**
	 * used by overall algo
	 * @var array
	 */
	private $aParentMenuNodes = [];

	/**
	 * used by new algo
	 * @var array
	 */
	private $aParentAdminMenus = [];

	/**
	 * used by overall algo
	 * @var array
	 */
	private $aParentModuleRootDirs = [];

	public function __construct(MFCompiler $oMFCompiler) {
		$this->oMFCompiler = $oMFCompiler;
	}

	public static function UseLegacyMenuCompilation(){
		self::$bUseLegacyMenuCompilation = true;
	}

	/**
	 * @param \ModelFactory $oFactory
	 * Initialize menu nodes arrays
	 * @return void
	 */
	public function LoadXmlMenus(\ModelFactory $oFactory) : void {
		foreach ($oFactory->GetNodes('menus/menu') as $oMenuNode) {
			$sMenuId = $oMenuNode->getAttribute('id');
			$this->aMenuNodes[$sMenuId] = $oMenuNode;

			$sModuleMenu = $oMenuNode->getAttribute('_created_in');
			$this->aMenusByModule[$sModuleMenu][] = $sMenuId;
		}
	}

	/**
	 * @param $aModules
	 * Initialize arrays related to parent/child menus
	 * @return void
	 */
	public function LoadModuleMenuInfo($aModules) : void
	{
		foreach ($aModules as $foo => $oModule) {
			$sModuleRootDir = $oModule->GetRootDir();
			$sModuleName = $oModule->GetName();

			if (array_key_exists($sModuleName, $this->aMenusByModule)) {
				$aMenusToLoad = [];
				$aParentMenus = [];

				foreach ($this->aMenusByModule[$sModuleName] as $sMenuId) {
					$oMenuNode = $this->aMenuNodes[$sMenuId];

					if (self::$bUseLegacyMenuCompilation){
						if ($sParent = $oMenuNode->GetChildText('parent', null)) {
							$aMenusToLoad[] = $sParent;
							$aParentMenus[] = $sParent;
						}
					} else {
						if ($oMenuNode->getAttribute("xsi:type") == 'MenuGroup') {
							$this->aParentModuleRootDirs[$sMenuId] = $sModuleRootDir;
						}

						if ($sParent = $oMenuNode->GetChildText('parent', null)) {
							$aMenusToLoad[] = $sParent;
							$aParentMenus[] = $sParent;

							$this->aParentModuleRootDirs[$sParent] = $sModuleRootDir;
						}

						if (array_key_exists($sMenuId, $this->aParentModuleRootDirs)){
							$this->aParentMenuNodes[$sMenuId] = $oMenuNode;
						}
					}

					// Note: the order matters: the parents must be defined BEFORE
					$aMenusToLoad[] = $sMenuId;
				}

				$this->aMenusToLoadByModule[$sModuleName] = array_unique($aMenusToLoad);
				$this->aParentMenusByModule[$sModuleName] = array_unique($aParentMenus);
			}
		}
	}

	/**
	 * Perform the actual "Compilation" for one module at a time
	 * @param \MFModule $oModule
	 * @param string $sTempTargetDir
	 * @param string $sFinalTargetDir
	 * @param string $sRelativeDir
	 * @param Page $oP
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function CompileModuleMenus(MFModule $oModule, $sTempTargetDir, $sFinalTargetDir, $sRelativeDir, $oP = null) : void
	{
		$this->aMenuLinesForAdmins = [];
		$this->aMenuLinesForAll = [];
		$aAdminMenus = [];

		$sModuleRootDir = $oModule->GetRootDir();
		$sModuleName = $oModule->GetName();

		$aParentMenus = $this->aParentMenusByModule[$sModuleName];
		foreach($this->aMenusToLoadByModule[$sModuleName] as $sMenuId)
		{
			$oMenuNode = $this->aMenuNodes[$sMenuId];
			if (is_null($oMenuNode))
			{
				throw new Exception("Module '{$oModule->GetId()}' (location : '$sModuleRootDir') contains an unknown menuId :  '$sMenuId'");
			}

			if (self::$bUseLegacyMenuCompilation) {
				if ($oMenuNode->getAttribute("xsi:type") == 'MenuGroup') {
					// Note: this algorithm is wrong
					// 1 - the module may appear empty in the current module, while children are defined in other modules
					// 2 - check recursively that child nodes are not empty themselves
					// Future algorithm:
					// a- browse the modules and build the menu tree
					// b- browse the tree and blacklist empty menus
					// c- before compiling, discard if blacklisted
					if (! in_array($oMenuNode->getAttribute("id"), $aParentMenus)) {
						// Discard empty menu groups
						continue;
					}
				}
			} else {
				if (array_key_exists($sMenuId, $this->aParentMenuNodes)) {
					// compile parent menus recursively
					$this->CompileParentMenuNode($sMenuId, $sTempTargetDir, $sFinalTargetDir, $sRelativeDir, $oP);
					continue;
				}
			}

			try
			{
				//both new/legacy algo: compile leaf menu
				$aMenuLines = $this->oMFCompiler->CompileMenu($oMenuNode, $sTempTargetDir, $sFinalTargetDir, $sRelativeDir, $oP);
			}
			catch (DOMFormatException $e)
			{
				throw new Exception("Failed to process menu '$sMenuId', from '$sModuleRootDir': ".$e->getMessage());
			}

			$sParent = $oMenuNode->GetChildText('parent', null);
			if (($oMenuNode->GetChildText('enable_admin_only') == '1') || isset($aAdminMenus[$sParent]) || isset($this->aParentAdminMenus[$sParent]))
			{
				$this->aMenuLinesForAdmins = array_merge($this->aMenuLinesForAdmins, $aMenuLines);
				$aAdminMenus[$oMenuNode->getAttribute("id")] = true;
			}
			else
			{
				$this->aMenuLinesForAll = array_merge($this->aMenuLinesForAll, $aMenuLines);
			}
		}
	}

	/**
	 * Perform parent menu compilation including its ancestrors (recursively)
	 * @param string $sMenuId
	 * @param string $sTempTargetDir
	 * @param string $sFinalTargetDir
	 * @param string $sRelativeDir
	 * @param Page $oP
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function CompileParentMenuNode(string $sMenuId, $sTempTargetDir, $sFinalTargetDir, $sRelativeDir, $oP = null) : void
	{
		$oMenuNode = $this->aParentMenuNodes[$sMenuId];
		$sStatus = array_key_exists($sMenuId, $this->aMenuProcessStatus) ? $this->aMenuProcessStatus[$sMenuId] : null;
		if ($sStatus === self::COMPILED){
			//node already processed before
			return;
		} else if ($sStatus === self::COMPILING){
			throw new \Exception("Cyclic dependency between parent menus ($sMenuId)");
		}

		$this->aMenuProcessStatus[$sMenuId] = self::COMPILING;

		try {
			$sParent = $oMenuNode->GetChildText('parent', null);
			if (! empty($sParent)){
				//compile parents before (even parent of parents ... recursively)
				$this->CompileParentMenuNode($sParent, $sTempTargetDir, $sFinalTargetDir, $sRelativeDir, $oP);
			}

			if (! array_key_exists($sMenuId, $this->aParentModuleRootDirs)){
				throw new Exception("Failed to process parent menu '$sMenuId' that is referenced by a child but not defined");
			}
			$sModuleRootDir = $this->aParentModuleRootDirs[$sMenuId];
			$aMenuLines = $this->oMFCompiler->CompileMenu($oMenuNode, $sTempTargetDir, $sFinalTargetDir, $sRelativeDir, $oP);
		} catch (DOMFormatException $e) {
			throw new Exception("Failed to process menu '$sMenuId', from '$sModuleRootDir': ".$e->getMessage());
		}
		$sParent = $oMenuNode->GetChildText('parent', null);
		if (($oMenuNode->GetChildText('enable_admin_only') == '1') || isset($this->aParentAdminMenus[$sParent])) {
			$this->aMenuLinesForAdmins = array_merge($this->aMenuLinesForAdmins, $aMenuLines);
			$this->aParentAdminMenus[$oMenuNode->getAttribute("id")] = true;
		} else {
			$this->aMenuLinesForAll = array_merge($this->aMenuLinesForAll, $aMenuLines);
		}

		$this->aMenuProcessStatus[$sMenuId] = self::COMPILED;
	}

	public function GetMenusByModule(string $sModuleName) : ?array
	{
		if (array_key_exists($sModuleName, $this->aMenusByModule)) {
			return $this->aMenusByModule[$sModuleName];
		}

		return null;
	}

	public function GetMenuLinesForAdmins(): array {
		return $this->aMenuLinesForAdmins;
	}

	public function GetMenuLinesForAll(): array {
		return $this->aMenuLinesForAll;
	}
}
