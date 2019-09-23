<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class OQLClassNode
{
	private $sClass;
	private $sClassAlias;
	private $sSelectedClassAlias;
	/** @var OQLJoin[][] */
	private $aJoins;
	private $aExtKeys;
	private $oBuild;

	/**
	 * OQLClassNode constructor.
	 *
	 * @param QueryBuilderContext $oBuild
	 * @param string $sClass Current node class
	 * @param string $sClassAlias Current node class alias
	 * @param string $sSelectedClassAlias Alias of the class requested in the filter (defaulted to $sClassAlias if null)
	 */
	public function __construct($oBuild, $sClass, $sClassAlias, $sSelectedClassAlias = null)
	{
		$this->sClass = $sClass;
		$this->sClassAlias = $sClassAlias;
		$this->aJoins = array();
		$this->aExtKeys = array();
		if (is_null($sSelectedClassAlias))
		{
			$this->sSelectedClassAlias = $sClassAlias;
		}
		else
		{
			$this->sSelectedClassAlias = $sSelectedClassAlias;
		}
		$this->oBuild = $oBuild;
	}

	public function AddExternalKey($sKeyAttCode)
	{
		if (!isset($this->aExtKeys[$sKeyAttCode]))
		{
			$this->aExtKeys[$sKeyAttCode] = array();
		}
	}

	public function AddExternalField($sKeyAttCode, $sFieldAttCode, $oAttDef)
	{
		$this->AddExternalKey($sKeyAttCode);
		$this->aExtKeys[$sKeyAttCode][$sFieldAttCode] = $oAttDef;
	}


	public function AddInnerJoin($oOQLClassNode, $sLeftField, $sRightField, $bOutbound = true)
	{
		$this->AddJoin(OQLJoin::JOIN_INNER, $oOQLClassNode, $sLeftField, $sRightField, $bOutbound);
	}

	public function AddLeftJoin($oOQLClassNode, $sLeftField, $sRightField, $bOutbound = true)
	{
		$this->AddJoin(OQLJoin::JOIN_LEFT, $oOQLClassNode, $sLeftField, $sRightField, $bOutbound);
	}
	
	public function AddInnerJoinTree($oOQLClassNode, $sLeftField, $sRightField, $bOutbound = true, $iOperatorCode = TREE_OPERATOR_BELOW, $bInvertOnClause = false)
	{
		$this->AddJoin(OQLJoin::JOIN_INNER_TREE, $oOQLClassNode, $sLeftField, $sRightField, $bOutbound, $iOperatorCode, $bInvertOnClause);
	}

	private function AddJoin($sJoinType, $oOQLClassNode, $sLeftField, $sRightField, $bOutbound = true, $sTreeOperator = null, $bInvertOnClause = false)
	{
		$oOQLJoin = new OQLJoin($this->oBuild, $sJoinType, $oOQLClassNode, $sLeftField, $sRightField, $bOutbound, $sTreeOperator,
			$bInvertOnClause);
		$this->AddOQLJoin($sLeftField, $oOQLJoin);
	}

	/**
	 * @param string $sLeftField
	 * @param OQLJoin $oOQLJoin
	 */
	public function AddOQLJoin($sLeftField, $oOQLJoin)
	{
		// Record Left join field expression
		// (right join field expression is recorded in OQLJoin)
		$sJoinFieldName = $this->GetClassAlias().'.'.$sLeftField;
		$this->oBuild->m_oQBExpressions->AddJoinField($sJoinFieldName, new FieldExpression($sLeftField, $this->GetClassAlias()));

		$this->aJoins[$sLeftField][] = $oOQLJoin;
	}

	public function DisplayHtml()
	{
	}

	public function RenderDebug()
	{
		$sOQL = "SELECT `{$this->sClassAlias}` FROM `{$this->sClass}` AS `{$this->sClassAlias}`";
		foreach ($this->aJoins as $aJoins)
		{
			foreach ($aJoins as $oJoin)
			{
				$sOQL .= "{$oJoin->RenderDebug($this->sClassAlias)}";
			}
		}


		return $sOQL;
	}

	public function GetExternalKeys()
	{
		return $this->aExtKeys;
	}

	public function HasExternalKey($sAttCode)
	{
		return array_key_exists($sAttCode, $this->aExtKeys);
	}

	public function GetExternalKey($sAttCode)
	{
		return $this->aExtKeys[$sAttCode];
	}

	public function GetClass()
	{
		return $this->sClass;
	}

	public function GetClassAlias()
	{
		return $this->sClassAlias;
	}

	/**
	 * @return string
	 */
	public function GetSelectedClassAlias()
	{
		return $this->sSelectedClassAlias;
	}

	public function GetJoins()
	{
		return $this->aJoins;
	}

	public function RemoveJoin($sLeftKey, $index)
	{
		unset($this->aJoins[$sLeftKey][$index]);
		if (empty($this->aJoins[$sLeftKey]))
		{
			unset($this->aJoins[$sLeftKey]);
		}
	}

}

class OQLJoin
{
	const JOIN_INNER = 'inner';
	const JOIN_LEFT = 'left';
	const JOIN_INNER_TREE = 'inner_tree';
	
	private $sJoinType;
	/** @var \OQLClassNode */
	private $oOQLClassNode;

	private $bOutbound;
	private $sLeftField;
	private $sRightField;
	private $sTreeOperator;
	private $bInvertOnClause;
	private $oBuild;

	/**
	 * OQLJoin constructor.
	 *
	 * @param QueryBuilderContext $oBuild
	 * @param string $sJoinType
	 * @param OQLClassNode $oOQLClassNode
	 * @param string $sLeftField
	 * @param string $sRightField
	 * @param bool $bOutbound
	 * @param string $sTreeOperator
	 * @param bool $bInvertOnClause
	 */
	public function __construct($oBuild, $sJoinType, $oOQLClassNode, $sLeftField, $sRightField, $bOutbound = true, $sTreeOperator = null, $bInvertOnClause = false)
	{
		// Record right join field expression
		// (left join field expression is recorded in OQLClassNode)
		$sJoinFieldName = $oOQLClassNode->GetClassAlias().'.'.$sRightField;
		$oBuild->m_oQBExpressions->AddJoinField($sJoinFieldName, new FieldExpression($sRightField, $oOQLClassNode->GetClassAlias()));

		$this->sJoinType = $sJoinType;
		$this->oOQLClassNode = $oOQLClassNode;
		$this->sLeftField = $sLeftField;
		$this->sRightField = $sRightField;
		$this->sTreeOperator = $sTreeOperator;
		$this->bInvertOnClause = $bInvertOnClause;
		$this->bOutbound = $bOutbound;
		$this->oBuild = $oBuild;
	}

	public function NewOQLJoinWithClassNode($oOQLClassNode)
	{
		return new self($this->oBuild, $this->sJoinType, $oOQLClassNode, $this->sLeftField, $this->sRightField, $this->bOutbound,
			$this->sTreeOperator, $this->bInvertOnClause);
	}

	/**
	 * @param QueryBuilderContext $oBuild
	 * @param SQLObjectQuery $oBaseSQLQuery
	 * @param SQLObjectQuery $oJoinedSQLQuery
	 */
	public function AddToSQLObjectQuery($oBuild, $oBaseSQLQuery, $oJoinedSQLQuery)
	{
		// Translate the fields before copy to SQL
		$sLeft = $oBaseSQLQuery->GetTableAlias().'.'.$this->sLeftField;
		$oLeftField = $oBuild->m_oQBExpressions->GetJoinField($sLeft);
		if ($oLeftField)
		{
			$sSQLLeft = $oLeftField->GetName();
		}
		else
		{
			$sSQLLeft = "no_field_found_for_$sLeft";
		}
		$sRight = $oJoinedSQLQuery->GetTableAlias().'.'.$this->sRightField;
		$oRightField = $oBuild->m_oQBExpressions->GetJoinField($sRight);
		if ($oRightField)
		{
			$sSQLRight = $oRightField->GetName();
		}
		else
		{
			$sSQLRight = "no_field_found_for_$sRight";
		}

		switch ($this->sJoinType)
		{
			case self::JOIN_INNER:
				$oBaseSQLQuery->AddInnerJoin($oJoinedSQLQuery, $sSQLLeft, $sSQLRight);
				break;
			case self::JOIN_LEFT:
				$oBaseSQLQuery->AddLeftJoin($oJoinedSQLQuery, $sSQLLeft, $sSQLRight);
				break;
			case self::JOIN_INNER_TREE:
				$sLeftFieldLeft = $sSQLLeft.'_left';
				$sLeftFieldRight = $sSQLLeft.'_right';
				$sRightFieldLeft = $sSQLRight.'_left';
				$sRightFieldRight = $sSQLRight.'_right';
				$sRightTableAlias = $this->oOQLClassNode->GetClassAlias();
				$oBaseSQLQuery->AddInnerJoinTree($oJoinedSQLQuery, $sLeftFieldLeft, $sLeftFieldRight, $sRightFieldLeft, $sRightFieldRight, $sRightTableAlias, $this->sTreeOperator, $this->bInvertOnClause);
				break;
		}
	}

	public function RenderDebug($sClassAlias, $sPrefix = "    ")
	{
		$sType = strtoupper($this->sJoinType);
		$sOQL = "\n{$sPrefix}{$sType} JOIN `{$this->oOQLClassNode->GetClass()}` AS `{$this->oOQLClassNode->GetClassAlias()}`";
		//$sOQL = str_pad($sOQL, 100);
		$sOQL .= "\n{$sPrefix}  ON `{$sClassAlias}`.`{$this->sLeftField}` = `{$this->oOQLClassNode->GetClassAlias()}`.`{$this->sRightField}`";
		$sPrefix .= "    ";
		foreach ($this->oOQLClassNode->GetJoins() as $aJoins)
		{
			foreach ($aJoins as $oJoin)
			{
				$sOQL .= " {$oJoin->RenderDebug($this->oOQLClassNode->GetClassAlias(), $sPrefix)}";
			}
		}

		return $sOQL;
	}

	/**
	 * @return \OQLClassNode
	 */
	public function GetOOQLClassNode()
	{
		return $this->oOQLClassNode;
	}

	/**
	 * @return bool
	 */
	public function IsOutbound()
	{
		return $this->bOutbound;
	}

}