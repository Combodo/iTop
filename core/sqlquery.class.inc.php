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
	private $m_sTable = '';
	private $m_sTableAlias = '';
	private $m_aFields = array();
	private $m_oConditionExpr = null;
	private $m_aFullTextNeedles = array();
	private $m_bToDelete = true; // The current table must be listed for deletion ?
	private $m_aValues = array(); // Values to set in case of an update query
	private $m_aJoinSelects = array();

	public function __construct($sTable, $sTableAlias, $aFields, $aFullTextNeedles = array(), $bToDelete = true, $aValues = array())
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
		$this->m_oConditionExpr = null;
		$this->m_aFullTextNeedles = $aFullTextNeedles;
		$this->m_bToDelete = $bToDelete;
		$this->m_aValues = $aValues;
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
		if (count($this->m_aFullTextNeedles) > 0)
		{
			echo "Full text criteria...<br/>\n";
			echo "<ul class=\"treeview\">\n";
			foreach ($this->m_aFullTextNeedles as $sFTNeedle)
			{
				echo "<li>$sFTNeedle</li>\n";
			}
			echo "</ul>";
		}
		if (count($this->m_aJoinSelects) > 0)
		{
			echo "Joined to...<br/>\n";
			echo "<ul class=\"treeview\">\n";
			foreach ($this->m_aJoinSelects as $aJoinInfo)
			{
				$sJoinType = $aJoinInfo["jointype"];
				$oSQLQuery = $aJoinInfo["select"];
				$sLeftField = $aJoinInfo["leftfield"];
				$sRightField = $aJoinInfo["rightfield"];
				$sRightTableAlias = $aJoinInfo["righttablealias"];

				echo "<li>Join '$sJoinType', $sLeftField, $sRightTableAlias.$sRightField".$oSQLQuery->DisplayHtml()."</li>\n";
			}
			echo "</ul>";
		}
		$aFrom = array();
		$aFields = array();
		$oCondition = null;
		$aDelTables = array();
		$aSetValues = array();
		$this->privRender($aFrom, $aFields, $oCondition, $aDelTables, $aSetValues);
		echo "From ...<br/>\n";
		echo "<pre style=\"font-size: smaller;\">\n";
		print_r($aFrom);
		echo "</pre>";
	}

	public function SetSelect($aExpressions)
	{
		$this->m_aFields = $aExpressions;
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
	public function AddInnerJoin($oSQLQuery, $sLeftField, $sRightField, $sRigthtTable = '')
	{
		$this->AddJoin("inner", $oSQLQuery, $sLeftField, $sRightField, $sRigthtTable);
	}
	public function AddLeftJoin($oSQLQuery, $sLeftField, $sRightField)
	{
		return $this->AddJoin("left", $oSQLQuery, $sLeftField, $sRightField);
	}
	
	// Interface, build the SQL query
	public function RenderDelete($aArgs = array())
	{
		// The goal will be to complete the list as we build the Joins
		$aFrom = array();
		$aFields = array();
		$oCondition = null;
		$aDelTables = array();
		$aSetValues = array();
		$this->privRender($aFrom, $aFields, $oCondition, $aDelTables, $aSetValues);

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
		$oCondition = null;
		$aDelTables = array();
		$aSetValues = array();
		$this->privRender($aFrom, $aFields, $oCondition, $aDelTables, $aSetValues);

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
		$oCondition = null;
		$aDelTables = array();
		$aSetValues = array();
		$this->privRender($aFrom, $aFields, $oCondition, $aDelTables, $aSetValues);

		$sFrom   = self::ClauseFrom($aFrom);
		$sWhere  = self::ClauseWhere($oCondition, $aArgs);
		if ($bGetCount)
		{
			$sSQL = "SELECT COUNT(*) AS COUNT FROM $sFrom WHERE $sWhere";
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
			$aOrderBySpec[] = '`'.$sFieldAlias.'`'.($bAscending ? " ASC" : " DESC");
		}
		$sOrderBy = implode(", ", $aOrderBySpec);
		return $sOrderBy;
	}

	// Purpose: prepare the query data, once for all
	private function privRender(&$aFrom, &$aFields, &$oCondition, &$aDelTables, &$aSetValues)
	{
		$sTableAlias = $this->privRenderSingleTable($aFrom, $aFields, $aDelTables, $aSetValues);

		// Add the full text search condition, based on each and every requested field
		//
		// To be updated with a real full text search based on the mySQL settings
		// (then it might move somewhere else !)
		//
		$oCondition = $this->m_oConditionExpr;
		if ((count($aFields) > 0) && (count($this->m_aFullTextNeedles) > 0))
		{
			$sFields = implode(', ', $aFields);
			$oFullTextExpr = Expression::FromSQL("CONCAT_WS(' ', $sFields)");
			
			// The cast is necessary because the CONCAT result in a binary string:
			// if any of the field is a binary string => case sensitive comparison
			//
			foreach($this->m_aFullTextNeedles as $sFTNeedle)
			{
				$oNewCond = new BinaryExpression($oFullTextExpr, 'LIKE', new ScalarExpression("%$sFTNeedle%"));
				if (is_null($oCondition))
				{
					$oCondition = $oNewCond;
				}
				else
				{
					$oCondition = $oCondition->LogAnd($oNewCond);
				}
			}
		}

		return $sTableAlias; 
	}

	private function privRenderSingleTable(&$aFrom, &$aFields, &$aDelTables, &$aSetValues, $sJoinType = 'first', $sCallerAlias = '', $sLeftField = '', $sRightField = '', $sRightTableAlias = '')
	{
		$aActualTableFields = CMDBSource::GetTableFieldsList($this->m_sTable);

		$aTranslationTable[$this->m_sTable]['*'] = $this->m_sTableAlias;

		// Handle the various kinds of join (or first table in the list)
		//
		if (empty($sRightTableAlias))
		{
			$sRightTableAlias = $this->m_sTableAlias;
		}
		$sJoinCond = "`$sCallerAlias`.`$sLeftField` = `$sRightTableAlias`.`$sRightField`";
		switch ($sJoinType)
		{
			case "first":
				$aFrom[$this->m_sTableAlias] = array("jointype"=>"first", "tablename"=>$this->m_sTable, "joincondition"=>"");
				break;
			case "inner":
			case "left":
			// table or tablealias ???
				$aFrom[$this->m_sTableAlias] = array("jointype"=>$sJoinType, "tablename"=>$this->m_sTable, "joincondition"=>"$sJoinCond");
				break;
		}

		// Given the alias, modify the fields and conditions
		// before adding them into the current lists
		//
		foreach($this->m_aFields as $sAlias => $oExpression)
		{
			$aFields["`$sAlias`"] = $oExpression->Render();
		}
		if ($this->m_bToDelete)
		{
			$aDelTables[] = "`{$this->m_sTableAlias}`";
		}
		foreach($this->m_aValues as $sFieldName=>$value)
		{
			$aSetValues["`{$this->m_sTableAlias}`.`$sFieldName`"] = $value; // quoted further!
		}

		// loop on joins, to complete the list of tables/fields/conditions
		//
		$aTempFrom = array(); // temporary subset of 'from' specs, to be grouped in the final query
		foreach ($this->m_aJoinSelects as $aJoinData)
		{
			$sJoinType = $aJoinData["jointype"];
			$oRightSelect = $aJoinData["select"];
			$sLeftField = $aJoinData["leftfield"];
			$sRightField = $aJoinData["rightfield"];
			$sRightTableAlias = $aJoinData["righttablealias"];

			$sJoinTableAlias = $oRightSelect->privRenderSingleTable($aTempFrom, $aFields, $aDelTables, $aSetValues, $sJoinType, $this->m_sTableAlias, $sLeftField, $sRightField, $sRightTableAlias);
		}
		$aFrom[$this->m_sTableAlias]['subfrom'] = $aTempFrom;

		return $this->m_sTableAlias;
	}

}

?>
