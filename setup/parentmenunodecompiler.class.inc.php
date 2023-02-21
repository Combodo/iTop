<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */


/**
 * @since 3.0.x N°4762
 */
class ParentMenuNodeCompiler
{
	/**
	 * @var MFCompiler
	 */

	private $oMFCompiler;

	/**
	 * @var array
	 */
	private $aParentModuleRootDirs;

	/**
	 * @var array
	 */
	private $aParentMenuNodes;

	/**
	 * @var array
	 */
	private $aAdminMenus;

	/**
	 * @var string
	 */
	private $sTempTargetDir;

	/**
	 * @var string
	 */
	private $sFinalTargetDir;

	/**
	 * @var string
	 */
	private $sRelativeDir;

	/**
	 * @var Page|null
	 */
	private $oP;

	/**
	 * @var array
	 */
	private $aMenuLinesForAdmins = [];

	/**
	 * @var array
	 */
	private $aMenuLinesForAll = [];

	/**
	 * @var array
	 */
	private $aMenuProcessStatus = [];

	const COMPILED = 1;
	const COMPILING = 2;

	public function __construct(MFCompiler $oMFCompiler, array $aParentModuleRootDirs, array $aParentMenuNodes, array $aAdminMenus,
		string $sTempTargetDir, string $sFinalTargetDir, string $sRelativeDir, ?Page $oP = null) {
		$this->oMFCompiler = $oMFCompiler;
		$this->aParentModuleRootDirs = $aParentModuleRootDirs;
		$this->aParentMenuNodes = $aParentMenuNodes;
		$this->aAdminMenus = $aAdminMenus;
		$this->sTempTargetDir = $sTempTargetDir;
		$this->sFinalTargetDir = $sFinalTargetDir;
		$this->sRelativeDir = $sRelativeDir;
		$this->oP = $oP;
	}

	public function CompileParentMenuNode(string $sMenuId) : void
	{
		$sStatus = array_key_exists($sMenuId, $this->aMenuProcessStatus) ? $this->aMenuProcessStatus[$sMenuId] : null;
		if ($sStatus === self::COMPILED){
			//node already processed before
			return;
		} else if ($sStatus === self::COMPILING){
			throw new \Exception("Cyclic dependency between parent menus ($sMenuId)");
		}

		$this->aMenuProcessStatus[$sMenuId] = self::COMPILING;

		try {
			if (! array_key_exists($sMenuId, $this->aParentMenuNodes)){
				throw new Exception("Failed to process parent menu '$sMenuId' that is referenced by a child but not defined");
			}
			$oMenuNode = $this->aParentMenuNodes[$sMenuId];

			$sParent = $oMenuNode->GetChildText('parent', null);
			if (! empty($sParent)){
				//compile parents before (even parent of parents ... recursively)
				$this->CompileParentMenuNode($sParent);
			}

			if (! array_key_exists($sMenuId, $this->aParentModuleRootDirs)){
				throw new Exception("Failed to process parent menu '$sMenuId' that is referenced by a child but not defined");
			}
			$sModuleRootDir = $this->aParentModuleRootDirs[$sMenuId];

			$aMenuLines = $this->oMFCompiler->CompileMenu($oMenuNode, $this->sTempTargetDir, $this->sFinalTargetDir, $this->sRelativeDir, $this->oP);
		} catch (DOMFormatException $e) {
			throw new Exception("Failed to process menu '$sMenuId', from '$sModuleRootDir': ".$e->getMessage());
		}
		$sParent = $oMenuNode->GetChildText('parent', null);
		if (($oMenuNode->GetChildText('enable_admin_only') == '1') || isset($this->aAdminMenus[$sParent])) {
			$this->aMenuLinesForAdmins = array_merge($this->aMenuLinesForAdmins, $aMenuLines);
			$this->aAdminMenus[$oMenuNode->getAttribute("id")] = true;
		} else {
			$this->aMenuLinesForAll = array_merge($this->aMenuLinesForAll, $aMenuLines);
		}

		$this->aMenuProcessStatus[$sMenuId] = self::COMPILED;
	}

	public function GetMenuLinesForAdmins(): array {
		return $this->aMenuLinesForAdmins;
	}

	public function GetMenuLinesForAll(): array {
		return $this->aMenuLinesForAll;
	}


}
