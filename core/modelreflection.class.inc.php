<?php
// Copyright (C) 2013 Combodo SARL
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
 * @copyright   Copyright (C) 2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
 
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

	abstract public function GetQuery($sOQL);

	abstract public function DictString($sStringCode, $sDefault = null, $bUserLanguageOnly = false);

	public function DictFormat($sFormatCode /*, ... arguments ....*/)
	{
		$sLocalizedFormat = $this->DictString($sFormatCode);
		$aArguments = func_get_args();
		array_shift($aArguments);
		
		if ($sLocalizedFormat == $sFormatCode)
		{
			// Make sure the information will be displayed (ex: an error occuring before the dictionary gets loaded)
			return $sFormatCode.' - '.implode(', ', $aArguments);
		}

		return vsprintf($sLocalizedFormat, $aArguments);
	}

	abstract public function GetIconSelectionField($sCode, $sLabel = '', $defaultValue = '');
}

abstract class QueryReflection
{
	/**
	 * Throws an exception in case of an invalid syntax
	 */
	abstract public function __construct($sOQL);

	abstract public function GetClass();
	abstract public function GetClassAlias();
}


class ModelReflectionRuntime extends ModelReflection
{
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
		if ($sScope != null)
		{
			$aScope = array();
			foreach (explode(',', $sScope) as $sScopeClass)
			{
				$aScope[] = trim($sScopeClass);
			}
		}
		$aAttributes = array();
		foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
		{
			$sAttributeClass = get_class($oAttDef);
			if ($aScope != null)
			{
				foreach ($aScope as $sScopeClass)
				{
					if (($sAttributeClass == $sScopeClass) || is_subclass_of($sAttributeClass, $sScopeClass))
					{
						$aAttributes[$sAttCode] = $sAttributeClass;
						break;
					}
				}
			}
			else
			{
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
		if (array_key_exists($sPropName, $aParams))
		{
			$ret = $aParams[$sPropName];
		}

		if ($oAttDef instanceof AttributeHierarchicalKey)
		{
			if ($sPropName == 'targetclass')
			{
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
		if ($bExcludeLinks)
		{
			$aExcluded = MetaModel::GetLinkClasses();
			$aRes = array();
			foreach ($aClasses as $sClass)
			{
				if (!array_key_exists($sClass, $aExcluded))
				{
					$aRes[] = $sClass;
				}
			}
		}
		else
		{
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

	public function GetQuery($sOQL)
	{
		return new QueryReflectionRuntime($sOQL);
	}

	public function DictString($sStringCode, $sDefault = null, $bUserLanguageOnly = false)
	{
		return Dict::S($sStringCode, $sDefault, $bUserLanguageOnly);
	}

	public function GetIconSelectionField($sCode, $sLabel = '', $defaultValue = '')
	{
		return new RunTimeIconSelectionField($sCode, $sLabel, $defaultValue);
	}
}


class QueryReflectionRuntime extends QueryReflection
{
	protected $oFilter;

	/**
	 *	throws an exception in case of a wrong syntax
	 */
	public function __construct($sOQL)
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
