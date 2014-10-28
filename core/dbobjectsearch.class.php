<?php
// Copyright (C) 2010-2012 Combodo SARL
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


/**
 * Define filters for a given class of objects (formerly named "filter") 
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
 
define('TREE_OPERATOR_EQUALS', 0);
define('TREE_OPERATOR_BELOW', 1);
define('TREE_OPERATOR_BELOW_STRICT', 2);
define('TREE_OPERATOR_NOT_BELOW', 3);
define('TREE_OPERATOR_NOT_BELOW_STRICT', 4);
define('TREE_OPERATOR_ABOVE', 5);
define('TREE_OPERATOR_ABOVE_STRICT', 6);
define('TREE_OPERATOR_NOT_ABOVE', 7);
define('TREE_OPERATOR_NOT_ABOVE_STRICT', 8);

class DBObjectSearch
{
	private $m_aClasses; // queried classes (alias => class name), the first item is the class corresponding to this filter (the rest is coming from subfilters)
	private $m_aSelectedClasses; // selected for the output (alias => class name)
	private $m_oSearchCondition;
	private $m_aParams;
	private $m_aFullText;
	private $m_aPointingTo;
	private $m_aReferencedBy;
	private $m_aRelatedTo;
	private $m_bDataFiltered;

	// By default, some information may be hidden to the current user
	// But it may happen that we need to disable that feature
	private $m_bAllowAllData = false;

	public function __construct($sClass, $sClassAlias = null)
	{
		if (is_null($sClassAlias)) $sClassAlias = $sClass;
		assert('is_string($sClass)');
		assert('MetaModel::IsValidClass($sClass)'); // #@# could do better than an assert, or at least give the caller's reference
		// => idee d'un assert avec call stack (autre utilisation = echec sur query SQL)

		$this->m_aSelectedClasses = array($sClassAlias => $sClass);
		$this->m_aClasses = array($sClassAlias => $sClass);
		$this->m_oSearchCondition = new TrueExpression;
		$this->m_aParams = array();
		$this->m_aFullText = array();
		$this->m_aPointingTo = array();
		$this->m_aReferencedBy = array();
		$this->m_aRelatedTo = array();
		$this->m_bDataFiltered = false;
		$this->m_aParentConditions = array();

		$this->m_aModifierProperties = array();
	}

	/**
	 * Perform a deep clone (as opposed to "clone" which does copy a reference to the underlying objects)
	 **/	 	
	public function DeepClone()
	{
		return unserialize(serialize($this)); // Beware this serializes/unserializes the search and its parameters as well
	}

	public function AllowAllData() {$this->m_bAllowAllData = true;}
	public function IsAllDataAllowed() {return $this->m_bAllowAllData;}
	public function IsDataFiltered() {return $this->m_bDataFiltered; }
	public function SetDataFiltered() {$this->m_bDataFiltered = true;}

	public function GetClassName($sAlias)
	{
		if (array_key_exists($sAlias, $this->m_aClasses))
		{
			return $this->m_aClasses[$sAlias];
		}
		else
		{
			throw new CoreException("Invalid class alias '$sAlias'");
		}
	}

	public function GetJoinedClasses() {return $this->m_aClasses;}

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
	 */	 	
	public function ChangeClass($sNewClass, $sAlias = null)
	{
		if (is_null($sAlias))
		{
			$sAlias = $this->GetClassAlias();
		}
		else
		{
			if (!array_key_exists($sAlias, $this->m_aClasses))
			{
				// discard silently - necessary when recursing on the related nodes (see code below)
				return;
			}
		}
		$sCurrClass = $this->GetClassName($sAlias);
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
		foreach($this->m_aRelatedTo as $aRelatedTo)
		{
			$aRelatedTo['flt']->ChangeClass($sNewClass, $sAlias);
		}
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
			foreach($aReferences as $sForeignExtKeyAttCode => $oForeignFilter)
			{
				$oForeignFilter->ChangeClass($sNewClass, $sAlias);
			}
		}
	}

	public function SetSelectedClasses($aNewSet)
	{
		$this->m_aSelectedClasses = array();
		foreach ($aNewSet as $sAlias => $sClass)
		{
			if (!array_key_exists($sAlias, $this->m_aClasses))
			{
				throw new CoreException('Unexpected class alias', array('alias'=>$sAlias, 'expected'=>$this->m_aClasses));
			}
			$this->m_aSelectedClasses[$sAlias] = $sClass;
		}
	}

	public function GetSelectedClasses()
	{
		return $this->m_aSelectedClasses;
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
		// #@# todo - if (!$this->m_oSearchCondition->IsTrue()) return false;
		if (count($this->m_aFullText) > 0) return false;
		if (count($this->m_aPointingTo) > 0) return false;
		if (count($this->m_aReferencedBy) > 0) return false;
		if (count($this->m_aRelatedTo) > 0) return false;
		if (count($this->m_aParentConditions) > 0) return false;
		return true;
	}
	
	public function Describe()
	{
		// To replace __Describe
	}

	public function DescribeConditionPointTo($sExtKeyAttCode, $aPointingTo)
	{
		if (empty($aPointingTo)) return "";
		foreach($aPointingTo as $iOperatorCode => $oFilter)
		{
			if ($oFilter->IsAny()) break;
			$oAtt = MetaModel::GetAttributeDef($this->GetClass(), $sExtKeyAttCode);
			$sOperator = '';
			switch($iOperatorCode)
			{
				case TREE_OPERATOR_EQUALS:
				$sOperator = 'having';
				break;
	
				case TREE_OPERATOR_BELOW:
				$sOperator = 'below';
				break;
	
				case TREE_OPERATOR_BELOW_STRICT:
				$sOperator = 'strictly below';
				break;
	
				case TREE_OPERATOR_NOT_BELOW:
				$sOperator = 'not below';
				break;
	
				case TREE_OPERATOR_NOT_BELOW_STRICT:
				$sOperator = 'strictly not below';
				break;

				case TREE_OPERATOR_ABOVE:
				$sOperator = 'above';
				break;
	
				case TREE_OPERATOR_ABOVE_STRICT:
				$sOperator = 'strictly above';
				break;
	
				case TREE_OPERATOR_NOT_ABOVE:
				$sOperator = 'not above';
				break;
	
				case TREE_OPERATOR_NOT_ABOVE_STRICT:
				$sOperator = 'strictly not above';
				break;
			}
			$aDescription[] = $oAtt->GetLabel()."$sOperator ({$oFilter->DescribeConditions()})";
		}
		return implode(' and ', $aDescription);
	}

	public function DescribeConditionRefBy($sForeignClass, $sForeignExtKeyAttCode)
	{
		if (!isset($this->m_aReferencedBy[$sForeignClass][$sForeignExtKeyAttCode])) return "";
		$oFilter = $this->m_aReferencedBy[$sForeignClass][$sForeignExtKeyAttCode];
		if ($oFilter->IsAny()) return "";
		$oAtt = MetaModel::GetAttributeDef($sForeignClass, $sForeignExtKeyAttCode);
		return "being ".$oAtt->GetLabel()." for ".$sForeignClass."s in ({$oFilter->DescribeConditions()})";
	}

	public function DescribeConditionRelTo($aRelInfo)
	{
		$oFilter = $aRelInfo['flt'];
		$sRelCode = $aRelInfo['relcode'];
		$iMaxDepth = $aRelInfo['maxdepth'];
		return "related ($sRelCode... peut mieux faire !, $iMaxDepth dig depth) to a {$oFilter->GetClass()} ({$oFilter->DescribeConditions()})";
	}


	public function DescribeConditions()
	{
		$aConditions = array();

		$aCondFT = array();
		foreach($this->m_aFullText as $sFullText)
		{
			$aCondFT[] = " contain word(s) '$sFullText'";
		}
		if (count($aCondFT) > 0)
		{
			$aConditions[] = "which ".implode(" and ", $aCondFT);
		}

		// #@# todo - review textual description of the JOIN and search condition (is that still feasible?)
		$aConditions[] = $this->RenderCondition();

		$aCondPoint = array();
		foreach($this->m_aPointingTo as $sExtKeyAttCode => $aPointingTo)
		{
			$aCondPoint[] = $this->DescribeConditionPointTo($sExtKeyAttCode, $aPointingTo);
		}
		if (count($aCondPoint) > 0)
		{
			$aConditions[] = implode(" and ", $aCondPoint);
		}

		$aCondReferred= array();
		foreach($this->m_aReferencedBy as $sForeignClass=>$aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode=>$oForeignFilter)
			{
				if ($oForeignFilter->IsAny()) continue;
				$aCondReferred[] = $this->DescribeConditionRefBy($sForeignClass, $sForeignExtKeyAttCode);
			}
		}
		foreach ($this->m_aRelatedTo as $aRelInfo)
		{
			$aCondReferred[] = $this->DescribeConditionRelTo($aRelInfo);
		}
		if (count($aCondReferred) > 0)
		{
			$aConditions[] = implode(" and ", $aCondReferred);
		}

		foreach ($this->m_aParentConditions as $aRelInfo)
		{
			$aCondReferred[] = $this->DescribeConditionParent($aRelInfo);
		}

		return implode(" and ", $aConditions);		
	}
	
	public function __DescribeHTML()
	{
		try
		{
			$sConditionDesc = $this->DescribeConditions();
		}
		catch (MissingQueryArgument $e)
		{
			$sConditionDesc = '?missing query argument?';
		}
		if (!empty($sConditionDesc))
		{
			return "Objects of class '".$this->GetClass()."', $sConditionDesc";
		}
		return "Any object of class '".$this->GetClass()."'";
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
//echo "<p>TransferConditionExpression:<br/>";
//echo "Adding Conditions:<br/><pre>oFilter:\n".print_r($oFilter, true)."\naTranslation:\n".print_r($aTranslation, true)."</pre>\n";
//echo "</p>";
		$oTranslated = $oFilter->GetCriteria()->Translate($aTranslation, false, false /* leave unresolved fields */);
//echo "Adding Conditions (translated):<br/><pre>".print_r($oTranslated, true)."</pre>\n";
		$this->AddConditionExpression($oTranslated);
		$this->m_aParams = array_merge($this->m_aParams, $oFilter->m_aParams);
	}

	protected function RenameParam($sOldName, $sNewName)
	{
		$this->m_oSearchCondition->RenameParam($sOldName, $sNewName);
		foreach($this->m_aRelatedTo as $aRelatedTo)
		{
			$aRelatedTo['flt']->RenameParam($sOldName, $sNewName);
		}
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
			foreach($aReferences as $sForeignExtKeyAttCode => $oForeignFilter)
			{
				$oForeignFilter->RenameParam($sOldName, $sNewName);
			}
		}

		foreach($this->m_aParentConditions as $aParent)
		{
			$aParent['expression']->RenameParam($sOldName, $sNewName);	
		}
	}
	
	public function ResetCondition()
	{
		$this->m_oSearchCondition = new TrueExpression();
		$this->m_aParentConditions = array();
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

	public function AddCondition($sFilterCode, $value, $sOpCode = null)
	{
		MyHelpers::CheckKeyInArray('filter code', $sFilterCode, MetaModel::GetClassFilterDefs($this->GetClass()));
		$oFilterDef = MetaModel::GetClassFilterDef($this->GetClass(), $sFilterCode);

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
		MyHelpers::CheckKeyInArray('operator', $sOpCode, $oFilterDef->GetOperators());

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

		case "IN":
			if (!is_array($value)) $value = array($value);
			$sListExpr = '('.implode(', ', CMDBSource::Quote($value)).')';
			$sOQLCondition = $oField->Render()." IN $sListExpr";
			break;

		case "NOTIN":
			if (!is_array($value)) $value = array($value);
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
			$this->m_aParams[$sFilterCode] = $value;
			$sOperator = $sOpCode;
		}

		switch($sOpCode)
		{
		case "IN":
		case "NOTIN":
			$oNewCondition = Expression::FromOQL($sOQLCondition);
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
	 * Specify a condition on external keys or link sets
	 * @param sAttSpec Can be either an attribute code or extkey->[sAttSpec] or linkset->[sAttSpec] and so on, recursively
	 *                 Example: infra_list->ci_id->location_id->country	 
	 * @param value The value to match (can be an array => IN(val1, val2...)
	 * @return void
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

	public function AddCondition_FullText($sFullText)
	{
		$this->m_aFullText[] = $sFullText;
	}

	public function AddCondition_Parent($sAttCode, $iOperatorCode, $oExpression)
	{
		$oAttDef = MetaModel::GetAttributeDef($this->GetClass(), $sAttCode);
		if (!$oAttDef instanceof AttributeHierarchicalKey)
		{
			throw new Exception("AddCondition_Parent can only be used on hierarchical keys. '$sAttCode' is not a hierarchical key.");
		}
		$this->m_aParentConditions[] = array(
			'attCode' => $sAttCode,
			'operator' => $iOperatorCode,
			'expression' => $oExpression,
		);
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
			foreach($aReferences as $sForeignExtKeyAttCode=>$oForeignFilter)
			{
				$oForeignFilter->AddToNameSpace($aClassAliases, $aAliasTranslation);
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
				foreach($aReferences as $sForeignExtKeyAttCode=>$oForeignFilter)
				{
					$ret = $oForeignFilter->GetNode($sAlias);
					if (is_object($ret))
					{
						return $ret;
					}
				}
			}
		}
		// Not found
		return null;
	}


	public function AddCondition_PointingTo(DBObjectSearch $oFilter, $sExtKeyAttCode, $iOperatorCode = TREE_OPERATOR_EQUALS)
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
		//       (as it was done and fixed an issue in MergeWith())
		//       this was not implemented here because it was causing a regression (login as admin, select an org, click on any badge)
		//       root cause: FromOQL relies on the fact that the passed filter can be modified later 
		// NO: $oFilter = $oFilter->DeepClone();
		// See also: Trac #639, and self::AddCondition_ReferencedBy()
		$aAliasTranslation = array();
		$res = $this->AddCondition_PointingTo_InNameSpace($oFilter, $sExtKeyAttCode, $this->m_aClasses, $aAliasTranslation, $iOperatorCode);
		$this->TransferConditionExpression($oFilter, $aAliasTranslation);
		return $res;
	}

	protected function AddCondition_PointingTo_InNameSpace(DBObjectSearch $oFilter, $sExtKeyAttCode, &$aClassAliases, &$aAliasTranslation, $iOperatorCode)
	{
		// Find the node on which the new tree must be attached (most of the time it is "this")
		$oReceivingFilter = $this->GetNode($this->GetClassAlias());

		$oFilter->AddToNamespace($aClassAliases, $aAliasTranslation);
		$oReceivingFilter->m_aPointingTo[$sExtKeyAttCode][$iOperatorCode][] = $oFilter;
	}

	public function AddCondition_ReferencedBy(DBObjectSearch $oFilter, $sForeignExtKeyAttCode)
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
		//       (as it was done and fixed an issue in MergeWith())
		//       this was not implemented here because it was causing a regression (login as admin, select an org, click on any badge)
		//       root cause: FromOQL relies on the fact that the passed filter can be modified later 
		// NO: $oFilter = $oFilter->DeepClone();
		// See also: Trac #639, and self::AddCondition_PointingTo()
		$aAliasTranslation = array();
		$res = $this->AddCondition_ReferencedBy_InNameSpace($oFilter, $sForeignExtKeyAttCode, $this->m_aClasses, $aAliasTranslation);
		$this->TransferConditionExpression($oFilter, $aAliasTranslation);
		return $res;
	}

	protected function AddCondition_ReferencedBy_InNameSpace(DBObjectSearch $oFilter, $sForeignExtKeyAttCode, &$aClassAliases, &$aAliasTranslation)
	{
		$sForeignClass = $oFilter->GetClass();

		// Find the node on which the new tree must be attached (most of the time it is "this")
		$oReceivingFilter = $this->GetNode($this->GetClassAlias());

		if (array_key_exists($sForeignClass, $this->m_aReferencedBy) && array_key_exists($sForeignExtKeyAttCode, $this->m_aReferencedBy[$sForeignClass]))
		{
			$oReceivingFilter->m_aReferencedBy[$sForeignClass][$sForeignExtKeyAttCode]->MergeWith_InNamespace($oFilter, $aClassAliases, $aAliasTranslation);
		}
		else
		{
			$oFilter->AddToNamespace($aClassAliases, $aAliasTranslation);

			// #@# The condition expression found in that filter should not be used - could be another kind of structure like a join spec tree !!!!
			//$oNewFilter = $oFilter->DeepClone();
			//$oNewFilter->ResetCondition();

			$oReceivingFilter->m_aReferencedBy[$sForeignClass][$sForeignExtKeyAttCode]= $oFilter;
		}
	}

	public function AddCondition_RelatedTo(DBObjectSearch $oFilter, $sRelCode, $iMaxDepth)
	{
		MyHelpers::CheckValueInArray('relation code', $sRelCode, MetaModel::EnumRelations());
		$this->m_aRelatedTo[] = array('flt'=>$oFilter, 'relcode'=>$sRelCode, 'maxdepth'=>$iMaxDepth);
	}

	public function MergeWith($oFilter)
	{
		$oFilter = $oFilter->DeepClone();
		$aAliasTranslation = array();
		$res = $this->MergeWith_InNamespace($oFilter, $this->m_aClasses, $aAliasTranslation);
		$this->TransferConditionExpression($oFilter, $aAliasTranslation);
		return $res;
	}

	protected function MergeWith_InNamespace($oFilter, &$aClassAliases, &$aAliasTranslation)
	{
		if ($this->GetClass() != $oFilter->GetClass())
		{
			throw new CoreException("Attempting to merge a filter of class '{$this->GetClass()}' with a filter of class '{$oFilter->GetClass()}'");
		}

		// Translate search condition into our aliasing scheme
		$aAliasTranslation[$oFilter->GetClassAlias()]['*'] = $this->GetClassAlias(); 

		$this->m_aFullText = array_merge($this->m_aFullText, $oFilter->m_aFullText);
		$this->m_aRelatedTo = array_merge($this->m_aRelatedTo, $oFilter->m_aRelatedTo);

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
			foreach($aReferences as $sForeignExtKeyAttCode => $oForeignFilter)
			{
				$this->AddCondition_ReferencedBy_InNamespace($oForeignFilter, $sForeignExtKeyAttCode, $aClassAliases, $aAliasTranslation);
			}
		}
	}

	public function GetCriteria() {return $this->m_oSearchCondition;}
	public function GetCriteria_FullText() {return $this->m_aFullText;}
	public function GetCriteria_PointingTo($sKeyAttCode = "")
	{
		if (empty($sKeyAttCode))
		{
			return $this->m_aPointingTo;
		}
		if (!array_key_exists($sKeyAttCode, $this->m_aPointingTo)) return array();
		return $this->m_aPointingTo[$sKeyAttCode];
	}
	public function GetCriteria_ReferencedBy($sRemoteClass = "", $sForeignExtKeyAttCode = "")
	{
		if (empty($sRemoteClass))
		{
			return $this->m_aReferencedBy;
		}
		if (!array_key_exists($sRemoteClass, $this->m_aReferencedBy)) return null;
		if (empty($sForeignExtKeyAttCode))
		{
			return $this->m_aReferencedBy[$sRemoteClass];
		}
		if (!array_key_exists($sForeignExtKeyAttCode, $this->m_aReferencedBy[$sRemoteClass])) return null;
		return $this->m_aReferencedBy[$sRemoteClass][$sForeignExtKeyAttCode];
	}
	public function GetCriteria_RelatedTo()
	{
		return $this->m_aRelatedTo;
	}

	public function SetInternalParams($aParams)
	{
		return $this->m_aParams = $aParams;
	}

	public function GetInternalParams()
	{
		return $this->m_aParams;
	}

	public function GetQueryParams()
	{
		$aParams = array();
		$this->m_oSearchCondition->Render($aParams, true);
		return $aParams;
	}

	public function ListConstantFields()
	{
		return $this->m_oSearchCondition->ListConstantFields();
	}
	
	public function RenderCondition()
	{
		return $this->m_oSearchCondition->Render($this->m_aParams, false);
	}

	/**
	 * Turn the parameters (:xxx) into scalar values in order to easily
	 * serialize a search
	 */
	public function ApplyParameters($aArgs)
	{
		return $this->m_oSearchCondition->ApplyParameters(array_merge($this->m_aParams, $aArgs));
	}
	
	public function serialize($bDevelopParams = false, $aContextParams = null)
	{
		$sOql = $this->ToOql($bDevelopParams, $aContextParams);
		return base64_encode(serialize(array($sOql, $this->m_aParams, $this->m_aModifierProperties)));
	}
	
	static public function unserialize($sValue)
	{
		$aData = unserialize(base64_decode($sValue));
		$sOql = $aData[0];
		$aParams = $aData[1];
		// We've tried to use gzcompress/gzuncompress, but for some specific queries
		// it was not working at all (See Trac #193)
		// gzuncompress was issuing a warning "data error" and the return object was null
		$oRetFilter = self::FromOQL($sOql, $aParams);
		$oRetFilter->m_aModifierProperties = $aData[2];
		return $oRetFilter;
	}

	// SImple BUt Structured Query Languag - SubuSQL
	//
	static private function Value2Expression($value)
	{
		$sRet = $value;
		if (is_array($value))
		{
			$sRet = VS_START.implode(', ', $value).VS_END;
		}
		else if (!is_numeric($value))
		{
			$sRet = "'".addslashes($value)."'";
		}
		return $sRet;
	}
	static private function Expression2Value($sExpr)
	{
		$retValue = $sExpr;
		if ((substr($sExpr, 0, 1) == "'") && (substr($sExpr, -1, 1) == "'"))
		{
			$sNoQuotes = substr($sExpr, 1, -1);
			return stripslashes($sNoQuotes);
		}
		if ((substr($sExpr, 0, 1) == VS_START) && (substr($sExpr, -1, 1) == VS_END))
		{
			$sNoBracket = substr($sExpr, 1, -1);
			$aRetValue = array();
			foreach (explode(",", $sNoBracket) as $sItem)
			{
				$aRetValue[] = self::Expression2Value(trim($sItem));
			}
			return $aRetValue;
		}
		return $retValue;
	}

	// Alternative to object mapping: the data are transfered directly into an array
	// This is 10 times faster than creating a set of objects, and makes sense when optimization is required
	/**
	 * @param hash $aOrderBy Array of '[<classalias>.]attcode' => bAscending
	 */	
	public function ToDataArray($aColumns = array(), $aOrderBy = array(), $aArgs = array())
	{
		$sSQL = MetaModel::MakeSelectQuery($this, $aOrderBy, $aArgs);
		$resQuery = CMDBSource::Query($sSQL);
		if (!$resQuery) return;

		if (count($aColumns) == 0)
		{
			$aColumns = array_keys(MetaModel::ListAttributeDefs($this->GetClass()));
			// Add the standard id (as first column)
			array_unshift($aColumns, 'id');
		}

		$aQueryCols = CMDBSource::GetColumns($resQuery);

		$sClassAlias = $this->GetClassAlias();
		$aColMap = array();
		foreach ($aColumns as $sAttCode)
		{
			$sColName = $sClassAlias.$sAttCode;
			if (in_array($sColName, $aQueryCols))
			{
				$aColMap[$sAttCode] = $sColName;
			}
		}

		$aRes = array();
		while ($aRow = CMDBSource::FetchArray($resQuery))
		{
			$aMappedRow = array();
			foreach ($aColMap as $sAttCode => $sColName)
			{
				$aMappedRow[$sAttCode] = $aRow[$sColName];
			}
			$aRes[] = $aMappedRow;
		}
		CMDBSource::FreeResult($resQuery);
		return $aRes;
	}

	public function ToOQL($bDevelopParams = false, $aContextParams = null)
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
	
		$sSelectedClasses = implode(', ', array_keys($this->m_aSelectedClasses));
		$sRes = 'SELECT '.$sSelectedClasses.' FROM';

		$sRes .= ' '.$this->GetFirstJoinedClass().' AS '.$this->GetFirstJoinedClassAlias();
		$sRes .= $this->ToOQL_Joins();
		$sRes .= " WHERE ".$this->m_oSearchCondition->Render($aParams, $bRetrofitParams);

		// Temporary: add more info about other conditions, necessary to avoid strange behaviors with the cache
		foreach($this->m_aFullText as $sFullText)
		{
			$sRes .= " AND MATCHES '$sFullText'";
		}
		return $sRes;
	}

	protected function ToOQL_Joins()
	{
		$sRes = '';
		foreach($this->m_aPointingTo as $sExtKey => $aPointingTo)
		{
			foreach($aPointingTo as $iOperatorCode => $aFilter)
			{
				foreach($aFilter as $oFilter)
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
					$sRes .= ' JOIN '.$oFilter->GetFirstJoinedClass().' AS '.$oFilter->GetFirstJoinedClassAlias().' ON '.$this->GetFirstJoinedClassAlias().'.'.$sExtKey.$sOperator.$oFilter->GetFirstJoinedClassAlias().'.id';
					$sRes .= $oFilter->ToOQL_Joins();				
				}
			}
		}
		foreach($this->m_aReferencedBy as $sForeignClass=>$aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode=>$oForeignFilter)
			{
				$sRes .= ' JOIN '.$oForeignFilter->GetFirstJoinedClass().' AS '.$oForeignFilter->GetFirstJoinedClassAlias().' ON '.$oForeignFilter->GetFirstJoinedClassAlias().'.'.$sForeignExtKeyAttCode.' = '.$this->GetFirstJoinedClassAlias().'.id';
				$sRes .= $oForeignFilter->ToOQL_Joins();
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

	// Create a search definition that leads to 0 result, still a valid search object
	static public function FromEmptySet($sClass)
	{
		$oResultFilter = new DBObjectSearch($sClass);
		$oResultFilter->m_oSearchCondition = new FalseExpression;
		return $oResultFilter;
	}

	static protected $m_aOQLQueries = array();

	// Do not filter out depending on user rights
	// In particular when we are currently in the process of evaluating the user rights...
	static public function FromOQL_AllData($sQuery, $aParams = null)
	{
		$oRes = self::FromOQL($sQuery, $aParams);
		$oRes->AllowAllData();
		return $oRes;
	}

	static public function FromOQL($sQuery, $aParams = null)
	{
		if (empty($sQuery)) return null;

		// Query caching
		$bOQLCacheEnabled = true;
		if ($bOQLCacheEnabled && array_key_exists($sQuery, self::$m_aOQLQueries))
		{
			// hit!
			$oClone = self::$m_aOQLQueries[$sQuery]->DeepClone();
			if (!is_null($aParams))
			{
				$oClone->m_aParams = $aParams;
			}
			return $oClone;
		}

		$oOql = new OqlInterpreter($sQuery);
		$oOqlQuery = $oOql->ParseObjectQuery();

		$oMetaModel = new ModelReflectionRuntime();
		$oOqlQuery->Check($oMetaModel, $sQuery); // Exceptions thrown in case of issue

		$sClass = $oOqlQuery->GetClass();
		$sClassAlias = $oOqlQuery->GetClassAlias();

		$oResultFilter = new DBObjectSearch($sClass, $sClassAlias);
		$aAliases = array($sClassAlias => $sClass);

		// Maintain an array of filters, because the flat list is in fact referring to a tree
		// And this will be an easy way to dispatch the conditions
		// $oResultFilter will be referenced by the other filters, or the other way around...
		$aJoinItems = array($sClassAlias => $oResultFilter);

		$aJoinSpecs = $oOqlQuery->GetJoins();
		if (is_array($aJoinSpecs))
		{
			foreach ($aJoinSpecs as $oJoinSpec)
			{
				$sJoinClass = $oJoinSpec->GetClass();
				$sJoinClassAlias = $oJoinSpec->GetClassAlias();

				// Assumption: ext key on the left only !!!
				// normalization should take care of this
				$oLeftField = $oJoinSpec->GetLeftField();
				$sFromClass = $oLeftField->GetParent();
				$sExtKeyAttCode = $oLeftField->GetName();

				$oRightField = $oJoinSpec->GetRightField();
				$sToClass = $oRightField->GetParent();

				$aAliases[$sJoinClassAlias] = $sJoinClass;
				$aJoinItems[$sJoinClassAlias] = new DBObjectSearch($sJoinClass, $sJoinClassAlias);

				if ($sFromClass == $sJoinClassAlias)
				{
					$oReceiver = $aJoinItems[$sToClass];
					$oNewComer = $aJoinItems[$sFromClass];

					$aAliasTranslation = array();
					$oReceiver->AddCondition_ReferencedBy_InNameSpace($oNewComer, $sExtKeyAttCode, $oReceiver->m_aClasses, $aAliasTranslation);
				}
				else
				{
					$sOperator = $oJoinSpec->GetOperator();
					switch($sOperator)
					{
						case '=':
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
					$oReceiver = $aJoinItems[$sFromClass];
					$oNewComer = $aJoinItems[$sToClass];

					$aAliasTranslation = array();
					$oReceiver->AddCondition_PointingTo_InNameSpace($oNewComer, $sExtKeyAttCode, $oReceiver->m_aClasses, $aAliasTranslation, $iOperatorCode);
				}
			}
		}

		// Check and prepare the select information
		$aSelected = array();
		foreach ($oOqlQuery->GetSelectedClasses() as $oClassDetails)
		{
			$sClassToSelect = $oClassDetails->GetValue();
			$aSelected[$sClassToSelect] = $aAliases[$sClassToSelect];
		}
		$oResultFilter->m_aClasses = $aAliases;
		$oResultFilter->SetSelectedClasses($aSelected);

		$oConditionTree = $oOqlQuery->GetCondition();
		if ($oConditionTree instanceof Expression)
		{
			$oResultFilter->m_oSearchCondition = $oResultFilter->OQLExpressionToCondition($sQuery, $oConditionTree, $aAliases);
		}

		if (!is_null($aParams))
		{
			$oResultFilter->m_aParams = $aParams;
		}

		if ($bOQLCacheEnabled)
		{
			self::$m_aOQLQueries[$sQuery] = $oResultFilter->DeepClone();
		}

		return $oResultFilter;
	}

	public function toxpath()
	{
		// #@# a voir...
	}
	static public function fromxpath()
	{
		// #@# a voir...
	}
}


?>
