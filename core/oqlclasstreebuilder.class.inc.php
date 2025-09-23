<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class OQLClassTreeBuilder
{
	/** @var \DBObjectSearch  */
	private $oDBObjectSearch;
	/** @var OQLClassNode */
	private $oOQLClassNode;
	/** @var \QueryBuilderContext  */
	private $oBuild;

	private $sClass;
	private $sClassAlias;

	/**
	 * OQLClassTreeBuilder constructor.
	 *
	 * @param \DBObjectSearch $oDBObjetSearch
	 * @param \QueryBuilderContext $oBuild
	 */
	protected function __construct($oDBObjetSearch, $oBuild)
	{
		$this->oBuild = $oBuild;
		$this->oDBObjectSearch = $oDBObjetSearch;
		$this->sClass = $oDBObjetSearch->GetFirstJoinedClass();
		$this->sClassAlias = $oDBObjetSearch->GetFirstJoinedClassAlias();
		if (empty($this->sClassAlias))
		{
			$this->sClassAlias = $oBuild->GetEmptyClassAlias();
		}
		$this->oOQLClassNode = new OQLClassNode($oBuild, $this->sClass, $this->sClassAlias);
	}

	/**
	 * @param \DBObjectSearch $oDBObjetSearch
	 * @param \QueryBuilderContext $oBuild
	 *
	 * @return \OQLClassNode
	 * @throws \CoreException
	 */
	public static function GetOQLClassTree($oDBObjetSearch, $oBuild)
	{
		$oOQLClassTreeBuilder = new OQLClassTreeBuilder($oDBObjetSearch, $oBuild);
		$oOQLClassNode = $oOQLClassTreeBuilder->DevelopOQLClassNode();
		$oOQLClassTreeOptimizer = new OQLClassTreeOptimizer($oOQLClassNode, $oBuild);
		$oOQLClassTreeOptimizer->OptimizeClassTree();
		$oOQLActualClassTreeResolver = new OQLActualClassTreeResolver($oOQLClassNode, $oBuild);
		$oOQLClassNode = $oOQLActualClassTreeResolver->Resolve();

		return $oOQLClassNode;
	}

	/**
	 * Develop OQL.
	 * Add joins from OQL (outgoing and incoming)
	 * Add joins for polymorphic expressions (expressions using derived classes
	 * instead of ancestor classes, i.e. friendly name and obsolescence flag)
	 * Add joins for expected external keys and external fields
	 * Behave recursively to build a tree of OQL class node
	 *
	 * @return \OQLClassNode
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function DevelopOQLClassNode()
	{
		$this->TranslateNestedRequests();
		$this->AddExternalKeysFromSearch();
		$aPolymorphicJoinAlias = $this->TranslatePolymorphicExpressions();
		$this->AddExpectedExternalFields();

		$this->JoinClassesForExternalKeys();
		$this->JoinClassesReferencedBy();
		$this->JoinClassesForPolymorphicExpressions($aPolymorphicJoinAlias);

		// That's all... cross fingers and we'll get some working query

		return $this->oOQLClassNode;
	}

	/**
	 * Get all Ext keys used by the filter
	 *
	 */
	private function AddExternalKeysFromSearch()
	{
		foreach ($this->oDBObjectSearch->GetCriteria_PointingTo() as $sKeyAttCode => $aPointingTo)
		{
			if (array_key_exists(TREE_OPERATOR_EQUALS, $aPointingTo))
			{
				$this->oOQLClassNode->AddExternalKey($sKeyAttCode);
			}
		}
	}

	/**
	 *
	 * @return array of classes to join for polymorphic expressions
	 *
	 * @throws \CoreException
	 */
	private function TranslatePolymorphicExpressions()
	{
		// array of (attcode => fieldexpression)
		$aExpectedAttributes = $this->oBuild->m_oQBExpressions->GetUnresolvedFields($this->sClassAlias);

		$aPolymorphicJoinAlias = array(); // array of (subclass => alias)
		foreach ($aExpectedAttributes as $sExpectedAttCode => $oExpression)
		{
			if (!MetaModel::IsValidAttCode($this->sClass, $sExpectedAttCode))
			{
				continue;
			}
			$oAttDef = MetaModel::GetAttributeDef($this->sClass, $sExpectedAttCode);
			if ($oAttDef->IsBasedOnOQLExpression())
			{
				// To optimize: detect a restriction on child classes in the condition expression
				//    e.g. SELECT FunctionalCI WHERE finalclass IN ('Server', 'VirtualMachine')
				$oExpression = DBObjectSearch::GetPolymorphicExpression($this->sClass, $sExpectedAttCode);

				$aRequiredFields = array();
				$oExpression->GetUnresolvedFields('', $aRequiredFields);
				$aTranslateFields = array();
				foreach ($aRequiredFields as $sSubClass => $aFields)
				{
					foreach ($aFields as $sAttCode => $oField)
					{
						$oAttDef = MetaModel::GetAttributeDef($sSubClass, $sAttCode);
						if ($oAttDef->IsExternalKey())
						{
							$sClassOfAttribute = MetaModel::GetAttributeOrigin($sSubClass, $sAttCode);
							if (MetaModel::IsParentClass($sClassOfAttribute, $this->sClass))
							{
								$this->oOQLClassNode->AddExternalKey($sAttCode);
							}
						}
						elseif ($oAttDef->IsExternalField())
						{
							$sKeyAttCode = $oAttDef->GetKeyAttCode();
							$sClassOfAttribute = MetaModel::GetAttributeOrigin($sSubClass, $sKeyAttCode);
							if (MetaModel::IsParentClass($sClassOfAttribute, $this->sClass))
							{
								$this->oOQLClassNode->AddExternalField($sKeyAttCode, $sAttCode, $oAttDef);
							}
						}
						else
						{
							$sClassOfAttribute = MetaModel::GetAttributeOrigin($sSubClass, $sAttCode);
						}

						if (MetaModel::IsParentClass($sClassOfAttribute, $this->sClass))
						{
							// The attribute is part of the standard query
							//
							$sAliasForAttribute = $this->sClassAlias;
						}
						else
						{
							// The attribute will be available from an additional outer join
							// For each subclass (table) one single join is enough
							//
							if (!array_key_exists($sClassOfAttribute, $aPolymorphicJoinAlias))
							{
								$sAliasForAttribute = $this->oBuild->GenerateClassAlias($this->sClassAlias.'_poly_'.$sClassOfAttribute,
									$sClassOfAttribute);
								$aPolymorphicJoinAlias[$sClassOfAttribute] = $sAliasForAttribute;
							}
							else
							{
								$sAliasForAttribute = $aPolymorphicJoinAlias[$sClassOfAttribute];
							}
						}

						$aTranslateFields[$sSubClass][$sAttCode] = new FieldExpression($sAttCode, $sAliasForAttribute);
					}
				}
				$oExpression = $oExpression->Translate($aTranslateFields, false);

				$aTranslateNow = array();
				$aTranslateNow[$this->sClassAlias][$sExpectedAttCode] = $oExpression;
				$this->oBuild->m_oQBExpressions->Translate($aTranslateNow, false);
			}
		}

		return $aPolymorphicJoinAlias;
	}

	/**
	 * Add the ext fields used in the select (external keys may be created for that)
	 *
	 * @throws \CoreException
	 */
	private function AddExpectedExternalFields()
	{
		// array of (attcode => fieldexpression)
		$aExpectedAttributes = $this->oBuild->m_oQBExpressions->GetUnresolvedFields($this->sClassAlias);

		foreach (MetaModel::ListAttributeDefs($this->sClass) as $sAttCode => $oAttDef)
		{
			if ($oAttDef->IsExternalField())
			{
				if (array_key_exists($sAttCode, $aExpectedAttributes))
				{
					// Add the external attribute
					$sKeyAttCode = $oAttDef->GetKeyAttCode();
					$this->oOQLClassNode->AddExternalField($sKeyAttCode, $sAttCode, $oAttDef);
				}
			}
		}
	}

	/**
	 * Add joins for external keys/fields
	 *
	 * @throws \Exception
	 */
	private function JoinClassesForExternalKeys()
	{
		// Get filters from the search outgoing joins
		$aAllPointingTo = $this->oDBObjectSearch->GetCriteria_PointingTo();

		// Add filters from external keys
		foreach (array_keys($this->oOQLClassNode->GetExternalKeys()) as $sKeyAttCode)
		{
			if (!MetaModel::IsValidAttCode($this->sClass, $sKeyAttCode))
			{
				continue;
			} // Not defined in the class, skip it
			$oKeyAttDef = MetaModel::GetAttributeDef($this->sClass, $sKeyAttCode);
			$aPointingTo = isset($aAllPointingTo[$sKeyAttCode]) ? $aAllPointingTo[$sKeyAttCode] : array();
			if (!array_key_exists(TREE_OPERATOR_EQUALS, $aPointingTo))
			{
				// The join was not explicitly defined in the filter,
				// we need to do it now
				$sKeyClass = $oKeyAttDef->GetTargetClass();
				$sKeyClassAlias = $this->oBuild->GenerateClassAlias($sKeyClass.'_'.$sKeyAttCode, $sKeyClass);
				$oExtFilter = new DBObjectSearch($sKeyClass, $sKeyClassAlias);

				$aAllPointingTo[$sKeyAttCode][TREE_OPERATOR_EQUALS][$sKeyClassAlias] = $oExtFilter;
			}
		}

		$oQBContextExpressions = $this->oBuild->m_oQBExpressions;
		foreach ($aAllPointingTo as $sKeyAttCode => $aPointingTo)
		{
			foreach ($aPointingTo as $iOperatorCode => $aFilter)
			{
				foreach ($aFilter as $oExtFilter)
				{
					if (!MetaModel::IsValidAttCode($this->sClass, $sKeyAttCode))
					{
						continue;
					} // Not defined in the class, skip it
					// The aliases should not conflict because normalization occurred while building the filter
					$oKeyAttDef = MetaModel::GetAttributeDef($this->sClass, $sKeyAttCode);
					$sKeyClass = $oExtFilter->GetFirstJoinedClass();
					$sKeyClassAlias = $oExtFilter->GetFirstJoinedClassAlias();

					// Note: there is no search condition in $oExtFilter, because normalization did merge the condition onto the top of the filter tree

					if ($iOperatorCode == TREE_OPERATOR_EQUALS)
					{
						if ($this->oOQLClassNode->HasExternalKey($sKeyAttCode))
						{
							// Specify expected attributes for the target class query
							// ... and use the current alias !
							$aTranslateNow = array(); // Translation for external fields - must be performed before the join is done (recursion...)
							foreach ($this->oOQLClassNode->GetExternalKey($sKeyAttCode) as $sAttCode => $oAtt)
							{
								$oExtAttDef = $oAtt->GetExtAttDef();
								if ($oExtAttDef->IsBasedOnOQLExpression())
								{
									$sExtAttCode = $oExtAttDef->GetCode();
								}
								else
								{
									$sExtAttCode = $oAtt->GetExtAttCode();
								}
								// Translate mainclass.extfield => remoteclassalias.remotefieldcode
								$aTranslateNow[$this->sClassAlias][$sAttCode] = new FieldExpression($sExtAttCode, $sKeyClassAlias);
							}

							if ($oKeyAttDef instanceof AttributeObjectKey)
							{
								// Add the condition: `$sTargetAlias`.$sClassAttCode IN (subclasses of $sKeyClass')
								$sClassAttCode = $oKeyAttDef->Get('class_attcode');
								$oClassListExpr = ListExpression::FromScalars(MetaModel::EnumChildClasses($sKeyClass,
									ENUM_CHILD_CLASSES_ALL));
								$oClassExpr = new FieldExpression($sClassAttCode, $this->sClassAlias);
								$oClassRestriction = new BinaryExpression($oClassExpr, 'IN', $oClassListExpr);
								$oQBContextExpressions->AddCondition($oClassRestriction);
							}

							// Translate prior to recursing
							//
							$oQBContextExpressions->Translate($aTranslateNow, false);
							$sExternalKeyField = 'id';

							$oOQLClassTreeBuilder = new OQLClassTreeBuilder($oExtFilter, $this->oBuild);
							$oSelectExtKey = $oOQLClassTreeBuilder->DevelopOQLClassNode();

							if ($oKeyAttDef->IsNullAllowed())
							{
								$this->oOQLClassNode->AddLeftJoin($oSelectExtKey, $sKeyAttCode, $sExternalKeyField, true);
							}
							else
							{
								$this->oOQLClassNode->AddInnerJoin($oSelectExtKey, $sKeyAttCode, $sExternalKeyField, true);
							}
						}
					}
					elseif (MetaModel::GetAttributeOrigin($sKeyClass, $sKeyAttCode) == $this->sClass)
					{
						$sExternalKeyField = $sKeyAttCode;

						$oOQLClassTreeBuilder = new OQLClassTreeBuilder($oExtFilter, $this->oBuild);
						$oSelectExtKey = $oOQLClassTreeBuilder->DevelopOQLClassNode();


						$this->oOQLClassNode->AddInnerJoinTree($oSelectExtKey, $sKeyAttCode, $sExternalKeyField, true, $iOperatorCode);
					}
				}
			}
		}
	}

	/**
	 * Filter on objects referencing me
	 *
	 * @throws \CoreException
	 */
	private function JoinClassesReferencedBy()
	{
		foreach ($this->oDBObjectSearch->GetCriteria_ReferencedBy() as $sForeignClass => $aReferences)
		{
			foreach ($aReferences as $sForeignExtKeyAttCode => $aFiltersByOperator)
			{
				foreach ($aFiltersByOperator as $iOperatorCode => $aFilters)
				{
					foreach ($aFilters as $oForeignFilter)
					{
						$oForeignKeyAttDef = MetaModel::GetAttributeDef($sForeignClass, $sForeignExtKeyAttCode);

						$sForeignClassAlias = $oForeignFilter->GetFirstJoinedClassAlias();

						if ($oForeignKeyAttDef instanceof AttributeObjectKey)
						{
							$sClassAttCode = $oForeignKeyAttDef->Get('class_attcode');

							// Add the condition: `$sForeignClassAlias`.$sClassAttCode IN (subclasses of $sClass')
							$oClassListExpr = ListExpression::FromScalars(MetaModel::EnumChildClasses($this->sClass,
								ENUM_CHILD_CLASSES_ALL));
							$oClassExpr = new FieldExpression($sClassAttCode, $sForeignClassAlias);
							$oClassRestriction = new BinaryExpression($oClassExpr, 'IN', $oClassListExpr);
							$this->oBuild->m_oQBExpressions->AddCondition($oClassRestriction);
						}

						$oOQLClassTreeBuilder = new OQLClassTreeBuilder($oForeignFilter, $this->oBuild);
						$oSelectForeign = $oOQLClassTreeBuilder->DevelopOQLClassNode();

						if ($iOperatorCode == TREE_OPERATOR_EQUALS)
						{
							$this->oOQLClassNode->AddInnerJoin($oSelectForeign, 'id', $sForeignExtKeyAttCode, false);
						}
						else
						{
							// Hierarchical key
							$this->oOQLClassNode->AddInnerJoinTree($oSelectForeign, $sForeignExtKeyAttCode, $sForeignExtKeyAttCode, false, $iOperatorCode, true);
						}
					}
				}
			}
		}
	}

	/**
	 * Additional JOINS for polymorphic expressions (friendlyname, obsolescenceflag...)
	 *
	 * @param array $aPolymorphicJoinAlias
	 *
	 * @throws \CoreException
	 */
	private function JoinClassesForPolymorphicExpressions($aPolymorphicJoinAlias)
	{
		foreach ($aPolymorphicJoinAlias as $sSubClass => $sSubClassAlias)
		{
			$oSubClassFilter = new DBObjectSearch($sSubClass, $sSubClassAlias);
			$oOQLClassTreeBuilder = new OQLClassTreeBuilder($oSubClassFilter, $this->oBuild);
			$oSelectPoly = $oOQLClassTreeBuilder->DevelopOQLClassNode();
			$this->oOQLClassNode->AddLeftJoin($oSelectPoly, 'id', 'id', true);
		}
	}

	/**
	 * Rename class aliases of nested requests to avoid collision with main request
	 */
	private function TranslateNestedRequests()
	{
		$aClassAliases = $this->oDBObjectSearch->GetJoinedClasses();
		$this->oDBObjectSearch->RenameNestedQueriesAliasesInNameSpace($aClassAliases);
	}
}
