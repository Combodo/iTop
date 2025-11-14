<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use DBObjectSearch;

/**
 * Special kind of External Key to manage a hierarchy of objects
 */
class AttributeHierarchicalKey extends AttributeExternalKey
{
	public const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_HIERARCHICAL_KEY;

	protected $m_sTargetClass;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		$aParams = parent::ListExpectedParams();
		$idx = array_search('targetclass', $aParams);
		unset($aParams[$idx]);
		$idx = array_search('jointype', $aParams);
		unset($aParams[$idx]);

		return $aParams; // Later: mettre les bons parametres ici !!
	}

	public function GetEditClass()
	{
		return "ExtKey";
	}

	public function RequiresIndex()
	{
		return true;
	}

	/*
	*  The target class is the class for which the attribute has been defined first
	*/
	public function SetHostClass($sHostClass)
	{
		if (!isset($this->m_sTargetClass)) {
			$this->m_sTargetClass = $sHostClass;
		}
		parent::SetHostClass($sHostClass);
	}

	public static function IsHierarchicalKey()
	{
		return true;
	}

	public function GetTargetClass($iType = EXTKEY_RELATIVE)
	{
		return $this->m_sTargetClass;
	}

	public function GetKeyAttDef($iType = EXTKEY_RELATIVE)
	{
		return $this;
	}

	public function GetKeyAttCode()
	{
		return $this->GetCode();
	}

	public function GetBasicFilterOperators()
	{
		return parent::GetBasicFilterOperators();
	}

	public function GetBasicFilterLooseOperator()
	{
		return parent::GetBasicFilterLooseOperator();
	}

	public function GetSQLColumns($bFullSpec = false)
	{
		$aColumns = [];
		$aColumns[$this->GetCode()] = 'INT(11)'.($bFullSpec ? ' DEFAULT 0' : '');
		$aColumns[$this->GetSQLLeft()] = 'INT(11)'.($bFullSpec ? ' DEFAULT 0' : '');
		$aColumns[$this->GetSQLRight()] = 'INT(11)'.($bFullSpec ? ' DEFAULT 0' : '');

		return $aColumns;
	}

	public function GetSQLRight()
	{
		return $this->GetCode().'_right';
	}

	public function GetSQLLeft()
	{
		return $this->GetCode().'_left';
	}

	public function GetSQLValues($value)
	{
		if (!is_array($value)) {
			$aValues[$this->GetCode()] = $value;
		} else {
			$aValues = [];
			$aValues[$this->GetCode()] = $value[$this->GetCode()];
			$aValues[$this->GetSQLRight()] = $value[$this->GetSQLRight()];
			$aValues[$this->GetSQLLeft()] = $value[$this->GetSQLLeft()];
		}

		return $aValues;
	}

	public function GetAllowedValues($aArgs = [], $sContains = '')
	{
		$oFilter = $this->GetHierachicalFilter($aArgs, $sContains);
		if ($oFilter) {
			$oValSetDef = $this->GetValuesDef();
			$oValSetDef->SetCondition($oFilter);

			return $oValSetDef->GetValues($aArgs, $sContains);
		} else {
			return parent::GetAllowedValues($aArgs, $sContains);
		}
	}

	public function GetAllowedValuesAsObjectSet($aArgs = [], $sContains = '', $iAdditionalValue = null)
	{
		$oValSetDef = $this->GetValuesDef();
		$oFilter = $this->GetHierachicalFilter($aArgs, $sContains, $iAdditionalValue);
		if ($oFilter) {
			$oValSetDef->SetCondition($oFilter);
		}
		$oSet = $oValSetDef->ToObjectSet($aArgs, $sContains, $iAdditionalValue);

		return $oSet;
	}

	public function GetAllowedValuesAsFilter($aArgs = [], $sContains = '', $iAdditionalValue = null)
	{
		$oFilter = $this->GetHierachicalFilter($aArgs, $sContains, $iAdditionalValue);
		if ($oFilter) {
			return $oFilter;
		}

		return parent::GetAllowedValuesAsFilter($aArgs, $sContains, $iAdditionalValue);
	}

	private function GetHierachicalFilter($aArgs = [], $sContains = '', $iAdditionalValue = null)
	{
		if (array_key_exists('this', $aArgs)) {
			// Hierarchical keys have one more constraint: the "parent value" cannot be
			// "under" themselves
			$iRootId = $aArgs['this']->GetKey();
			if ($iRootId > 0) { // ignore objects that do no exist in the database...
				$sClass = $this->m_sTargetClass;

				return DBObjectSearch::FromOQL("SELECT $sClass AS node JOIN $sClass AS root ON node.".$this->GetCode()." NOT BELOW root.id WHERE root.id = $iRootId");
			}
		}

		return false;
	}

	/**
	 * Find the corresponding "link" attribute on the target class, if any
	 *
	 * @return null | AttributeDefinition
	 */
	public function GetMirrorLinkAttribute()
	{
		return null;
	}
}
