<?php
// Copyright (C) 2015 Combodo SARL
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
 * SQLUnionQuery
 * build a mySQL compatible SQL query
 *
 * @copyright   Copyright (C) 2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * SQLUnionQuery
 * build a mySQL compatible SQL query
 *
 * @package     iTopORM
 */


class SQLUnionQuery extends SQLQuery
{
	protected $aQueries;
	protected $aGroupBy;

	public function __construct($aQueries, $aGroupBy)
	{
		parent::__construct();

		$this->aQueries = array();
		foreach ($aQueries as $oSQLQuery)
		{
			$this->aQueries[] = $oSQLQuery->DeepClone();
		}
		$this->aGroupBy = $aGroupBy;
	}

	public function DisplayHtml()
	{
		$aQueriesHtml = array();
		foreach ($this->aQueries as $oSQLQuery)
		{
			$aQueriesHtml[] = '<p>'.$oSQLQuery->DisplayHtml().'</p>';
		}
		echo implode('UNION', $aQueries);
	}

	public function AddInnerJoin($oSQLQuery, $sLeftField, $sRightField, $sRightTable = '')
	{
		foreach ($this->aQueries as $oSubSQLQuery)
		{
			$oSubSQLQuery->AddInnerJoin($oSQLQuery->DeepClone(), $sLeftField, $sRightField, $sRightTable = '');
		}
	}

	public function RenderDelete($aArgs = array())
	{
		throw new Exception(__class__.'::'.__function__.'Not implemented !');
	}

	// Interface, build the SQL query
	public function RenderUpdate($aArgs = array())
	{
		throw new Exception(__class__.'::'.__function__.'Not implemented !');
	}

	// Interface, build the SQL query
	public function RenderSelect($aOrderBy = array(), $aArgs = array(), $iLimitCount = 0, $iLimitStart = 0, $bGetCount = false, $bBeautifulQuery = false)
	{
		$this->m_bBeautifulQuery = $bBeautifulQuery;
		$sLineSep = $this->m_bBeautifulQuery ? "\n" : '';
		$sIndent = $this->m_bBeautifulQuery ? "   " : null;

		$aSelects = array();
		foreach ($this->aQueries as $oSQLQuery)
		{
			// Render SELECTS without orderby/limit/count
			$aSelects[] = $oSQLQuery->RenderSelect(array(), $aArgs, 0, 0, false, $bBeautifulQuery);
		}
		$sSelects = '('.implode(")$sLineSep UNION$sLineSep(", $aSelects).')';

		if ($bGetCount)
		{
			$sFrom = "($sLineSep$sSelects$sLineSep) as __selects__";
			$sSQL = "SELECT$sLineSep COUNT(*) AS COUNT$sLineSep FROM $sFrom$sLineSep";
		}
		else
		{
			$aSelects = array();
			foreach ($this->aQueries as $oSQLQuery)
			{
				// Render SELECT without orderby/limit/count
				$aSelects[] = $oSQLQuery->RenderSelect(array(), $aArgs, 0, 0, false, $bBeautifulQuery);
			}
			$sSelect = $this->aQueries[0]->RenderSelectClause();
			$sOrderBy = $this->aQueries[0]->RenderOrderByClause($aOrderBy);
			if (!empty($sOrderBy))
			{
				$sOrderBy = "ORDER BY $sOrderBy$sLineSep";
			}
			if ($iLimitCount > 0)
			{
				$sLimit = 'LIMIT '.$iLimitStart.', '.$iLimitCount;
			}
			else
			{
				$sLimit = '';
			}
			$sSQL = $sSelects.$sLineSep.$sOrderBy.' '.$sLimit;
		}
		return $sSQL;
	}

	// Interface, build the SQL query
	public function RenderGroupBy($aArgs = array(), $bBeautifulQuery = false)
	{
		$this->m_bBeautifulQuery = $bBeautifulQuery;
		$sLineSep = $this->m_bBeautifulQuery ? "\n" : '';
		$sIndent = $this->m_bBeautifulQuery ? "   " : null;

		$aSelects = array();
		foreach ($this->aQueries as $oSQLQuery)
		{
			// Render SELECTS without orderby/limit/count
			$aSelects[] = $oSQLQuery->RenderSelect(array(), $aArgs, 0, 0, false, $bBeautifulQuery);
		}
		$sSelects = '('.implode(")$sLineSep UNION$sLineSep(", $aSelects).')';
		$sFrom = "($sLineSep$sSelects$sLineSep) as __selects__";

		$aAliases = array();
		foreach ($this->aGroupBy as $sGroupAlias => $trash)
		{
			$aAliases[] = "`$sGroupAlias`";
		}
		$sSelect = implode(', ', $aAliases);
		$sGroupBy = implode(', ', $aAliases);

		$sSQL = "SELECT $sSelect,$sLineSep COUNT(*) AS _itop_count_$sLineSep FROM $sFrom$sLineSep GROUP BY $sGroupBy";
		return $sSQL;
	}


	public function OptimizeJoins($aUsedTables, $bTopCall = true)
	{
		foreach ($this->aQueries as $oSQLQuery)
		{
			$oSQLQuery->OptimizeJoins($aUsedTables);
		}
	}
}
