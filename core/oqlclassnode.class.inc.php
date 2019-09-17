<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class OQLClassNode
{
	private $sClass;
	private $sClassAlias;
	/** @var OQLJoin[] */
	private $aJoins;
	private $aExtKeys;

	/**
	 * OQLClassNode constructor.
	 *
	 * @param string $sClass
	 * @param string $sClassAlias
	 */
	public function __construct($sClass, $sClassAlias)
	{
		$this->sClass = $sClass;
		$this->sClassAlias = $sClassAlias;
		$this->aJoins = array();
		$this->aExtKeys = array();
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
		$oOQLJoin = new OQLJoin($sJoinType, $oOQLClassNode, $sLeftField, $sRightField, $bOutbound, $sTreeOperator, $bInvertOnClause);
		$this->aJoins[] = $oOQLJoin;
		return $oOQLJoin;
	}

	public function DisplayHtml()
	{
	}

	public function RenderDebug()
	{
		$sOQL = "SELECT `{$this->sClassAlias}` FROM `{$this->sClass}` AS `{$this->sClassAlias}`";
		foreach ($this->aJoins as $oJoin)
		{
			$sOQL .= "{$oJoin->RenderDebug($this->sClassAlias)}";
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

	public function GetJoins()
	{
		return $this->aJoins;
	}

	public function RemoveJoin($index)
	{
		unset($this->aJoins[$index]);
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

	/**
	 * OQLJoin constructor.
	 *
	 * @param $sJoinType
	 * @param $oOQLClassNode
	 * @param $sLeftField
	 * @param $sRightField
	 * @param bool $bOutbound
	 * @param string $sTreeOperator
	 * @param bool $bInvertOnClause
	 */
	public function __construct($sJoinType, $oOQLClassNode, $sLeftField, $sRightField, $bOutbound = true, $sTreeOperator = null, $bInvertOnClause = false)
	{
		$this->sJoinType = $sJoinType;
		$this->oOQLClassNode = $oOQLClassNode;
		$this->sLeftField = $sLeftField;
		$this->sRightField = $sRightField;
		$this->sTreeOperator = $sTreeOperator;
		$this->bInvertOnClause = $bInvertOnClause;
		$this->bOutbound = $bOutbound;
	}

	public function RenderDebug($sClassAlias, $sPrefix = "    ")
	{
		$sType = strtoupper($this->sJoinType);
		$sOQL = "\n{$sPrefix}{$sType} JOIN `{$this->oOQLClassNode->GetClass()}` AS `{$this->oOQLClassNode->GetClassAlias()}`";
		//$sOQL = str_pad($sOQL, 100);
		$sOQL .= "\n{$sPrefix}  ON `{$sClassAlias}`.`{$this->sLeftField}` = `{$this->oOQLClassNode->GetClassAlias()}`.`{$this->sRightField}`";
		$sPrefix .= "    ";
		foreach ($this->oOQLClassNode->GetJoins() as $oJoin)
		{
			$sOQL .= " {$oJoin->RenderDebug($this->oOQLClassNode->GetClassAlias(), $sPrefix)}";
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