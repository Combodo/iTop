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
 * SQLUnionQuery
 * build a mySQL compatible SQL query
 *
 * @copyright   Copyright (C) 2024 Combodo SAS
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
	protected $aSelectExpr;

	public function __construct($aQueries, $aGroupBy, $aSelectExpr = array())
	{
		parent::__construct();

		$this->aQueries = array();
		foreach ($aQueries as $oSQLQuery)
		{
			$this->aQueries[] = $oSQLQuery->DeepClone();
		}
		$this->aGroupBy = $aGroupBy;
		$this->aSelectExpr = $aSelectExpr;
	}

	public function DisplayHtml()
	{
		$aQueriesHtml = array();
		foreach ($this->aQueries as $oSQLQuery)
		{
			$aQueriesHtml[] = '<p>'.$oSQLQuery->DisplayHtml().'</p>';
		}
		echo implode('UNION', $aQueriesHtml);
	}

	public function AddInnerJoin($oSQLQuery, $sLeftField, $sRightField, $sRightTable = '')
	{
		foreach ($this->aQueries as $oSubSQLQuery)
		{
			$oSubSQLQuery->AddInnerJoin($oSQLQuery->DeepClone(), $sLeftField, $sRightField, $sRightTable = '');
		}
	}

	/**
	 * @param array $aArgs
	 * @throws Exception
	 */
	public function RenderDelete($aArgs = array())
	{
		throw new Exception(__class__.'::'.__function__.'Not implemented !');
	}

	// Interface, build the SQL query

	/**
	 * @param array $aArgs
	 * @throws Exception
	 */
	public function RenderUpdate($aArgs = array())
	{
		throw new Exception(__class__.'::'.__function__.'Not implemented !');
	}

	/**
	 * Interface, build the SQL query
	 *
	 * @param array $aOrderBy
	 * @param array $aArgs
	 * @param int $iLimitCount
	 * @param int $iLimitStart
	 * @param bool $bGetCount
	 * @param bool $bBeautifulQuery
	 *
	 * @return string
	 * @throws \CoreException
	 */
	public function RenderSelect($aOrderBy = array(), $aArgs = array(), $iLimitCount = 0, $iLimitStart = 0, $bGetCount = false, $bBeautifulQuery = false)
	{
		$this->m_bBeautifulQuery = $bBeautifulQuery;
		$sLineSep = $this->m_bBeautifulQuery ? "\n" : '';

		$aSelects = array();
		foreach ($this->aQueries as $oSQLQuery)
		{
			// Render SELECTS without orderby/limit/count
			/** @var \SQLObjectQuery $oSQLQuery */
			$aSelects[] = $oSQLQuery->RenderSelect(array(), $aArgs, 0, 0, false, $bBeautifulQuery);
		}
		if ($iLimitCount > 0)
		{
			$sLimitStart = '(';
			$sLimit = 'LIMIT '.$iLimitStart.', '.$iLimitCount;
			$sLimitEnd = ')';
		}
		else
		{
			$sLimitStart = '';
			$sLimit = '';
			$sLimitEnd = '';
		}

		if ($bGetCount)
		{
			$sSelects = "{$sLimitStart}".implode(" {$sLimit}{$sLimitEnd}{$sLineSep} UNION{$sLineSep} {$sLimitStart}", $aSelects)." {$sLimit}{$sLimitEnd}";
			$sFrom = "({$sLineSep}{$sSelects}{$sLineSep}) as __selects__";
			$sSQL = "SELECT COUNT(*) AS COUNT FROM (SELECT$sLineSep 1 $sLineSep FROM {$sFrom}{$sLineSep}) AS _union_alderaan_";
		}
		else
		{
			$sOrderBy = $this->aQueries[0]->RenderOrderByClause($aOrderBy);
			if (!empty($sOrderBy))
			{
				$sOrderBy = "ORDER BY {$sOrderBy}{$sLineSep} {$sLimit}";
				$sSQL = implode(" {$sLineSep} UNION{$sLineSep} ", $aSelects).$sLineSep.$sOrderBy;
			}
			else
			{
				$sSQL = $sLimitStart.implode(" {$sLimit}{$sLimitEnd} {$sLineSep} UNION{$sLineSep} {$sLimitStart}", $aSelects)." {$sLimit}{$sLimitEnd}";
			}
		}
		return $sSQL;
	}

	// Interface, build the SQL query

	/**
	 * @param array $aArgs
	 * @param bool $bBeautifulQuery
	 * @param array $aOrderBy
	 * @param int $iLimitCount
	 * @param int $iLimitStart
	 * @return string
	 * @throws CoreException
	 */
	public function RenderGroupBy($aArgs = array(), $bBeautifulQuery = false, $aOrderBy = array(), $iLimitCount = 0, $iLimitStart = 0)
	{
		$this->m_bBeautifulQuery = $bBeautifulQuery;
		$sLineSep = $this->m_bBeautifulQuery ? "\n" : '';

		$aSelects = array();
		foreach ($this->aQueries as $oSQLQuery)
		{
			// Render SELECTS without orderby/limit/count
			$aSelects[] = $oSQLQuery->RenderSelect(array(), $aArgs, 0, 0, false, $bBeautifulQuery);
		}
		$sSelects = '('.implode(")$sLineSep UNION$sLineSep(", $aSelects).')';
		$sFrom = "($sLineSep$sSelects$sLineSep) as __selects__";

		$aSelectAliases = array();
		$aGroupAliases = array();
		foreach ($this->aGroupBy as $sGroupAlias => $trash)
		{
			$aSelectAliases[$sGroupAlias] = "`$sGroupAlias`";
			$aGroupAliases[] = "`$sGroupAlias`";
		}
		foreach($this->aSelectExpr as $sSelectAlias => $oExpr)
		{
			$aSelectAliases[$sSelectAlias] = $oExpr->RenderExpression(true)." AS `$sSelectAlias`";
		}

		$sSelect = implode(",$sLineSep ", $aSelectAliases);
		$sGroupBy = implode(', ', $aGroupAliases);

		$sOrderBy = self::ClauseOrderBy($aOrderBy, $aSelectAliases);
		if (!empty($sGroupBy))
		{
			$sGroupBy = "GROUP BY $sGroupBy$sLineSep";
		}
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


		$sSQL = "SELECT $sSelect,$sLineSep COUNT(*) AS _itop_count_$sLineSep FROM $sFrom$sLineSep $sGroupBy $sOrderBy$sLineSep $sLimit";
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
