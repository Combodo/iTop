<?php
/**
 * Class SQLObjectQueryBuilder
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class SQLObjectQueryBuilder
{
	/** @var \DBObjectSearch  */
	private $oDBObjetSearch;
	/** @var bool  */
	private $bOptimizeQueries;
	/** @var bool  */
	private $bDebugQuery;

	/**
	 * SQLObjectQueryBuilder constructor.
	 *
	 * @param \DBObjectSearch $oDBObjetSearch
	 * @param bool $bOptimizeQueries
	 * @param bool $bDebugQuery
	 */
	public function __construct($oDBObjetSearch, $bOptimizeQueries, $bDebugQuery)
	{
		$this->oDBObjetSearch = $oDBObjetSearch;
		$this->bOptimizeQueries = $bOptimizeQueries;
		$this->bDebugQuery = $bDebugQuery;
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
		if ($bGetCount)
		{
			// Avoid adding all the fields for counts
			$aAttToLoad = array();
			foreach ($this->oDBObjetSearch->GetSelectedClasses() as $sClassAlias => $sClass)
			{
				$aAttToLoad[$sClassAlias] = array();
			}
		}

		$oBuild = new QueryBuilderContext($this->oDBObjetSearch, $aModifierProperties, $aGroupByExpr, $aSelectedClasses, $aSelectExpr, $aAttToLoad);

		$oSQLQuery = $this->MakeSQLObjectQueryRoot($oBuild, $aAttToLoad, array(), $aGroupByExpr, $aSelectExpr);

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
		$oSQLQuery = $this->MakeSQLObjectQueryRoot($oBuild, $aAttToLoad, array());
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
		$oSQLQuery = $this->MakeSQLObjectQueryRoot($oBuild, $aAttToLoad, $aValues);
		return $oSQLQuery;
	}

	/**
	 * @param \QueryBuilderContext $oBuild
	 * @param null $aAttToLoad
	 * @param array $aValues
	 * @param array $aGroupByExpr
	 * @param array $aSelectExpr
	 *
	 * @return null|SQLObjectQuery
	 * @throws \CoreException
	 */
	private function MakeSQLObjectQueryRoot($oBuild, $aAttToLoad = null, $aValues = array(), $aGroupByExpr = null, $aSelectExpr = null)
	{
		$oSQLQuery = $this->MakeSQLObjectQuery($oBuild, $aAttToLoad, $aValues);

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

		$aMandatoryTables = null;
		if ($this->bOptimizeQueries)
		{
			$oBuild->m_oQBExpressions->GetMandatoryTables($aMandatoryTables);
			$oSQLQuery->OptimizeJoins($aMandatoryTables);
		}

		// Filter for archive flag
		// Filter tables as late as possible: do not interfere with the optimization process
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
	 * @param null $aAttToLoad
	 * @param array $aValues
	 * @return null|SQLObjectQuery
	 * @throws \CoreException
	 */
	private function MakeSQLObjectQuery($oBuild, $aAttToLoad = null, $aValues = array())
	{
		// Note: query class might be different than the class of the filter
		// -> this occurs when we are linking our class to an external class (referenced by, or pointing to)
		$sClass = $this->oDBObjetSearch->GetFirstJoinedClass();
		$sClassAlias = $this->oDBObjetSearch->GetFirstJoinedClassAlias();

		// array of (attcode => fieldexpression)
		$aExpectedAtts = $oBuild->m_oQBExpressions->GetUnresolvedFields($sClassAlias);

		// Compute a clear view of required joins (from the current class)
		// Build the list of external keys:
		// -> ext keys required by an explicit join
		// -> ext keys mentioned in a 'pointing to' condition
		// -> ext keys required for an external field
		// -> ext keys required for a friendly name
		//
		$aExtKeys = array(); // array of sTableClass => array of (sAttCode (keys) => array of (sAttCode (fields)=> oAttDef))
		//
		// Optimization: could be partially computed once for all (cached) ?
		//

		// Get all Ext keys used by the filter
		foreach ($this->oDBObjetSearch->GetCriteria_PointingTo() as $sKeyAttCode => $aPointingTo)
		{
			if (array_key_exists(TREE_OPERATOR_EQUALS, $aPointingTo))
			{
				$sKeyTableClass = MetaModel::GetAttributeOrigin($sClass, $sKeyAttCode);
				$aExtKeys[$sKeyTableClass][$sKeyAttCode] = array();
			}
		}

		$aFNJoinAlias = array(); // array of (subclass => alias)
		foreach ($aExpectedAtts as $sExpectedAttCode => $oExpression)
		{
			if (!MetaModel::IsValidAttCode($sClass, $sExpectedAttCode)) continue;
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sExpectedAttCode);
			if ($oAttDef->IsBasedOnOQLExpression())
			{
				// To optimize: detect a restriction on child classes in the condition expression
				//    e.g. SELECT FunctionalCI WHERE finalclass IN ('Server', 'VirtualMachine')
				$oExpression = DBObjectSearch::GetPolymorphicExpression($sClass, $sExpectedAttCode);

				$aRequiredFields = array();
				$oExpression->GetUnresolvedFields('', $aRequiredFields);
				$aTranslateFields = array();
				foreach($aRequiredFields as $sSubClass => $aFields)
				{
					foreach($aFields as $sAttCode => $oField)
					{
						$oAttDef = MetaModel::GetAttributeDef($sSubClass, $sAttCode);
						if ($oAttDef->IsExternalKey())
						{
							$sClassOfAttribute = MetaModel::GetAttributeOrigin($sSubClass, $sAttCode);
							$aExtKeys[$sClassOfAttribute][$sAttCode] = array();
						}
						elseif ($oAttDef->IsExternalField())
						{
							$sKeyAttCode = $oAttDef->GetKeyAttCode();
							$sClassOfAttribute = MetaModel::GetAttributeOrigin($sSubClass, $sKeyAttCode);
							$aExtKeys[$sClassOfAttribute][$sKeyAttCode][$sAttCode] = $oAttDef;
						}
						else
						{
							$sClassOfAttribute = MetaModel::GetAttributeOrigin($sSubClass, $sAttCode);
						}

						if (MetaModel::IsParentClass($sClassOfAttribute, $sClass))
						{
							// The attribute is part of the standard query
							//
							$sAliasForAttribute = $sClassAlias;
						}
						else
						{
							// The attribute will be available from an additional outer join
							// For each subclass (table) one single join is enough
							//
							if (!array_key_exists($sClassOfAttribute, $aFNJoinAlias))
							{
								$sAliasForAttribute = $oBuild->GenerateClassAlias($sClassAlias.'_fn_'.$sClassOfAttribute, $sClassOfAttribute);
								$aFNJoinAlias[$sClassOfAttribute] = $sAliasForAttribute;
							}
							else
							{
								$sAliasForAttribute = $aFNJoinAlias[$sClassOfAttribute];
							}
						}

						$aTranslateFields[$sSubClass][$sAttCode] = new FieldExpression($sAttCode, $sAliasForAttribute);
					}
				}
				$oExpression = $oExpression->Translate($aTranslateFields, false);

				$aTranslateNow = array();
				$aTranslateNow[$sClassAlias][$sExpectedAttCode] = $oExpression;
				$oBuild->m_oQBExpressions->Translate($aTranslateNow, false);
			}
		}

		// Add the ext fields used in the select (eventually adds an external key)
		foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode=>$oAttDef)
		{
			if ($oAttDef->IsExternalField())
			{
				if (array_key_exists($sAttCode, $aExpectedAtts))
				{
					// Add the external attribute
					$sKeyAttCode = $oAttDef->GetKeyAttCode();
					$sKeyTableClass = MetaModel::GetAttributeOrigin($sClass, $sKeyAttCode);
					$aExtKeys[$sKeyTableClass][$sKeyAttCode][$sAttCode] = $oAttDef;
				}
			}
		}

		$sKeyField = MetaModel::DBGetKey($sClass);
		$bRootFirst = MetaModel::GetConfig()->Get('optimize_requests_for_join_count');
		if ($bRootFirst)
		{
			// First query built from the root, adding all tables including the leaf
			//   Before N.1065 we were joining from the leaf first, but this wasn't a good choice :
			//   most of the time (obsolescence, friendlyname, ...) we want to get a root attribute !
			//
			$oSelectBase = null;
			$aClassHierarchy = MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL, true);
			$bIsClassStandaloneClass = (count($aClassHierarchy) == 1);
			foreach($aClassHierarchy as $sSomeClass)
			{
				if (!MetaModel::HasTable($sSomeClass))
				{
					continue;
				}

				//self::DbgTrace("Adding join from root to leaf: $sSomeClass... let's call MakeSQLObjectQuerySingleTable()");
				$oSelectParentTable = $this->MakeSQLObjectQuerySingleTable($oBuild, $aAttToLoad, $sSomeClass, $aExtKeys, $aValues);
				if (is_null($oSelectBase))
				{
					$oSelectBase = $oSelectParentTable;
					if (!$bIsClassStandaloneClass && (MetaModel::IsRootClass($sSomeClass)))
					{
						// As we're linking to root class first, we're adding a where clause on the finalClass attribute :
						//      COALESCE($sRootClassFinalClass IN ('$sExpectedClasses'), 1)
						// If we don't, the child classes can be removed in the query optimisation phase, including the leaf that was queried
						// So we still need to filter records to only those corresponding to the child classes !
						// The coalesce is mandatory if we have a polymorphic query (left join)
						$oClassListExpr = ListExpression::FromScalars(MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL));
						$sFinalClassSqlColumnName = MetaModel::DBGetClassField($sSomeClass);
						$oClassExpr = new FieldExpression($sFinalClassSqlColumnName, $oSelectBase->GetTableAlias());
						$oInExpression = new BinaryExpression($oClassExpr, 'IN', $oClassListExpr);
						$oTrueExpression = new TrueExpression();
						$aCoalesceAttr = array($oInExpression, $oTrueExpression);
						$oFinalClassRestriction = new FunctionExpression("COALESCE", $aCoalesceAttr);

						$oBuild->m_oQBExpressions->AddCondition($oFinalClassRestriction);
					}
				}
				else
				{
					$oSelectBase->AddInnerJoin($oSelectParentTable, $sKeyField, MetaModel::DBGetKey($sSomeClass));
				}
			}
		}
		else
		{
			// First query built upon on the leaf (ie current) class
			//
			//self::DbgTrace("Main (=leaf) class, call MakeSQLObjectQuerySingleTable()");
			if (MetaModel::HasTable($sClass))
			{
				$oSelectBase = $this->MakeSQLObjectQuerySingleTable($oBuild, $aAttToLoad, $sClass, $aExtKeys, $aValues);
			}
			else
			{
				$oSelectBase = null;

				// As the join will not filter on the expected classes, we have to specify it explicitely
				$sExpectedClasses = implode("', '", MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL));
				$oFinalClassRestriction = Expression::FromOQL("`$sClassAlias`.finalclass IN ('$sExpectedClasses')");
				$oBuild->m_oQBExpressions->AddCondition($oFinalClassRestriction);
			}

			// Then we join the queries of the eventual parent classes (compound model)
			foreach(MetaModel::EnumParentClasses($sClass) as $sParentClass)
			{
				if (!MetaModel::HasTable($sParentClass)) continue;

				//self::DbgTrace("Parent class: $sParentClass... let's call MakeSQLObjectQuerySingleTable()");
				$oSelectParentTable = $this->MakeSQLObjectQuerySingleTable($oBuild, $aAttToLoad, $sParentClass, $aExtKeys, $aValues);
				if (is_null($oSelectBase))
				{
					$oSelectBase = $oSelectParentTable;
				}
				else
				{
					$oSelectBase->AddInnerJoin($oSelectParentTable, $sKeyField, MetaModel::DBGetKey($sParentClass));
				}
			}
		}

		// Filter on objects referencing me
		//
		foreach($this->oDBObjetSearch->GetCriteria_ReferencedBy() as $sForeignClass=>$aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode => $aFiltersByOperator)
			{
				foreach ($aFiltersByOperator as $iOperatorCode => $aFilters)
				{
					foreach ($aFilters as $oForeignFilter)
					{
						$oForeignKeyAttDef = MetaModel::GetAttributeDef($sForeignClass, $sForeignExtKeyAttCode);

						//self::DbgTrace("Referenced by foreign key: $sForeignExtKeyAttCode... let's call MakeSQLObjectQuery()");

						$sForeignClassAlias = $oForeignFilter->GetFirstJoinedClassAlias();
						$oBuild->m_oQBExpressions->PushJoinField(new FieldExpression($sForeignExtKeyAttCode, $sForeignClassAlias));

						if ($oForeignKeyAttDef instanceof AttributeObjectKey)
						{
							$sClassAttCode = $oForeignKeyAttDef->Get('class_attcode');

							// Add the condition: `$sForeignClassAlias`.$sClassAttCode IN (subclasses of $sClass')
							$oClassListExpr = ListExpression::FromScalars(MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL));
							$oClassExpr = new FieldExpression($sClassAttCode, $sForeignClassAlias);
							$oClassRestriction = new BinaryExpression($oClassExpr, 'IN', $oClassListExpr);
							$oBuild->m_oQBExpressions->AddCondition($oClassRestriction);
						}

						$oSQLObjectQueryBuilder = new SQLObjectQueryBuilder($oForeignFilter, $this->bOptimizeQueries, $this->bDebugQuery);
						$oSelectForeign = $oSQLObjectQueryBuilder->MakeSQLObjectQuery($oBuild, $aAttToLoad);

						$oJoinExpr = $oBuild->m_oQBExpressions->PopJoinField();
						$sForeignKeyTable = $oJoinExpr->GetParent();
						$sForeignKeyColumn = $oJoinExpr->GetName();

						if ($iOperatorCode == TREE_OPERATOR_EQUALS)
						{
							$oSelectBase->AddInnerJoin($oSelectForeign, $sKeyField, $sForeignKeyColumn, $sForeignKeyTable);
						}
						else
						{
							// Hierarchical key
							$KeyLeft = $oForeignKeyAttDef->GetSQLLeft();
							$KeyRight = $oForeignKeyAttDef->GetSQLRight();

							$oSelectBase->AddInnerJoinTree($oSelectForeign, $KeyLeft, $KeyRight, $KeyLeft, $KeyRight, $sForeignKeyTable, $iOperatorCode, true);
						}
					}
				}
			}
		}

		// Additional JOINS for Friendly names
		//
		foreach ($aFNJoinAlias as $sSubClass => $sSubClassAlias)
		{
			$oSubClassFilter = new DBObjectSearch($sSubClass, $sSubClassAlias);
			$oSQLObjectQueryBuilder = new SQLObjectQueryBuilder($oSubClassFilter, $this->bOptimizeQueries, $this->bDebugQuery);
			$oSelectFN = $oSQLObjectQueryBuilder->MakeSQLObjectQuerySingleTable($oBuild, $aAttToLoad, $sSubClass, $aExtKeys, array());
			$oSelectBase->AddLeftJoin($oSelectFN, $sKeyField, MetaModel::DBGetKey($sSubClass));
		}

		// That's all... cross fingers and we'll get some working query

		//MyHelpers::var_dump_html($oSelectBase, true);
		//MyHelpers::var_dump_html($oSelectBase->RenderSelect(), true);
		if ($this->bDebugQuery) $oSelectBase->DisplayHtml();
		return $oSelectBase;
	}

	/**
	 * @param \QueryBuilderContext $oBuild
	 * @param $aAttToLoad
	 * @param $sTableClass
	 * @param $aExtKeys
	 * @param $aValues
	 *
	 * @return \SQLObjectQuery
	 * @throws \CoreException
	 */
	private function MakeSQLObjectQuerySingleTable($oBuild, $aAttToLoad, $sTableClass, $aExtKeys, $aValues)
	{
		// $aExtKeys is an array of sTableClass => array of (sAttCode (keys) => array of sAttCode (fields))

		// Prepare the query for a single table (compound objects)
		// Ignores the items (attributes/filters) that are not on the target table
		// Perform an (inner or left) join for every external key (and specify the expected fields)
		//
		// Returns an SQLQuery
		//
		$sTargetClass = $this->oDBObjetSearch->GetFirstJoinedClass();
		$sTargetAlias = $this->oDBObjetSearch->GetFirstJoinedClassAlias();
		$sTable = MetaModel::DBGetTable($sTableClass);
		$sTableAlias = $oBuild->GenerateTableAlias($sTargetAlias.'_'.$sTable, $sTable);

		$aTranslation = array();
		$aExpectedAtts = $oBuild->m_oQBExpressions->GetUnresolvedFields($sTargetAlias);

		$bIsOnQueriedClass = array_key_exists($sTargetAlias, $oBuild->GetRootFilter()->GetSelectedClasses());

		//self::DbgTrace("Entering: tableclass=$sTableClass, filter=".$this->oDBObjetSearch->ToOQL().", ".($bIsOnQueriedClass ? "MAIN" : "SECONDARY"));

		// 1 - SELECT and UPDATE
		//
		// Note: no need for any values nor fields for foreign Classes (ie not the queried Class)
		//
		$aUpdateValues = array();


		// 1/a - Get the key and friendly name
		//
		// We need one pkey to be the key, let's take the first one available
		$oSelectedIdField = null;
		$oIdField = new FieldExpressionResolved(MetaModel::DBGetKey($sTableClass), $sTableAlias);
		$aTranslation[$sTargetAlias]['id'] = $oIdField;

		if ($bIsOnQueriedClass)
		{
			// Add this field to the list of queried fields (required for the COUNT to work fine)
			$oSelectedIdField = $oIdField;
		}

		// 1/b - Get the other attributes
		//
		foreach(MetaModel::ListAttributeDefs($sTableClass) as $sAttCode=>$oAttDef)
		{
			// Skip this attribute if not defined in this table
			if (MetaModel::GetAttributeOrigin($sTargetClass, $sAttCode) != $sTableClass) continue;

			// Skip this attribute if not made of SQL columns
			if (count($oAttDef->GetSQLExpressions()) == 0) continue;

			// Update...
			//
			if ($bIsOnQueriedClass && array_key_exists($sAttCode, $aValues))
			{
				assert ($oAttDef->IsBasedOnDBColumns());
				foreach ($oAttDef->GetSQLValues($aValues[$sAttCode]) as $sColumn => $sValue)
				{
					$aUpdateValues[$sColumn] = $sValue;
				}
			}
		}

		// 2 - The SQL query, for this table only
		//
		$oSelectBase = new SQLObjectQuery($sTable, $sTableAlias, array(), $bIsOnQueriedClass, $aUpdateValues, $oSelectedIdField);

		// 3 - Resolve expected expressions (translation table: alias.attcode => table.column)
		//
		foreach(MetaModel::ListAttributeDefs($sTableClass) as $sAttCode=>$oAttDef)
		{
			// Skip this attribute if not defined in this table
			if (MetaModel::GetAttributeOrigin($sTargetClass, $sAttCode) != $sTableClass) continue;

			// Select...
			//
			if ($oAttDef->IsExternalField())
			{
				// skip, this will be handled in the joined tables (done hereabove)
			}
			else
			{
				// standard field, or external key
				// add it to the output
				foreach ($oAttDef->GetSQLExpressions() as $sColId => $sSQLExpr)
				{
					if (array_key_exists($sAttCode.$sColId, $aExpectedAtts))
					{
						$oFieldSQLExp = new FieldExpressionResolved($sSQLExpr, $sTableAlias);
						foreach (MetaModel::EnumPlugins('iQueryModifier') as $sPluginClass => $oQueryModifier)
						{
							$oFieldSQLExp = $oQueryModifier->GetFieldExpression($oBuild, $sTargetClass, $sAttCode, $sColId, $oFieldSQLExp, $oSelectBase);
						}
						$aTranslation[$sTargetAlias][$sAttCode.$sColId] = $oFieldSQLExp;
					}
				}
			}
		}

		// 4 - The external keys -> joins...
		//
		$aAllPointingTo = $this->oDBObjetSearch->GetCriteria_PointingTo();

		if (array_key_exists($sTableClass, $aExtKeys))
		{
			foreach ($aExtKeys[$sTableClass] as $sKeyAttCode => $aExtFields)
			{
				$oKeyAttDef = MetaModel::GetAttributeDef($sTableClass, $sKeyAttCode);

				$aPointingTo = $this->oDBObjetSearch->GetCriteria_PointingTo($sKeyAttCode);
				if (!array_key_exists(TREE_OPERATOR_EQUALS, $aPointingTo))
				{
					// The join was not explicitely defined in the filter,
					// we need to do it now
					$sKeyClass =  $oKeyAttDef->GetTargetClass();
					$sKeyClassAlias = $oBuild->GenerateClassAlias($sKeyClass.'_'.$sKeyAttCode, $sKeyClass);
					$oExtFilter = new DBObjectSearch($sKeyClass, $sKeyClassAlias);

					$aAllPointingTo[$sKeyAttCode][TREE_OPERATOR_EQUALS][$sKeyClassAlias] = $oExtFilter;
				}
			}
		}

		foreach ($aAllPointingTo as $sKeyAttCode => $aPointingTo)
		{
			foreach($aPointingTo as $iOperatorCode => $aFilter)
			{
				foreach($aFilter as $oExtFilter)
				{
					if (!MetaModel::IsValidAttCode($sTableClass, $sKeyAttCode)) continue; // Not defined in the class, skip it
					// The aliases should not conflict because normalization occured while building the filter
					$oKeyAttDef = MetaModel::GetAttributeDef($sTableClass, $sKeyAttCode);
					$sKeyClass =  $oExtFilter->GetFirstJoinedClass();
					$sKeyClassAlias = $oExtFilter->GetFirstJoinedClassAlias();

					// Note: there is no search condition in $oExtFilter, because normalization did merge the condition onto the top of the filter tree

					if ($iOperatorCode == TREE_OPERATOR_EQUALS)
					{
						if (array_key_exists($sTableClass, $aExtKeys) && array_key_exists($sKeyAttCode, $aExtKeys[$sTableClass]))
						{
							// Specify expected attributes for the target class query
							// ... and use the current alias !
							$aTranslateNow = array(); // Translation for external fields - must be performed before the join is done (recursion...)
							foreach($aExtKeys[$sTableClass][$sKeyAttCode] as $sAttCode => $oAtt)
							{
								$oExtAttDef = $oAtt->GetExtAttDef();
								if ($oExtAttDef->IsBasedOnOQLExpression())
								{
									$aTranslateNow[$sTargetAlias][$sAttCode] = new FieldExpression($oExtAttDef->GetCode(), $sKeyClassAlias);
								}
								else
								{
									$sExtAttCode = $oAtt->GetExtAttCode();
									// Translate mainclass.extfield => remoteclassalias.remotefieldcode
									$oRemoteAttDef = MetaModel::GetAttributeDef($sKeyClass, $sExtAttCode);
									foreach ($oRemoteAttDef->GetSQLExpressions() as $sColId => $sRemoteAttExpr)
									{
										$aTranslateNow[$sTargetAlias][$sAttCode.$sColId] = new FieldExpression($sExtAttCode, $sKeyClassAlias);
									}
								}
							}

							if ($oKeyAttDef instanceof AttributeObjectKey)
							{
								// Add the condition: `$sTargetAlias`.$sClassAttCode IN (subclasses of $sKeyClass')
								$sClassAttCode = $oKeyAttDef->Get('class_attcode');
								$oClassAttDef = MetaModel::GetAttributeDef($sTargetClass, $sClassAttCode);
								foreach ($oClassAttDef->GetSQLExpressions() as $sColId => $sSQLExpr)
								{
									$aTranslateNow[$sTargetAlias][$sClassAttCode.$sColId] = new FieldExpressionResolved($sSQLExpr, $sTableAlias);
								}

								$oClassListExpr = ListExpression::FromScalars(MetaModel::EnumChildClasses($sKeyClass, ENUM_CHILD_CLASSES_ALL));
								$oClassExpr = new FieldExpression($sClassAttCode, $sTargetAlias);
								$oClassRestriction = new BinaryExpression($oClassExpr, 'IN', $oClassListExpr);
								$oBuild->m_oQBExpressions->AddCondition($oClassRestriction);
							}

							// Translate prior to recursing
							//
							$oBuild->m_oQBExpressions->Translate($aTranslateNow, false);

							//self::DbgTrace("External key $sKeyAttCode (class: $sKeyClass), call MakeSQLObjectQuery()");
							$oBuild->m_oQBExpressions->PushJoinField(new FieldExpression('id', $sKeyClassAlias));

							$oSQLObjectQueryBuilder = new SQLObjectQueryBuilder($oExtFilter, $this->bOptimizeQueries, $this->bDebugQuery);
							$oSelectExtKey = $oSQLObjectQueryBuilder->MakeSQLObjectQuery($oBuild, $aAttToLoad);

							$oJoinExpr = $oBuild->m_oQBExpressions->PopJoinField();
							$sExternalKeyTable = $oJoinExpr->GetParent();
							$sExternalKeyField = $oJoinExpr->GetName();

							$aCols = $oKeyAttDef->GetSQLExpressions(); // Workaround a PHP bug: sometimes issuing a Notice if invoking current(somefunc())
							$sLocalKeyField = current($aCols); // get the first column for an external key

							//self::DbgTrace("External key $sKeyAttCode, Join on $sLocalKeyField = $sExternalKeyField");
							if ($oKeyAttDef->IsNullAllowed())
							{
								$oSelectBase->AddLeftJoin($oSelectExtKey, $sLocalKeyField, $sExternalKeyField);
							}
							else
							{
								$oSelectBase->AddInnerJoin($oSelectExtKey, $sLocalKeyField, $sExternalKeyField, $sExternalKeyTable);
							}
						}
					}
					elseif(MetaModel::GetAttributeOrigin($sKeyClass, $sKeyAttCode) == $sTableClass)
					{
						$oBuild->m_oQBExpressions->PushJoinField(new FieldExpression($sKeyAttCode, $sKeyClassAlias));
						$oSQLObjectQueryBuilder = new SQLObjectQueryBuilder($oExtFilter, $this->bOptimizeQueries, $this->bDebugQuery);
						$oSelectExtKey = $oSQLObjectQueryBuilder->MakeSQLObjectQuery($oBuild, $aAttToLoad);
						$oJoinExpr = $oBuild->m_oQBExpressions->PopJoinField();
						$sExternalKeyTable = $oJoinExpr->GetParent();
						$sExternalKeyField = $oJoinExpr->GetName();
						$sLeftIndex = $sExternalKeyField.'_left';
						$sRightIndex = $sExternalKeyField.'_right';

						$LocalKeyLeft = $oKeyAttDef->GetSQLLeft();
						$LocalKeyRight = $oKeyAttDef->GetSQLRight();

						$oSelectBase->AddInnerJoinTree($oSelectExtKey, $LocalKeyLeft, $LocalKeyRight, $sLeftIndex, $sRightIndex, $sExternalKeyTable, $iOperatorCode);
					}
				}
			}
		}

		// Translate the selected columns
		//
		$oBuild->m_oQBExpressions->Translate($aTranslation, false);

		// Filter out archived records
		//
		if (MetaModel::IsArchivable($sTableClass))
		{
			if (!$oBuild->GetRootFilter()->GetArchiveMode())
			{
				$bIsOnJoinedClass = array_key_exists($sTargetAlias, $oBuild->GetRootFilter()->GetJoinedClasses());
				if ($bIsOnJoinedClass)
				{
					if (MetaModel::IsParentClass($sTableClass, $sTargetClass))
					{
						$oNotArchived = new BinaryExpression(new FieldExpressionResolved('archive_flag', $sTableAlias), '=', new ScalarExpression(0));
						$oBuild->AddFilteredTable($sTableAlias, $oNotArchived);
					}
				}
			}
		}
		return $oSelectBase;
	}


}