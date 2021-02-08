<?php
// Copyright (c) 2010-2018 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
//

// THIS FILE IS DEPRECATED

/** @internal Dev hack for disabling some query build optimizations (Folding/Merging) */
define('ENABLE_OPT', true);

/**
 * A search over a DBObject
 *
 * This is the most common search cases, the other class representing a search is DBUnionSearch.
 * For clarity purpose, since only the constructor vary between DBObjectSearch and DBUnionSearch, all the API is documented on the common
 * ancestor: DBSearch Please refer to DBSearch's documentation
 *
 * @api
 * @see DBSearch
 * @see DBUnionSearch
 * @package     iTopORM
 * @phpdoc-tuning-exclude-inherited this tag prevent PHPdoc from displaying inherited methods. This is done in order to force the API doc.
 *     location into DBSearch only.
 */
class DBObjectSearch extends DBSearch
{
	private $m_aClasses; // queried classes (alias => class name), the first item is the class corresponding to this filter (the rest is coming from subfilters)
	private $m_aSelectedClasses; // selected for the output (alias => class name)
	private $m_oSearchCondition;
	private $m_aParams;
	private $m_aPointingTo;
	private $m_aReferencedBy;

    /**
     * @var bool whether or not some information should be hidden to the current user. Default to false == hide information.
     * @see AllowAllData()
     */
	protected $m_bAllowAllData = false;
	protected $m_bDataFiltered = false;

	public function ToJSON()
	{
		return '{}';
	}

	/**
     * DBObjectSearch constructor.
     *
     * @api
     *
     * @param string      $sClass
     * @param string|null $sClassAlias
     *
     * @throws Exception
     */
	public function __construct($sClass, $sClassAlias = null)
	{
		parent::__construct();

		if (is_null($sClassAlias)) $sClassAlias = $sClass;
		if(!is_string($sClass)) throw new Exception('DBObjectSearch::__construct called with a non-string parameter: $sClass = '.print_r($sClass, true));
		if(!MetaModel::IsValidClass($sClass)) throw new Exception('DBObjectSearch::__construct called for an invalid class: "'.$sClass.'"');

		$this->m_aSelectedClasses = array($sClassAlias => $sClass);
		$this->m_aClasses = array($sClassAlias => $sClass);
		$this->m_oSearchCondition = new TrueExpression;
		$this->m_aParams = array();
		$this->m_aPointingTo = array();
		$this->m_aReferencedBy = array();
	}

	public function AllowAllData($bAllowAllData = true) {$this->m_bAllowAllData = $bAllowAllData;}
	public function IsAllDataAllowed() {return $this->m_bAllowAllData;}
	protected function IsDataFiltered() {return $this->m_bDataFiltered; }
	protected function SetDataFiltered() {$this->m_bDataFiltered = true;}

	// Create a search definition that leads to 0 result, still a valid search object
	static public function FromEmptySet($sClass)
	{
		$oResultFilter = new DBObjectSearch($sClass);
		$oResultFilter->m_oSearchCondition = new FalseExpression;
		return $oResultFilter;
	}


	public function GetJoinedClasses() {return $this->m_aClasses;}

	public function GetClassName($sAlias)
	{
		if (array_key_exists($sAlias, $this->m_aSelectedClasses))
		{
			return $this->m_aSelectedClasses[$sAlias];
		}
		else
		{
			throw new CoreException("Invalid class alias '$sAlias'");
		}
	}

	public function GetClass()
	{
		return reset($this->m_aSelectedClasses);
	}
	public function GetClassAlias()
	{
		reset($this->m_aSelectedClasses);
		return key($this->m_aSelectedClasses);
	}

	public function GetFirstJoinedClass()
	{
		return reset($this->m_aClasses);
	}
	public function GetFirstJoinedClassAlias()
	{
		reset($this->m_aClasses);
		return key($this->m_aClasses);
	}

	/**
	 * Change the class (only subclasses are supported as of now, because the conditions must fit the new class)
	 * Defaults to the first selected class (most of the time it is also the first joined class
	 *
	 * @param $sNewClass
	 * @param null $sAlias
	 *
	 * @throws \CoreException
	 */
	public function ChangeClass($sNewClass, $sAlias = null)
	{
		if (is_null($sAlias))
		{
			$sAlias = $this->GetClassAlias();
		}
		else
		{
			if (!array_key_exists($sAlias, $this->m_aSelectedClasses))
			{
				// discard silently - necessary when recursing on the related nodes (see code below)
				return;
			}
		}
		$sCurrClass = $this->GetClassName($sAlias);
		if ($sNewClass == $sCurrClass)
		{
			// Skip silently
			return;
		}
		if (!MetaModel::IsParentClass($sCurrClass, $sNewClass))
		{
			throw new Exception("Could not change the search class from '$sCurrClass' to '$sNewClass'. Only child classes are permitted.");
		}

		// Change for this node
		//
		$this->m_aSelectedClasses[$sAlias] = $sNewClass;
		$this->m_aClasses[$sAlias] = $sNewClass;

		// Change for all the related node (yes, this was necessary with some queries - strange effects otherwise)
		//
		foreach($this->m_aPointingTo as $sExtKeyAttCode=>$aPointingTo)
		{
			foreach($aPointingTo as $iOperatorCode => $aFilter)
			{
				foreach($aFilter as $oExtFilter)
				{
					$oExtFilter->ChangeClass($sNewClass, $sAlias);
				}
			}
		}
		foreach($this->m_aReferencedBy as $sForeignClass => $aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode => $aFiltersByOperator)
			{
				foreach ($aFiltersByOperator as $iOperatorCode => $aFilters)
				{
					foreach ($aFilters as $oForeignFilter)
					{
						$oForeignFilter->ChangeClass($sNewClass, $sAlias);
					}
				}
			}
		}
	}

	public function GetSelectedClasses()
	{
		return $this->m_aSelectedClasses;
	}

	/**
	 * @param array $aSelectedClasses array of aliases
	 * @throws CoreException
	 */
	public function SetSelectedClasses($aSelectedClasses)
	{
		$this->m_aSelectedClasses = array();
		foreach ($aSelectedClasses as $sAlias)
		{
			if (!array_key_exists($sAlias, $this->m_aClasses))
			{
				throw new CoreException("SetSelectedClasses: Invalid class alias $sAlias");
			}
			$this->m_aSelectedClasses[$sAlias] = $this->m_aClasses[$sAlias];
		}
	}

	/**
	 * Change any alias of the query tree
	 *
	 * @param $sOldName
	 * @param $sNewName
	 *
	 * @return bool True if the alias has been found and changed
	 * @throws \Exception
	 */
	public function RenameAlias($sOldName, $sNewName)
	{
		$bFound = false;
		if (array_key_exists($sOldName, $this->m_aClasses))
		{
			$bFound = true;
		}
		if (array_key_exists($sNewName, $this->m_aClasses))
		{
			throw new Exception("RenameAlias: alias '$sNewName' already used");
		}

		$aClasses = array();
		foreach ($this->m_aClasses as $sAlias => $sClass)
		{
			if ($sAlias === $sOldName)
			{
				$aClasses[$sNewName] = $sClass;
			}
			else
			{
				$aClasses[$sAlias] = $sClass;
			}
		}
		$this->m_aClasses = $aClasses;

		$aSelectedClasses = array();
		foreach ($this->m_aSelectedClasses as $sAlias => $sClass)
		{
			if ($sAlias === $sOldName)
			{
				$aSelectedClasses[$sNewName] = $sClass;
			}
			else
			{
				$aSelectedClasses[$sAlias] = $sClass;
			}
		}
		$this->m_aSelectedClasses = $aSelectedClasses;

		$this->m_oSearchCondition->RenameAlias($sOldName, $sNewName);

		foreach($this->m_aPointingTo as $sExtKeyAttCode=>$aPointingTo)
		{
			foreach($aPointingTo as $iOperatorCode => $aFilter)
			{
				foreach($aFilter as $oExtFilter)
				{
					$bFound = $oExtFilter->RenameAlias($sOldName, $sNewName) || $bFound;
				}
			}
		}
		foreach($this->m_aReferencedBy as $sForeignClass => $aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode => $aFiltersByOperator)
			{
				foreach ($aFiltersByOperator as $iOperatorCode => $aFilters)
				{
					foreach ($aFilters as $oForeignFilter)
					{
						$bFound = $oForeignFilter->RenameAlias($sOldName, $sNewName) || $bFound;
					}
				}
			}
		}
		return $bFound;
	}


	public function RenameAliasesInNameSpace($aClassAliases, $aAliasTranslation = array())
	{
	}

	public function TranslateConditions($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true)
	{
	}


	public function SetModifierProperty($sPluginClass, $sProperty, $value)
	{
		$this->m_aModifierProperties[$sPluginClass][$sProperty] = $value;
	}

	public function GetModifierProperties($sPluginClass)
	{
		if (array_key_exists($sPluginClass, $this->m_aModifierProperties))
		{
			return $this->m_aModifierProperties[$sPluginClass];
		}
		else
		{
			return array();
		}
	}

	public function IsAny()
	{
		if (!$this->m_oSearchCondition->IsTrue()) return false;
		if (count($this->m_aPointingTo) > 0) return false;
		if (count($this->m_aReferencedBy) > 0) return false;
		return true;
	}

	protected function TransferConditionExpression($oFilter, $aTranslation)
	{
		// Prevent collisions in the parameter names by renaming them if needed
		foreach($this->m_aParams as $sParam => $value)
		{
			if (array_key_exists($sParam, $oFilter->m_aParams) && ($value != $oFilter->m_aParams[$sParam]))
			{
				// Generate a new and unique name for the collinding parameter
				$index = 1;
				while(array_key_exists($sParam.$index, $oFilter->m_aParams))
				{
					$index++;
				}
				$secondValue = $oFilter->m_aParams[$sParam];
				$oFilter->RenameParam($sParam, $sParam.$index);
				unset($oFilter->m_aParams[$sParam]);
				$oFilter->m_aParams[$sParam.$index] = $secondValue;
			}
		}
		$oTranslated = $oFilter->GetCriteria()->Translate($aTranslation, false, false /* leave unresolved fields */);
		$this->AddConditionExpression($oTranslated);
		$this->m_aParams = array_merge($this->m_aParams, $oFilter->m_aParams);
	}

	protected function RenameParam($sOldName, $sNewName)
	{
		$this->m_oSearchCondition->RenameParam($sOldName, $sNewName);
		foreach($this->m_aPointingTo as $sExtKeyAttCode=>$aPointingTo)
		{
			foreach($aPointingTo as $iOperatorCode => $aFilter)
			{
				foreach($aFilter as $oExtFilter)
				{
					$oExtFilter->RenameParam($sOldName, $sNewName);
				}
			}
		}
		foreach($this->m_aReferencedBy as $sForeignClass => $aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode => $aFiltersByOperator)
			{
				foreach ($aFiltersByOperator as $iOperatorCode => $aFilters)
				{
					foreach ($aFilters as $oForeignFilter)
					{
						$oForeignFilter->RenameParam($sOldName, $sNewName);
					}
				}
			}
		}
	}
	
	public function ResetCondition()
	{
		$this->m_oSearchCondition = new TrueExpression();
		// ? is that usefull/enough, do I need to rebuild the list after the subqueries ?
	}

	public function MergeConditionExpression($oExpression)
	{
		$this->m_oSearchCondition = $this->m_oSearchCondition->LogOr($oExpression); 
	}

	public function AddConditionExpression($oExpression)
	{
		$this->m_oSearchCondition = $this->m_oSearchCondition->LogAnd($oExpression); 
	}

  	public function AddNameCondition($sName)
	{
		$oValueExpr = new ScalarExpression($sName);
		$oNameExpr = new FieldExpression('friendlyname', $this->GetClassAlias());
		$oNewCondition = new BinaryExpression($oNameExpr, '=', $oValueExpr);
		$this->AddConditionExpression($oNewCondition);
	}

	/**
	 * @param string $sFilterCode
	 * @param mixed $value
	 * @param string $sOpCode operator to use : 'IN', 'NOT IN', 'Contains',' Begins with', 'Finishes with', ...
	 * @param bool $bParseSearchString
	 *
	 * @throws \CoreException
	 *
	 * @see AddConditionForInOperatorUsingParam for IN/NOT IN queries with lots of params
	 */
	public function AddCondition($sFilterCode, $value, $sOpCode = null, $bParseSearchString = false)
	{
		MyHelpers::CheckKeyInArray('filter code in class: '.$this->GetClass(), $sFilterCode, MetaModel::GetClassFilterDefs($this->GetClass()));

		$oField = new FieldExpression($sFilterCode, $this->GetClassAlias());
		if (empty($sOpCode))
		{
			if ($sFilterCode == 'id')
			{
				$sOpCode = '=';
			}
			else
			{
				$oAttDef = MetaModel::GetAttributeDef($this->GetClass(), $sFilterCode);
				$oNewCondition = $oAttDef->GetSmartConditionExpression($value, $oField, $this->m_aParams);
				$this->AddConditionExpression($oNewCondition);
				return;
			}
		}
		// Parse search strings if needed and if the filter code corresponds to a valid attcode
		if($bParseSearchString && MetaModel::IsValidAttCode($this->GetClass(), $sFilterCode))
		{
			$oAttDef = MetaModel::GetAttributeDef($this->GetClass(), $sFilterCode);
			$value = $oAttDef->ParseSearchString($value);
		}

		// Preserve backward compatibility - quick n'dirty way to change that API semantic
		//
		switch($sOpCode)
		{
		case 'SameDay':
		case 'SameMonth':
		case 'SameYear':
		case 'Today':
		case '>|':
		case '<|':
		case '=|':
			throw new CoreException('Deprecated operator, please consider using OQL (SQL) expressions like "(TO_DAYS(NOW()) - TO_DAYS(x)) AS AgeDays"', array('operator' => $sOpCode));
			break;

		case 'IN':
			if (!is_array($value)) $value = array($value);
			if (count($value) === 0) throw new Exception('AddCondition '.$sOpCode.': Value cannot be an empty array.');
			$sListExpr = '('.implode(', ', CMDBSource::Quote($value)).')';
			$sOQLCondition = $oField->Render()." IN $sListExpr";
			break;

		case 'NOTIN':
			if (!is_array($value)) $value = array($value);
            if (count($value) === 0) throw new Exception('AddCondition '.$sOpCode.': Value cannot be an empty array.');
			$sListExpr = '('.implode(', ', CMDBSource::Quote($value)).')';
			$sOQLCondition = $oField->Render()." NOT IN $sListExpr";
			break;

		case 'Contains':
			$this->m_aParams[$sFilterCode] = "%$value%";
			$sOperator = 'LIKE';
			break;

		case 'Begins with':
			$this->m_aParams[$sFilterCode] = "$value%";
			$sOperator = 'LIKE';
			break;

		case 'Finishes with':
			$this->m_aParams[$sFilterCode] = "%$value";
			$sOperator = 'LIKE';
			break;

		default:
			if ($value === null)
			{
				switch ($sOpCode)
				{
					case '=':
						$sOpCode = '*Expression*';
						$oExpression = new FunctionExpression('ISNULL', array($oField));
						break;
					case '!=':
						$sOpCode = '*Expression*';
						$oExpression = new FunctionExpression('ISNULL', array($oField));
						$oExpression = new BinaryExpression($oExpression, '=', new ScalarExpression(0));
						break;
					default:
						throw new Exception("AddCondition on null value: unsupported operator '$sOpCode''");
				}
			}
			else
			{
				$this->m_aParams[$sFilterCode] = $value;
				$sOperator = $sOpCode;
			}
		}

		switch($sOpCode)
		{
			case '*Expression*':
				$oNewCondition = $oExpression;
				break;
			case "IN":
			case "NOTIN":
				// this will parse all of the values... Can take forever if there are lots of them !
				// In this case using a parameter is far better : WHERE ... IN (:my_param)
				$oNewCondition = Expression::FromOQL($sOQLCondition);
				break;

			case 'MATCHES':
				$oRightExpr = new ScalarExpression($value);
				$oNewCondition = new MatchExpression($oField, $oRightExpr);
				break;

			case 'Contains':
			case 'Begins with':
			case 'Finishes with':
			default:
				$oRightExpr = new VariableExpression($sFilterCode);
				$oNewCondition = new BinaryExpression($oField, $sOperator, $oRightExpr);
		}

		$this->AddConditionExpression($oNewCondition);
	}

	/**
	 * @param string $sFilterCode attribute code to use
	 * @param array $aValues
	 * @param bool $bPositiveMatch if true will add a IN filter, else a NOT IN
	 *
	 * @throws \CoreException
	 *
	 * @since 2.5.0 N°1418
	 */
	public function AddConditionForInOperatorUsingParam($sFilterCode, $aValues, $bPositiveMatch = true)
	{
		$oFieldExpression = new FieldExpression($sFilterCode, $this->GetClassAlias());

		$sOperator = $bPositiveMatch ? 'IN' : 'NOT IN';

		$sInParamName = $this->GenerateUniqueParamName();
		$oParamExpression = new VariableExpression($sInParamName);
		$this->GetInternalParamsByRef()[$sInParamName] = $aValues;

		$oListExpression = new ListExpression(array($oParamExpression));

		$oInCondition = new BinaryExpression($oFieldExpression, $sOperator, $oListExpression);
		$this->AddConditionExpression($oInCondition);
	}

	/**
	 * Specify a condition on external keys or link sets
	 * @param string $sAttSpec Can be either an attribute code or extkey->[sAttSpec] or linkset->[sAttSpec] and so on, recursively
	 * Example: infra_list->ci_id->location_id->country
	 * @param $value
	 * @return void
	 * @throws \CoreException
	 * @throws \CoreWarning
	 */
	public function AddConditionAdvanced($sAttSpec, $value)
	{
		$sClass = $this->GetClass();

		$iPos = strpos($sAttSpec, '->');
		if ($iPos !== false)
		{
			$sAttCode = substr($sAttSpec, 0, $iPos);
			$sSubSpec = substr($sAttSpec, $iPos + 2);

			if (!MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				throw new Exception("Invalid attribute code '$sClass/$sAttCode' in condition specification '$sAttSpec'");
			}

			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			if ($oAttDef->IsLinkSet())
			{
				$sTargetClass = $oAttDef->GetLinkedClass();
				$sExtKeyToMe = $oAttDef->GetExtKeyToMe();

				$oNewFilter = new DBObjectSearch($sTargetClass);
				$oNewFilter->AddConditionAdvanced($sSubSpec, $value);

				$this->AddCondition_ReferencedBy($oNewFilter, $sExtKeyToMe);
			}
			elseif ($oAttDef->IsExternalKey(EXTKEY_ABSOLUTE))
			{
				$sTargetClass = $oAttDef->GetTargetClass(EXTKEY_ABSOLUTE);

				$oNewFilter = new DBObjectSearch($sTargetClass);
				$oNewFilter->AddConditionAdvanced($sSubSpec, $value);

				$this->AddCondition_PointingTo($oNewFilter, $sAttCode);
			}
			else
			{
				throw new Exception("Attribute specification '$sAttSpec', '$sAttCode' should be either a link set or an external key");
			}
		}
		else
		{
			// $sAttSpec is an attribute code
			//
			if (is_array($value))
			{
				$oField = new FieldExpression($sAttSpec, $this->GetClass());
				$oListExpr = ListExpression::FromScalars($value);
				$oInValues = new BinaryExpression($oField, 'IN', $oListExpr);

				$this->AddConditionExpression($oInValues);
			}
			else
			{
				$this->AddCondition($sAttSpec, $value);
			}
		}
	}

	public function AddCondition_FullText($sNeedle)
	{
		// Transform the full text condition into additional condition expression
		$aFullTextFields = array();
		foreach (MetaModel::ListAttributeDefs($this->GetClass()) as $sAttCode => $oAttDef)
		{
			if (!$oAttDef->IsScalar()) continue;
			if ($oAttDef->IsExternalKey()) continue;
			$aFullTextFields[] = new FieldExpression($sAttCode, $this->GetClassAlias());
		}
		$oTextFields = new CharConcatWSExpression(' ', $aFullTextFields);

		$sQueryParam = 'needle';
		$oFlexNeedle = new CharConcatExpression(array(new ScalarExpression('%'), new VariableExpression($sQueryParam), new ScalarExpression('%')));

		$oNewCond = new BinaryExpression($oTextFields, 'LIKE', $oFlexNeedle);
		$this->AddConditionExpression($oNewCond);
		$this->m_aParams[$sQueryParam] = $sNeedle;
	}

	protected function AddToNameSpace(&$aClassAliases, &$aAliasTranslation, $bTranslateMainAlias = true)
	{
		if ($bTranslateMainAlias)
		{
			$sOrigAlias = $this->GetFirstJoinedClassAlias();
			if (array_key_exists($sOrigAlias, $aClassAliases))
			{
				$sNewAlias = MetaModel::GenerateUniqueAlias($aClassAliases, $sOrigAlias, $this->GetFirstJoinedClass());
				if (isset($this->m_aSelectedClasses[$sOrigAlias]))
				{
					$this->m_aSelectedClasses[$sNewAlias] = $this->GetFirstJoinedClass();
					unset($this->m_aSelectedClasses[$sOrigAlias]);
				}

				// TEMPORARY ALGORITHM (m_aClasses is not correctly updated, it is not possible to add a subtree onto a subnode)
				// Replace the element at the same position (unset + set is not enough because the hash array is ordered)
				$aPrevList = $this->m_aClasses;
				$this->m_aClasses = array();
				foreach ($aPrevList as $sSomeAlias => $sSomeClass)
				{
					if ($sSomeAlias == $sOrigAlias)
					{
						$this->m_aClasses[$sNewAlias] = $sSomeClass; // note: GetFirstJoinedClass now returns '' !!!
					}
					else
					{
						$this->m_aClasses[$sSomeAlias] = $sSomeClass;
					}
				}
	
				// Translate the condition expression with the new alias
				$aAliasTranslation[$sOrigAlias]['*'] = $sNewAlias;
			}
	
			// add the alias into the filter aliases list
			$aClassAliases[$this->GetFirstJoinedClassAlias()] = $this->GetFirstJoinedClass();
		}
		
		foreach($this->m_aPointingTo as $sExtKeyAttCode=>$aPointingTo)
		{
			foreach($aPointingTo as $iOperatorCode => $aFilter)
			{
				foreach($aFilter as $oFilter)
				{
					$oFilter->AddToNameSpace($aClassAliases, $aAliasTranslation);
				}
			}
		}

		foreach($this->m_aReferencedBy as $sForeignClass=>$aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode => $aFiltersByOperator)
			{
				foreach ($aFiltersByOperator as $iOperatorCode => $aFilters)
				{
					foreach ($aFilters as $oForeignFilter)
					{
						$oForeignFilter->AddToNameSpace($aClassAliases, $aAliasTranslation);
					}
				}
			}
		}
	}


	// Browse the tree nodes recursively
	//
	protected function GetNode($sAlias)
	{
		if ($this->GetFirstJoinedClassAlias() == $sAlias)
		{
			return $this;
		}
		else
		{
			foreach($this->m_aPointingTo as $sExtKeyAttCode=>$aPointingTo)
			{
				foreach($aPointingTo as $iOperatorCode => $aFilter)
				{
					foreach($aFilter as $oFilter)
					{
						$ret = $oFilter->GetNode($sAlias);
						if (is_object($ret))
						{
							return $ret;
						}
					}
				}
			}
			foreach($this->m_aReferencedBy as $sForeignClass=>$aReferences)
			{
				foreach($aReferences as $sForeignExtKeyAttCode => $aFiltersByOperator)
				{
					foreach ($aFiltersByOperator as $iOperatorCode => $aFilters)
					{
						foreach ($aFilters as $oForeignFilter)
						{
							$ret = $oForeignFilter->GetNode($sAlias);
							if (is_object($ret))
							{
								return $ret;
							}
						}
					}
				}
			}
		}
		// Not found
		return null;
	}

	/**
	 * Helper to
	 * - convert a translation table (format optimized for the translation in an expression tree) into simple hash
	 * - compile over an eventually existing map
	 *
	 * @param array $aRealiasingMap Map to update
	 * @param array $aAliasTranslation Translation table resulting from calls to MergeWith_InNamespace
	 * @return void of <old-alias> => <new-alias>
	 */
	protected function UpdateRealiasingMap(&$aRealiasingMap, $aAliasTranslation)
	{
		if ($aRealiasingMap !== null)
		{
			foreach ($aAliasTranslation as $sPrevAlias => $aRules)
			{
				if (isset($aRules['*']))
				{
					$sNewAlias = $aRules['*'];
					$sOriginalAlias = array_search($sPrevAlias, $aRealiasingMap);
					if ($sOriginalAlias !== false)
					{
						$aRealiasingMap[$sOriginalAlias] = $sNewAlias;
					}
					else
					{
						$aRealiasingMap[$sPrevAlias] = $sNewAlias;
					}
				}
			}
		}
	}

	/**
	 * Completes the list of alias=>class by browsing the whole structure recursively
	 * This a workaround to handle some cases in which the list of classes is not correctly updated.
	 * This code should disappear as soon as DBObjectSearch get split between a container search class and a Node class
	 *
	 * @param array $aClasses List to be completed
	 */
	protected function RecomputeClassList(&$aClasses)
	{
		$aClasses[$this->GetFirstJoinedClassAlias()] = $this->GetFirstJoinedClass();

		// Recurse in the query tree
		foreach($this->m_aPointingTo as $sExtKeyAttCode=>$aPointingTo)
		{
			foreach($aPointingTo as $iOperatorCode => $aFilter)
			{
				foreach($aFilter as $oFilter)
				{
					$oFilter->RecomputeClassList($aClasses);
				}
			}
		}

		foreach($this->m_aReferencedBy as $sForeignClass=>$aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode => $aFiltersByOperator)
			{
				foreach ($aFiltersByOperator as $iOperatorCode => $aFilters)
				{
					foreach ($aFilters as $oForeignFilter)
					{
						$oForeignFilter->RecomputeClassList($aClasses);
					}
				}
			}
		}
	}

	/**
	 * @param DBObjectSearch $oFilter
	 * @param $sExtKeyAttCode
	 * @param int $iOperatorCode
	 * @param null $aRealiasingMap array of <old-alias> => <new-alias>, for each alias that has changed
	 * @throws CoreException
	 * @throws CoreWarning
	 */
	public function AddCondition_PointingTo(DBObjectSearch $oFilter, $sExtKeyAttCode, $iOperatorCode = TREE_OPERATOR_EQUALS, &$aRealiasingMap = null)
	{
		if (!MetaModel::IsValidKeyAttCode($this->GetClass(), $sExtKeyAttCode))
		{
			throw new CoreWarning("The attribute code '$sExtKeyAttCode' is not an external key of the class '{$this->GetClass()}'");
		}
		$oAttExtKey = MetaModel::GetAttributeDef($this->GetClass(), $sExtKeyAttCode);
		if(!MetaModel::IsSameFamilyBranch($oFilter->GetClass(), $oAttExtKey->GetTargetClass()))
		{
			throw new CoreException("The specified filter (pointing to {$oFilter->GetClass()}) is not compatible with the key '{$this->GetClass()}::$sExtKeyAttCode', which is pointing to {$oAttExtKey->GetTargetClass()}");
		}
		if(($iOperatorCode != TREE_OPERATOR_EQUALS) && !($oAttExtKey instanceof AttributeHierarchicalKey))
		{
			throw new CoreException("The specified tree operator $iOperatorCode is not applicable to the key '{$this->GetClass()}::$sExtKeyAttCode', which is not a HierarchicalKey");
		}
		// Note: though it seems to be a good practice to clone the given source filter
		//       (as it was done and fixed an issue in Intersect())
		//       this was not implemented here because it was causing a regression (login as admin, select an org, click on any badge)
		//       root cause: FromOQL relies on the fact that the passed filter can be modified later 
		// NO: $oFilter = $oFilter->DeepClone();
		// See also: Trac #639, and self::AddCondition_ReferencedBy()
		$aAliasTranslation = array();
		$res = $this->AddCondition_PointingTo_InNameSpace($oFilter, $sExtKeyAttCode, $this->m_aClasses, $aAliasTranslation, $iOperatorCode);
		$this->TransferConditionExpression($oFilter, $aAliasTranslation);
		$this->UpdateRealiasingMap($aRealiasingMap, $aAliasTranslation);

		if (ENABLE_OPT && ($oFilter->GetClass() == $oFilter->GetFirstJoinedClass()))
		{
			if (isset($oFilter->m_aReferencedBy[$this->GetClass()][$sExtKeyAttCode][$iOperatorCode]))
			{
				foreach ($oFilter->m_aReferencedBy[$this->GetClass()][$sExtKeyAttCode][$iOperatorCode] as $oRemoteFilter)
				{
					if ($this->GetClass() == $oRemoteFilter->GetClass())
					{
						// Optimization - fold sibling query
						$aAliasTranslation = array();
						$this->MergeWith_InNamespace($oRemoteFilter, $this->m_aClasses, $aAliasTranslation);
						unset($oFilter->m_aReferencedBy[$this->GetClass()][$sExtKeyAttCode][$iOperatorCode]);
						$this->m_oSearchCondition = $this->m_oSearchCondition->Translate($aAliasTranslation, false, false);
						$this->UpdateRealiasingMap($aRealiasingMap, $aAliasTranslation);
						break;
					}
				}
			}
		}
		$this->RecomputeClassList($this->m_aClasses);
		return $res;
	}

	protected function AddCondition_PointingTo_InNameSpace(DBObjectSearch $oFilter, $sExtKeyAttCode, &$aClassAliases, &$aAliasTranslation, $iOperatorCode)
	{
		// Find the node on which the new tree must be attached (most of the time it is "this")
		$oReceivingFilter = $this->GetNode($this->GetClassAlias());

		$bMerged = false;
		if (ENABLE_OPT && isset($oReceivingFilter->m_aPointingTo[$sExtKeyAttCode][$iOperatorCode]))
		{
			foreach ($oReceivingFilter->m_aPointingTo[$sExtKeyAttCode][$iOperatorCode] as $oExisting)
			{
				if ($oExisting->GetClass() == $oFilter->GetClass())
				{
					$oExisting->MergeWith_InNamespace($oFilter, $oExisting->m_aClasses, $aAliasTranslation);
					$bMerged = true;
					break;
				}
			}
		}
		if (!$bMerged)
		{
			$oFilter->AddToNamespace($aClassAliases, $aAliasTranslation);
			$oReceivingFilter->m_aPointingTo[$sExtKeyAttCode][$iOperatorCode][] = $oFilter;
		}
	}

	/**
	 * @param DBObjectSearch $oFilter
	 * @param $sForeignExtKeyAttCode
	 * @param int $iOperatorCode
	 * @param null $aRealiasingMap array of <old-alias> => <new-alias>, for each alias that has changed
	 * @return void
	 * @throws \CoreException
	 */
	public function AddCondition_ReferencedBy(DBObjectSearch $oFilter, $sForeignExtKeyAttCode, $iOperatorCode = TREE_OPERATOR_EQUALS, &$aRealiasingMap = null)
	{
		$sForeignClass = $oFilter->GetClass();
		if (!MetaModel::IsValidKeyAttCode($sForeignClass, $sForeignExtKeyAttCode))
		{
			throw new CoreException("The attribute code '$sForeignExtKeyAttCode' is not an external key of the class '{$sForeignClass}'");
		}
		$oAttExtKey = MetaModel::GetAttributeDef($sForeignClass, $sForeignExtKeyAttCode);
		if(!MetaModel::IsSameFamilyBranch($this->GetClass(), $oAttExtKey->GetTargetClass()))
		{
			// à refaire en spécifique dans FromOQL
			throw new CoreException("The specified filter (objects referencing an object of class {$this->GetClass()}) is not compatible with the key '{$sForeignClass}::$sForeignExtKeyAttCode', which is pointing to {$oAttExtKey->GetTargetClass()}");
		}

		// Note: though it seems to be a good practice to clone the given source filter
		//       (as it was done and fixed an issue in Intersect())
		//       this was not implemented here because it was causing a regression (login as admin, select an org, click on any badge)
		//       root cause: FromOQL relies on the fact that the passed filter can be modified later 
		// NO: $oFilter = $oFilter->DeepClone();
		// See also: Trac #639, and self::AddCondition_PointingTo()
		$aAliasTranslation = array();
		$this->AddCondition_ReferencedBy_InNameSpace($oFilter, $sForeignExtKeyAttCode, $this->m_aClasses, $aAliasTranslation, $iOperatorCode);
		$this->TransferConditionExpression($oFilter, $aAliasTranslation);
		$this->UpdateRealiasingMap($aRealiasingMap, $aAliasTranslation);

		if (ENABLE_OPT && ($oFilter->GetClass() == $oFilter->GetFirstJoinedClass()))
		{
			if (isset($oFilter->m_aPointingTo[$sForeignExtKeyAttCode][$iOperatorCode]))
			{
				foreach ($oFilter->m_aPointingTo[$sForeignExtKeyAttCode][$iOperatorCode] as $oRemoteFilter)
				{
					if ($this->GetClass() == $oRemoteFilter->GetClass())
					{
						// Optimization - fold sibling query
						$aAliasTranslation = array();
						$this->MergeWith_InNamespace($oRemoteFilter, $this->m_aClasses, $aAliasTranslation);
						unset($oFilter->m_aPointingTo[$sForeignExtKeyAttCode][$iOperatorCode]);
						$this->m_oSearchCondition  = $this->m_oSearchCondition->Translate($aAliasTranslation, false, false);
						$this->UpdateRealiasingMap($aRealiasingMap, $aAliasTranslation);
						break;
					}
				}
			}
		}
		$this->RecomputeClassList($this->m_aClasses);
	}

	protected function AddCondition_ReferencedBy_InNameSpace(DBObjectSearch $oFilter, $sForeignExtKeyAttCode, &$aClassAliases, &$aAliasTranslation, $iOperatorCode)
	{
		$sForeignClass = $oFilter->GetClass();

		// Find the node on which the new tree must be attached (most of the time it is "this")
		$oReceivingFilter = $this->GetNode($this->GetClassAlias());

		$bMerged = false;
		if (ENABLE_OPT && isset($oReceivingFilter->m_aReferencedBy[$sForeignClass][$sForeignExtKeyAttCode][$iOperatorCode]))
		{
			foreach ($oReceivingFilter->m_aReferencedBy[$sForeignClass][$sForeignExtKeyAttCode][$iOperatorCode] as $oExisting)
			{
				if ($oExisting->GetClass() == $oFilter->GetClass())
				{
					$oExisting->MergeWith_InNamespace($oFilter, $oExisting->m_aClasses, $aAliasTranslation);
					$bMerged = true;
					break;
				}
			}
		}
		if (!$bMerged)
		{
			$oFilter->AddToNamespace($aClassAliases, $aAliasTranslation);
			$oReceivingFilter->m_aReferencedBy[$sForeignClass][$sForeignExtKeyAttCode][$iOperatorCode][] = $oFilter;
		}
	}

	/**
	 * Filter this search with another search.
	 * Initial search is unmodified.
	 * The difference with Intersect, is that an alias can be provided,
	 * the filtered class does not need to be the first joined class.
	 *
	 * @param string $sClassAlias class being filtered
	 * @param DBSearch $oFilter Filter to apply
	 *
	 * @return DBSearch The filtered search
	 * @throws \CoreException
	 */
	public function Filter($sClassAlias, DBSearch $oFilter)
	{
		// If the conditions are the correct ones for Intersect
		if (($this->GetFirstJoinedClassAlias() == $sClassAlias))
		{
			return $this->Intersect($oFilter);
		}

		/** @var \DBObjectSearch $oFilteredSearch */
		$oFilteredSearch = $this->DeepClone();
		$oFilterExpression = self::FilterSubClass($oFilteredSearch, $sClassAlias, $oFilter, $this->m_aClasses);
		if ($oFilterExpression === false)
		{
			throw new CoreException("Limitation: cannot filter search");
		}

		$oFilteredSearch->AddConditionExpression($oFilterExpression);

		return $oFilteredSearch;
	}

	/**
	 * Filter "in place" the search (filtered part is replaced in the initial search)
	 *
	 * @param DBObjectSearch $oSearch Search to filter, modified with the given filter
	 * @param string $sClassAlias class to filter
	 * @param \DBSearch $oFilter Filter to apply
	 *
	 * @return \Expression|false
	 * @throws \CoreException
	 */
	private static function FilterSubClass(DBObjectSearch &$oSearch, $sClassAlias, DBSearch $oFilter, $aRootClasses)
	{
		if (($oSearch->GetFirstJoinedClassAlias() == $sClassAlias))
		{
			$oSearch->ResetCondition();
			$oSearch = $oSearch->IntersectSubClass($oFilter, $aRootClasses);
			return $oSearch->GetCriteria();
		}

		/** @var Expression $oFilterExpression */
		// Search in the filter tree where is the correct DBSearch
		foreach ($oSearch->m_aPointingTo as $sExtKey => $aPointingTo)
		{
			foreach ($aPointingTo as $iOperatorCode => $aFilters)
			{
				foreach ($aFilters as $index => $oExtFilter)
				{
					$oFilterExpression = self::FilterSubClass($oExtFilter, $sClassAlias, $oFilter, $aRootClasses);
					if ($oFilterExpression !== false)
					{
						$oSearch->m_aPointingTo[$sExtKey][$iOperatorCode][$index] = $oExtFilter;
						return $oFilterExpression;
					}
				}
			}
		}

		foreach($oSearch->m_aReferencedBy as $sForeignClass => $aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode => $aFiltersByOperator)
			{
				foreach ($aFiltersByOperator as $iOperatorCode => $aFilters)
				{
					foreach ($aFilters as $index => $oForeignFilter)
					{
						$oFilterExpression = self::FilterSubClass($oForeignFilter, $sClassAlias, $oFilter, $aRootClasses);
						if ($oFilterExpression !== false)
						{
							$oSearch->m_aReferencedBy[$sForeignClass][$sForeignExtKeyAttCode][$iOperatorCode][$index] = $oForeignFilter;
							return $oFilterExpression;
						}
					}
				}
			}
		}

		return false;
	}
	
	/**
	 * @inheritDoc
	 * @throws \CoreException
	 */
	public function Intersect(DBSearch $oFilter)
	{
		return $this->IntersectSubClass($oFilter, $this->m_aClasses);
	}

	/**
	 * @param \DBSearch $oFilter
	 * @param array $aRootClasses classes of the root search (for aliases)
	 *
	 * @return \DBUnionSearch|mixed
	 * @throws \CoreException
	 */
	protected function IntersectSubClass(DBSearch $oFilter, $aRootClasses)
	{
		if ($oFilter instanceof DBUnionSearch)
		{
			// Develop! 
			$aFilters = $oFilter->GetSearches();
		}
		else
		{
			$aFilters = array($oFilter);
		}

		$aSearches = array();
		foreach ($aFilters as $oRightFilter)
		{
			// Limitation: the queried class must be the first declared class
			if ($oRightFilter->GetFirstJoinedClassAlias() != $oRightFilter->GetClassAlias())
			{
				throw new CoreException("Limitation: cannot merge two queries if the queried class ({$oRightFilter->GetClass()} AS {$oRightFilter->GetClassAlias()}) is not the first joined class ({$oRightFilter->GetFirstJoinedClass()} AS {$oRightFilter->GetFirstJoinedClassAlias()})");
			}

			/** @var \DBObjectSearch $oLeftFilter */
			$oLeftFilter = $this->DeepClone();
			$oRightFilter = $oRightFilter->DeepClone();

			$bAllowAllData = ($oLeftFilter->IsAllDataAllowed() && $oRightFilter->IsAllDataAllowed());
			if ($bAllowAllData)
			{
				$oLeftFilter->AllowAllData();
			}

			if ($oLeftFilter->GetFirstJoinedClass() != $oRightFilter->GetClass())
			{
				if (MetaModel::IsParentClass($oLeftFilter->GetFirstJoinedClass(), $oRightFilter->GetClass()))
				{
					// Specialize $oLeftFilter
					$oLeftFilter->ChangeClass($oRightFilter->GetClass(), $oLeftFilter->GetFirstJoinedClassAlias());
				}
				elseif (MetaModel::IsParentClass($oRightFilter->GetFirstJoinedClass(), $oLeftFilter->GetClass()))
				{
					// Specialize $oRightFilter
					$oRightFilter->ChangeClass($oLeftFilter->GetClass());
				}
				else
				{
					throw new CoreException("Attempting to merge a filter of class '{$oLeftFilter->GetClass()}' with a filter of class '{$oRightFilter->GetClass()}'");
				}
			}

			$aAliasTranslation = array();
			$oLeftFilter->MergeWith_InNamespace($oRightFilter, $aRootClasses, $aAliasTranslation);
			$oLeftFilter->TransferConditionExpression($oRightFilter, $aAliasTranslation);
			$aSearches[] = $oLeftFilter;
		}
		if (count($aSearches) == 1)
		{
			// return a DBObjectSearch
			return $aSearches[0];
		}
		else
		{
			return new DBUnionSearch($aSearches);
		}
	}

	protected function MergeWith_InNamespace($oFilter, &$aClassAliases, &$aAliasTranslation)
	{
		if ($this->GetClass() != $oFilter->GetClass())
		{
			throw new CoreException("Attempting to merge a filter of class '{$this->GetClass()}' with a filter of class '{$oFilter->GetClass()}'");
		}

		// Translate search condition into our aliasing scheme
		$aAliasTranslation[$oFilter->GetClassAlias()]['*'] = $this->GetClassAlias(); 

		foreach($oFilter->m_aPointingTo as $sExtKeyAttCode=>$aPointingTo)
		{
			foreach($aPointingTo as $iOperatorCode => $aFilter)
			{
				foreach($aFilter as $oExtFilter)
				{
					$this->AddCondition_PointingTo_InNamespace($oExtFilter, $sExtKeyAttCode, $aClassAliases, $aAliasTranslation, $iOperatorCode);
				}
			}
		}
		foreach($oFilter->m_aReferencedBy as $sForeignClass => $aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode => $aFiltersByOperator)
			{
				foreach ($aFiltersByOperator as $iOperatorCode => $aFilters)
				{
					foreach ($aFilters as $oForeignFilter)
					{
						$this->AddCondition_ReferencedBy_InNamespace($oForeignFilter, $sForeignExtKeyAttCode, $aClassAliases, $aAliasTranslation, $iOperatorCode);
					}
				}
			}
		}
	}

	public function GetCriteria() {return $this->m_oSearchCondition;}
	public function GetCriteria_FullText() {throw new Exception("Removed GetCriteria_FullText");}
	public function GetCriteria_PointingTo($sKeyAttCode = "")
	{
		if (empty($sKeyAttCode))
		{
			return $this->m_aPointingTo;
		}
		if (!array_key_exists($sKeyAttCode, $this->m_aPointingTo)) return array();
		return $this->m_aPointingTo[$sKeyAttCode];
	}
	protected function GetCriteria_ReferencedBy()
	{
		return $this->m_aReferencedBy;
	}

	public function SetInternalParams($aParams)
	{
		return $this->m_aParams = $aParams;
	}

	/**
	 * @return array <strong>warning</strong> : array returned by value
	 * @see self::GetInternalParamsByRef to get the attribute by reference
	 */
	public function GetInternalParams()
	{
		return $this->m_aParams;
	}

	/**
	 * @return array
	 * @see http://php.net/manual/en/language.references.return.php
	 * @since 2.5.1 N°1582
	 */
	public function &GetInternalParamsByRef()
	{
		return $this->m_aParams;
	}

	/**
	 * @param string $sKey
	 * @param mixed $value
	 * @param bool $bDoNotOverride
	 *
	 * @throws \CoreUnexpectedValue if $bDoNotOverride and $sKey already exists
	 */
	public function AddInternalParam($sKey, $value, $bDoNotOverride = false)
	{
		if ($bDoNotOverride)
		{
			if (array_key_exists($sKey, $this->m_aParams))
			{
				throw new CoreUnexpectedValue("The key $sKey already exists with value : ".$this->m_aParams[$sKey]);
			}
		}

		$this->m_aParams[$sKey] = $value;
	}

	public function GetQueryParams($bExcludeMagicParams = true)
	{
		$aParams = array();
		$this->m_oSearchCondition->Render($aParams, true);

		if ($bExcludeMagicParams)
		{
			$aRet = array();

			// Make the list of acceptable arguments... could be factorized with run_query, into oSearch->GetQueryParams($bExclude magic params)
			$aNakedMagicArguments = array();
			foreach (MetaModel::PrepareQueryArguments(array()) as $sArgName => $value)
			{
				$iPos = strpos($sArgName, '->object()');
				if ($iPos === false)
				{
					$aNakedMagicArguments[$sArgName] = $value;
				}
				else
				{
					$aNakedMagicArguments[substr($sArgName, 0, $iPos)] = true;
				}
			}
			foreach ($aParams as $sParam => $foo)
			{
				$iPos = strpos($sParam, '->');
				if ($iPos === false)
				{
					$sRefName = $sParam;
				}
				else
				{
					$sRefName = substr($sParam, 0, $iPos);
				}
				if (!array_key_exists($sRefName, $aNakedMagicArguments))
				{
					$aRet[$sParam] = $foo;
				}
			}
		}

		return $aRet;
	}

	public function ListConstantFields()
	{
		return $this->m_oSearchCondition->ListConstantFields();
	}

	/**
	 * Turn the parameters (:xxx) into scalar values in order to easily
	 * serialize a search
	 * @param $aArgs
*/
	public function ApplyParameters($aArgs)
	{
		$this->m_oSearchCondition->ApplyParameters(array_merge($this->m_aParams, $aArgs));
	}
	
	public function ToOQL($bDevelopParams = false, $aContextParams = null, $bWithAllowAllFlag = false)
	{
		// Currently unused, but could be useful later
		$bRetrofitParams = false;

		if ($bDevelopParams)
		{
			if (is_null($aContextParams))
			{
				$aParams = array_merge($this->m_aParams);
			}
			else
			{
				$aParams = array_merge($aContextParams, $this->m_aParams);
			}
			$aParams = MetaModel::PrepareQueryArguments($aParams);
		}
		else
		{
			// Leave it as is, the rendering will be made with parameters in clear
			$aParams = null;
		}

		$aSelectedAliases = array();
		foreach ($this->m_aSelectedClasses as $sAlias => $sClass)
		{
			$aSelectedAliases[] = '`' . $sAlias . '`';
		}
		$sSelectedClasses = implode(', ', $aSelectedAliases);
		$sRes = 'SELECT '.$sSelectedClasses.' FROM';

		$sRes .= ' ' . $this->GetFirstJoinedClass() . ' AS `' . $this->GetFirstJoinedClassAlias() . '`';
		$sRes .= $this->ToOQL_Joins();
		$sRes .= " WHERE ".$this->m_oSearchCondition->Render($aParams, $bRetrofitParams);

		if ($bWithAllowAllFlag && $this->m_bAllowAllData)
		{
			$sRes .= " ALLOW ALL DATA";
		}
		return $sRes;
	}

	protected function OperatorCodeToOQL($iOperatorCode)
	{
		switch($iOperatorCode)
		{
			case TREE_OPERATOR_EQUALS:
				$sOperator = ' = ';
				break;

			case TREE_OPERATOR_BELOW:
				$sOperator = ' BELOW ';
				break;

			case TREE_OPERATOR_BELOW_STRICT:
				$sOperator = ' BELOW STRICT ';
				break;

			case TREE_OPERATOR_NOT_BELOW:
				$sOperator = ' NOT BELOW ';
				break;

			case TREE_OPERATOR_NOT_BELOW_STRICT:
				$sOperator = ' NOT BELOW STRICT ';
				break;

			case TREE_OPERATOR_ABOVE:
				$sOperator = ' ABOVE ';
				break;

			case TREE_OPERATOR_ABOVE_STRICT:
				$sOperator = ' ABOVE STRICT ';
				break;

			case TREE_OPERATOR_NOT_ABOVE:
				$sOperator = ' NOT ABOVE ';
				break;

			case TREE_OPERATOR_NOT_ABOVE_STRICT:
				$sOperator = ' NOT ABOVE STRICT ';
				break;

		}
		return $sOperator;
	}

	protected function ToOQL_Joins()
	{
		$sRes = '';
		foreach($this->m_aPointingTo as $sExtKey => $aPointingTo)
		{
			foreach($aPointingTo as $iOperatorCode => $aFilter)
			{
				$sOperator = $this->OperatorCodeToOQL($iOperatorCode);
				foreach($aFilter as $oFilter)
				{
					$sRes .= ' JOIN ' . $oFilter->GetFirstJoinedClass() . ' AS `' . $oFilter->GetFirstJoinedClassAlias() . '` ON `' . $this->GetFirstJoinedClassAlias() . '`.' . $sExtKey . $sOperator . '`' . $oFilter->GetFirstJoinedClassAlias() . '`.id';
					$sRes .= $oFilter->ToOQL_Joins();				
				}
			}
		}
		foreach($this->m_aReferencedBy as $sForeignClass=>$aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode => $aFiltersByOperator)
			{
				foreach ($aFiltersByOperator as $iOperatorCode => $aFilters)
				{
					$sOperator = $this->OperatorCodeToOQL($iOperatorCode);
					foreach ($aFilters as $oForeignFilter)
					{
						$sRes .= ' JOIN ' . $oForeignFilter->GetFirstJoinedClass() . ' AS `' . $oForeignFilter->GetFirstJoinedClassAlias() . '` ON `' . $oForeignFilter->GetFirstJoinedClassAlias() . '`.' . $sForeignExtKeyAttCode . $sOperator . '`' . $this->GetFirstJoinedClassAlias() . '`.id';
						$sRes .= $oForeignFilter->ToOQL_Joins();
					}
				}
			}
		}
		return $sRes;
	}

	protected function OQLExpressionToCondition($sQuery, $oExpression, $aClassAliases)
	{
		if ($oExpression instanceof BinaryOqlExpression)
		{
			$sOperator = $oExpression->GetOperator();
			$oLeft = $this->OQLExpressionToCondition($sQuery, $oExpression->GetLeftExpr(), $aClassAliases);
			$oRight = $this->OQLExpressionToCondition($sQuery, $oExpression->GetRightExpr(), $aClassAliases);
			return new BinaryExpression($oLeft, $sOperator, $oRight);
		}
		elseif ($oExpression instanceof MatchOqlExpression)
		{
			$oLeft = $this->OQLExpressionToCondition($sQuery, $oExpression->GetLeftExpr(), $aClassAliases);
			$oRight = $this->OQLExpressionToCondition($sQuery, $oExpression->GetRightExpr(), $aClassAliases);

			return new MatchExpression($oLeft, $oRight);
		}
		elseif ($oExpression instanceof FieldOqlExpression)
		{
			$sClassAlias = $oExpression->GetParent();
			$sFltCode = $oExpression->GetName();
			if (empty($sClassAlias))
			{
				// Need to find the right alias
				// Build an array of field => array of aliases
				$aFieldClasses = array();
				foreach($aClassAliases as $sAlias => $sReal)
				{
					foreach(MetaModel::GetFiltersList($sReal) as $sAnFltCode)
					{
						$aFieldClasses[$sAnFltCode][] = $sAlias;
					}
				}
				$sClassAlias = $aFieldClasses[$sFltCode][0];
			}
			return new FieldExpression($sFltCode, $sClassAlias);
		}
		elseif ($oExpression instanceof VariableOqlExpression)
		{
			return new VariableExpression($oExpression->GetName());
		}
		elseif ($oExpression instanceof TrueOqlExpression)
		{
			return new TrueExpression;
		}
		elseif ($oExpression instanceof ScalarOqlExpression)
		{
			return new ScalarExpression($oExpression->GetValue());
		}
		elseif ($oExpression instanceof ListOqlExpression)
		{
			$aItems = array();
			foreach ($oExpression->GetItems() as $oItemExpression)
			{
				$aItems[] = $this->OQLExpressionToCondition($sQuery, $oItemExpression, $aClassAliases);
			}
			return new ListExpression($aItems);
		}
		elseif ($oExpression instanceof FunctionOqlExpression)
		{
			$aArgs = array();
			foreach ($oExpression->GetArgs() as $oArgExpression)
			{
				$aArgs[] = $this->OQLExpressionToCondition($sQuery, $oArgExpression, $aClassAliases);
			}
			return new FunctionExpression($oExpression->GetVerb(), $aArgs);
		}
		elseif ($oExpression instanceof IntervalOqlExpression)
		{
			return new IntervalExpression($oExpression->GetValue(), $oExpression->GetUnit());
		}
		else
		{
			throw new CoreException('Unknown expression type', array('class'=>get_class($oExpression), 'query'=>$sQuery));
		}
	}

	public function InitFromOqlQuery(OqlQuery $oOqlQuery, $sQuery)
	{
		$oModelReflection = new ModelReflectionRuntime();
		$sClass = $oOqlQuery->GetClass($oModelReflection);
		$sClassAlias = $oOqlQuery->GetClassAlias();

		$aAliases = array($sClassAlias => $sClass);

		// Note: the condition must be built here, it may be altered later on when optimizing some joins
		$oConditionTree = $oOqlQuery->GetCondition();
		if ($oConditionTree instanceof Expression)
		{
			$aRawAliases = array($sClassAlias => $sClass);
			$aJoinSpecs = $oOqlQuery->GetJoins();
			if (is_array($aJoinSpecs))
			{
				foreach ($aJoinSpecs as $oJoinSpec)
				{
					$aRawAliases[$oJoinSpec->GetClassAlias()] = $oJoinSpec->GetClass();
				}
			}
			$this->m_oSearchCondition = $this->OQLExpressionToCondition($sQuery, $oConditionTree, $aRawAliases);
		}

		// Maintain an array of filters, because the flat list is in fact referring to a tree
		// And this will be an easy way to dispatch the conditions
		// $this will be referenced by the other filters, or the other way around...
		$aJoinItems = array($sClassAlias => $this);

		$aJoinSpecs = $oOqlQuery->GetJoins();
		if (is_array($aJoinSpecs))
		{
			$aAliasTranslation = array();
			foreach ($aJoinSpecs as $oJoinSpec)
			{
				$sJoinClass = $oJoinSpec->GetClass();
				$sJoinClassAlias = $oJoinSpec->GetClassAlias();
				if (isset($aAliasTranslation[$sJoinClassAlias]['*']))
				{
					$sJoinClassAlias = $aAliasTranslation[$sJoinClassAlias]['*'];
				}

				// Assumption: ext key on the left only !!!
				// normalization should take care of this
				$oLeftField = $oJoinSpec->GetLeftField();
				$sFromClass = $oLeftField->GetParent();
				if (isset($aAliasTranslation[$sFromClass]['*']))
				{
					$sFromClass = $aAliasTranslation[$sFromClass]['*'];
				}
				$sExtKeyAttCode = $oLeftField->GetName();

				$oRightField = $oJoinSpec->GetRightField();
				$sToClass = $oRightField->GetParent();
				if (isset($aAliasTranslation[$sToClass]['*']))
				{
					$sToClass = $aAliasTranslation[$sToClass]['*'];
				}

				$aAliases[$sJoinClassAlias] = $sJoinClass;
				$aJoinItems[$sJoinClassAlias] = new DBObjectSearch($sJoinClass, $sJoinClassAlias);

				$sOperator = $oJoinSpec->GetOperator();
				switch($sOperator)
				{
					case '=':
					default:
						$iOperatorCode = TREE_OPERATOR_EQUALS;
						break;
					case 'BELOW':
						$iOperatorCode = TREE_OPERATOR_BELOW;
						break;
					case 'BELOW_STRICT':
						$iOperatorCode = TREE_OPERATOR_BELOW_STRICT;
						break;
					case 'NOT_BELOW':
						$iOperatorCode = TREE_OPERATOR_NOT_BELOW;
						break;
					case 'NOT_BELOW_STRICT':
						$iOperatorCode = TREE_OPERATOR_NOT_BELOW_STRICT;
						break;
					case 'ABOVE':
						$iOperatorCode = TREE_OPERATOR_ABOVE;
						break;
					case 'ABOVE_STRICT':
						$iOperatorCode = TREE_OPERATOR_ABOVE_STRICT;
						break;
					case 'NOT_ABOVE':
						$iOperatorCode = TREE_OPERATOR_NOT_ABOVE;
						break;
					case 'NOT_ABOVE_STRICT':
						$iOperatorCode = TREE_OPERATOR_NOT_ABOVE_STRICT;
						break;
				}

				if ($sFromClass == $sJoinClassAlias)
				{
					$oReceiver = $aJoinItems[$sToClass];
					$oNewComer = $aJoinItems[$sFromClass];
					$oReceiver->AddCondition_ReferencedBy_InNameSpace($oNewComer, $sExtKeyAttCode, $oReceiver->m_aClasses, $aAliasTranslation, $iOperatorCode);
				}
				else
				{
					$oReceiver = $aJoinItems[$sFromClass];
					$oNewComer = $aJoinItems[$sToClass];
					$oReceiver->AddCondition_PointingTo_InNameSpace($oNewComer, $sExtKeyAttCode, $oReceiver->m_aClasses, $aAliasTranslation, $iOperatorCode);
				}
			}
			$this->m_oSearchCondition = $this->m_oSearchCondition->Translate($aAliasTranslation, false, false /* leave unresolved fields */);
		}

		// Check and prepare the select information
		$this->m_aSelectedClasses = array();
		foreach ($oOqlQuery->GetSelectedClasses() as $oClassDetails)
		{
			$sClassToSelect = $oClassDetails->GetValue();
			$this->m_aSelectedClasses[$sClassToSelect] = $aAliases[$sClassToSelect];
		}
		$this->m_aClasses = $aAliases;
	}

	////////////////////////////////////////////////////////////////////////////
	//
	// Construction of the SQL queries
	//
	////////////////////////////////////////////////////////////////////////////

	public function MakeDeleteQuery($aArgs = array())
	{
		$aModifierProperties = MetaModel::MakeModifierProperties($this);
		$oBuild = new QueryBuilderContext($this, $aModifierProperties);
		$oSQLQuery = $this->MakeSQLObjectQuery($oBuild, array($this->GetClassAlias() => array()), array());
		$oSQLQuery->SetCondition($oBuild->m_oQBExpressions->GetCondition());
		$oSQLQuery->SetSelect($oBuild->m_oQBExpressions->GetSelect());
		$oSQLQuery->OptimizeJoins(array());
		$aScalarArgs = MetaModel::PrepareQueryArguments($aArgs, $this->GetInternalParams());
		$sRet = $oSQLQuery->RenderDelete($aScalarArgs);
		return $sRet;
	}

	public function MakeUpdateQuery($aValues, $aArgs = array())
	{
		// $aValues is an array of $sAttCode => $value
		$aModifierProperties = MetaModel::MakeModifierProperties($this);
		$oBuild = new QueryBuilderContext($this, $aModifierProperties);
		$aRequested = array(); // Requested attributes are the updated attributes
		foreach ($aValues as $sAttCode => $value)
		{
			$aRequested[$sAttCode] = MetaModel::GetAttributeDef($this->GetClass(), $sAttCode);
		}
		$oSQLQuery = $this->MakeSQLObjectQuery($oBuild, array($this->GetClassAlias() => $aRequested), $aValues);
		$oSQLQuery->SetCondition($oBuild->m_oQBExpressions->GetCondition());
		$oSQLQuery->SetSelect($oBuild->m_oQBExpressions->GetSelect());
		$oSQLQuery->OptimizeJoins(array());
		$aScalarArgs = MetaModel::PrepareQueryArguments($aArgs, $this->GetInternalParams());
		$sRet = $oSQLQuery->RenderUpdate($aScalarArgs);
		return $sRet;
	}

	/**
	 * Generate an INSERT statement.
	 * Note : unlike `RenderUpdate` and `RenderSelect`, it is limited to one and only one table.
	 *
	 * @param array $aValues is an array of $sAttCode => $value
	 * @param array $aArgs
	 *
	 * @return string
	 * @throws \CoreException
	 */
	public function MakeInsertQuery($aValues, $aArgs = array())
	{
		$oSQLObjectQueryBuilder = new SQLObjectQueryBuilder($this);
		$oSQLQuery = $oSQLObjectQueryBuilder->MakeSQLObjectUpdateQuery($aValues);
		$aScalarArgs = MetaModel::PrepareQueryArguments($aArgs, $this->GetInternalParams());
		$sRet = $oSQLQuery->RenderInsert($aScalarArgs);
		return $sRet;
	}

	public function GetSQLQueryStructure($aAttToLoad, $bGetCount, $aGroupByExpr = null, $aSelectedClasses = null, $aSelectExpr = null)
	{
		// Hide objects that are not visible to the current user
		//
		$oSearch = $this;
		if (!$this->IsAllDataAllowed() && !$this->IsDataFiltered())
		{
			$oVisibleObjects = UserRights::GetSelectFilter($this->GetClass(), $this->GetModifierProperties('UserRightsGetSelectFilter'));
			if ($oVisibleObjects === false)
			{
				// Make sure this is a valid search object, saying NO for all
				$oVisibleObjects = DBObjectSearch::FromEmptySet($this->GetClass());
			}
			if (is_object($oVisibleObjects))
			{
				$oVisibleObjects->AllowAllData();
				$oSearch = $this->Intersect($oVisibleObjects);
				$oSearch->SetDataFiltered();
			}
		}

		// Compute query modifiers properties (can be set in the search itself, by the context, etc.)
		//
		$aModifierProperties = MetaModel::MakeModifierProperties($oSearch);

		// Create a unique cache id
		//
		$aContextData = array();
		$bCanCache = true;
		if (self::$m_bQueryCacheEnabled || self::$m_bTraceQueries)
		{
			if (isset($_SERVER['REQUEST_URI']))
			{
				$aContextData['sRequestUri'] = $_SERVER['REQUEST_URI'];
			}
			else if (isset($_SERVER['SCRIPT_NAME']))
			{
				$aContextData['sRequestUri'] = $_SERVER['SCRIPT_NAME'];
			}
			else
			{
				$aContextData['sRequestUri'] = '';
			}

			// Need to identify the query
			$sOqlQuery = $oSearch->ToOql(false, null, true);
			if ((strpos($sOqlQuery, '`id` IN (') !== false) || (strpos($sOqlQuery, '`id` NOT IN (') !== false))
			{
				// Requests containing "id IN" are not worth caching
				$bCanCache = false;
			}

			$aContextData['sOqlQuery'] = $sOqlQuery;

			if (count($aModifierProperties))
			{
				array_multisort($aModifierProperties);
				$sModifierProperties = json_encode($aModifierProperties);
			}
			else
			{
				$sModifierProperties = '';
			}
			$aContextData['sModifierProperties'] = $sModifierProperties;

			$sRawId = Dict::GetUserLanguage().'-'.$sOqlQuery.$sModifierProperties;
			if (!is_null($aAttToLoad))
			{
				$sRawId .= json_encode($aAttToLoad);
			}
			$aContextData['aAttToLoad'] = $aAttToLoad;
			if (!is_null($aGroupByExpr))
			{
				foreach($aGroupByExpr as $sAlias => $oExpr)
				{
					$sRawId .= 'g:'.$sAlias.'!'.$oExpr->Render();
				}
			}
			if (!is_null($aSelectExpr))
			{
				foreach($aSelectExpr as $sAlias => $oExpr)
				{
					$sRawId .= 'se:'.$sAlias.'!'.$oExpr->Render();
				}
			}
			$aContextData['aGroupByExpr'] = $aGroupByExpr;
			$aContextData['aSelectExpr'] = $aSelectExpr;
			$sRawId .= $bGetCount;
			$aContextData['bGetCount'] = $bGetCount;
			if (is_array($aSelectedClasses))
			{
				$sRawId .= implode(',', $aSelectedClasses); // Unions may alter the list of selected columns
			}
			$aContextData['aSelectedClasses'] = $aSelectedClasses;
			$bIsArchiveMode = $oSearch->GetArchiveMode();
			$sRawId .= $bIsArchiveMode ? '--arch' : '';
			$bShowObsoleteData = $oSearch->GetShowObsoleteData();
			$sRawId .= $bShowObsoleteData ? '--obso' : '';
			$aContextData['bIsArchiveMode'] = $bIsArchiveMode;
			$aContextData['bShowObsoleteData'] = $bShowObsoleteData;
			$sOqlId = md5($sRawId);
		}
		else
		{
			$sOqlQuery = "SELECTING... ".$oSearch->GetClass();
			$sOqlId = "query id ? n/a";
		}


		// Query caching
		//
		$sOqlAPCCacheId = null;
		if (self::$m_bQueryCacheEnabled && $bCanCache)
		{
			// Warning: using directly the query string as the key to the hash array can FAIL if the string
			// is long and the differences are only near the end... so it's safer (but not bullet proof?)
			// to use a hash (like md5) of the string as the key !
			//
			// Example of two queries that were found as similar by the hash array:
			// SELECT SLT JOIN lnkSLTToSLA AS L1 ON L1.slt_id=SLT.id JOIN SLA ON L1.sla_id = SLA.id JOIN lnkContractToSLA AS L2 ON L2.sla_id = SLA.id JOIN CustomerContract ON L2.contract_id = CustomerContract.id WHERE SLT.ticket_priority = 1 AND SLA.service_id = 3 AND SLT.metric = 'TTO' AND CustomerContract.customer_id = 2
			// and
			// SELECT SLT JOIN lnkSLTToSLA AS L1 ON L1.slt_id=SLT.id JOIN SLA ON L1.sla_id = SLA.id JOIN lnkContractToSLA AS L2 ON L2.sla_id = SLA.id JOIN CustomerContract ON L2.contract_id = CustomerContract.id WHERE SLT.ticket_priority = 1 AND SLA.service_id = 3 AND SLT.metric = 'TTR' AND CustomerContract.customer_id = 2
			// the only difference is R instead or O at position 285 (TTR instead of TTO)...
			//
			if (array_key_exists($sOqlId, self::$m_aQueryStructCache))
			{
				// hit!

				$oSQLQuery = unserialize(serialize(self::$m_aQueryStructCache[$sOqlId]));
				// Note: cloning is not enough because the subtree is made of objects
			}
			elseif (self::$m_bUseAPCCache)
			{
				// Note: For versions of APC older than 3.0.17, fetch() accepts only one parameter
				//
				$sOqlAPCCacheId = 'itop-'.MetaModel::GetEnvironmentId().'-query-cache-'.$sOqlId;
				$oKPI = new ExecutionKPI();
				$result = apc_fetch($sOqlAPCCacheId);
				$oKPI->ComputeStats('Query APC (fetch)', $sOqlQuery);

				if (is_object($result))
				{
					$oSQLQuery = $result;
					self::$m_aQueryStructCache[$sOqlId] = $oSQLQuery;
				}
			}
		}

		if (!isset($oSQLQuery))
		{
			$oKPI = new ExecutionKPI();
			$oSQLQuery = $oSearch->BuildSQLQueryStruct($aAttToLoad, $bGetCount, $aModifierProperties, $aGroupByExpr, $aSelectedClasses, $aSelectExpr);
			$oKPI->ComputeStats('BuildSQLQueryStruct', $sOqlQuery);

			if (self::$m_bQueryCacheEnabled)
			{
				if ($bCanCache && self::$m_bUseAPCCache)
				{
					$oSQLQuery->m_aContextData = $aContextData;
					$oKPI = new ExecutionKPI();
					apc_store($sOqlAPCCacheId, $oSQLQuery, self::$m_iQueryCacheTTL);
					$oKPI->ComputeStats('Query APC (store)', $sOqlQuery);
				}

				self::$m_aQueryStructCache[$sOqlId] = $oSQLQuery->DeepClone();
			}
		}
		return $oSQLQuery;
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
	protected function BuildSQLQueryStruct($aAttToLoad, $bGetCount, $aModifierProperties, $aGroupByExpr = null, $aSelectedClasses = null, $aSelectExpr = null)
	{
		$oBuild = new QueryBuilderContext($this, $aModifierProperties, $aGroupByExpr, $aSelectedClasses, $aSelectExpr);

		$oSQLQuery = $this->MakeSQLObjectQuery($oBuild, $aAttToLoad, array());
		$oSQLQuery->SetCondition($oBuild->m_oQBExpressions->GetCondition());
		if (is_array($aGroupByExpr))
		{
			$aCols = $oBuild->m_oQBExpressions->GetGroupBy();
			$oSQLQuery->SetGroupBy($aCols);
			$oSQLQuery->SetSelect($aCols);
		}
		else
		{
			$oSQLQuery->SetSelect($oBuild->m_oQBExpressions->GetSelect());
		}
		if ($aSelectExpr)
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

		$aMandatoryTables = null;
		if (self::$m_bOptimizeQueries)
		{
			if ($bGetCount)
			{
				// Simplify the query if just getting the count
				$oSQLQuery->SetSelect(array());
			}
			$oBuild->m_oQBExpressions->GetMandatoryTables($aMandatoryTables);
			$oSQLQuery->OptimizeJoins($aMandatoryTables);
		}
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
	 * @param $oBuild
	 * @param null $aAttToLoad
	 * @param array $aValues
	 * @return null|SQLObjectQuery
	 * @throws \CoreException
	 */
	protected function MakeSQLObjectQuery(&$oBuild, $aAttToLoad = null, $aValues = array())
	{
		// Note: query class might be different than the class of the filter
		// -> this occurs when we are linking our class to an external class (referenced by, or pointing to)
		$sClass = $this->GetFirstJoinedClass();
		$sClassAlias = $this->GetFirstJoinedClassAlias();

		$bIsOnQueriedClass = array_key_exists($sClassAlias, $oBuild->GetRootFilter()->GetSelectedClasses());

		//self::DbgTrace("Entering: ".$this->ToOQL().", ".($bIsOnQueriedClass ? "MAIN" : "SECONDARY"));

		//$sRootClass = MetaModel::GetRootClass($sClass);
		$sKeyField = MetaModel::DBGetKey($sClass);

		if ($bIsOnQueriedClass)
		{
			// default to the whole list of attributes + the very std id/finalclass
			$oBuild->m_oQBExpressions->AddSelect($sClassAlias.'id', new FieldExpression('id', $sClassAlias));
			if (is_null($aAttToLoad) || !array_key_exists($sClassAlias, $aAttToLoad))
			{
				$sSelectedClass = $oBuild->GetSelectedClass($sClassAlias);
				$aAttList = MetaModel::ListAttributeDefs($sSelectedClass);
			}
			else
			{
				$aAttList = $aAttToLoad[$sClassAlias];
			}
			foreach ($aAttList as $sAttCode => $oAttDef)
			{
				if (!$oAttDef->IsScalar()) continue;
				// keep because it can be used for sorting - if (!$oAttDef->LoadInObject()) continue;

				if ($oAttDef->IsBasedOnOQLExpression())
				{
					$oBuild->m_oQBExpressions->AddSelect($sClassAlias.$sAttCode, new FieldExpression($sAttCode, $sClassAlias));
				}
				else
				{
					foreach ($oAttDef->GetSQLExpressions() as $sColId => $sSQLExpr)
					{
						$oBuild->m_oQBExpressions->AddSelect($sClassAlias.$sAttCode.$sColId, new FieldExpression($sAttCode.$sColId, $sClassAlias));
					}
				}
			}
		}
		//echo "<p>oQBExpr ".__LINE__.": <pre>\n".print_r($oBuild->m_oQBExpressions, true)."</pre></p>\n";
		$aExpectedAtts = array(); // array of (attcode => fieldexpression)
		//echo "<p>".__LINE__.": GetUnresolvedFields($sClassAlias, ...)</p>\n";
		$oBuild->m_oQBExpressions->GetUnresolvedFields($sClassAlias, $aExpectedAtts);

		// Compute a clear view of required joins (from the current class)
		// Build the list of external keys:
		// -> ext keys required by an explicit join
		// -> ext keys mentionned in a 'pointing to' condition
		// -> ext keys required for an external field
		// -> ext keys required for a friendly name
		//
		$aExtKeys = array(); // array of sTableClass => array of (sAttCode (keys) => array of (sAttCode (fields)=> oAttDef))
		//
		// Optimization: could be partially computed once for all (cached) ?
		//  

		if ($bIsOnQueriedClass)
		{
			// Get all Ext keys for the queried class (??)
			foreach(MetaModel::GetKeysList($sClass) as $sKeyAttCode)
			{
				$sKeyTableClass = MetaModel::GetAttributeOrigin($sClass, $sKeyAttCode);
				$aExtKeys[$sKeyTableClass][$sKeyAttCode] = array();
			}
		}
		// Get all Ext keys used by the filter
		foreach ($this->GetCriteria_PointingTo() as $sKeyAttCode => $aPointingTo)
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
				$oExpression = static::GetPolymorphicExpression($sClass, $sExpectedAttCode);

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

				self::DbgTrace("Adding join from root to leaf: $sSomeClass... let's call MakeSQLObjectQuerySingleTable()");
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
			self::DbgTrace("Main (=leaf) class, call MakeSQLObjectQuerySingleTable()");
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

				self::DbgTrace("Parent class: $sParentClass... let's call MakeSQLObjectQuerySingleTable()");
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
		foreach($this->m_aReferencedBy as $sForeignClass=>$aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode => $aFiltersByOperator)
			{
				foreach ($aFiltersByOperator as $iOperatorCode => $aFilters)
				{
					foreach ($aFilters as $oForeignFilter)
					{
						$oForeignKeyAttDef = MetaModel::GetAttributeDef($sForeignClass, $sForeignExtKeyAttCode);

						self::DbgTrace("Referenced by foreign key: $sForeignExtKeyAttCode... let's call MakeSQLObjectQuery()");
						//self::DbgTrace($oForeignFilter);
						//self::DbgTrace($oForeignFilter->ToOQL());
						//self::DbgTrace($oSelectForeign);
						//self::DbgTrace($oSelectForeign->RenderSelect(array()));

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

						$oSelectForeign = $oForeignFilter->MakeSQLObjectQuery($oBuild, $aAttToLoad);

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
			$oSelectFN = $oSubClassFilter->MakeSQLObjectQuerySingleTable($oBuild, $aAttToLoad, $sSubClass, $aExtKeys, array());
			$oSelectBase->AddLeftJoin($oSelectFN, $sKeyField, MetaModel::DBGetKey($sSubClass));
		}

		// That's all... cross fingers and we'll get some working query

		//MyHelpers::var_dump_html($oSelectBase, true);
		//MyHelpers::var_dump_html($oSelectBase->RenderSelect(), true);
		if (self::$m_bDebugQuery) $oSelectBase->DisplayHtml();
		return $oSelectBase;
	}

	protected function MakeSQLObjectQuerySingleTable(&$oBuild, $aAttToLoad, $sTableClass, $aExtKeys, $aValues)
	{
		// $aExtKeys is an array of sTableClass => array of (sAttCode (keys) => array of sAttCode (fields))

		// Prepare the query for a single table (compound objects)
		// Ignores the items (attributes/filters) that are not on the target table
		// Perform an (inner or left) join for every external key (and specify the expected fields)
		//
		// Returns an SQLQuery
		//
		$sTargetClass = $this->GetFirstJoinedClass();
		$sTargetAlias = $this->GetFirstJoinedClassAlias();
		$sTable = MetaModel::DBGetTable($sTableClass);
		$sTableAlias = $oBuild->GenerateTableAlias($sTargetAlias.'_'.$sTable, $sTable);

		$aTranslation = array();
		$aExpectedAtts = array();
		$oBuild->m_oQBExpressions->GetUnresolvedFields($sTargetAlias, $aExpectedAtts);
		
		$bIsOnQueriedClass = array_key_exists($sTargetAlias, $oBuild->GetRootFilter()->GetSelectedClasses());

		self::DbgTrace("Entering: tableclass=$sTableClass, filter=".$this->ToOQL().", ".($bIsOnQueriedClass ? "MAIN" : "SECONDARY"));

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
						/**
						 * @var string $sPluginClass
						 * @var iQueryModifier $oQueryModifier
						 */
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
		$aAllPointingTo = $this->GetCriteria_PointingTo();

		if (array_key_exists($sTableClass, $aExtKeys))
		{
			foreach ($aExtKeys[$sTableClass] as $sKeyAttCode => $aExtFields)
			{
				$oKeyAttDef = MetaModel::GetAttributeDef($sTableClass, $sKeyAttCode);

				$aPointingTo = $this->GetCriteria_PointingTo($sKeyAttCode);
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

							self::DbgTrace("External key $sKeyAttCode (class: $sKeyClass), call MakeSQLObjectQuery()");
							$oBuild->m_oQBExpressions->PushJoinField(new FieldExpression('id', $sKeyClassAlias));
			
							$oSelectExtKey = $oExtFilter->MakeSQLObjectQuery($oBuild, $aAttToLoad);
			
							$oJoinExpr = $oBuild->m_oQBExpressions->PopJoinField();
							$sExternalKeyTable = $oJoinExpr->GetParent();
							$sExternalKeyField = $oJoinExpr->GetName();
			
							$aCols = $oKeyAttDef->GetSQLExpressions(); // Workaround a PHP bug: sometimes issuing a Notice if invoking current(somefunc())
							$sLocalKeyField = current($aCols); // get the first column for an external key
			
							self::DbgTrace("External key $sKeyAttCode, Join on $sLocalKeyField = $sExternalKeyField");
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
						$oSelectExtKey = $oExtFilter->MakeSQLObjectQuery($oBuild, $aAttToLoad);
						$oJoinExpr = $oBuild->m_oQBExpressions->PopJoinField();
						$sExternalKeyTable = $oJoinExpr->GetParent();
						$sExternalKeyField = $oJoinExpr->GetName();
						$sLeftIndex = $sExternalKeyField.'_left'; // TODO use GetSQLLeft()
						$sRightIndex = $sExternalKeyField.'_right'; // TODO use GetSQLRight()
	
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

	/**
	 * Get the expression for the class and its subclasses (if finalclass = 'subclass' ...)
	 * Simplifies the final expression by grouping classes having the same expression
	 * @param $sClass
	 * @param $sAttCode
	 * @return \FunctionExpression|mixed|null
	 * @throws \CoreException
*/
	static public function GetPolymorphicExpression($sClass, $sAttCode)
	{
		$oExpression = ExpressionCache::GetCachedExpression($sClass, $sAttCode);
		if (!empty($oExpression))
		{
			return $oExpression;
		}

		// 1st step - get all of the required expressions (instantiable classes)
		//            and group them using their OQL representation
		//
		$aExpressions = array(); // signature => array('expression' => oExp, 'classes' => array of classes)
		foreach (MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL) as $sSubClass)
		{
			if (($sSubClass != $sClass) && MetaModel::IsAbstract($sSubClass)) continue;

			$oAttDef = MetaModel::GetAttributeDef($sSubClass, $sAttCode);
			$oSubClassExp = $oAttDef->GetOQLExpression($sSubClass);

			// 3rd step - position the attributes in the hierarchy of classes
			//
			$oSubClassExp->Browse(function($oNode) use ($sSubClass) {
				if ($oNode instanceof FieldExpression)
				{
					$sAttCode = $oNode->GetName();
					$oAttDef = MetaModel::GetAttributeDef($sSubClass, $sAttCode);
					if ($oAttDef->IsExternalField())
					{
						$sKeyAttCode = $oAttDef->GetKeyAttCode();
						$sClassOfAttribute = MetaModel::GetAttributeOrigin($sSubClass, $sKeyAttCode);
					}
					else
					{
						$sClassOfAttribute = MetaModel::GetAttributeOrigin($sSubClass, $sAttCode);
					}
					$sParent = MetaModel::GetAttributeOrigin($sClassOfAttribute, $oNode->GetName());
					$oNode->SetParent($sParent);
				}
			});

			$sSignature = $oSubClassExp->Render();
			if (!array_key_exists($sSignature, $aExpressions))
			{
				$aExpressions[$sSignature] = array(
					'expression' => $oSubClassExp,
					'classes' => array(),
				);
			}
			$aExpressions[$sSignature]['classes'][] = $sSubClass;
		}

		// 2nd step - build the final name expression depending on the finalclass
		//
		if (count($aExpressions) == 1)
		{
			$aExpData = reset($aExpressions);
			$oExpression = $aExpData['expression'];
		}
		else
		{
			$oExpression = null;
			foreach ($aExpressions as $sSignature => $aExpData)
			{
				$oClassListExpr = ListExpression::FromScalars($aExpData['classes']);
				$oClassExpr = new FieldExpression('finalclass', $sClass);
				$oClassInList = new BinaryExpression($oClassExpr, 'IN', $oClassListExpr);

				if (is_null($oExpression))
				{
					$oExpression = $aExpData['expression'];
				}
				else
				{
					$oExpression = new FunctionExpression('IF', array($oClassInList, $aExpData['expression'], $oExpression));
				}
			}
		}
		return $oExpression;
	}

	/**
	 * @param array $aAttCodes array of attCodes to search into
	 * @param string $sNeedle one word to be searched
	 *
	 * @throws \CoreException
	 */
	public function AddCondition_FullTextOnAttributes(array $aAttCodes, $sNeedle)
	{
	}

	public function ListParameters()
	{
		return $this->GetCriteria()->ListParameters();
	}
}
