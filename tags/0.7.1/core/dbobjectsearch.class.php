<?php

/**
 * Define filters for a given class of objects (formerly named "filter") 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */


/**
 * Sibusql - value set start 
 * @package     iTopORM
 * @info        zis is private 
 */
define('VS_START', '{');
/**
 * Sibusql - value set end 
 * @package     iTopORM
 */
define('VS_END', '}');


define('SIBUSQLPARAMREGEXP', "/\\$\\[(.*)\\:(.*)\\:(.*)\\]/U");
define('SIBUSQLTHISREGEXP', "/this\\.(.*)/U");


/**
 * Define filters for a given class of objects (formerly named "filter") 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @mytagrom    youpi
 * @since       1.0
 * @version     1.1.1.1 $
 */
class DBObjectSearch
{
	private $m_sClass;
	private $m_sClassAlias;
	private $m_aClasses; // queried classes (alias => class name)
	private $m_oSearchCondition;
	private $m_aFullText;
	private $m_aPointingTo;
	private $m_aReferencedBy;
	private $m_aRelatedTo;

	public function __construct($sClass, $sClassAlias = '')
	{
		if (empty($sClassAlias)) $sClassAlias = $sClass;
		assert('is_string($sClass)');
		assert('MetaModel::IsValidClass($sClass)'); // #@# could do better than an assert, or at least give the caller's reference
		// => idee d'un assert avec call stack (autre utilisation = echec sur query SQL)
		if (empty($sClassAlias)) $sClassAlias = $sClass;
		$this->m_sClass = $sClass;
		$this->m_sClassAlias = $sClassAlias;
		$this->m_aClasses = array($sClassAlias => $sClass);
		$this->m_oSearchCondition = new TrueExpression;
		$this->m_aFullText = array();
		$this->m_aPointingTo = array();
		$this->m_aReferencedBy = array();
		$this->m_aRelatedTo = array();
	}

	public function IsAny()
	{
		// #@# todo - if (!$this->m_oSearchCondition->IsTrue()) return false;
		if (count($this->m_aFullText) > 0) return false;
		if (count($this->m_aPointingTo) > 0) return false;
		if (count($this->m_aReferencedBy) > 0) return false;
		if (count($this->m_aRelatedTo) > 0) return false;
		return true;
	}
	
	public function Describe()
	{
		// To replace __Describe
	}

	public function DescribeConditionPointTo($sExtKeyAttCode)
	{
		if (!isset($this->m_aPointingTo[$sExtKeyAttCode])) return "";
		$oFilter = $this->m_aPointingTo[$sExtKeyAttCode];
		if ($oFilter->IsAny()) return "";
		$oAtt = MetaModel::GetAttributeDef($this->GetClass(), $sExtKeyAttCode);
		return $oAtt->GetLabel()." having ({$oFilter->DescribeConditions()})";
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
		foreach($this->m_aPointingTo as $sExtKeyAttCode=>$oFilter)
		{
			if ($oFilter->IsAny()) continue;
			$aCondPoint[] = $this->DescribeConditionPointTo($sExtKeyAttCode);
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

		return implode(" and ", $aConditions);		
	}
	
	public function __DescribeHTML()
	{
		$sConditionDesc = $this->DescribeConditions();
		if (!empty($sConditionDesc))
		{
			return "Objects of class '$this->m_sClass', $sConditionDesc";
		}
		return "Any object of class '$this->m_sClass'";
	}

	protected function TransferConditionExpression($oFilter, $aTranslation)
	{
		$oTranslated = $oFilter->GetCriteria()->Translate($aTranslation, false);
		$this->AddConditionExpression($oTranslated);
	}

	public function ResetCondition()
	{
		$this->m_oSearchCondition = new TrueExpression();
		// ? is that usefull/enough, do I need to rebuild the list after the subqueries ?
		// $this->m_aClasses = array($this->m_sClassAlias => $this->m_sClass);
	}

	public function AddConditionExpression($oExpression)
	{
		$this->m_oSearchCondition = $this->m_oSearchCondition->LogAnd($oExpression); 
	}

	public function AddCondition($sFilterCode, $value, $sOpCode = null)
	{
	// #@# backward compatibility for pkey/id
	if (strtolower(trim($sFilterCode)) == 'pkey') $sFilterCode = 'id';
// #@# todo - obsolete smoothly, first send exceptions
//		throw new CoreException('SibusQL has been obsoleted, please update your queries', array('sibusql'=>$sQuery, 'oql'=>$oFilter->ToOQL()));

		MyHelpers::CheckKeyInArray('filter code', $sFilterCode, MetaModel::GetClassFilterDefs($this->m_sClass));
		$oFilterDef = MetaModel::GetClassFilterDef($this->m_sClass, $sFilterCode);

		if (empty($sOpCode))
		{
			$sOpCode = $oFilterDef->GetLooseOperator();
		}
		MyHelpers::CheckKeyInArray('operator', $sOpCode, $oFilterDef->GetOperators());

		// Preserve backward compatibility - quick n'dirty way to change that API semantic
		//
		$oField = new FieldExpression($sFilterCode, $this->m_sClassAlias);
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
			$oRightExpr = new ScalarExpression("%$value%");
			$sOperator = 'LIKE';
			break;

		case 'Begins with':
			$oRightExpr = new ScalarExpression("$value%");
			$sOperator = 'LIKE';
			break;

		case 'Finishes with':
			$oRightExpr = new ScalarExpression("%$value");
			$sOperator = 'LIKE';
			break;

		default:
			$oRightExpr = new ScalarExpression($value);
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
			$oNewCondition = new BinaryExpression($oField, $sOperator, $oRightExpr);
		}

		$this->AddConditionExpression($oNewCondition);
	}

	public function AddCondition_FullText($sFullText)
	{
		$this->m_aFullText[] = $sFullText;
	}

	protected function AddToNameSpace(&$aClassAliases, &$aAliasTranslation)
	{
		$sOrigAlias = $this->m_sClassAlias;
		if (array_key_exists($sOrigAlias, $aClassAliases))
		{
			$this->m_sClassAlias = MetaModel::GenerateUniqueAlias($aClassAliases, $sOrigAlias, $this->m_sClass);
			// Translate the condition expression with the new alias
			$aAliasTranslation[$sOrigAlias]['*'] = $this->m_sClassAlias;
		}

		foreach($this->m_aPointingTo as $sExtKeyAttCode=>$oFilter)
		{
			$oFilter->AddToNameSpace($aClassAliases, $aAliasTranslation);
		}

		foreach($this->m_aReferencedBy as $sForeignClass=>$aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode=>$oForeignFilter)
			{
				$oForeignFilter->AddToNameSpace($aClassAliases, $aAliasTranslation);
			}
		}
	}

	public function AddCondition_PointingTo(DBObjectSearch $oFilter, $sExtKeyAttCode)
	{
		$aAliasTranslation = array();
		$res = $this->AddCondition_PointingTo_InNameSpace($oFilter, $sExtKeyAttCode, $this->m_aClasses, $aAliasTranslation);
		$this->TransferConditionExpression($oFilter, $aAliasTranslation);
		return $res;
	}

	protected function AddCondition_PointingTo_InNameSpace(DBObjectSearch $oFilter, $sExtKeyAttCode, &$aClassAliases, &$aAliasTranslation)
	{
		if (!MetaModel::IsValidKeyAttCode($this->GetClass(), $sExtKeyAttCode))
		{
			trigger_error("The attribute code '$sExtKeyAttCode' is not an external key of the class '{$this->GetClass()}' - the condition will be ignored", E_USER_WARNING);
		}
		$oAttExtKey = MetaModel::GetAttributeDef($this->GetClass(), $sExtKeyAttCode);
		if(!MetaModel::IsSameFamilyBranch($oFilter->GetClass(), $oAttExtKey->GetTargetClass()))
		{
			trigger_error("The specified filter (pointing to {$oFilter->GetClass()}) is not compatible with the key '{$this->GetClass()}::$sExtKeyAttCode', which is pointing to {$oAttExtKey->GetTargetClass()}", E_USER_ERROR);
		}

		if (array_key_exists($sExtKeyAttCode, $this->m_aPointingTo))
		{
			$this->m_aPointingTo[$sExtKeyAttCode]->MergeWith_InNamespace($oFilter, $aClassAliases, $aAliasTranslation);
		}
		else
		{
			$oFilter->AddToNamespace($aClassAliases, $aAliasTranslation);

			// #@# The condition expression found in that filter should not be used - could be another kind of structure like a join spec tree !!!!
			// $oNewFilter = clone $oFilter;
			// $oNewFilter->ResetCondition();

			$this->m_aPointingTo[$sExtKeyAttCode] = $oFilter;
		}
	}

	public function AddCondition_ReferencedBy(DBObjectSearch $oFilter, $sForeignExtKeyAttCode)
	{
		$aAliasTranslation = array();
		$res = $this->AddCondition_ReferencedBy_InNameSpace($oFilter, $sForeignExtKeyAttCode, $this->m_aClasses, $aAliasTranslation);
		$this->TransferConditionExpression($oFilter, $aAliasTranslation);
		return $res;
	}

	protected function AddCondition_ReferencedBy_InNameSpace(DBObjectSearch $oFilter, $sForeignExtKeyAttCode, &$aClassAliases, &$aAliasTranslation)
	{
		$sForeignClass = $oFilter->GetClass();
		$sForeignClassAlias = $oFilter->GetClassAlias();
		if (!MetaModel::IsValidKeyAttCode($sForeignClass, $sForeignExtKeyAttCode))
		{
			trigger_error("The attribute code '$sForeignExtKeyAttCode' is not an external key of the class '{$sForeignClass}' - the condition will be ignored", E_USER_WARNING);
		}
		$oAttExtKey = MetaModel::GetAttributeDef($sForeignClass, $sForeignExtKeyAttCode);
		if(!MetaModel::IsSameFamilyBranch($this->GetClass(), $oAttExtKey->GetTargetClass()))
		{
			trigger_error("The specified filter (objects referencing an object of class {$this->GetClass()}) is not compatible with the key '{$sForeignClass}::$sForeignExtKeyAttCode', which is pointing to {$oAttExtKey->GetTargetClass()}", E_USER_ERROR);
		}
		if (array_key_exists($sForeignClass, $this->m_aReferencedBy) && array_key_exists($sForeignExtKeyAttCode, $this->m_aReferencedBy[$sForeignClass]))
		{
			$this->m_aReferencedBy[$sForeignClass][$sForeignExtKeyAttCode]->MergeWith_InNamespace($oFilter, $aClassAliases, $aAliasTranslation);
		}
		else
		{
			$oFilter->AddToNamespace($aClassAliases, $aAliasTranslation);

			// #@# The condition expression found in that filter should not be used - could be another kind of structure like a join spec tree !!!!
			//$oNewFilter = clone $oFilter;
			//$oNewFilter->ResetCondition();

			$this->m_aReferencedBy[$sForeignClass][$sForeignExtKeyAttCode]= $oFilter;
		}
	}

	public function AddCondition_LinkedTo(DBObjectSearch $oLinkFilter, $sExtKeyAttCodeToMe, $sExtKeyAttCodeTarget, DBObjectSearch $oFilterTarget)
	{
		$oLinkFilterFinal = clone $oLinkFilter;
		$oLinkFilterFinal->AddCondition_PointingTo($sExtKeyAttCodeToMe);

		$this->AddCondition_ReferencedBy($oLinkFilterFinal, $sExtKeyAttCodeToMe);
	}

	public function AddCondition_RelatedTo(DBObjectSearch $oFilter, $sRelCode, $iMaxDepth)
	{
		MyHelpers::CheckValueInArray('relation code', $sRelCode, MetaModel::EnumRelations());
		$this->m_aRelatedTo[] = array('flt'=>$oFilter, 'relcode'=>$sRelCode, 'maxdepth'=>$iMaxDepth);
	}

	public function MergeWith($oFilter)
	{
		$aAliasTranslation = array();
		$res = $this->MergeWith_InNamespace($oFilter, $this->m_aClasses, $aAliasTranslation);
		$this->TransferConditionExpression($oFilter, $aAliasTranslation);
		return $res;
	}

	protected function MergeWith_InNamespace($oFilter, &$aClassAliases, &$aAliasTranslation)
	{
		if ($this->GetClass() != $oFilter->GetClass())
		{
			trigger_error("Attempting to merge a filter of class '{$this->GetClass()}' with a filter of class '{$oFilter->GetClass()}'", E_USER_ERROR);
		}

		// Translate search condition into our aliasing scheme
		$aAliasTranslation[$oFilter->GetClassAlias()]['*'] = $this->GetClassAlias(); 

		$this->m_aFullText = array_merge($this->m_aFullText, $oFilter->m_aFullText);
		$this->m_aRelatedTo = array_merge($this->m_aRelatedTo, $oFilter->m_aRelatedTo);

		foreach($oFilter->m_aPointingTo as $sExtKeyAttCode=>$oExtFilter)
		{
			$this->AddCondition_PointingTo_InNamespace($oExtFilter, $sExtKeyAttCode, $aClassAliases, $aAliasTranslation);
		}
		foreach($oFilter->m_aReferencedBy as $sForeignClass => $aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode => $oForeignFilter)
			{
				$this->AddCondition_ReferencedBy_InNamespace($oForeignFilter, $sForeignExtKeyAttCode, $aClassAliases, $aAliasTranslation);
			}
		}
	}


	public function GetClassName($sAlias) {return $this->m_aClasses[$sAlias];}
	public function GetClasses() {return $this->m_aClasses;}

	public function GetClass() {return $this->m_sClass;}
	public function GetClassAlias() {return $this->m_sClassAlias;}
	public function GetCriteria() {return $this->m_oSearchCondition;}
	public function GetCriteria_FullText() {return $this->m_aFullText;}
	public function GetCriteria_PointingTo($sKeyAttCode = "")
	{
		if (empty($sKeyAttCode))
		{
			return $this->m_aPointingTo;
		}
		if (!array_key_exists($sKeyAttCode, $this->m_aPointingTo)) return null;
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

	public function RenderCondition()
	{
		return $this->m_oSearchCondition->Render();
	}

	public function serialize()
	{
		// Efficient but resulting in long strings:
		// -> return (base64_encode(serialize($this)));

		$sValue = $this->GetClass()."\n";
		$sValue .= $this->GetClassAlias()."\n";

		foreach($this->m_aClasses as $sClassAlias => $sClass)
		{
			// A stands for "Aliases"
			$sValue .= "A:$sClassAlias:$sClass\n";
		}
		foreach($this->m_aFullText as $sFullText)
		{
			// F stands for "Full text"
			$sValue .= "F:".$sFullText."\n";
		}
		$sValue .= "C:".$this->m_oSearchCondition->serialize()."\n";

		foreach($this->m_aPointingTo as $sExtKey=>$oFilter)
		{
			// P stands for "Pointing to"
			$sValue .= "P:".$sExtKey.":".$oFilter->serialize()."\n";
		}
		foreach($this->m_aReferencedBy as $sForeignClass=>$aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode=>$oForeignFilter)
			{
				// R stands for "Referenced by"
				$sValue .= "R:".$sForeignExtKeyAttCode.":".$oForeignFilter->serialize()."\n";
			}
		}
		foreach($this->m_aRelatedTo as $aRelatedTo)
		{
			$oFilter = $aRelatedTo['flt'];
			$sRelCode = $aRelatedTo['relcode'];
			$iMaxDepth = $aRelatedTo['maxdepth'];
			
			$sValue .= "T:".$oFilter->serialize().":$sRelCode:$iMaxDepth";
		}
		return base64_encode($sValue);
	}
	
	static public function unserialize($sValue)
	{
		// See comment above...
		// -> return (unserialize(base64_decode($sValue)));

		$sClearText = base64_decode($sValue);
		$aValues = split("\n", $sClearText);
		$i = 0;
		$sClass = $aValues[$i++];
		$sClassAlias = $aValues[$i++];
		$oFilter = new DBObjectSearch($sClass, $sClassAlias);
		while($i < count($aValues) && !empty($aValues[$i]))
		{
			$aCondition = split(":", $aValues[$i++]);
			switch ($aCondition[0])
			{
			case "A":
				$oFilter->m_aClasses[$aCondition[1]] = $aCondition[2];
				break;
			case "F":
				$oFilter->AddCondition_FullText($aCondition[1]);
				break;
			case "C":
				$oFilter->m_oSearchCondition = Expression::unserialize($aCondition[1]);
				break;
			case "P":
				//$oAtt = DBObject::GetAttributeDef($sClass, $aCondition[1]);
				//$sRemoteClass = $oAtt->GetTargetClass();
				$oSubFilter = self::unserialize($aCondition[2]);
				$sExtKeyAttCode = $aCondition[1];
				$oFilter->AddCondition_PointingTo($oSubFilter, $sExtKeyAttCode);
				break;
			case "R":
				$oRemoteFilter = self::unserialize($aCondition[2]);
				$sExtKeyAttCodeToMe = $aCondition[1];
				$oFilter->AddCondition_ReferencedBy($oRemoteFilter, $sExtKeyAttCodeToMe);
				break;
			case "T":
				$oSubFilter = self::unserialize($aCondition[1]);
				$sRelCode = $aCondition[2];
				$iMaxDepth = $aCondition[3];
				$oFilter->AddCondition_RelatedTo($oSubFilter, $sRelCode, $iMaxDepth);
			default:
				trigger_error("invalid filter definition (cannot unserialize the data, clear text = '$sClearText')", E_USER_ERROR);
			}
		}
		return $oFilter;
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

	public function ToOQL()
	{
		$sRes = "SELECT ".$this->GetClass().' AS '.$this->GetClassAlias();
		$sRes .= $this->ToOQL_Joins();
		$sRes .= " WHERE ".$this->m_oSearchCondition->Render();
		return $sRes;
	}

	protected function ToOQL_Joins()
	{
		$sRes = '';
		foreach($this->m_aPointingTo as $sExtKey=>$oFilter)
		{
			$sRes .= ' JOIN '.$oFilter->GetClass().' AS '.$oFilter->GetClassAlias().' ON '.$this->GetClassAlias().'.'.$sExtKey.' = '.$oFilter->GetClassAlias().'.id';
			$sRes .= $oFilter->ToOQL_Joins();
		}
		foreach($this->m_aReferencedBy as $sForeignClass=>$aReferences)
		{
			foreach($aReferences as $sForeignExtKeyAttCode=>$oForeignFilter)
			{
				$sRes .= ' JOIN '.$oForeignFilter->GetClass().' AS '.$oForeignFilter->GetClassAlias().' ON '.$oForeignFilter->GetClassAlias().'.'.$sForeignExtKeyAttCode.' = '.$this->GetClassAlias().'.id';
				$sRes .= $oForeignFilter->ToOQL_Joins();
			}
		}
		return $sRes;
	}

	public function ToSibusQL()
	{
		return "NONONO";
	}

	static private function privProcessParams($sQuery, array $aParams, $oDbObject)
	{
		$iPlaceHoldersCount = preg_match_all(SIBUSQLPARAMREGEXP, $sQuery, $aMatches, PREG_SET_ORDER);
		if ($iPlaceHoldersCount > 0)
		{
			foreach($aMatches as $aMatch)
			{
				$sStringToSearch = $aMatch[0];
				$sParameterName = $aMatch[1];
				$sDefaultValue = $aMatch[2];
				$sDescription = $aMatch[3];
				
				$sValue = $sDefaultValue;
				if (array_key_exists($sParameterName, $aParams))
				{
					$sValue = $aParams[$sParameterName];
					unset($aParams[$sParameterName]);
				}
				else if (is_object($oDbObject))
				{
					if (strpos($sParameterName, "this.") === 0)
					{
						$sAttCode = substr($sParameterName, strlen("this."));
						if ($sAttCode == 'id')
						{
							$sValue = $oDbObject->GetKey();
						}
						else if ($sAttCode == 'class')
						{
							$sValue = get_class($oDbObject);
						}
						else if (MetaModel::IsValidAttCode(get_class($oDbObject), $sAttCode))
						{
							$sValue = $oDbObject->Get($sAttCode);
						}
					}
				}
				$sQuery = str_replace($sStringToSearch, $sValue, $sQuery);
			}
		}
		if (count($aParams) > 0)
		{
			trigger_error("Unused parameter(s) for this SibusQL expression: (".implode(', ', array_keys($aParams)).")");
		}
		return $sQuery;
	}

	static public function ListSibusQLParams($sQuery)
	{
		$aRet = array();
		$iPlaceHoldersCount = preg_match_all(SIBUSQLPARAMREGEXP, $sQuery, $aMatches, PREG_SET_ORDER);
		if ($iPlaceHoldersCount > 0)
		{
			foreach($aMatches as $aMatch)
			{
				$sStringToSearch = $aMatch[0];
				$sParameterName = $aMatch[1];
				$sDefaultValue = $aMatch[2];
				$sDescription = $aMatch[3];
				$aRet[$sParameterName]["description"] = $sDescription;
				$aRet[$sParameterName]["default"] = $sDefaultValue;
			}
		}
		return $aRet;
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
				// Try to find an alias
				// Build an array of field => array of aliases
				$aFieldClasses = array();
				foreach($aClassAliases as $sAlias => $sReal)
				{
					foreach(MetaModel::GetFiltersList($sReal) as $sAnFltCode)
					{
						$aFieldClasses[$sAnFltCode][] = $sAlias;
					}
				}
				if (!array_key_exists($sFltCode, $aFieldClasses))
				{
					throw new OqlNormalizeException('Unknown filter code', $sQuery, $oExpression->GetNameDetails(), array_keys($aFieldClasses));
				}
				if (count($aFieldClasses[$sFltCode]) > 1)
				{
					throw new OqlNormalizeException('Ambiguous filter code', $sQuery, $oExpression->GetNameDetails());
				}
				$sClassAlias = $aFieldClasses[$sFltCode][0];
			}
			else
			{
				if (!array_key_exists($sClassAlias, $aClassAliases))
				{
					throw new OqlNormalizeException('Unknown class [alias]', $sQuery, $oExpression->GetParentDetails(), array_keys($aClassAliases));
				}
				$sClass = $aClassAliases[$sClassAlias];
				if (!MetaModel::IsValidFilterCode($sClass, $sFltCode))
				{
					throw new OqlNormalizeException('Unknown filter code', $sQuery, $oExpression->GetNameDetails(), MetaModel::GetFiltersList($sClass));
				}
			}

			return new FieldExpression($sFltCode, $sClassAlias);
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
			return new ListExpression($oExpression->GetItems());
		}
		elseif ($oExpression instanceof FunctionOqlExpression)
		{
			return new FunctionExpression($oExpression->GetVerb(), $oExpression->GetArgs());
		}
		else
		{
			throw new CoreException('Unknown expression type', array('class'=>get_class($oExpression), 'query'=>$sQuery));
		}
	}

	static public function FromOQL($sQuery, array $aParams = array(), $oObject = null)
	{
		if (empty($sQuery)) return null;

		$oOql = new OqlInterpreter($sQuery);
		$oOqlQuery = $oOql->ParseQuery();
		
		$sClass = $oOqlQuery->GetClass();
		$sClassAlias = $oOqlQuery->GetClassAlias();

		if (!MetaModel::IsValidClass($sClass))
		{
			throw new OqlNormalizeException('Unknown class', $sQuery, $oOqlQuery->GetClassDetails(), MetaModel::GetClasses());
		}

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
				if (!MetaModel::IsValidClass($sJoinClass))
				{
					throw new OqlNormalizeException('Unknown class', $sQuery, $oJoinSpec->GetClassDetails(), MetaModel::GetClasses());
				}
				if (array_key_exists($sJoinClassAlias, $aAliases))
				{
					if ($sJoinClassAlias != $sJoinClass)
					{
						throw new OqlNormalizeException('Duplicate class alias', $sQuery, $oJoinSpec->GetClassAliasDetails());
					}
					else
					{
						throw new OqlNormalizeException('Duplicate class name', $sQuery, $oJoinSpec->GetClassDetails());
					}
				} 

				// Assumption: ext key on the left only !!!
				// normalization should take care of this
				$oLeftField = $oJoinSpec->GetLeftField();
				$sFromClass = $oLeftField->GetParent();
				$sExtKeyAttCode = $oLeftField->GetName();

				$oRightField = $oJoinSpec->GetRightField();
				$sToClass = $oRightField->GetParent();
				$sPKeyDescriptor = $oRightField->GetName();
				if ($sPKeyDescriptor != 'id')
				{
					throw new OqlNormalizeException('Wrong format for Join clause (right hand), expecting an id', $sQuery, $oRightField->GetNameDetails(), array('id'));
				}

				$aAliases[$sJoinClassAlias] = $sJoinClass;
				$aJoinItems[$sJoinClassAlias] = new DBObjectSearch($sJoinClass, $sJoinClassAlias);

				if (!array_key_exists($sFromClass, $aJoinItems))
				{
					throw new OqlNormalizeException('Unknown class in join condition (left expression)', $sQuery, $oLeftField->GetParentDetails(), array_keys($aJoinItems));
				}
				if (!array_key_exists($sToClass, $aJoinItems))
				{
					throw new OqlNormalizeException('Unknown class in join condition (right expression)', $sQuery, $oRightField->GetParentDetails(), array_keys($aJoinItems));
				}
				$aExtKeys = array_keys(MetaModel::GetExternalKeys($aAliases[$sFromClass]));
				if (!in_array($sExtKeyAttCode, $aExtKeys))
				{
					throw new OqlNormalizeException('Unknown external key in join condition (left expression)', $sQuery, $oLeftField->GetNameDetails(), $aExtKeys);
				}

				if ($sFromClass == $sJoinClassAlias)
				{
					$aJoinItems[$sToClass]->AddCondition_ReferencedBy($aJoinItems[$sFromClass], $sExtKeyAttCode);
				}
				else
				{
					$aJoinItems[$sFromClass]->AddCondition_PointingTo($aJoinItems[$sToClass], $sExtKeyAttCode);
				}
			}
		}

		$oConditionTree = $oOqlQuery->GetCondition();
		if ($oConditionTree instanceof Expression)
		{
			$oResultFilter->m_oSearchCondition = $oResultFilter->OQLExpressionToCondition($sQuery, $oConditionTree, $aAliases);
		}

		return $oResultFilter;
	}

	static public function FromSibusQL($sQuery, array $aParams = array(), $oObject = null)
	{
		if (empty($sQuery)) return null;
		$sQuery = self::privProcessParams($sQuery, $aParams, $oObject);

		$iSepPos = strpos($sQuery, ":");
		if ($iSepPos === false)
		{
			if (preg_match('@^\\s*SELECT@', $sQuery))
			{
				return self::FromOQL($sQuery, $aParams, $oObject);
			}
			// Only the class was specified -> all rows are required
			$sClass = trim($sQuery);
			$oFilter = new DBObjectSearch($sClass);
		}
		else
		{
			$sClass = trim(substr($sQuery, 0, $iSepPos));
			$sConds = trim(substr($sQuery, $iSepPos + 1));
			$aValues = split(" AND ", $sConds);
	
			$oFilter = new DBObjectSearch($sClass);
	
			foreach ($aValues as $sCond)
			{
				$sCond = trim($sCond);
				
				if (strpos($sCond, "* HAS ") === 0)
				{
					$sValue = self::Expression2Value(substr($sCond, strlen("* HAS ")));
					$oFilter->AddCondition_FullText($sValue);
				}
				else if (preg_match("@^(\S+) IN \\((.+)\\)$@", $sCond, $aMatches))
				{
					$sExtKeyAttCode = $aMatches[1];
					$sFilterExp = $aMatches[2];

					$oSubFilter = self::FromSibuSQL($sFilterExp);
					$oFilter->AddCondition_PointingTo($oSubFilter, $sExtKeyAttCode);
				}
				else if (strpos($sCond, "PKEY IS ") === 0)
				{
					if (preg_match("@^PKEY IS (\S+) IN \\((.+)\\)$@", $sCond, $aMatches))
					{
						$sExtKeyAttCodeToMe = $aMatches[1];
						$sFilterExp = $aMatches[2];
						$oRemoteFilter = self::FromSibuSQL($sFilterExp);
						$oFilter->AddCondition_ReferencedBy($oRemoteFilter, $sExtKeyAttCodeToMe);
					}
				}
				else if (strpos($sCond, "RELATED") === 0)
				{
					if (preg_match("@^RELATED\s*\\((.+)\\)\s*TO\s*\\((.+)\\)@", trim($sCond), $aMatches))
					{
						$aRelation = explode(',', trim($aMatches[1]));
						$sRelCode = trim($aRelation[0]);
						$iMaxDepth = intval(trim($aRelation[1]));
						$sFilterExp = trim($aMatches[2]);

						$oSubFilter = self::FromSibuSQL($sFilterExp);
						$oFilter->AddCondition_RelatedTo($oSubFilter, $sRelCode, $iMaxDepth);
					}
				}
				else
				{
					$sOperandExpr = "'.*'|\d+|-\d+|".VS_START.".+".VS_END;
					if (preg_match("@^(\S+)\s+(.*)\s+($sOperandExpr)$@", $sCond, $aMatches))
					{
						$sFltCode = trim($aMatches[1]);
						$sOpCode = trim($aMatches[2]);
						$value = self::Expression2Value($aMatches[3]);
						$oFilter->AddCondition($sFltCode, $value, $sOpCode);
					}
					else
					{
						trigger_error("Wrong format for filter definition: '$sQuery'");
					}
				}
			}
		}

// #@# todo - obsolete smoothly, first give the OQL version !
//		throw new CoreException('SibusQL has been obsoleted, please update your queries', array('sibusql'=>$sQuery, 'oql'=>$oFilter->ToOQL()));

		return $oFilter;
	}

	// Sexy display of a SibuSQL expression
	static public function SibuSQLAsHtml($sQuery)
	{
		$sQuery = htmlentities($sQuery);
		$aParams = self::ListSibusQLParams($sQuery);
		$aParamValues = array();
		foreach ($aParams as $sParamName => $aParamInfo)
		{
			$sDescription = $aParamInfo["description"];
			$sDefaultValue = $aParamInfo["default"];
			$aParamValues[$sParamName] = "<span style=\"background-color:#aaa;\" title\"$sDescription (default to '$sDefaultValue')\">$sParamName</span>";
		}
		$sQuery = self::privProcessParams($sQuery, $aParamValues, null);
		return $sQuery;
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
