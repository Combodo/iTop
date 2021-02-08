<?php

class QueryBuilderExpressions
{
	/**
	 * @var Expression
	 */
	protected $m_oConditionExpr;
	/**
	 * @var Expression[]
	 */
	protected $m_aSelectExpr;
	/**
	 * @var Expression[]
	 */
	protected $m_aGroupByExpr;
	/**
	 * @var Expression[]
	 */
	protected $m_aJoinFields;
	/**
	 * @var string[]
	 */
	protected $m_aClassIds;

	public function __construct(DBObjectSearch $oSearch, $aGroupByExpr = null, $aSelectExpr = null)
	{
		$this->m_oConditionExpr = $oSearch->GetCriteria();
		if (!$oSearch->GetShowObsoleteData())
		{
			foreach ($oSearch->GetSelectedClasses() as $sAlias => $sClass)
			{
				if (MetaModel::IsObsoletable($sClass))
				{
					$oNotObsolete = new BinaryExpression(new FieldExpression('obsolescence_flag', $sAlias), '=', new ScalarExpression(0));
					$this->m_oConditionExpr = $this->m_oConditionExpr->LogAnd($oNotObsolete);
				}
			}
		}
		$this->m_aSelectExpr = is_null($aSelectExpr) ? array() : $aSelectExpr;
		$this->m_aGroupByExpr = $aGroupByExpr;
		$this->m_aJoinFields = array();
		$this->m_aJoinFields = array();

		$this->m_aClassIds = array();
		foreach ($oSearch->GetJoinedClasses() as $sClassAlias => $sClass)
		{
			$this->m_aClassIds[$sClassAlias] = new FieldExpression('id', $sClassAlias);
		}
	}

	public function GetSelect()
	{
		return $this->m_aSelectExpr;
	}

	public function GetGroupBy()
	{
		return $this->m_aGroupByExpr;
	}

	public function GetCondition()
	{
		return $this->m_oConditionExpr;
	}

	/**
	 * @return Expression|mixed
	 */
	public function PopJoinField()
	{
		return array_pop($this->m_aJoinFields);
	}

	/**
	 * @param string $sAttAlias
	 * @param Expression $oExpression
	 */
	public function AddSelect($sAttAlias, Expression $oExpression)
	{
		$this->m_aSelectExpr[$sAttAlias] = $oExpression;
	}

	/**
	 * @param Expression $oExpression
	 */
	public function AddCondition(Expression $oExpression)
	{
		$this->m_oConditionExpr = $this->m_oConditionExpr->LogAnd($oExpression);
	}

	/**
	 * @param Expression $oExpression
	 */
	public function PushJoinField(Expression $oExpression)
	{
		array_push($this->m_aJoinFields, $oExpression);
	}

	public function AddJoinField($sName, Expression $oExpression)
	{
		$this->m_aJoinFields[$sName] = $oExpression;
	}

	public function GetJoinField($sName)
	{
		return isset($this->m_aJoinFields[$sName]) ? $this->m_aJoinFields[$sName] : null;
	}

	/**
	 * Get tables representing the queried objects
	 * Could be further optimized: when the first join is an outer join, then the rest can be omitted
	 *
	 * @param array $aTables
	 *
	 * @return array
	 */
	public function GetMandatoryTables(&$aTables = null)
	{
		if (is_null($aTables))
		{
			$aTables = array();
		}

		foreach ($this->m_aClassIds as $sClass => $oExpression)
		{
			$oExpression->CollectUsedParents($aTables);
		}

		return $aTables;
	}

	/**
	 * @param $sAlias
	 *
	 * @return FieldExpression[] of unresolved fields
	 */
	public function GetUnresolvedFields($sAlias)
	{
		$aUnresolved = $this->GetExpectedFields($sAlias);
		foreach ($this->m_aJoinFields as $oExpression)
		{
			$oExpression->GetUnresolvedFields($sAlias, $aUnresolved);
		}
		return $aUnresolved;
	}

	/**
	 * Get Expected fields from Select and Conditions
	 * (Joins are excluded)
	 *
	 * @param string $sAlias
	 *
	 * @return FieldExpression[]
	 */
	public function GetExpectedFields($sAlias)
	{
		$aUnresolved = array();
		$this->m_oConditionExpr->GetUnresolvedFields($sAlias, $aUnresolved);

		foreach ($this->m_aSelectExpr as $sColAlias => $oExpr)
		{
			$oExpr->GetUnresolvedFields($sAlias, $aUnresolved);
		}
		if (!empty($this->m_aGroupByExpr))
		{
			foreach ($this->m_aGroupByExpr as $sColAlias => $oExpr)
			{
				$oExpr->GetUnresolvedFields($sAlias, $aUnresolved);
			}
		}
		return $aUnresolved;
	}

	public function Translate($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true)
	{
		$this->m_oConditionExpr = $this->m_oConditionExpr->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
		foreach ($this->m_aSelectExpr as $sColAlias => $oExpr)
		{
			$this->m_aSelectExpr[$sColAlias] = $oExpr->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
			if ($this->m_aSelectExpr[$sColAlias] instanceof FieldExpressionResolved)
			{
				// Split the field with the relevant alias
				foreach ($this->m_aSelectExpr[$sColAlias]->AdditionalExpressions() as $sSuffix => $oAdditionalExpr)
				{
					$this->m_aSelectExpr[$sColAlias.$sSuffix] = $oAdditionalExpr->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
				}
			}
		}
		if ($this->m_aGroupByExpr)
		{
			foreach ($this->m_aGroupByExpr as $sColAlias => $oExpr)
			{
				$this->m_aGroupByExpr[$sColAlias] = $oExpr->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
			}
		}
		foreach ($this->m_aJoinFields as $index => $oExpression)
		{
			$this->m_aJoinFields[$index] = $oExpression->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
		}
		foreach ($this->m_aClassIds as $sClass => $oExpression)
		{
			$this->m_aClassIds[$sClass] = $oExpression->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
		}
	}

	public function RenameParam($sOldName, $sNewName)
	{
		$this->m_oConditionExpr->RenameParam($sOldName, $sNewName);
		foreach ($this->m_aSelectExpr as $sColAlias => $oExpr)
		{
			$this->m_aSelectExpr[$sColAlias] = $oExpr->RenameParam($sOldName, $sNewName);
		}
		if ($this->m_aGroupByExpr)
		{
			foreach ($this->m_aGroupByExpr as $sColAlias => $oExpr)
			{
				$this->m_aGroupByExpr[$sColAlias] = $oExpr->RenameParam($sOldName, $sNewName);
			}
		}
		foreach ($this->m_aJoinFields as $index => $oExpression)
		{
			$this->m_aJoinFields[$index] = $oExpression->RenameParam($sOldName, $sNewName);
		}
	}
}