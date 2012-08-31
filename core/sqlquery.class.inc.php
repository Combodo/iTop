<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * SQLQuery
 * build an mySQL compatible SQL query
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


/**
 * SQLQuery
 * build an mySQL compatible SQL query
 *
 * @package     iTopORM
 */

require_once('cmdbsource.class.inc.php');


class SQLQuery
{
	private $m_SourceOQL = '';
	private $m_sTable = '';
	private $m_sTableAlias = '';
	private $m_aFields = array();
	private $m_aGroupBy = array();
	private $m_oConditionExpr = null;
	private $m_bToDelete = true; // The current table must be listed for deletion ?
	private $m_aValues = array(); // Values to set in case of an update query
	private $m_oSelectedIdField = null;
	private $m_aJoinSelects = array();

	public function __construct($sTable, $sTableAlias, $aFields, $bToDelete = true, $aValues = array(), $oSelectedIdField = null)
	{
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

	public function SetSourceOQL($sOQL)
	{
		$this->m_SourceOQL = $sOQL;
	}

	public function GetSourceOQL()
	{
		return $this->m_SourceOQL;
	}

	public function DisplayHtml()
	{
		if (count($this->m_aFields) == 0) $sFields = "";
		else
		{
			$aFieldDesc = array();
			foreach ($this->m_aFields as $sAlias => $oExpression)
			{
				$aFieldDesc[] = $oExpression->Render()." as <em>$sAlias</em>";
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
					$sOnCondition = $aJoinInfo["on_expression"]->Render();

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
		$aFrom = array();
		$aFields = array();
		$aGroupBy = array();
		$oCondition = null;
		$aDelTables = array();
		$aSetValues = array();
		$aSelectedIdFields = array();
		$this->privRender($aFrom, $aFields, $aGroupBy, $oCondition, $aDelTables, $aSetValues, $aSelectedIdFields);
		echo "From ...<br/>\n";
		echo "<pre style=\"font-size: smaller;\">\n";
		print_r($aFrom);
		echo "</pre>";
	}

	public function SetSelect($aExpressions)
	{
		$this->m_aFields = $aExpressions;
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
			$this->m_oConditionExpr->LogAnd($oConditionExpr);
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
	public function AddInnerJoinTree($oSQLQuery, $sLeftFieldLeft, $sLeftFieldRight, $sRightFieldLeft, $sRightFieldRight, $sRightTableAlias = '', $iOperatorCode = TREE_OPERATOR_BELOW)
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
			"tree_operator" => $iOperatorCode);
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
	public function RenderDelete($aArgs = array())
	{
		// The goal will be to complete the list as we build the Joins
		$aFrom = array();
		$aFields = array();
		$aGroupBy = array();
		$oCondition = null;
		$aDelTables = array();
		$aSetValues = array();
		$aSelectedIdFields = array();
		$this->privRender($aFrom, $aFields, $aGroupBy, $oCondition, $aDelTables, $aSetValues, $aSelectedIdFields);

		// Target: DELETE myAlias1, myAlias2 FROM t1 as myAlias1, t2 as myAlias2, t3 as topreserve WHERE ...

		$sDelete = self::ClauseDelete($aDelTables);
		$sFrom   = self::ClauseFrom($aFrom);
		// #@# safety net to redo ?
		/*
		if ($this->m_oConditionExpr->IsAny())
		-- if (count($aConditions) == 0) --
		{
			throw new CoreException("Building a request wich will delete every object of a given table -looks suspicious- please use truncate instead...");
		}
		*/
		if (is_null($oCondition))
		{
			// Delete all !!!
		}
		else
		{
			$sWhere  = self::ClauseWhere($oCondition, $aArgs);
			return "DELETE $sDelete FROM $sFrom WHERE $sWhere";
		}
	}

	// Interface, build the SQL query
	public function RenderUpdate($aArgs = array())
	{
		// The goal will be to complete the list as we build the Joins
		$aFrom = array();
		$aFields = array();
		$aGroupBy = array();
		$oCondition = null;
		$aDelTables = array();
		$aSetValues = array();
		$aSelectedIdFields = array();
		$this->privRender($aFrom, $aFields, $aGroupBy, $oCondition, $aDelTables, $aSetValues, $aSelectedIdFields);
		$sFrom   = self::ClauseFrom($aFrom);
		$sValues = self::ClauseValues($aSetValues);
		$sWhere  = self::ClauseWhere($oCondition, $aArgs);
		return "UPDATE $sFrom SET $sValues WHERE $sWhere";
	}

	// Interface, build the SQL query
	public function RenderSelect($aOrderBy = array(), $aArgs = array(), $iLimitCount = 0, $iLimitStart = 0, $bGetCount = false)
	{
		// The goal will be to complete the lists as we build the Joins
		$aFrom = array();
		$aFields = array();
		$aGroupBy = array();
		$oCondition = null;
		$aDelTables = array();
		$aSetValues = array();
		$aSelectedIdFields = array();
		$this->privRender($aFrom, $aFields, $aGroupBy, $oCondition, $aDelTables, $aSetValues, $aSelectedIdFields);

		$sFrom   = self::ClauseFrom($aFrom);
		$sWhere  = self::ClauseWhere($oCondition, $aArgs);
		if ($bGetCount)
		{
			if (count($aSelectedIdFields) > 0)
			{
				$sIDFields = implode(', ', $aSelectedIdFields);
				$sSQL = "SELECT COUNT(DISTINCT $sIDFields) AS COUNT FROM $sFrom WHERE $sWhere";
			}
			else
			{
				$sSQL = "SELECT COUNT(*) AS COUNT FROM $sFrom WHERE $sWhere";
			}
		}
		else
		{
			$sSelect = self::ClauseSelect($aFields);
			$sOrderBy = self::ClauseOrderBy($aOrderBy);
			if (!empty($sOrderBy))
			{
				$sOrderBy = "ORDER BY $sOrderBy";
			}
			if ($iLimitCount > 0)
			{
				$sLimit = 'LIMIT '.$iLimitStart.', '.$iLimitCount;
			}
			else
			{
				$sLimit = '';
			}
			$sSQL = "SELECT DISTINCT $sSelect FROM $sFrom WHERE $sWhere $sOrderBy $sLimit";
		}
		return $sSQL;
	}

	// Interface, build the SQL query
	public function RenderGroupBy($aArgs = array())
	{
		// The goal will be to complete the lists as we build the Joins
		$aFrom = array();
		$aFields = array();
		$aGroupBy = array();
		$oCondition = null;
		$aDelTables = array();
		$aSetValues = array();
		$aSelectedIdFields = array();
		$this->privRender($aFrom, $aFields, $aGroupBy, $oCondition, $aDelTables, $aSetValues, $aSelectedIdFields);

		$sSelect = self::ClauseSelect($aFields);
		$sFrom   = self::ClauseFrom($aFrom);
		$sWhere  = self::ClauseWhere($oCondition, $aArgs);
		$sGroupBy = self::ClauseGroupBy($aGroupBy);
		$sSQL = "SELECT $sSelect, COUNT(*) AS _itop_count_ FROM $sFrom WHERE $sWhere GROUP BY $sGroupBy";
		return $sSQL;
	}

	private static function ClauseSelect($aFields)
	{
		$aSelect = array();
		foreach ($aFields as $sFieldAlias => $sSQLExpr)
		{
			$aSelect[] = "$sSQLExpr AS $sFieldAlias";
		}
		$sSelect = implode(', ', $aSelect);
		return $sSelect;
	}

	private static function ClauseGroupBy($aGroupBy)
	{
		$sRes = implode(', ', $aGroupBy);
		return $sRes;
	}

	private static function ClauseDelete($aDelTableAliases)
	{
		$aDelTables = array();
		foreach ($aDelTableAliases as $sTableAlias)
		{
			$aDelTables[] = "$sTableAlias";
		}
		$sDelTables = implode(', ', $aDelTables);
		return $sDelTables;
	}

	private static function ClauseFrom($aFrom)
	{
		$sFrom = "";
		foreach ($aFrom as $sTableAlias => $aJoinInfo)
		{
			switch ($aJoinInfo["jointype"])
			{
				case "first":
					$sFrom .= "`".$aJoinInfo["tablename"]."` AS `$sTableAlias`";
					$sFrom .= " ".self::ClauseFrom($aJoinInfo["subfrom"]);
					break;
				case "inner":
				case "inner_tree":
					$sFrom .= " INNER JOIN (`".$aJoinInfo["tablename"]."` AS `$sTableAlias`";
					$sFrom .= " ".self::ClauseFrom($aJoinInfo["subfrom"]);
					$sFrom .= ") ON ".$aJoinInfo["joincondition"];
					break;
				case "left":
					$sFrom .= " LEFT JOIN (`".$aJoinInfo["tablename"]."` AS `$sTableAlias`";
					$sFrom .= " ".self::ClauseFrom($aJoinInfo["subfrom"]);
					$sFrom .= ") ON ".$aJoinInfo["joincondition"];
					break;
				default:
					throw new CoreException("Unknown jointype: '".$aJoinInfo["jointype"]."'");
			}
		}
		return $sFrom;
	}

	private static function ClauseValues($aValues)
	{
		$aSetValues = array();
		foreach ($aValues as $sFieldSpec => $value)
		{
			$aSetValues[] = "$sFieldSpec = ".CMDBSource::Quote($value);
		}
		$sSetValues = implode(', ', $aSetValues);
		return $sSetValues;
	}

	private static function ClauseWhere($oConditionExpr, $aArgs = array())
	{
		if (is_null($oConditionExpr))
		{
			return '1';
		}
		else
		{
			return $oConditionExpr->Render($aArgs);
		}
	}

	private static function ClauseOrderBy($aOrderBy)
	{
		$aOrderBySpec = array();
		foreach($aOrderBy as $sFieldAlias => $bAscending)
		{
			// Note: sFieldAlias must have backticks around column aliases
			$aOrderBySpec[] = $sFieldAlias.($bAscending ? " ASC" : " DESC");
		}
		$sOrderBy = implode(", ", $aOrderBySpec);
		return $sOrderBy;
	}

	// Purpose: prepare the query data, once for all
	private function privRender(&$aFrom, &$aFields, &$aGroupBy, &$oCondition, &$aDelTables, &$aSetValues, &$aSelectedIdFields)
	{
		$sTableAlias = $this->privRenderSingleTable($aFrom, $aFields, $aGroupBy, $aDelTables, $aSetValues, $aSelectedIdFields, '', array('jointype' => 'first'));
		$oCondition = $this->m_oConditionExpr;
		return $sTableAlias; 
	}

	private function privRenderSingleTable(&$aFrom, &$aFields, &$aGroupBy, &$aDelTables, &$aSetValues, &$aSelectedIdFields, $sCallerAlias = '', $aJoinData)
	{
		$aActualTableFields = CMDBSource::GetTableFieldsList($this->m_sTable);

		$aTranslationTable[$this->m_sTable]['*'] = $this->m_sTableAlias;

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
					$sJoinCond = $aJoinData["on_expression"]->Render();
				}
				else
				{
					$sJoinCond = "`$sCallerAlias`.`{$aJoinData['leftfield']}` = `$sRightTableAlias`.`{$aJoinData['rightfield']}`";
				}
				$aFrom[$this->m_sTableAlias] = array("jointype"=>$aJoinData['jointype'], "tablename"=>$this->m_sTable, "joincondition"=>"$sJoinCond");
				break;
			case "inner_tree":
				$sNodeLeft = "`$sCallerAlias`.`{$aJoinData['leftfield']}`";
				$sNodeRight = "`$sCallerAlias`.`{$aJoinData['rightfield']}`";
				$sRootLeft = "`$sRightTableAlias`.`{$aJoinData['rightfield_left']}`";
				$sRootRight = "`$sRightTableAlias`.`{$aJoinData['rightfield_right']}`";
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
			$aFields["`$sAlias`"] = $oExpression->Render();
		}
		if ($this->m_aGroupBy)
		{
			foreach($this->m_aGroupBy as $sAlias => $oExpression)
			{
				$aGroupBy["`$sAlias`"] = $oExpression->Render();
			}
		}
		if ($this->m_bToDelete)
		{
			$aDelTables[] = "`{$this->m_sTableAlias}`";
		}
//echo "<p>in privRenderSingleTable this->m_aValues<pre>".print_r($this->m_aValues, true)."</pre></p>\n";	
		foreach($this->m_aValues as $sFieldName=>$value)
		{
			$aSetValues["`{$this->m_sTableAlias}`.`$sFieldName`"] = $value; // quoted further!
		}

  		if (!is_null($this->m_oSelectedIdField))
  		{
  			$aSelectedIdFields[] = $this->m_oSelectedIdField->Render();
		}

		// loop on joins, to complete the list of tables/fields/conditions
		//
		$aTempFrom = array(); // temporary subset of 'from' specs, to be grouped in the final query
		foreach ($this->m_aJoinSelects as $aJoinData)
		{
			$oRightSelect = $aJoinData["select"];

			$sJoinTableAlias = $oRightSelect->privRenderSingleTable($aTempFrom, $aFields, $aGroupBy, $aDelTables, $aSetValues, $aSelectedIdFields, $this->m_sTableAlias, $aJoinData);
		}
		$aFrom[$this->m_sTableAlias]['subfrom'] = $aTempFrom;

		return $this->m_sTableAlias;
	}

}

?>
