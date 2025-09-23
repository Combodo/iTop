<?php
// Copyright (C) 2010-2024 Combodo SAS
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
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class QueryBuilderContext
{
	protected $m_oRootFilter;
	protected $m_aClassAliases;
	protected $m_aTableAliases;
	protected $m_aModifierProperties;
	protected $m_aSelectedClasses;
	protected $m_aFilteredTables;
	protected $m_sEmptyClassAlias;

	/** @var \QueryBuilderExpressions */
	public $m_oQBExpressions;

	/**
	 * QueryBuilderContext constructor.
	 *
	 * @param $oFilter
	 * @param $aModifierProperties
	 * @param array $aGroupByExpr
	 * @param array $aSelectedClasses
	 * @param array $aSelectExpr
	 * @param array $aAttToLoad
	 *
	 * @throws \CoreException
	 */
	public function __construct($oFilter, $aModifierProperties, $aGroupByExpr = null, $aSelectedClasses = null, $aSelectExpr = null, $aAttToLoad = null)
	{
		$this->m_oRootFilter = $oFilter;
		$this->m_oQBExpressions = new QueryBuilderExpressions($oFilter, $aGroupByExpr, $aSelectExpr);

		$this->m_aClassAliases = $oFilter->GetJoinedClasses();
		$this->m_aTableAliases = array();
		$this->m_aFilteredTables = array();

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

		// Add all the attribute of interest
		foreach ($this->m_aSelectedClasses as $sClassAlias => $sClass)
		{
			$sTableAlias = $sClassAlias;
			if (empty($sTableAlias))
			{
				$sTableAlias = $this->GenerateClassAlias("$sClass", $sClass);
				$this->m_sEmptyClassAlias = $sTableAlias;
			}
			// default to the whole list of attributes + the very std id/finalclass
			$this->m_oQBExpressions->AddSelect($sClassAlias.'id', new FieldExpression('id', $sTableAlias));
			if (is_null($aAttToLoad) || !array_key_exists($sClassAlias, $aAttToLoad))
			{
				$sSelectedClass = $this->GetSelectedClass($sClassAlias);
				$aAttList = MetaModel::ListAttributeDefs($sSelectedClass);
			}
			else
			{
				$aAttList = $aAttToLoad[$sClassAlias];
			}
			foreach ($aAttList as $sAttCode => $oAttDef)
			{
				if (!$oAttDef->IsScalar())
				{
					continue;
				}
				$oExpression = new FieldExpression($sAttCode, $sTableAlias);
				$this->m_oQBExpressions->AddSelect($sClassAlias.$sAttCode, $oExpression);
			}
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

	public function AddFilteredTable($sTableAlias, $oCondition)
	{
		if (array_key_exists($sTableAlias, $this->m_aFilteredTables))
		{
			$this->m_aFilteredTables[$sTableAlias][] = $oCondition;
		}
		else
		{
			$this->m_aFilteredTables[$sTableAlias] = array($oCondition);
		}
	}

	public function GetFilteredTables()
	{
		return $this->m_aFilteredTables;
	}

	/**
	 * @return string
	 */
	public function GetEmptyClassAlias()
	{
		return $this->m_sEmptyClassAlias;
	}


}
