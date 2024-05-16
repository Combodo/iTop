<?php
/**
 * Class SQLObjectQueryBuilder
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class SQLObjectQueryBuilder
{
	/** @var \DBObjectSearch  */
	private $oDBObjetSearch;

	/**
	 * SQLObjectQueryBuilder constructor.
	 *
	 * @param \DBObjectSearch $oDBObjetSearch
	 */
	public function __construct($oDBObjetSearch)
	{
		$this->oDBObjetSearch = $oDBObjetSearch;
	}

	/**
	 * @param array $aAttToLoad
	 * @param bool $bGetCount
	 * @param array $aModifierProperties
	 * @param array $aGroupByExpr
	 * @param array $aSelectedClasses
	 * @param array $aSelectExpr
	 *
	 * @return null|SQLObjectQuery
	 * @throws \CoreException
	 */
	public function BuildSQLQueryStruct($aAttToLoad, $bGetCount, $aModifierProperties, $aGroupByExpr = null, $aSelectedClasses = null, $aSelectExpr = null)
	{
		if ($bGetCount || !is_null($aGroupByExpr))
		{
			// Avoid adding all the fields for counts or "group by" requests
			$aAttToLoad = array();
			foreach ($this->oDBObjetSearch->GetSelectedClasses() as $sClassAlias => $sClass)
			{
				$aAttToLoad[$sClassAlias] = array();
			}
		}

		$oBuild = new QueryBuilderContext($this->oDBObjetSearch, $aModifierProperties, $aGroupByExpr, $aSelectedClasses, $aSelectExpr, $aAttToLoad);
		$oSQLQuery = $this->MakeSQLObjectQueryRoot($oBuild, array(), $aGroupByExpr, $aSelectExpr);

		return $oSQLQuery;
	}

	/**
	 * @return \SQLObjectQuery|null
	 * @throws \CoreException
	 */
	public function MakeSQLObjectDeleteQuery()
	{
		$aModifierProperties = MetaModel::MakeModifierProperties($this->oDBObjetSearch);
		$aAttToLoad = array($this->oDBObjetSearch->GetClassAlias() => array());
		$oBuild = new QueryBuilderContext($this->oDBObjetSearch, $aModifierProperties, null, null, null, $aAttToLoad);
		$oSQLQuery = $this->MakeSQLObjectQueryRoot($oBuild, array());
		return $oSQLQuery;
	}

	/**
	 * @param array $aValues an array of $sAttCode => $value
	 *
	 * @return \SQLObjectQuery|null
	 * @throws \CoreException
	 */
	public function MakeSQLObjectUpdateQuery($aValues)
	{
		$aModifierProperties = MetaModel::MakeModifierProperties($this->oDBObjetSearch);
		$aRequested = array(); // Requested attributes are the updated attributes
		foreach ($aValues as $sAttCode => $value)
		{
			$aRequested[$sAttCode] = MetaModel::GetAttributeDef($this->oDBObjetSearch->GetClass(), $sAttCode);
		}
		$aAttToLoad = array($this->oDBObjetSearch->GetClassAlias() => $aRequested);
		$oBuild = new QueryBuilderContext($this->oDBObjetSearch, $aModifierProperties, null, null, null, $aAttToLoad);
		$oSQLQuery = $this->MakeSQLObjectQueryRoot($oBuild, $aValues);
		return $oSQLQuery;
	}

	/**
	 * @param \QueryBuilderContext $oBuild
	 *
	 * @return \OQLClassNode
	 * @throws \CoreException
	 */
	private function GetOQLClassTree($oBuild)
	{
		return OQLClassTreeBuilder::GetOQLClassTree($this->oDBObjetSearch, $oBuild);
	}

	/**
	 *
	 * @param \QueryBuilderContext $oBuild  The builder will be unusable after that
	 *
	 * @return string
	 * @throws \CoreException
	 */
	public function DebugOQLClassTree($oBuild)
	{
		return $this->GetOQLClassTree($oBuild)->RenderDebug();
	}


	/**
	 * @param \QueryBuilderContext $oBuild
	 * @param array $aValues
	 * @param array $aGroupByExpr
	 * @param array $aSelectExpr
	 *
	 * @return null|SQLObjectQuery
	 * @throws \CoreException
	 */
	private function MakeSQLObjectQueryRoot($oBuild, $aValues = array(), $aGroupByExpr = null, $aSelectExpr = null)
	{
		$oOQLClassNode = $this->GetOQLClassTree($oBuild);

		$oSQLQuery = $this->MakeSQLObjectQuery($oBuild, $oOQLClassNode, $aValues);

		/**
		 * Add SQL Level additional information
		 */
		$oSQLQuery->SetCondition($oBuild->m_oQBExpressions->GetCondition());

		if (is_array($aGroupByExpr))
		{
			$aCols = $oBuild->m_oQBExpressions->GetGroupBy();
			$oSQLQuery->SetGroupBy($aCols);
			$oSQLQuery->SetSelect($aCols);

			if (!empty($aSelectExpr))
			{
				// Get the fields corresponding to the select expressions
				foreach($oBuild->m_oQBExpressions->GetSelect() as $sAlias => $oExpr)
				{
					if (key_exists($sAlias, $aSelectExpr))
					{
						$oSQLQuery->AddSelect($sAlias, $oExpr);
					}
				}
			}
		}
		else
		{
			$oSQLQuery->SetSelect($oBuild->m_oQBExpressions->GetSelect());
		}

		// Filter for archive flag
		// Filter tables as late as possible: do not interfere with the optimization process
		$aMandatoryTables = $oBuild->m_oQBExpressions->GetMandatoryTables();
		foreach ($oBuild->GetFilteredTables() as $sTableAlias => $aConditions)
		{
			if ($aMandatoryTables && array_key_exists($sTableAlias, $aMandatoryTables))
			{
				foreach ($aConditions as $oCondition)
				{
					$oSQLQuery->AddCondition($oCondition);
				}
			}
		}

		return $oSQLQuery;
	}


	/**
	 * @param \QueryBuilderContext $oBuild
	 * @param \OQLClassNode $oOQLClassNode
	 * @param array $aValues
	 *
	 * @return \SQLObjectQuery
	 * @throws \CoreException
	 */
	private function MakeSQLObjectQuery($oBuild, $oOQLClassNode, $aValues)
	{
		$oSQLQuery = $this->MakeSQLObjectQueryNode($oBuild, $oOQLClassNode, $aValues);

		return $oSQLQuery;
	}


	/**
	 * @param \QueryBuilderContext $oBuild
	 * @param \OQLClassNode $oOQLClassNode
	 * @param array $aValues
	 *
	 * @return \SQLObjectQuery
	 * @throws \CoreException
	 */
	private function MakeSQLObjectQueryNode($oBuild, $oOQLClassNode, $aValues)
	{
		$sClass = $oOQLClassNode->GetNodeClass();
		$sTable = MetaModel::DBGetTable($sClass);
		$sClassAlias = $oOQLClassNode->GetNodeClassAlias();
		$sSelectedClassAlias = $oOQLClassNode->GetOQLClassAlias();
		$bIsOnQueriedClass = array_key_exists($sSelectedClassAlias, $oBuild->GetRootFilter()->GetSelectedClasses());
		$aExpectedAttributes = $oBuild->m_oQBExpressions->GetUnresolvedFields($sClassAlias);
		$oSelectedIdField = null;

		$aTranslation = array();
		$aUpdateValues = array();
		$oIdField = new FieldExpressionResolved(MetaModel::DBGetKey($sClass), $sClassAlias);
		$aTranslation[$sClassAlias]['id'] = $oIdField;
		if ($bIsOnQueriedClass)
		{
			// Add this field to the list of queried fields (required for the COUNT to work fine)
			$oSelectedIdField = $oIdField;
			foreach ($aExpectedAttributes as $sAttCode => $oExpression)
			{
				if (!array_key_exists($sAttCode, $aValues))
				{
					continue;
				}
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
				// Skip this attribute if not made of SQL columns nor in current table
				if (count($oAttDef->GetSQLExpressions()) == 0 || $oAttDef->IsExternalField())
				{
					continue;
				}
				foreach ($oAttDef->GetSQLValues($aValues[$sAttCode]) as $sColumn => $sValue)
				{
					$aUpdateValues[$sColumn] = $sValue;
				}
			}
		}

		$oBaseSQLQuery = new SQLObjectQuery($sTable, $sClassAlias, array(), $bIsOnQueriedClass, $aUpdateValues, $oSelectedIdField);

		foreach ($aExpectedAttributes as $sAttCode => $oExpression)
		{
			if (!MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				continue;
			}
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			$oFieldSQLExp = new FieldExpressionResolved($oAttDef->GetSQLExpressions(), $sClassAlias);
			/**
			 * @var string $sPluginClass
			 * @var iQueryModifier $oQueryModifier
			 */
			foreach (MetaModel::EnumPlugins('iQueryModifier') as $sPluginClass => $oQueryModifier)
			{
				$oFieldSQLExp = $oQueryModifier->GetFieldExpression($oBuild, $sClass, $sAttCode, '', $oFieldSQLExp, $oBaseSQLQuery);
			}
			$aTranslation[$sClassAlias][$sAttCode] = $oFieldSQLExp;
		}

		// Translate the selected columns
		//
		$oBuild->m_oQBExpressions->Translate($aTranslation, false);

		// Filter out archived records
		//
		if (MetaModel::IsArchivable($sClass))
		{
			if (!$oBuild->GetRootFilter()->GetArchiveMode())
			{
				$oNotArchived = new BinaryExpression(new FieldExpressionResolved('archive_flag', $sClassAlias), '=', new ScalarExpression(0));
				$oBuild->AddFilteredTable($sClassAlias, $oNotArchived);
			}
		}

		// Add Joins
		$aJoins = $oOQLClassNode->GetJoins();
		foreach ($aJoins as $aJoin)
		{
			foreach ($aJoin as $oOQLJoin)
			{
				$oJoinedClassNode = $oOQLJoin->GetOOQLClassNode();
				$oJoinedSQLQuery = $this->MakeSQLObjectQueryNode($oBuild, $oJoinedClassNode, $aValues);
				$oOQLJoin->AddToSQLObjectQuery($oBuild, $oBaseSQLQuery, $oJoinedSQLQuery);
			}
		}

		return $oBaseSQLQuery;
	}

}
