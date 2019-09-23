<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
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
	 */
	public function __construct($oOQLClassNode, $oBuild)
	{
		$this->oOQLClassNode = $oOQLClassNode;
		$this->oBuild = $oBuild;
	}

	/**
	 * Assign attributes on their original classes
	 *
	 * @throws \CoreException
	 */
	public function Resolve()
	{
		$sClassAlias = $this->oOQLClassNode->GetClassAlias();
		$sClass = $this->oOQLClassNode->GetClass();
		$aExpectedAttributes = $this->oBuild->m_oQBExpressions->GetUnresolvedFields($sClassAlias);
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

		$aTranslateFields = array();
		foreach ($aExpectedAttributes as $sAttCode => $oExpression)
		{
			if (!MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				continue;
			}
			$sOriginClass = MetaModel::GetAttributeOrigin($sClass, $sAttCode);
			if (is_null($aClassAndAncestorsNodes[$sOriginClass]))
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
				$sOriginClassAlias = $oOriginClassNode->GetClassAlias();
				$aTranslateFields[$sClassAlias][$sAttCode] = new FieldExpression($sAttCode, $sOriginClassAlias);
			}

			// Add Joins corresponding to external keys
			$this->ResolveJoins($sAttCode, $oOriginClassNode);
		}

		// Create joins for ancestor classes
		/** @var \OQLClassNode $oBaseNode */
		$oBaseNode = null;
		foreach ($aClassAndAncestorsNodes as $sOriginClass => $oOriginClassNode)
		{
			if (is_null($oOriginClassNode))
			{
				continue;
			}
			if (is_null($oBaseNode))
			{
				$oBaseNode = $oOriginClassNode;
				continue;
			}
			// Add inner join
			$oBaseNode->AddInnerJoin($oOriginClassNode, 'id', 'id');
		}

		if (is_null($oBaseNode))
		{
			// If no class was generated above, keep the initial one
			return $this->oOQLClassNode;
		}

		if (isset($aExpectedAttributes['id']) && !isset($aClassAndAncestorsNodes[$sClass]))
		{
			$sFirstClassAlias = $oBaseNode->GetClassAlias();
			$aTranslateFields[$sClassAlias]['id'] = new FieldExpression('id', $sFirstClassAlias);
		}
		$this->oBuild->m_oQBExpressions->Translate($aTranslateFields, false);

		// Add Joins corresponding to 'id'
		$this->ResolveJoins('id', $oBaseNode);

		// Add finalclass condition if not the requested class
		if ($oBaseNode->GetClass() != $sClass)
		{
			$sExpectedClasses = implode("', '", MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL));
			$oInExpression = Expression::FromOQL("`".$oBaseNode->GetClassAlias()."`.finalclass IN ('$sExpectedClasses')");
			$oTrueExpression = new TrueExpression();
			$aCoalesceAttr = array($oInExpression, $oTrueExpression);
			$oFinalClassRestriction = new FunctionExpression("COALESCE", $aCoalesceAttr);
			$this->oBuild->m_oQBExpressions->AddCondition($oFinalClassRestriction);
		}

		return $oBaseNode;
	}

	/**
	 * Move the joins from the selected class to the class where the external key is instantiated
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
				$oBaseJoinedClassNode = $oBaseOQLJoin->GetOOQLClassNode();
				$oOQLActualClassTreeResolver = new OQLActualClassTreeResolver($oBaseJoinedClassNode, $this->oBuild);
				$oResolvedClassNode = $oOQLActualClassTreeResolver->Resolve();
				$oOriginClassNode->AddOQLJoin($sAttCode, $oBaseOQLJoin->NewOQLJoinWithClassNode($oResolvedClassNode));
			}
		}
	}
}