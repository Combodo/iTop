<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * A union of DBObjectSearches
 *
 * This search class represent an union over a collection of DBObjectSearch.
 * For clarity purpose, since only the constructor vary between DBObjectSearch and DBUnionSearch, all the API is documented on the common ancestor: DBSearch
 * Please refer to DBSearch's documentation
 *
 * @copyright   Copyright (C) 2015-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 *
 *
 * @package     iTopORM
 * @phpdoc-tuning-exclude-inherited this tag prevent PHPdoc from displaying inherited methods. This is done in order to force the API doc. location into DBSearch only.
 * @api
 * @see DBSearch
 * @see DBObjectSearch
 */
class DBUnionSearch extends DBSearch
{
	protected $aSearches; // source queries
	protected $aSelectedClasses; // alias => classes (lowest common ancestors) computed at construction
	protected $aColumnToAliases;
    /**
     * DBUnionSearch constructor.
     *
     * @api
     *
     * @param $aSearches
     *
     * @throws CoreException
     */
	public function __construct($aSearches)
	{
		if (count ($aSearches) == 0)
		{
			throw new CoreException('A DBUnionSearch must be made of at least one search');
		}

		$this->aSearches = array();
		foreach ($aSearches as $oSearch)
		{
			if ($oSearch instanceof DBUnionSearch)
			{
				foreach ($oSearch->aSearches as $oSubSearch)
				{
					$this->aSearches[] = $oSubSearch->DeepClone();
				}
			} else {
				$this->aSearches[] = $oSearch->DeepClone();
			}
		}

		$this->ComputeSelectedClasses();
	}

	public function AllowAllData($bAllowAllData = true)
	{
		foreach ($this->aSearches as $oSearch) {
			$oSearch->AllowAllData();
		}
	}

	public function IsAllDataAllowed()
	{
		foreach ($this->aSearches as $oSearch) {
			if ($oSearch->IsAllDataAllowed() === false) return false;
		}
		return true;
	}

	public function SetArchiveMode($bEnable)
	{
		foreach ($this->aSearches as $oSearch)
		{
			$oSearch->SetArchiveMode($bEnable);
		}
		parent::SetArchiveMode($bEnable);
	}

	public function SetShowObsoleteData($bShow)
	{
		foreach ($this->aSearches as $oSearch)
		{
			$oSearch->SetShowObsoleteData($bShow);
		}
		parent::SetShowObsoleteData($bShow);
	}

	/**
	 * Find the lowest common ancestor for each of the selected class
	 *
	 * @throws \Exception
	 */
	protected function ComputeSelectedClasses()
	{
		// 1 - Collect all the column/classes
		$aColumnToClasses = [];
		$this->aColumnToAliases = [];
		foreach ($this->aSearches as $iPos => $oSearch)
		{
			$aSelected = array_values($oSearch->GetSelectedClasses());

			if ($iPos != 0)
			{
				if (count($aSelected) < count($aColumnToClasses))
				{
					throw new Exception('Too few selected classes in the subquery #'.($iPos+1));
				}
				if (count($aSelected) > count($aColumnToClasses))
				{
					throw new Exception('Too many selected classes in the subquery #'.($iPos+1));
				}
			}

			foreach ($aSelected as $iColumn => $sClass)
			{
				$aColumnToClasses[$iColumn][$iPos] = $sClass;
			}

			// Store the aliases by column to map them later (the first query impose the aliases)
			$aAliases = array_keys($oSearch->GetSelectedClasses());
			foreach ($aAliases as $iColumn => $sAlias)
			{
				$this->aColumnToAliases[$iColumn][$iPos] = $sAlias;
			}
		}

		// 2 - Build the index column => alias
		$oFirstSearch = $this->aSearches[0];
		$aColumnToAlias = array_keys($oFirstSearch->GetSelectedClasses());

		// 3 - Compute alias => lowest common ancestor
		$this->aSelectedClasses = [];
		foreach ($aColumnToClasses as $iColumn => $aClasses)
		{
			$sAlias = $aColumnToAlias[$iColumn];
			$sAncestor = MetaModel::GetLowestCommonAncestor($aClasses);
			if (is_null($sAncestor))
			{
				throw new Exception('Could not find a common ancestor for the column '.($iColumn+1).' (Classes: '.implode(', ', $aClasses).')');
			}
			$this->aSelectedClasses[$sAlias] = $sAncestor;
		}
	}

	public function GetSearches()
	{
		return $this->aSearches;
	}

	public function GetFirstJoinedClass()
	{
		return $this->GetClass();
	}

	/**
	 * Limited to the selected classes
	 */
	public function GetClassName($sAlias)
	{
		if (array_key_exists($sAlias, $this->aSelectedClasses))
		{
			return $this->aSelectedClasses[$sAlias];
		}
		else
		{
			throw new CoreException("Invalid class alias '$sAlias'");
		}
	}

	public function GetClass()
	{
		return reset($this->aSelectedClasses);
	}

	public function GetClassAlias()
	{
		reset($this->aSelectedClasses);
		return key($this->aSelectedClasses);
	}


	/**
	 * Change the class (only subclasses are supported as of now, because the conditions must fit the new class)
	 * Defaults to the first selected class
	 * Only the selected classes can be changed
	 */	 	
	public function ChangeClass($sNewClass, $sAlias = null)
	{
		if (is_null($sAlias))
		{
			$sAlias = $this->GetClassAlias();
		}
		elseif (!array_key_exists($sAlias, $this->aSelectedClasses))
		{
			// discard silently - necessary when recursing (??? copied from DBObjectSearch)
			return;
		}

		// 1 - identify the impacted column
		$iColumn = array_search($sAlias, array_keys($this->aSelectedClasses));

		// 2 - change for each search
		foreach ($this->aSearches as $oSearch)
		{
			$aSearchAliases = array_keys($oSearch->GetSelectedClasses());
			$sSearchAlias = $aSearchAliases[$iColumn];
			$oSearch->ChangeClass($sNewClass, $sSearchAlias);
		}

		// 3 - record the change
		$this->aSelectedClasses[$sAlias] = $sNewClass;
	}

	public function GetSelectedClasses()
	{
		return $this->aSelectedClasses;
	}

	/**
	 * Set the selected classes for this query.
	 * The selected classes can be either in the selected classes of all the queries,
	 * or they should exist in all the sub-queries of the union.
	 *
	 * @param array $aSelectedClasses array of aliases
	 *
	 * @throws \Exception
	 */
	public function SetSelectedClasses($aSelectedClasses)
	{
		// Get the columns corresponding the given aliases
		$aSelectedColumns = [];
		$oFirstSearch = $this->aSearches[0];
		$aAliasesToColumn = array_flip(array_keys($oFirstSearch->GetSelectedClasses()));
		foreach ($aSelectedClasses as $sSelectedAlias) {
			if (!isset($aAliasesToColumn[$sSelectedAlias])) {
				// The selected class is not in the selected classes of the union,
				// try to delegate the feature to the sub-queries
				$aSelectedColumns = [];
				break;
			}
			$aSelectedColumns[] = $aAliasesToColumn[$sSelectedAlias];
		}

		// 1 - change for each search
		foreach ($this->aSearches as $iPos => $oSearch) {
			$aCurrentSelectedAliases = [];
			if (count($aSelectedColumns) === 0) {
				// Default to the list of aliases given
				$aCurrentSelectedAliases = $aSelectedClasses;
			} else {
				// Map the aliases for each query
				foreach ($aSelectedColumns as $iColumn) {
					$aCurrentSelectedAliases[] = $this->aColumnToAliases[$iColumn][$iPos];
				}
			}

			// Throws an exception if not valid
			$oSearch->SetSelectedClasses($aCurrentSelectedAliases);
		}

		// 2 - update the lowest common ancestors
		$this->ComputeSelectedClasses();
	}

	/**
	 * Change any alias of the query tree
	 *
	 * @param $sOldName
	 * @param $sNewName
	 * @return bool True if the alias has been found and changed
	 */
	public function RenameAlias($sOldName, $sNewName)
	{
		$bRet = false;
		foreach ($this->aSearches as $oSearch)
		{
			$bRet = $oSearch->RenameAlias($sOldName, $sNewName) || $bRet;
		}
		return $bRet;
	}

	public function RenameAliasesInNameSpace($aClassAliases, $aAliasTranslation = array())
	{
		foreach ($this->aSearches as $oSearch)
		{
			$oSearch->RenameAliasesInNameSpace($aClassAliases, $aAliasTranslation);
		}
	}

	public function TranslateConditions($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true)
	{
		foreach ($this->aSearches as $oSearch)
		{
			$oSearch->TranslateConditions($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
		}
	}



	public function IsAny()
	{
		$bIsAny = true;
		foreach ($this->aSearches as $oSearch)
		{
			if (!$oSearch->IsAny())
			{
				$bIsAny = false;
				break;
			}
		}
		return $bIsAny;
	}

	public function ResetCondition()
	{
		foreach ($this->aSearches as $oSearch)
		{
			$oSearch->ResetCondition();
		}
	}

	public function MergeConditionExpression($oExpression)
	{
		$aAliases = array_keys($this->aSelectedClasses);
		foreach ($this->aSearches as $iSearchIndex => $oSearch)
		{
			$oClonedExpression = $oExpression->DeepClone();
			if ($iSearchIndex != 0)
			{
				foreach (array_keys($oSearch->GetSelectedClasses()) as $iColumn => $sSearchAlias)
				{
					$oClonedExpression->RenameAlias($aAliases[$iColumn], $sSearchAlias);
				}
			}
			$oSearch->MergeConditionExpression($oClonedExpression);
		}
	}

	public function AddConditionExpression($oExpression)
	{
		$aAliases = array_keys($this->aSelectedClasses);
		foreach ($this->aSearches as $iSearchIndex => $oSearch)
		{
			$oClonedExpression = $oExpression->DeepClone();
			if ($iSearchIndex != 0)
			{
				foreach (array_keys($oSearch->GetSelectedClasses()) as $iColumn => $sSearchAlias)
				{
					$oClonedExpression->RenameAlias($aAliases[$iColumn], $sSearchAlias);
				}
			}
			$oSearch->AddConditionExpression($oClonedExpression);
		}
	}

  	public function AddNameCondition($sName)
	{
		foreach ($this->aSearches as $oSearch)
		{
			$oSearch->AddNameCondition($sName);
		}
	}

	public function AddCondition($sFilterCode, $value, $sOpCode = null)
	{
		foreach ($this->aSearches as $oSearch)
		{
			$oSearch->AddCondition($sFilterCode, $value, $sOpCode);
		}
	}

	/**
	 * Specify a condition on external keys or link sets
	 * @param String sAttSpec Can be either an attribute code or extkey->[sAttSpec] or linkset->[sAttSpec] and so on, recursively
	 *                 Example: infra_list->ci_id->location_id->country	 
	 * @param Object value The value to match (can be an array => IN(val1, val2...)
	 * @return void
	 */
	public function AddConditionAdvanced($sAttSpec, $value)
	{
		foreach ($this->aSearches as $oSearch)
		{
			$oSearch->AddConditionAdvanced($sAttSpec, $value);
		}
	}

	public function AddCondition_FullText($sFullText)
	{
		foreach ($this->aSearches as $oSearch)
		{
			$oSearch->AddCondition_FullText($sFullText);
		}
	}

	public function AddCondition_FullTextOnAttributes(array $aAttCodes, $sNeedle)
	{
		foreach ($this->aSearches as $oSearch)
		{
			$oSearch->AddCondition_FullTextOnAttributes($aAttCodes, $sNeedle);
		}
	}

		/**
	 * @param DBObjectSearch $oFilter
	 * @param $sExtKeyAttCode
	 * @param int $iOperatorCode
	 * @param null $aRealiasingMap array of [old-alias][] => <new-alias>, for each alias that has changed (@since 2.7.2)
	 */
	public function AddCondition_PointingTo(DBObjectSearch $oFilter, $sExtKeyAttCode, $iOperatorCode = TREE_OPERATOR_EQUALS, &$aRealiasingMap = null)
	{
		foreach ($this->aSearches as $oSearch)
		{
			$oConditionFilter = $oFilter->DeepClone();
			$oSearch->AddCondition_PointingTo($oConditionFilter, $sExtKeyAttCode, $iOperatorCode, $aRealiasingMap);
		}
	}

	/**
	 * @param DBObjectSearch $oFilter
	 * @param $sForeignExtKeyAttCode
	 * @param int $iOperatorCode
	 * @param null $aRealiasingMap array of [old-alias][] => <new-alias>, for each alias that has changed (@since 2.7.2)
	 */
	public function AddCondition_ReferencedBy(DBObjectSearch $oFilter, $sForeignExtKeyAttCode, $iOperatorCode = TREE_OPERATOR_EQUALS, &$aRealiasingMap = null)
	{
		foreach ($this->aSearches as $oSearch)
		{
			$oConditionFilter = $oFilter->DeepClone();
			$oSearch->AddCondition_ReferencedBy($oConditionFilter, $sForeignExtKeyAttCode, $iOperatorCode, $aRealiasingMap);
		}
	}

	public function Filter($sClassAlias, DBSearch $oFilter)
	{
		$aSearches = array();
		foreach ($this->aSearches as $oSearch)
		{
			if (!$oSearch->IsAllDataAllowed()) {
				$aSearches[] = $oSearch->Filter($sClassAlias, $oFilter);
			} else {
				$aSearches[] = $oSearch;
			}
		}
		return new DBUnionSearch($aSearches);
	}

	public function Intersect(DBSearch $oFilter)
	{
		$aSearches = array();
		foreach ($this->aSearches as $oSearch)
		{
			$aSearches[] = $oSearch->Intersect($oFilter);
		}
		return new DBUnionSearch($aSearches);
	}

	public function SetInternalParams($aParams)
	{
		foreach ($this->aSearches as $oSearch)
		{
			$oSearch->SetInternalParams($aParams);
		}
	}

	public function GetInternalParams()
	{
		$aParams = array();
		foreach ($this->aSearches as $oSearch)
		{
			$aParams = array_merge($oSearch->GetInternalParams(), $aParams);
		}
		return $aParams;
	}

	public function GetQueryParams($bExcludeMagicParams = true)
	{
		$aParams = array();
		foreach ($this->aSearches as $oSearch)
		{
			$aParams = array_merge($oSearch->GetQueryParams($bExcludeMagicParams), $aParams);
		}
		return $aParams;
	}

	public function ListConstantFields()
	{
		// Somewhat complex to implement for unions, for a poor benefit
		return array();
	}

	/**
	 * Turn the parameters (:xxx) into scalar values in order to easily
	 * serialize a search
	 */
	public function ApplyParameters($aArgs)
	{
		foreach ($this->aSearches as $oSearch)
		{
			$oSearch->ApplyParameters($aArgs);
		}
	}

	/**
	 * Overloads for query building
	 */ 
	public function ToOQL($bDevelopParams = false, $aContextParams = null, $bWithAllowAllFlag = false)
	{
		$aSubQueries = array();
		foreach ($this->aSearches as $oSearch)
		{
			$aSubQueries[] = $oSearch->ToOQL($bDevelopParams, $aContextParams, $bWithAllowAllFlag);
		}
		$sRet = implode(' UNION ', $aSubQueries);
		return $sRet;
	}

	/**
	 * {@inheritDoc}
	 * @see DBSearch::ToJSON()
	 */
	public function ToJSON()
	{
		$sRet = array('unions' => array());
		foreach ($this->aSearches as $oSearch)
		{
			$sRet['unions'][] = $oSearch->ToJSON();
		}
		return $sRet;
	}

	/**
	 * Returns a new DBUnionSearch object where duplicates queries have been removed based on their OQLs
	 *
	 * @return \DBUnionSearch
	 * @throws \CoreException
	 */
	public function RemoveDuplicateQueries()
	{
		$aQueries = array();
		$aSearches = array();

		foreach ($this->GetSearches() as $oTmpSearch)
		{
			$sQuery = $oTmpSearch->ToOQL(true);
			if (!in_array($sQuery, $aQueries))
			{
				$aQueries[] = $sQuery;
				$aSearches[] = $oTmpSearch;
			}
		}

		$oNewSearch = new DBUnionSearch($aSearches);
		
		return $oNewSearch;
	}

	////////////////////////////////////////////////////////////////////////////
	//
	// Construction of the SQL queries
	//
	////////////////////////////////////////////////////////////////////////////

	public function MakeDeleteQuery($aArgs = array())
	{
		throw new Exception('MakeDeleteQuery is not implemented for the unions!');
	}

	public function MakeUpdateQuery($aValues, $aArgs = array())
	{
		throw new Exception('MakeUpdateQuery is not implemented for the unions!');
	}

	public function GetSQLQueryStructure($aAttToLoad, $bGetCount, $aGroupByExpr = null, $aSelectedClasses = null, $aSelectExpr = null)
	{
		if (count($this->aSearches) == 1)
		{
			return $this->aSearches[0]->GetSQLQueryStructure($aAttToLoad, $bGetCount, $aGroupByExpr, $aSelectedClasses, $aSelectExpr);
		}

		$aSQLQueries = array();
		$aAliases = array_keys($this->aSelectedClasses);
		$aQueryAttToLoad = null;
		$aUnionQuerySelectExpr = array();
		foreach ($this->aSearches as $iSearch => $oSearch)
		{
			$aSearchAliases = array_keys($oSearch->GetSelectedClasses());

			// The selected classes from the query build perspective are the lowest common ancestors amongst the various queries
			// (used when it comes to determine which attributes must be selected)
			$aSearchSelectedClasses = array();
			foreach ($aSearchAliases as $iColumn => $sSearchAlias)
			{
				$sAlias = $aAliases[$iColumn];
				$aSearchSelectedClasses[$sSearchAlias] = $this->aSelectedClasses[$sAlias];
			}

			if ($bGetCount)
			{
				// Select only ids for the count to allow optimization of joins
				foreach($aSearchAliases as $sSearchAlias)
				{
					$aQueryAttToLoad[$sSearchAlias] = array();
				}
			}
			else
			{
				if (is_null($aAttToLoad))
				{
					$aQueryAttToLoad = null;
				}
				else
				{
					// (Eventually) Transform the aliases
					$aQueryAttToLoad = array();
					foreach($aAttToLoad as $sAlias => $aAttributes)
					{
						$iColumn = array_search($sAlias, $aAliases);
						$sQueryAlias = ($iColumn === false) ? $sAlias : $aSearchAliases[$iColumn];
						$aQueryAttToLoad[$sQueryAlias] = $aAttributes;
					}
				}
			}

			if (is_null($aGroupByExpr))
			{
				$aQueryGroupByExpr = null;
			}
			else
			{
				// Clone (and eventually transform) the group by expressions
				$aQueryGroupByExpr = array();
				$aTranslationData = array();
				$aQueryColumns = array_keys($oSearch->GetSelectedClasses());
				foreach ($aAliases as $iColumn => $sAlias)
				{
					$sQueryAlias = $aQueryColumns[$iColumn];
					$aTranslationData[$sAlias]['*'] = $sQueryAlias;
					$aQueryGroupByExpr[$sAlias.'id'] = new FieldExpression('id', $sQueryAlias);
				}
				foreach ($aGroupByExpr as $sExpressionAlias => $oExpression)
				{
					$aQueryGroupByExpr[$sExpressionAlias] = $oExpression->Translate($aTranslationData, false, false);
				}
			}

			if (is_null($aSelectExpr))
			{
				$aQuerySelectExpr = null;
			}
			else
			{
				$aQuerySelectExpr = array();
				$aTranslationData = array();
				$aQueryColumns = array_keys($oSearch->GetSelectedClasses());
				foreach($aAliases as $iColumn => $sAlias)
				{
					$sQueryAlias = $aQueryColumns[$iColumn];
					$aTranslationData[$sAlias]['*'] = $sQueryAlias;
				}
				foreach($aSelectExpr as $sExpressionAlias => $oExpression)
				{
					$oExpression->Browse(function ($oNode) use (&$aQuerySelectExpr, &$aTranslationData)
					{
						if ($oNode instanceof FieldExpression)
						{
							$sAlias = $oNode->GetParent()."__".$oNode->GetName();
							if (!key_exists($sAlias, $aQuerySelectExpr))
							{
								$aQuerySelectExpr[$sAlias] = $oNode->Translate($aTranslationData, false, false);
							}
							$aTranslationData[$oNode->GetParent()][$oNode->GetName()] = new FieldExpression($sAlias);
						}
					});
					// Only done for the first select as aliases are named after the first query
					if (!array_key_exists($sExpressionAlias, $aUnionQuerySelectExpr))
					{
						$aUnionQuerySelectExpr[$sExpressionAlias] = $oExpression->Translate($aTranslationData, false, false);
					}
				}
			}
			$oSubQuery = $oSearch->GetSQLQueryStructure($aQueryAttToLoad, false, $aQueryGroupByExpr, $aSearchSelectedClasses, $aQuerySelectExpr);
			if (count($aSearchAliases) > 1)
			{
				// Necessary to make sure that selected columns will match throughout all the queries
				// (default order of selected fields depending on the order of JOINS)
				$oSubQuery->SortSelectedFields();
			}
			$aSQLQueries[] = $oSubQuery;
		}

		$oSQLQuery = new SQLUnionQuery($aSQLQueries, $aGroupByExpr, $aUnionQuerySelectExpr);
		//MyHelpers::var_dump_html($oSQLQuery, true);
		//MyHelpers::var_dump_html($oSQLQuery->RenderSelect(), true);
		if (self::$m_bDebugQuery) $oSQLQuery->DisplayHtml();
		return $oSQLQuery;
	}

	protected function IsDataFiltered()
	{
		$bIsAllDataFiltered = true;
		foreach ($this->aSearches as $oSearch)
		{
			if (!$oSearch->IsDataFiltered())
			{
				$bIsAllDataFiltered = false;
				break;
			}
		}
		return $bIsAllDataFiltered;
	}

	protected function SetDataFiltered()
	{
		foreach ($this->aSearches as $oSearch)
		{
			$oSearch->SetDataFiltered();
		}
	}



	public function AddConditionForInOperatorUsingParam($sFilterCode, $aValues, $bPositiveMatch = true)
	{
		$sInParamName = $this->GenerateUniqueParamName();
		foreach ($this->aSearches as $iSearchIndex => $oSearch)
		{
			$oFieldExpression = new FieldExpression($sFilterCode, $oSearch->GetClassAlias());

			$sOperator = $bPositiveMatch ? 'IN' : 'NOT IN';

			$oParamExpression = new VariableExpression($sInParamName);
			$oSearch->GetInternalParamsByRef()[$sInParamName] = $aValues;

			$oListExpression = new ListExpression(array($oParamExpression));
			$oInCondition = new BinaryExpression($oFieldExpression, $sOperator, $oListExpression);
			$oSearch->AddConditionExpression($oInCondition);
		}
	}

	function GetExpectedArguments(): array
	{
		$aVariableCriteria = array();
		foreach ($this->aSearches as $oSearch)
		{
			$aVariableCriteria = array_merge($aVariableCriteria, $oSearch->GetExpectedArguments());
		}

		return $aVariableCriteria;
	}
}
