<?php
// Copyright (C) 2015-2024 Combodo SAS
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
 * SQLObjectQuery
 * build a mySQL compatible SQL query
 *
 * @copyright   Copyright (C) 2015-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * SQLObjectQuery
 * build a mySQL compatible SQL query
 *
 * @package     iTopORM
 */


class SQLObjectQuery extends SQLQuery
{
	public $m_aContextData = null;
	public $m_iOriginalTableCount = 0;
	private $m_sTable = '';
	private $m_sTableAlias = '';
	private $m_aFields = array();
	private $m_aGroupBy = array();
	private $m_oConditionExpr = null;
	private $m_bToDelete = true; // The current table must be listed for deletion ?
	private $m_aValues = array(); // Values to set in case of an update query
	private $m_oSelectedIdField = null;
	private $m_aJoinSelects = array();
	protected $m_bBeautifulQuery = false;

	// Data set by PrepareRendering()
	private $__aFrom;
	private $__aFields;
	private $__aGroupBy;
	private $__aDelTables;
	private $__aSetValues;
	private $__aSelectedIdFields;


	public function __construct($sTable, $sTableAlias, $aFields, $bToDelete = true, $aValues = array(), $oSelectedIdField = null)
	{
		parent::__construct();

		// This check is not needed but for developping purposes
		//if (!CMDBSource::IsTable($sTable))
		//{
		//	throw new CoreException("Unknown table '$sTable'");
		//}

		// $aFields must be an array of "alias"=>"expr"
		// $oConditionExpr must be a condition tree
		// $aValues is an array of "alias"=>value

		$this->m_sTable = $sTable;
		$this->m_sTableAlias = $sTableAlias;
		$this->m_aFields = $aFields;
		$this->m_aGroupBy = null;
		$this->m_oConditionExpr = null;
		$this->m_bToDelete = $bToDelete;
		$this->m_aValues = $aValues;
		$this->m_oSelectedIdField = $oSelectedIdField;
	}

	public function GetTableAlias()
	{
		return $this->m_sTableAlias;
	}

	public function DisplayHtml()
	{
		if (count($this->m_aFields) == 0) $sFields = "";
		else
		{
			$aFieldDesc = array();
			foreach ($this->m_aFields as $sAlias => $oExpression)
			{
				$aFieldDesc[] = $oExpression->RenderExpression(false)." as <em>$sAlias</em>";
			}
			$sFields = " =&gt; ".implode(', ', $aFieldDesc);
		}
		echo "<b>$this->m_sTable</b>$sFields<br/>\n";
		// #@# todo - display html of an expression tree
		//$this->m_oConditionExpr->DisplayHtml()
		if (count($this->m_aJoinSelects) > 0)
		{
			echo "Joined to...<br/>\n";
			echo "<ul class=\"treeview\">\n";
			foreach ($this->m_aJoinSelects as $aJoinInfo)
			{
				$sJoinType = $aJoinInfo["jointype"];
				$oSQLQuery = $aJoinInfo["select"];
				if (isset($aJoinInfo["on_expression"]))
				{
					$sOnCondition = $aJoinInfo["on_expression"]->RenderExpression(false);

					echo "<li>Join '$sJoinType', ON ($sOnCondition)".$oSQLQuery->DisplayHtml()."</li>\n";
				}
				else
				{
					$sLeftField = $aJoinInfo["leftfield"];
					$sRightField = $aJoinInfo["rightfield"];
					$sRightTableAlias = $aJoinInfo["righttablealias"];
	
					echo "<li>Join '$sJoinType', $sLeftField, $sRightTableAlias.$sRightField".$oSQLQuery->DisplayHtml()."</li>\n";
				}
			}
			echo "</ul>";
		}
		$this->PrepareRendering();
		echo "From ...<br/>\n";
		echo "<pre style=\"font-size: smaller;\">\n";
		print_r($this->__aFrom);
		echo "</pre>";
	}

	public function SetSelect($aExpressions)
	{
		$this->m_aFields = $aExpressions;
	}

	public function SortSelectedFields()
	{
		ksort($this->m_aFields);
	}

	public function AddSelect($sAlias, $oExpression)
	{
		$this->m_aFields[$sAlias] = $oExpression;
	}

	public function SetGroupBy($aExpressions)
	{
		$this->m_aGroupBy = $aExpressions;
	}

	public function SetCondition($oConditionExpr)
	{
		$this->m_oConditionExpr = $oConditionExpr;
	}

	public function AddCondition($oConditionExpr)
	{
		if (is_null($this->m_oConditionExpr))
		{
			$this->m_oConditionExpr = $oConditionExpr;
		}
		else
		{
			$this->m_oConditionExpr = $this->m_oConditionExpr->LogAnd($oConditionExpr);
		}
	}

	private function AddJoin($sJoinType, $oSQLQuery, $sLeftField, $sRightField, $sRightTableAlias = '')
	{
		assert((get_class($oSQLQuery) == __CLASS__) || is_subclass_of($oSQLQuery, __CLASS__));
		// No need to check this here but for development purposes
		//if (!CMDBSource::IsField($this->m_sTable, $sLeftField))
		//{
		//	throw new CoreException("Unknown field '$sLeftField' in table '".$this->m_sTable);
		//}

		if (empty($sRightTableAlias))
		{
			$sRightTableAlias = $oSQLQuery->m_sTableAlias;
		}
// #@# Could not be verified here because the namespace is unknown - do we need to check it there?
//
//		if (!CMDBSource::IsField($sRightTable, $sRightField))
//		{
//			throw new CoreException("Unknown field '$sRightField' in table '".$sRightTable."'");
//		}
		$this->m_aJoinSelects[] = array(
			"jointype" => $sJoinType,
			"select" => $oSQLQuery,
			"leftfield" => $sLeftField,
			"rightfield" => $sRightField,
			"righttablealias" => $sRightTableAlias
		);
	}
	public function AddInnerJoin($oSQLQuery, $sLeftField, $sRightField, $sRightTable = '')
	{
		$this->AddJoin("inner", $oSQLQuery, $sLeftField, $sRightField, $sRightTable);
	}
	public function AddInnerJoinTree($oSQLQuery, $sLeftFieldLeft, $sLeftFieldRight, $sRightFieldLeft, $sRightFieldRight, $sRightTableAlias = '', $iOperatorCode = TREE_OPERATOR_BELOW, $bInvertOnClause = false)
	{
		assert((get_class($oSQLQuery) == __CLASS__) || is_subclass_of($oSQLQuery, __CLASS__));
		if (empty($sRightTableAlias))
		{
			$sRightTableAlias = $oSQLQuery->m_sTableAlias;
		}
		$this->m_aJoinSelects[] = array(
			"jointype" => 'inner_tree',
			"select" => $oSQLQuery,
			"leftfield" => $sLeftFieldLeft,
			"rightfield" => $sLeftFieldRight,
			"rightfield_left" => $sRightFieldLeft,
			"rightfield_right" => $sRightFieldRight,
			"righttablealias" => $sRightTableAlias,
			"tree_operator" => $iOperatorCode,
			'invert_on_clause' => $bInvertOnClause
		);
	}
	public function AddLeftJoin($oSQLQuery, $sLeftField, $sRightField)
	{
		return $this->AddJoin("left", $oSQLQuery, $sLeftField, $sRightField);
	}

	public function AddInnerJoinEx(SQLQuery $oSQLQuery, Expression $oOnExpression)
	{
		$this->m_aJoinSelects[] = array(
			"jointype" => 'inner',
			"select" => $oSQLQuery,
			"on_expression" => $oOnExpression
		);
	}

	public function AddLeftJoinEx(SQLQuery $oSQLQuery, Expression $oOnExpression)
	{
		$this->m_aJoinSelects[] = array(
			"jointype" => 'left',
			"select" => $oSQLQuery,
			"on_expression" => $oOnExpression
		);
	}
	
	// Interface, build the SQL query

	/**
	 * @param array $aArgs
	 * @return string
	 * @throws CoreException
	 */
	public function RenderDelete($aArgs = array())
	{
		$this->PrepareRendering();

		// Target: DELETE myAlias1, myAlias2 FROM t1 as myAlias1, t2 as myAlias2, t3 as topreserve WHERE ...

		$sDelete = self::ClauseDelete($this->__aDelTables);
		$sFrom   = self::ClauseFrom($this->__aFrom);
		// #@# safety net to redo ?
		/*
		if ($this->m_oConditionExpr->IsAny())
		-- if (count($aConditions) == 0) --
		{
			throw new CoreException("Building a request wich will delete every object of a given table -looks suspicious- please use truncate instead...");
		}
		*/
		if (is_null($this->m_oConditionExpr))
		{
			// Delete all !!!
		}
		else
		{
			$sWhere  = self::ClauseWhere($this->m_oConditionExpr, $aArgs);
			return "DELETE $sDelete FROM $sFrom WHERE $sWhere";
		}
		return '';
	}

	/**
	 *	Needed for the unions
	 */
	public function RenderSelectClause()
	{
		$this->PrepareRendering();
		$sSelect = self::ClauseSelect($this->__aFields);
		return $sSelect;
	}

	/**
	 *    Needed for the unions
	 * @param $aOrderBy
	 * @return string
	 * @throws CoreException
	 */
	public function RenderOrderByClause($aOrderBy)
	{
		$this->PrepareRendering();
		$sOrderBy = self::ClauseOrderBy($aOrderBy, $this->__aFields);
		return $sOrderBy;
	}

	// Interface, build the SQL query

	/**
	 * @param array $aArgs
	 * @return string
	 * @throws CoreException
	 */
	public function RenderUpdate($aArgs = array())
	{
		$this->PrepareRendering();
		$sFrom   = self::ClauseFrom($this->__aFrom);
		$sValues = self::ClauseValues($this->__aSetValues);
		$sWhere  = self::ClauseWhere($this->m_oConditionExpr, $aArgs);
		return "UPDATE $sFrom SET $sValues WHERE $sWhere";
	}


	/**
	 * Generate an INSERT statement.
	 * Note : unlike `RenderUpdate` and `RenderSelect`, it is limited to one and only one table.
	 *
	 *
	 * @param array $aArgs
	 * @return string
	 * @throws CoreException
	 */
	public function RenderInsert($aArgs = array())
	{
		$this->PrepareRendering();
		$aJoinInfo = reset($this->__aFrom);

		if ($aJoinInfo['jointype'] != 'first' || count($this->__aFrom) > 1)
		{
			throw new CoreException('Cannot render insert');
		}

		$sFrom   = "`{$aJoinInfo['tablename']}`";

		$sColList = '`'.implode('`,`', array_keys($this->m_aValues)).'`';

		$aSetValues = array();
		foreach ($this->__aSetValues as $sFieldSpec => $value)
		{
			$aSetValues[] = CMDBSource::Quote($value);
		}
		$sValues = implode(',', $aSetValues);

		return "INSERT INTO $sFrom ($sColList) VALUES  ($sValues)";
	}


	/**
	 * @param array $aOrderBy
	 * @param array $aArgs
	 * @param int $iLimitCount
	 * @param int $iLimitStart
	 * @param bool $bGetCount
	 * @param bool $bBeautifulQuery
	 * @return string
	 * @throws CoreException
	 */
	public function RenderSelect($aOrderBy = array(), $aArgs = array(), $iLimitCount = 0, $iLimitStart = 0, $bGetCount = false, $bBeautifulQuery = false)
	{
		$this->m_bBeautifulQuery = $bBeautifulQuery;
		$sLineSep = $this->m_bBeautifulQuery ? "\n" : '';
		$sIndent = $this->m_bBeautifulQuery ? "   " : null;

		$this->PrepareRendering();
		$sFrom   = self::ClauseFrom($this->__aFrom, $sIndent);
		$sWhere  = self::ClauseWhere($this->m_oConditionExpr, $aArgs);
		// Sanity
		$iLimitCount = (int)$iLimitCount;
		if ($iLimitCount > 0)
		{
			// Sanity
			$iLimitStart = (int)$iLimitStart;
			$sLimit = 'LIMIT '.$iLimitStart.', '.$iLimitCount;
		}
		else
		{
			$sLimit = '';
		}
		if ($bGetCount)
		{
			if (count($this->__aSelectedIdFields) > 0)
			{
				$aCountFields = [];
				$aCountI = [];
				$i = 0;
				foreach ($this->__aSelectedIdFields as $sFieldExpr) {
					$aCountFields[] = "COALESCE($sFieldExpr, 0) AS idCount$i"; // Null values are excluded from the count
					$aCountI[] = 'idCount'.$i++;
				}
				$sCountFields = implode(', ', $aCountFields);
				$sCountI = implode('+ ', $aCountI);
				// Count can be limited for performance reason, in this case the total amount is not important,
				// we only need to know if the number of entries is greater than a certain amount.
				$sSQL = "SELECT COUNT(*) AS COUNT FROM (SELECT$sLineSep DISTINCT $sCountFields $sLineSep FROM $sFrom$sLineSep WHERE $sWhere $sLimit) AS _alderaan_ WHERE $sCountI>0";
			}
			else
			{
				$sSQL = "SELECT COUNT(*) AS COUNT FROM (SELECT$sLineSep 1 $sLineSep FROM $sFrom$sLineSep WHERE $sWhere $sLimit) AS _alderaan_";
			}
		}
		else
		{
			$sSelect = self::ClauseSelect($this->__aFields, $sLineSep);
			$sOrderBy = self::ClauseOrderBy($aOrderBy, $this->__aFields);
			if (!empty($sOrderBy))
			{
				$sOrderBy = "ORDER BY $sOrderBy$sLineSep";
			}

			$sSQL = "SELECT$sLineSep DISTINCT $sSelect$sLineSep FROM $sFrom$sLineSep WHERE $sWhere$sLineSep $sOrderBy $sLimit";
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
		$sIndent = $this->m_bBeautifulQuery ? "   " : null;

		$this->PrepareRendering();

		$sSelect = self::ClauseSelect($this->__aFields);
		$sFrom   = self::ClauseFrom($this->__aFrom, $sIndent);
		$sWhere  = self::ClauseWhere($this->m_oConditionExpr, $aArgs);
		$sGroupBy = self::ClauseGroupBy($this->__aGroupBy);
		$sOrderBy = self::ClauseOrderBy($aOrderBy, $this->__aFields);
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
		if (count($this->__aSelectedIdFields) > 0)
		{
			$aCountFields = array();
			foreach ($this->__aSelectedIdFields as $sFieldExpr)
			{
				$aCountFields[] = "COALESCE($sFieldExpr, 0)"; // Null values are excluded from the count
			}
			$sCountFields = implode(', ', $aCountFields);
			$sCountClause = "DISTINCT $sCountFields";
		}
		else
		{
			$sCountClause = '*';
		}
		$sSQL = "SELECT $sSelect,$sLineSep COUNT($sCountClause) AS _itop_count_$sLineSep FROM $sFrom$sLineSep WHERE $sWhere$sLineSep $sGroupBy $sOrderBy$sLineSep $sLimit";
		return $sSQL;
	}

	// Purpose: prepare the query data, once for all
	private function PrepareRendering()
	{
		if (is_null($this->__aFrom))
		{
			$this->__aFrom = array();
			$this->__aFields = array();
			$this->__aGroupBy = array();
			$this->__aDelTables = array();
			$this->__aSetValues = array();
			$this->__aSelectedIdFields = array();
	
			$this->PrepareSingleTable($this, $this->__aFrom, '', array('jointype' => 'first'));
		}
	}

	/**
	 * @param \SQLObjectQuery $oRootQuery
	 * @param $aFrom
	 * @param $sCallerAlias
	 * @param $aJoinData
	 *
	 * @return string
	 *
	 * @since 2.7.7 3.0.1 3.1.0 N°3129 Remove default value for $sCallerAlias for PHP 8.0 compat (Private method with only 2 calls in the class, both providing the optional parameter)
	 */
	private function PrepareSingleTable(SQLObjectQuery $oRootQuery, &$aFrom, $sCallerAlias, $aJoinData)
	{
		$aTranslationTable[$this->m_sTable]['*'] = $this->m_sTableAlias;
		$sJoinCond = '';

		// Handle the various kinds of join (or first table in the list)
		//
		if (empty($aJoinData['righttablealias']))
		{
			$sRightTableAlias = $this->m_sTableAlias;
		}
		else
		{
			$sRightTableAlias = $aJoinData['righttablealias'];
		}
		switch ($aJoinData['jointype'])
		{
			case "first":
				$aFrom[$this->m_sTableAlias] = array("jointype"=>"first", "tablename"=>$this->m_sTable, "joincondition"=>"");
				break;
			case "inner":
			case "left":
				if (isset($aJoinData["on_expression"]))
				{
					$sJoinCond = $aJoinData["on_expression"]->RenderExpression(true);
				}
				else
				{
					$sJoinCond = "`$sCallerAlias`.`{$aJoinData['leftfield']}` = `$sRightTableAlias`.`{$aJoinData['rightfield']}`";
				}
				$aFrom[$this->m_sTableAlias] = array("jointype"=>$aJoinData['jointype'], "tablename"=>$this->m_sTable, "joincondition"=>"$sJoinCond");
				break;
			case "inner_tree":
				if ($aJoinData['invert_on_clause'])
				{
					$sRootLeft = "`$sCallerAlias`.`{$aJoinData['leftfield']}`";
					$sRootRight = "`$sCallerAlias`.`{$aJoinData['rightfield']}`";
					$sNodeLeft = "`$sRightTableAlias`.`{$aJoinData['rightfield_left']}`";
					$sNodeRight = "`$sRightTableAlias`.`{$aJoinData['rightfield_right']}`";
				}
				else
				{
					$sNodeLeft = "`$sCallerAlias`.`{$aJoinData['leftfield']}`";
					$sNodeRight = "`$sCallerAlias`.`{$aJoinData['rightfield']}`";
					$sRootLeft = "`$sRightTableAlias`.`{$aJoinData['rightfield_left']}`";
					$sRootRight = "`$sRightTableAlias`.`{$aJoinData['rightfield_right']}`";
				}
				switch($aJoinData['tree_operator'])
				{
					case TREE_OPERATOR_BELOW:
					$sJoinCond = "$sNodeLeft >= $sRootLeft AND $sNodeLeft <= $sRootRight";
					break;
					
					case TREE_OPERATOR_BELOW_STRICT:
					$sJoinCond = "$sNodeLeft > $sRootLeft AND $sNodeLeft < $sRootRight";
					break;
					
					case TREE_OPERATOR_NOT_BELOW: // Complementary of 'BELOW'
					$sJoinCond = "$sNodeLeft < $sRootLeft OR $sNodeLeft > $sRootRight";
					break;
					
					case TREE_OPERATOR_NOT_BELOW_STRICT: // Complementary of BELOW_STRICT
					$sJoinCond = "$sNodeLeft <= $sRootLeft OR $sNodeLeft >= $sRootRight";
					break;

					case TREE_OPERATOR_ABOVE:
					$sJoinCond = "$sNodeLeft <= $sRootLeft AND $sNodeRight >= $sRootRight";
					break;
					
					case TREE_OPERATOR_ABOVE_STRICT:
					$sJoinCond = "$sNodeLeft < $sRootLeft AND $sNodeRight > $sRootRight";
					break;
					
					case TREE_OPERATOR_NOT_ABOVE: // Complementary of 'ABOVE'
					$sJoinCond = "$sNodeLeft > $sRootLeft OR $sNodeRight < $sRootRight";
					break;
					
					case TREE_OPERATOR_NOT_ABOVE_STRICT: // Complementary of ABOVE_STRICT
					$sJoinCond = "$sNodeLeft >= $sRootLeft OR $sNodeRight <= $sRootRight";
					break;
					
				}
				$aFrom[$this->m_sTableAlias] = array("jointype"=>$aJoinData['jointype'], "tablename"=>$this->m_sTable, "joincondition"=>"$sJoinCond");
				break;
		}

		// Given the alias, modify the fields and conditions
		// before adding them into the current lists
		//
		foreach($this->m_aFields as $sAlias => $oExpression)
		{
			$oRootQuery->__aFields["`$sAlias`"] = $oExpression->RenderExpression(true);
		}
		if ($this->m_aGroupBy)
		{
			foreach($this->m_aGroupBy as $sAlias => $oExpression)
			{
				$oRootQuery->__aGroupBy["`$sAlias`"] = $oExpression->RenderExpression(true);
			}
		}
		if ($this->m_bToDelete)
		{
			$oRootQuery->__aDelTables[] = "`{$this->m_sTableAlias}`";
		}
		foreach($this->m_aValues as $sFieldName=>$value)
		{
			$oRootQuery->__aSetValues["`{$this->m_sTableAlias}`.`$sFieldName`"] = $value; // quoted further!
		}

  		if (!is_null($this->m_oSelectedIdField))
  		{
		    $oRootQuery->__aSelectedIdFields[] = $this->m_oSelectedIdField->RenderExpression(true);
		}

		// loop on joins, to complete the list of tables/fields/conditions
		//
		$aTempFrom = array(); // temporary subset of 'from' specs, to be grouped in the final query
		foreach ($this->m_aJoinSelects as $aJoinData)
		{
			/** @var \SQLObjectQuery $oRightSelect */
			$oRightSelect = $aJoinData["select"];

			$oRightSelect->PrepareSingleTable($oRootQuery, $aTempFrom, $this->m_sTableAlias, $aJoinData);
		}
		$aFrom[$this->m_sTableAlias]['subfrom'] = $aTempFrom;

		return $this->m_sTableAlias;
	}

	public function OptimizeJoins($aUsedTables, $bTopCall = true)
	{
		$this->m_iOriginalTableCount = $this->CountTables();
		if ($bTopCall)
		{
			// Top call: complete the list of tables absolutely required to perform the right query
			$this->CollectUsedTables($aUsedTables);
		}

		$aToDiscard = array();
		foreach ($this->m_aJoinSelects as $i => $aJoinInfo)
		{
			$oSQLQuery = $aJoinInfo["select"];
			$sTableAlias = $oSQLQuery->GetTableAlias();
			if ($oSQLQuery->OptimizeJoins($aUsedTables, false) && !array_key_exists($sTableAlias, $aUsedTables))
			{
				$aToDiscard[] = $i;
			}
		}
		foreach ($aToDiscard as $i)
		{
			unset($this->m_aJoinSelects[$i]);
		}

		return (count($this->m_aJoinSelects) == 0);
	}

	public function CountTables()
	{
		$iRet = 1;
		foreach ($this->m_aJoinSelects as $i => $aJoinInfo)
		{
			$oSQLQuery = $aJoinInfo["select"];
			$iRet += $oSQLQuery->CountTables();
		}
		return $iRet;
	}

	public function CollectUsedTables(&$aTables)
	{
		$this->m_oConditionExpr->CollectUsedParents($aTables);
		foreach($this->m_aFields as $sFieldAlias => $oField)
		{
			$oField->CollectUsedParents($aTables);
		}
		if ($this->m_aGroupBy)
		{
			foreach($this->m_aGroupBy as $sAlias => $oExpression)
			{
				$oExpression->CollectUsedParents($aTables);
			}
		}
  		if (!is_null($this->m_oSelectedIdField))
  		{
  			$this->m_oSelectedIdField->CollectUsedParents($aTables);
		}

		foreach ($this->m_aJoinSelects as $i => $aJoinInfo)
		{
			$oSQLQuery = $aJoinInfo["select"];
			if ($oSQLQuery->HasRequiredTables($aTables))
			{
				// There is something required in the branch, then this node is a MUST
				if (isset($aJoinInfo['righttablealias']))
				{
					$aTables[$aJoinInfo['righttablealias']] = true;
				}
				if (isset($aJoinInfo["on_expression"]))
				{
					$aJoinInfo["on_expression"]->CollectUsedParents($aTables);
				}
			}
		}

		return $aTables;
	}

	// Is required in the JOIN, and therefore we must ensure that the join expression will be valid
	protected function HasRequiredTables(&$aTables)
	{
		$bResult = false;
		if (array_key_exists($this->m_sTableAlias, $aTables))
		{
			$bResult = true;
		}
		foreach ($this->m_aJoinSelects as $i => $aJoinInfo)
		{
			$oSQLQuery = $aJoinInfo["select"];
			if ($oSQLQuery->HasRequiredTables($aTables))
			{
				// There is something required in the branch, then this node is a MUST
				if (isset($aJoinInfo['righttablealias']))
				{
					$aTables[$aJoinInfo['righttablealias']] = true;
				}
				if (isset($aJoinInfo["on_expression"]))
				{
					$aJoinInfo["on_expression"]->CollectUsedParents($aTables);
				}
				$bResult = true;
			}
		}
		// None of the tables is in the list of required tables
		return $bResult;
	}

}
