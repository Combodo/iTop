<?php

// Copyright (C) 2024 Combodo SAS
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

/**
 * Reflection API for the MetaModel (partial)
 *
 * @copyright   Copyright (C) 2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Exclude the parent class from the list
 *
 * @package     iTopORM
 */
define('ENUM_CHILD_CLASSES_EXCLUDETOP', 1);
/**
 * Include the parent class in the list
 *
 * @package     iTopORM
*/
define('ENUM_CHILD_CLASSES_ALL', 2);

abstract class ModelReflection
{
	abstract public function GetClassIcon($sClass, $bImgTag = true);
	abstract public function IsValidAttCode($sClass, $sAttCode);
	abstract public function GetName($sClass);
	abstract public function GetLabel($sClass, $sAttCodeEx);
	abstract public function GetValueLabel($sClass, $sAttCode, $sValue);
	abstract public function ListAttributes($sClass, $sScope = null);
	abstract public function GetAttributeProperty($sClass, $sAttCode, $sPropName, $default = null);
	abstract public function GetAllowedValues_att($sClass, $sAttCode);
	abstract public function HasChildrenClasses($sClass);
	abstract public function GetClasses($sCategories = '', $bExcludeLinks = false);
	abstract public function IsValidClass($sClass);
	abstract public function IsSameFamilyBranch($sClassA, $sClassB);
	abstract public function GetParentClass($sClass);
	abstract public function GetFiltersList($sClass);
	abstract public function IsValidFilterCode($sClass, $sFilterCode);

	/**
	 * @since 3.3.0
	 */
	abstract public function IsAbstract($sClass): bool;

	/**
	 * @param string $sOQL
	 *
	 * @return \DBObjectSearch
	 */
	abstract public function GetQuery($sOQL);

	abstract public function DictString($sStringCode, $sDefault = null, $bUserLanguageOnly = false);

	public function DictFormat($sFormatCode /*, ... arguments ....*/)
	{
		$sLocalizedFormat = $this->DictString($sFormatCode);
		$aArguments = func_get_args();
		array_shift($aArguments);

		if ($sLocalizedFormat == $sFormatCode) {
			// Make sure the information will be displayed (ex: an error occuring before the dictionary gets loaded)
			return $sFormatCode.' - '.implode(', ', $aArguments);
		}

		return utils::VSprintf($sLocalizedFormat, $aArguments);
	}

	/**
	 * @param $sCode
	 * @param string $sLabel
	 * @param string $defaultValue
	 *
	 * @return \RunTimeIconSelectionField
	 * @deprecated since 3.3.0 replaced by GetAvailableIcons
	 */
	abstract public function GetIconSelectionField($sCode, $sLabel = '', $defaultValue = '');

	/**
	 * Find available icons for the current context
	 *
	 * @return array of ['value', 'label', 'icon'] where 'value' is the relative path on disk, 'label' the name to display and 'icon' is the URL to get the image
	 */
	abstract public function GetAvailableIcons(): array;

	abstract public function GetRootClass($sClass);
	abstract public function EnumChildClasses($sClass, $iOption = ENUM_CHILD_CLASSES_EXCLUDETOP);
}

abstract class QueryReflection
{
	/**
	 * Throws an exception in case of an invalid syntax
	 */
	abstract public function __construct($sOQL, ModelReflection $oModelReflection);

	abstract public function GetClass();
	abstract public function GetClassAlias();
}

class ModelReflectionRuntime extends ModelReflection
{
	private static array $aAllIcons = [];

	public function __construct()
	{
	}

	public function GetClassIcon($sClass, $bImgTag = true)
	{
		return MetaModel::GetClassIcon($sClass, $bImgTag);
	}

	public function IsValidAttCode($sClass, $sAttCode)
	{
		return MetaModel::IsValidAttCode($sClass, $sAttCode);
	}

	public function GetName($sClass)
	{
		return MetaModel::GetName($sClass);
	}

	public function GetLabel($sClass, $sAttCodeEx)
	{
		return MetaModel::GetLabel($sClass, $sAttCodeEx);
	}

	public function GetValueLabel($sClass, $sAttCode, $sValue)
	{
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		return $oAttDef->GetValueLabel($sValue);
	}

	public function ListAttributes($sClass, $sScope = null)
	{
		$aScope = null;
		if ($sScope != null) {
			$aScope = [];
			foreach (explode(',', $sScope) as $sScopeClass) {
				$aScope[] = trim($sScopeClass);
			}
		}
		$aAttributes = [];
		foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef) {
			$sAttributeClass = get_class($oAttDef);
			if ($aScope != null) {
				foreach ($aScope as $sScopeClass) {
					if (is_a($sAttributeClass, $sScopeClass, true)) {
						$aAttributes[$sAttCode] = $sAttributeClass;
						break;
					}
				}
			} else {
				$aAttributes[$sAttCode] = $sAttributeClass;
			}
		}
		return $aAttributes;
	}

	public function GetAttributeProperty($sClass, $sAttCode, $sPropName, $default = null)
	{
		$ret = $default;

		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$aParams = $oAttDef->GetParams();
		if (array_key_exists($sPropName, $aParams)) {
			$ret = $aParams[$sPropName];
		}

		if ($oAttDef instanceof AttributeHierarchicalKey) {
			if ($sPropName == 'targetclass') {
				$ret = $sClass;
			}
		}
		return $ret;
	}

	public function GetAllowedValues_att($sClass, $sAttCode)
	{
		return MetaModel::GetAllowedValues_att($sClass, $sAttCode);
	}

	public function HasChildrenClasses($sClass)
	{
		return MetaModel::HasChildrenClasses($sClass);
	}

	public function GetClasses($sCategories = '', $bExcludeLinks = false)
	{
		$aClasses = MetaModel::GetClasses($sCategories);
		if ($bExcludeLinks) {
			$aExcluded = MetaModel::GetLinkClasses();
			$aRes = [];
			foreach ($aClasses as $sClass) {
				if (!array_key_exists($sClass, $aExcluded)) {
					$aRes[] = $sClass;
				}
			}
		} else {
			$aRes = $aClasses;
		}
		return $aRes;
	}

	public function IsValidClass($sClass)
	{
		return MetaModel::IsValidClass($sClass);
	}

	public function IsSameFamilyBranch($sClassA, $sClassB)
	{
		return MetaModel::IsSameFamilyBranch($sClassA, $sClassB);
	}

	public function GetParentClass($sClass)
	{
		return MetaModel::GetParentClass($sClass);
	}

	public function GetFiltersList($sClass)
	{
		return MetaModel::GetFiltersList($sClass);
	}

	public function IsValidFilterCode($sClass, $sFilterCode)
	{
		return MetaModel::IsValidFilterCode($sClass, $sFilterCode);
	}

	public function IsAbstract($sClass): bool
	{
		return MetaModel::IsAbstract($sClass);
	}

	public function GetQuery($sOQL)
	{
		return new QueryReflectionRuntime($sOQL, $this);
	}

	public function DictString($sStringCode, $sDefault = null, $bUserLanguageOnly = false)
	{
		return Dict::S($sStringCode, $sDefault, $bUserLanguageOnly);
	}

	public function GetIconSelectionField($sCode, $sLabel = '', $defaultValue = '')
	{
		return new RunTimeIconSelectionField($sCode, $sLabel, $defaultValue);
	}

	public function GetAvailableIcons(): array
	{
		$aFolderList = [
			APPROOT.'env-'.utils::GetCurrentEnvironment() => utils::GetAbsoluteUrlModulesRoot(),
			APPROOT.'images/icons' => utils::GetAbsoluteUrlAppRoot().'images/icons',
		];
		if (count(self::$aAllIcons) == 0) {
			foreach ($aFolderList as $sFolderPath => $sUrlPrefix) {
				$aIcons = self::FindIconsOnDisk($sFolderPath);
				ksort($aIcons);

				foreach ($aIcons as $sFilePath) {
					self::$aAllIcons[] = ['value' => $sFilePath, 'label' => basename($sFilePath), 'icon' => $sUrlPrefix.$sFilePath];
				}
			}
		}

		return self::$aAllIcons;
	}

	private static function FindIconsOnDisk(string $sBaseDir, string $sDir = '', array &$aFilesSpecs = []): array
	{
		$aResult = [];
		// Populate automatically the list of icon files
		if ($hDir = @opendir($sBaseDir.'/'.$sDir)) {
			while (($sFile = readdir($hDir)) !== false) {
				$aMatches = [];
				if (($sFile != '.') && ($sFile != '..') && ($sFile != 'lifecycle') && is_dir($sBaseDir.'/'.$sDir.'/'.$sFile)) {
					$sDirSubPath = ($sDir == '') ? $sFile : $sDir.'/'.$sFile;
					$aResult = array_merge($aResult, self::FindIconsOnDisk($sBaseDir, $sDirSubPath, $aFilesSpecs));
				}
				$sSize = filesize($sBaseDir.'/'.$sDir.'/'.$sFile);
				if (isset($aFilesSpecs[$sFile]) && $aFilesSpecs[$sFile] == $sSize) {
					continue;
				}
				if (preg_match('/\.(png|jpg|jpeg|gif|svg)$/i', $sFile, $aMatches)) { // png, jp(e)g, gif and svg are considered valid
					$aResult[$sFile.'_'.$sDir] = $sDir.'/'.$sFile;
					$aFilesSpecs[$sFile] = $sSize;
				}
			}
			closedir($hDir);
		}

		return $aResult;
	}

	public function GetRootClass($sClass)
	{
		return MetaModel::GetRootClass($sClass);
	}

	public function EnumChildClasses($sClass, $iOption = ENUM_CHILD_CLASSES_EXCLUDETOP)
	{
		return MetaModel::EnumChildClasses($sClass, $iOption);
	}
}

class QueryReflectionRuntime extends QueryReflection
{
	protected $oFilter;

	/**
	 *	throws an exception in case of a wrong syntax
	 */
	public function __construct($sOQL, ModelReflection $oModelReflection)
	{
		$this->oFilter = DBObjectSearch::FromOQL($sOQL);
	}

	public function GetClass()
	{
		return $this->oFilter->GetClass();
	}

	public function GetClassAlias()
	{
		return $this->oFilter->GetClassAlias();
	}
}
