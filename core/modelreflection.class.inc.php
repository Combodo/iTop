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
 
interface ModelReflection
{
	public function GetClassIcon($sClass, $bImgTag = true); 
	public function IsValidAttCode($sClass, $sAttCode);
	public function GetName($sClass);
	public function GetLabel($sClass, $sAttCodeEx);
	public function ListAttributeDefs($sClass);
	public function GetAllowedValues_att($sClass, $sAttCode);
	public function HasChildrenClasses($sClass);
	public function GetClasses($sCategories = '');
	public function IsValidClass($sClass);
	public function GetExternalKeys($sClass);
	public function GetAttributeDef($sClass, $sAttCode);
	public function IsSameFamilyBranch($sClassA, $sClassB);
	public function GetFiltersList($sClass);
	public function IsValidFilterCode($sClass, $sFilterCode);
}

class ModelReflectionRuntime implements ModelReflection
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
 
	public function ListAttributeDefs($sClass)
	{
		return MetaModel::ListAttributeDefs($sClass);
	}
 
	public function GetAllowedValues_att($sClass, $sAttCode)
	{
		return MetaModel::GetAllowedValues_att($sClass, $sAttCode);
	}
 
	public function HasChildrenClasses($sClass)
	{
		return MetaModel::HasChildrenClasses($sClass);
	}
 
	public function GetClasses($sCategories = '')
	{
		return MetaModel::GetClasses($sCategories);
	}

	public function IsValidClass($sClass)
	{
		return MetaModel::IsValidClass($sClass);
	}

	public function GetExternalKeys($sClass)
	{
		return MetaModel::GetExternalKeys($sClass);
	}

	public function GetAttributeDef($sClass, $sAttCode)
	{
		return MetaModel::GetAttributeDef($sClass, $sAttCode);
	}

	public function IsSameFamilyBranch($sClassA, $sClassB)
	{
		return MetaModel::IsSameFamilyBranch($sClassA, $sClassB);
	}

	public function GetFiltersList($sClass)
	{
		return MetaModel::GetFiltersList($sClass);
	}

	public function IsValidFilterCode($sClass, $sFilterCode)
	{
		return MetaModel::IsValidFilterCode($sClass, $sFilterCode);
	}
}