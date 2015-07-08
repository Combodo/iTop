<?php
// Copyright (C) 2010-2015 Combodo SARL
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
 * Associated with the metamodel -> MakeQuery/MakeQuerySingleTable
 *
 * @copyright   Copyright (C) 2010-2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class QueryBuilderContext
{
	protected $m_oRootFilter;
	protected $m_aClassAliases;
	protected $m_aTableAliases;
	protected $m_aModifierProperties;
	protected $m_aSelectedClasses;

	public $m_oQBExpressions;

	public function __construct($oFilter, $aModifierProperties, $aGroupByExpr = null, $aSelectedClasses = null)
	{
		$this->m_oRootFilter = $oFilter;
		$this->m_oQBExpressions = new QueryBuilderExpressions($oFilter, $aGroupByExpr);

		$this->m_aClassAliases = $oFilter->GetJoinedClasses();
		$this->m_aTableAliases = array();

		$this->m_aModifierProperties = $aModifierProperties;
		if (is_null($aSelectedClasses))
		{
			$this->m_aSelectedClasses = $oFilter->GetSelectedClasses();
		}
		else
		{
			// For the unions, the selected classes can be upper in the hierarchy (lowest common ancestor)
			$this->m_aSelectedClasses = $aSelectedClasses;
		}
	}

	public function GetRootFilter()
	{
		return $this->m_oRootFilter;
	}

	public function GenerateTableAlias($sNewName, $sRealName)
	{
		return MetaModel::GenerateUniqueAlias($this->m_aTableAliases, $sNewName, $sRealName);
	}

	public function GenerateClassAlias($sNewName, $sRealName)
	{
		return MetaModel::GenerateUniqueAlias($this->m_aClassAliases, $sNewName, $sRealName);
	}

	public function GetModifierProperties($sPluginClass)
	{
		if (array_key_exists($sPluginClass, $this->m_aModifierProperties))
		{
			return $this->m_aModifierProperties[$sPluginClass];
		}
		else
		{
			return array();
		}
	}

	public function GetSelectedClass($sAlias)
	{
		return $this->m_aSelectedClasses[$sAlias];
	}
}
