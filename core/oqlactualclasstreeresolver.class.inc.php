<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class OQLActualClassTreeResolver
{
	/** @var OQLClassNode */
	private $oOQLClassNode;
	/** @var QueryBuilderContext */
	private $oBuild;

	/**
	 * OQLActualClassTreeResolver constructor.
	 *
	 * @param OQLClassNode $oOQLClassNode
	 * @param QueryBuilderContext $oBuild
	 * @param array $aJoinedAliases
	 */
	public function __construct($oOQLClassNode, $oBuild)
	{
		$this->oOQLClassNode = $oOQLClassNode;
		$this->oBuild = $oBuild;
	}

	/**
	 * Assign attributes on their original classes
	 *
	 * @param string $sIncomingKeyAttCode Key used for the join (entry point of the class)
	 *
	 * @return \OQLClassNode
	 * @throws \CoreException
	 */
	public function Resolve($sIncomingKeyAttCode = null)
	{
		$sClass = $this->oOQLClassNode->GetNodeClass();
		$sClassAlias = $this->oOQLClassNode->GetNodeClassAlias();
		$aExpectedAttributes = $this->oBuild->m_oQBExpressions->GetUnresolvedFields($sClassAlias);
		if (!is_null($sIncomingKeyAttCode) && !isset($aExpectedAttributes[$sIncomingKeyAttCode]))
		{
			// Add entry point as expected attribute
			$aExpectedAttributes[$sIncomingKeyAttCode] = new FieldExpression($sIncomingKeyAttCode, $sClassAlias);
		}
		$aClasses = MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL, false);
		/** @var OQLClassNode[] $aClassAndAncestorsNodes */
		$aClassAndAncestorsNodes = array();
		foreach ($aClasses as $sFamilyClass)
		{
			// Remove unnecessary classes
			if (MetaModel::HasTable($sFamilyClass))
			{
				$aClassAndAncestorsNodes[$sFamilyClass] = null;
			}
		}

		if (empty($aClassAndAncestorsNodes))
		{
			throw new CoreException("Impossible to query the class $sClass");
		}

		$oBaseNode = null;
		$aTranslateFields = array();
		foreach ($aExpectedAttributes as $sAttCode => $oExpression)
		{
			// 'id' is managed later
			if ($sAttCode == 'id')
			{
				continue;
			}
			// Attributes can be stored in attributes list or for magic ones into filter codes list.
			$sOriginClass = null;
			if (MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				$sOriginClass = MetaModel::GetAttributeOrigin($sClass, $sAttCode);
			}
			else if (MetaModel::IsValidFilterCode($sClass, $sAttCode))
			{
				$sOriginClass = MetaModel::GetFilterCodeOrigin($sClass, $sAttCode);
			}
			else
			{
				continue;
			}
			if (!isset($aClassAndAncestorsNodes[$sOriginClass]) || is_null($aClassAndAncestorsNodes[$sOriginClass]))
			{
				if ($sOriginClass == $sClass)
				{
					$sOriginClassAlias = $sClassAlias;
				}
				else
				{
					$sOriginClassAlias = $this->oBuild->GenerateTableAlias($sClassAlias.'_'.$sOriginClass, $sClass);
				}

				$oOriginClassNode = new OQLClassNode($this->oBuild, $sOriginClass, $sOriginClassAlias, $sClassAlias);
				$aClassAndAncestorsNodes[$sOriginClass] = $oOriginClassNode;
			}
			else
			{
				$oOriginClassNode = $aClassAndAncestorsNodes[$sOriginClass];
			}

			if ($sOriginClass != $sClass)
			{
				// Alias changed, set a new translation
				$sOriginClassAlias = $oOriginClassNode->GetNodeClassAlias();
				$aTranslateFields[$sClassAlias][$sAttCode] = new FieldExpression($sAttCode, $sOriginClassAlias);
			}

			// Add Joins corresponding to external keys
			$this->ResolveJoins($sAttCode, $oOriginClassNode);

			if ($sAttCode === $sIncomingKeyAttCode)
			{
				// This is the entry point of the class
				$oBaseNode = $oOriginClassNode;
			}
		}

		// Create joins for ancestor classes
		/** @var \OQLClassNode $oBaseNode */
		$sFirstValidAncestor = null;
		foreach ($aClassAndAncestorsNodes as $sOriginClass => $oOriginClassNode)
		{
			if (is_null($sFirstValidAncestor))
			{
				$sFirstValidAncestor = $sOriginClass;
			}
			if (is_null($oOriginClassNode))
			{
				continue;
			}
			if (is_null($oBaseNode))
			{
				$oBaseNode = $oOriginClassNode;
				continue;
			}
			if ($oBaseNode === $oOriginClassNode)
			{
				// Don't link to itself
				continue;
			}
			$oBaseNode->AddInnerJoin($oOriginClassNode, 'id', 'id');
		}

		if (is_null($oBaseNode))
		{
			// If no class was generated above, keep the first valid ancestor
			if (is_null($sFirstValidAncestor) || ($sFirstValidAncestor == $sClass))
			{
				// take current node
				$oBaseNode = $this->oOQLClassNode->CloneNode();
			}
			else
			{
				// Use the first valid class to build a default node
				$sDefaultClassAlias = $this->oBuild->GenerateTableAlias($sClassAlias.'_'.$sFirstValidAncestor, $sClass);
				$oBaseNode = new OQLClassNode($this->oBuild, $sFirstValidAncestor, $sDefaultClassAlias);
			}
		}

		if (isset($aExpectedAttributes['id']) && !isset($aClassAndAncestorsNodes[$sClass]))
		{
			$sFirstClassAlias = $oBaseNode->GetNodeClassAlias();
			$aTranslateFields[$sClassAlias]['id'] = new FieldExpression('id', $sFirstClassAlias);
		}
		$this->oBuild->m_oQBExpressions->Translate($aTranslateFields, false);

		// Add Joins corresponding to 'id'
		$this->ResolveJoins('id', $oBaseNode);

		// Add finalclass condition if not the requested class
		if ($oBaseNode->GetNodeClass() != $sClass)
		{
			$sExpectedClasses = implode("', '", MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL));
			$oInExpression = Expression::FromOQL("`".$oBaseNode->GetNodeClassAlias()."`.finalclass IN ('$sExpectedClasses')");
			$oTrueExpression = new TrueExpression();
			$aCoalesceAttr = array($oInExpression, $oTrueExpression);
			$oFinalClassRestriction = new FunctionExpression("COALESCE", $aCoalesceAttr);
			$this->oBuild->m_oQBExpressions->AddCondition($oFinalClassRestriction);
		}

		return $oBaseNode;
	}

	/**
	 * Move the joins from the selected class to the class where the external key is instantiated
	 * The joined class is also resolved using the right key as entry point
	 *
	 * @param string $sAttCode (can be an external key)
	 * @param \OQLClassNode $oOriginClassNode real class to join
	 *
	 * @throws \CoreException
	 */
	private function ResolveJoins($sAttCode, OQLClassNode $oOriginClassNode)
	{
		// Joins on the selected class
		$aJoins = $this->oOQLClassNode->GetJoins();

		if (isset($aJoins[$sAttCode]))
		{
			foreach ($aJoins[$sAttCode] as $oBaseOQLJoin)
			{
				// transfer the join from OQL class tree to actual class tree
				$oBaseJoinedClassNode = $oBaseOQLJoin->GetOOQLClassNode();
				$oOQLActualClassTreeResolver = new OQLActualClassTreeResolver($oBaseJoinedClassNode, $this->oBuild);
				// Use the right key to link to actual join class tree
				$oResolvedClassNode = $oOQLActualClassTreeResolver->Resolve($oBaseOQLJoin->GetRightField());
				$oOriginClassNode->AddOQLJoin($sAttCode, $oBaseOQLJoin->NewOQLJoinWithClassNode($oResolvedClassNode));
			}
		}
	}
}
