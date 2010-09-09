<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Class dbObject: the root of persistent classes
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once('metamodel.class.php');

/**
 * A persistent object, as defined by the metamodel 
 *
 * @package     iTopORM
 */
abstract class DBObject
{
	private static $m_aMemoryObjectsByClass = array();

	private $m_bIsInDB = false; // true IIF the object is mapped to a DB record
	private $m_iKey = null;
	private $m_aCurrValues = array();
	protected $m_aOrigValues = array();

	private $m_bDirty = false; // Means: "a modification is ongoing"
										// The object may have incorrect external keys, then any attempt of reload must be avoided
	private $m_bCheckStatus = null; // Means: the object has been verified and is consistent with integrity rules
													//        if null, then the check has to be performed again to know the status
	protected $m_aCheckIssues = null;
	protected $m_aAsArgs = null; // The current object as a standard argument (cache)

	private $m_bFullyLoaded = false; // Compound objects can be partially loaded
	private $m_aLoadedAtt = array(); // Compound objects can be partially loaded, array of sAttCode

	// Use the MetaModel::NewObject to build an object (do we have to force it?)
	public function __construct($aRow = null, $sClassAlias = '')
	{
		if (!empty($aRow))
		{
			$this->FromRow($aRow, $sClassAlias);
			$this->m_bFullyLoaded = $this->IsFullyLoaded();
			return;
		}
		// Creation of brand new object
		//

		$this->m_iKey = self::GetNextTempId(get_class($this));

		// set default values
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode=>$oAttDef)
		{
			$this->m_aCurrValues[$sAttCode] = $oAttDef->GetDefaultValue();
			$this->m_aOrigValues[$sAttCode] = null;
			if ($oAttDef->IsExternalField())
			{
				// This field has to be read from the DB 
				$this->m_aLoadedAtt[$sAttCode] = false;
			}
			else
			{
				// No need to trigger a reload for that attribute
				// Let's consider it as being already fully loaded
				$this->m_aLoadedAtt[$sAttCode] = true;
			}
		}
	}

	// Read-only <=> Written once (archive)
	static public function IsReadOnly()
	{
		return false;
	}

	public function RegisterAsDirty()
	{
		// While the object may be written to the DB, it is NOT possible to reload it
		// or at least not possible to reload it the same way
		$this->m_bDirty = true;	
	}

	public function IsNew()
	{
		return (!$this->m_bIsInDB);
	}

	// Returns an Id for memory objects
	static protected function GetNextTempId($sClass)
	{
		if (!array_key_exists($sClass, self::$m_aMemoryObjectsByClass))
		{
			self::$m_aMemoryObjectsByClass[$sClass] = 0;
		}
		self::$m_aMemoryObjectsByClass[$sClass]++;
		return (- self::$m_aMemoryObjectsByClass[$sClass]);
	}

	public function __toString()
	{
		$sRet = '';
		$sClass = get_class($this);
		$sRootClass = MetaModel::GetRootClass($sClass);
		$iPKey = $this->GetKey();
		$sRet .= "<b title=\"$sRootClass\">$sClass</b>::$iPKey<br/>\n";
		$sRet .= "<ul class=\"treeview\">\n";
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode=>$oAttDef)
		{
			$sRet .= "<li>".$oAttDef->GetLabel()." = ".$this->GetAsHtml($sAttCode)."</li>\n";
		}
		$sRet .= "</ul>";
		return $sRet;
	}
	
	// Restore initial values... mmmm, to be discussed
	public function DBRevert()
	{
		$this->Reload();
	}

	protected function IsFullyLoaded()
	{
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode=>$oAttDef)
		{
			@$bIsLoaded = $this->m_aLoadedAtt[$sAttCode];
			if ($bIsLoaded !== true)
			{
				return false;
			}
		}
		return true;
	}

	protected function Reload()
	{
		assert($this->m_bIsInDB);
		$aRow = MetaModel::MakeSingleRow(get_class($this), $this->m_iKey);
		if (empty($aRow))
		{
			throw new CoreException("Failed to reload object of class '".get_class($this)."', id = ".$this->m_iKey);
		}
		$this->FromRow($aRow);

		// Process linked set attributes
		//
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode=>$oAttDef)
		{
			if (!$oAttDef->IsLinkSet()) continue;

			// Load the link information
			$sLinkClass = $oAttDef->GetLinkedClass();
			$sExtKeyToMe = $oAttDef->GetExtKeyToMe();

			// The class to target is not the current class, because if this is a derived class,
			// it may differ from the target class, then things start to become confusing
			$oRemoteExtKeyAtt = MetaModel::GetAttributeDef($sLinkClass, $sExtKeyToMe);
			$sMyClass = $oRemoteExtKeyAtt->GetTargetClass();

			$oMyselfSearch = new DBObjectSearch($sMyClass);
			$oMyselfSearch->AddCondition('id', $this->m_iKey, '=');

			$oLinkSearch = new DBObjectSearch($sLinkClass);
			$oLinkSearch->AddCondition_PointingTo($oMyselfSearch, $sExtKeyToMe);
			$oLinks = new DBObjectSet($oLinkSearch);

			$this->m_aCurrValues[$sAttCode] = $oLinks;
			$this->m_aOrigValues[$sAttCode] = clone $this->m_aCurrValues[$sAttCode];
			$this->m_aLoadedAtt[$sAttCode] = true;
		}

		$this->m_bFullyLoaded = true;
	}

	protected function FromRow($aRow, $sClassAlias = '')
	{
		if (strlen($sClassAlias) == 0)
		{
			// Default to the current class
			$sClassAlias = get_class($this);
		}

		$this->m_iKey = null;
		$this->m_bIsInDB = true;
		$this->m_aCurrValues = array();
		$this->m_aOrigValues = array();
		$this->m_aLoadedAtt = array();
		$this->m_bCheckStatus = true;

		// Get the key
		//
		$sKeyField = $sClassAlias."id";
		if (!array_key_exists($sKeyField, $aRow))
		{
			// #@# Bug ?
			throw new CoreException("Missing key for class '".get_class($this)."'");
		}
		else
		{
			$iPKey = $aRow[$sKeyField];
			if (!self::IsValidPKey($iPKey))
			{
				throw new CoreWarning("An object id must be an integer value ($iPKey)");
			}
			$this->m_iKey = $iPKey;
		}

		// Build the object from an array of "attCode"=>"value")
		//
		$bFullyLoaded = true; // ... set to false if any attribute is not found
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode=>$oAttDef)
		{
			// Say something, whatever the type of attribute
			$this->m_aLoadedAtt[$sAttCode] = false;

			// Skip links (could not be loaded by the mean of this query)
			if ($oAttDef->IsLinkSet()) continue;

			// Note: we assume that, for a given attribute, if it can be loaded,
			// then one column will be found with an empty suffix, the others have a suffix
			// Take care: the function isset will return false in case the value is null,
			// which is something that could happen on open joins
			$sAttRef = $sClassAlias.$sAttCode;
			if (array_key_exists($sAttRef, $aRow))
			{
				$value = $oAttDef->FromSQLToValue($aRow, $sAttRef);

				$this->m_aCurrValues[$sAttCode] = $value;
				$this->m_aOrigValues[$sAttCode] = $value;
				$this->m_aLoadedAtt[$sAttCode] = true;
			}
			else
			{
				// This attribute was expected and not found in the query columns
				$bFullyLoaded = false;
			}
		}
		return $bFullyLoaded;
	}
	
	public function Set($sAttCode, $value)
	{
		if ($sAttCode == 'finalclass')
		{
			// Ignore it - this attribute is set upon object creation and that's it
			return;
		}
		$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		if ($this->m_bIsInDB && !$this->m_bFullyLoaded && !$this->m_bDirty)
		{
			// First time Set is called... ensure that the object gets fully loaded
			// Otherwise we would lose the values on a further Reload
			//           + consistency does not make sense !
			$this->Reload();
		}

		if ($oAttDef->IsExternalKey() && is_object($value))
		{
			// Setting an external key with a whole object (instead of just an ID)
			// let's initialize also the external fields that depend on it
			// (useful when building objects in memory and not from a query)
			if ( (get_class($value) != $oAttDef->GetTargetClass()) && (!is_subclass_of($value, $oAttDef->GetTargetClass())))
			{
				throw new CoreUnexpectedValue("Trying to set the value of '$sAttCode', to an object of class '".get_class($value)."', whereas it's an ExtKey to '".$oAttDef->GetTargetClass()."'. Ignored");
			}
			else
			{
				// The object has changed, reset caches
				$this->m_bCheckStatus = null;
				$this->m_aAsArgs = null;

				$this->m_aCurrValues[$sAttCode] = $value->GetKey();
				foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sCode => $oDef)
				{
					if ($oDef->IsExternalField() && ($oDef->GetKeyAttCode() == $sAttCode))
					{
						$this->m_aCurrValues[$sCode] = $value->Get($oDef->GetExtAttCode());
					}
				}
			}
			return;
		}
		if(!$oAttDef->IsScalar() && !is_object($value))
		{
			throw new CoreUnexpectedValue("scalar not allowed for attribute '$sAttCode', setting default value (empty list)");
		}
		if($oAttDef->IsLinkSet())
		{
			if((get_class($value) != 'DBObjectSet') && !is_subclass_of($value, 'DBObjectSet'))
			{
				throw new CoreUnexpectedValue("expecting a set of persistent objects (found a '".get_class($value)."'), setting default value (empty list)");
			}

			$oObjectSet = $value;
			$sSetClass = $oObjectSet->GetClass();
			$sLinkClass = $oAttDef->GetLinkedClass();
			// not working fine :-(   if (!is_subclass_of($sSetClass, $sLinkClass))
			if ($sSetClass != $sLinkClass)
			{
				throw new CoreUnexpectedValue("expecting a set of '$sLinkClass' objects (found a set of '$sSetClass'), setting default value (empty list)");
			}
		}

		$realvalue = $oAttDef->MakeRealValue($value);
		$this->m_aCurrValues[$sAttCode] = $realvalue;

		// The object has changed, reset caches
		$this->m_bCheckStatus = null;
		$this->m_aAsArgs = null;

		// Make sure we do not reload it anymore... before saving it
		$this->RegisterAsDirty();
	}

	public function Get($sAttCode)
	{
		if (!array_key_exists($sAttCode, MetaModel::ListAttributeDefs(get_class($this))))
		{
			throw new CoreException("Unknown attribute code '$sAttCode' for the class ".get_class($this));
		}
		if ($this->m_bIsInDB && !$this->m_aLoadedAtt[$sAttCode] && !$this->m_bDirty)
		{
			// #@# non-scalar attributes.... handle that differently
			$this->Reload();
		}
		return $this->m_aCurrValues[$sAttCode];
	}

	public function GetOriginal($sAttCode)
	{
		if (!array_key_exists($sAttCode, MetaModel::ListAttributeDefs(get_class($this))))
		{
			throw new CoreException("Unknown attribute code '$sAttCode' for the class ".get_class($this));
		}
		return $this->m_aOrigValues[$sAttCode];
	}

	/**
	 * Updates the value of an external field by (re)loading the object
	 * corresponding to the external key and getting the value from it
	 * @param string $sAttCode Attribute code of the external field to update
	 * @return void
	 */
	protected function UpdateExternalField($sAttCode)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		if ($oAttDef->IsExternalField())
		{
			$sTargetClass = $oAttDef->GetTargetClass();
			$objkey = $this->Get($oAttDef->GetKeyAttCode());
			$oObj = MetaModel::GetObject($sTargetClass, $objkey);
			if (is_object($oObj))
			{
				$value = $oObj->Get($oAttDef->GetExtAttCode());
				$this->Set($sAttCode, $value);
			}
		}
	}
	
	// Compute scalar attributes that depend on any other type of attribute
	public function DoComputeValues()
	{
		if (is_callable(array($this, 'ComputeValues')))
		{
			// First check that we are not currently computing the fields
			// (yes, we need to do some things like Set/Get to compute the fields which will in turn trigger the update...)
			foreach (debug_backtrace() as $aCallInfo)
			{
				if (!array_key_exists("class", $aCallInfo)) continue;
				if ($aCallInfo["class"] != get_class($this)) continue;
				if ($aCallInfo["function"] != "ComputeValues") continue;
				return; //skip!
			}
			
			$this->ComputeValues();
		}
	}

	public function GetAsHTML($sAttCode)
	{
		$sClass = get_class($this);
		$oAtt = MetaModel::GetAttributeDef($sClass, $sAttCode);

		$aExtKeyFriends = MetaModel::GetExtKeyFriends($sClass, $sAttCode);
		if (count($aExtKeyFriends) > 0)
		{
			// This attribute is an ext key (in this class or in another class)
			// The corresponding value is an id of the remote object
			// Let's try to use the corresponding external fields for a sexy display

			$aAvailableFields = array();
			foreach ($aExtKeyFriends as $sDispAttCode => $oExtField)
			{
				$aAvailableFields[$oExtField->GetExtAttCode()] = $oExtField->GetAsHTML($this->Get($oExtField->GetCode()));
			}

			$sTargetClass = $oAtt->GetTargetClass(EXTKEY_ABSOLUTE);
			return $this->MakeHyperLink($sTargetClass, $this->Get($sAttCode), $aAvailableFields);
		}

		// That's a standard attribute (might be an ext field or a direct field, etc.)
		return $oAtt->GetAsHTML($this->Get($sAttCode));
	}

	public function GetEditValue($sAttCode)
	{
		$sClass = get_class($this);
		$oAtt = MetaModel::GetAttributeDef($sClass, $sAttCode);

		if ($oAtt->IsExternalKey())
		{
			$sTargetClass = $oAtt->GetTargetClass();
			if ($this->IsNew())
			{
				// The current object exists only in memory, don't try to query it in the DB !
				// instead let's query for the object pointed by the external key, and get its name
				$targetObjId = $this->Get($sAttCode);
				$oTargetObj = MetaModel::GetObject($sTargetClass, $targetObjId, false); // false => not sure it exists
				if (is_object($oTargetObj))
				{
					$sEditValue = $oTargetObj->GetName();
				}
				else
				{
					$sEditValue = 0;
				}					
			}
			else
			{
				$aAvailableFields = array();
				// retrieve the "external fields" linked to this external key
				foreach (MetaModel::GetExternalFields(get_class($this), $sAttCode) as $oExtField)
				{
					$aAvailableFields[$oExtField->GetExtAttCode()] = $oExtField->GetAsHTML($this->Get($oExtField->GetCode()));
				}
				// Use the "name" of the target class as the label of the hyperlink
				// unless it's not available in the external fields...
				$sExtClassNameAtt = MetaModel::GetNameAttributeCode($sTargetClass);
				if (isset($aAvailableFields[$sExtClassNameAtt]))
				{
					$sEditValue = $aAvailableFields[$sExtClassNameAtt];
				}
				else
				{
					$sEditValue = implode(' / ', $aAvailableFields);
				}
			}
		}
		else
		{
			$sEditValue = $oAtt->GetEditValue($this->Get($sAttCode));
		}
		return $sEditValue;
	}

	public function GetAsXML($sAttCode)
	{
		$oAtt = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		return $oAtt->GetAsXML($this->Get($sAttCode));
	}

	public function GetAsCSV($sAttCode, $sSeparator = ',', $sTextQualifier = '"')
	{
		$oAtt = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		return $oAtt->GetAsCSV($this->Get($sAttCode), $sSeparator, $sTextQualifier);
	}

	protected static function MakeHyperLink($sObjClass, $sObjKey, $aAvailableFields)
	{
		if ($sObjKey == 0) return '<em>undefined</em>';

		return MetaModel::GetName($sObjClass)."::$sObjKey";
	}

	public function GetHyperlink()
	{
		$aAvailableFields[MetaModel::GetNameAttributeCode(get_class($this))] = $this->GetName();
		return $this->MakeHyperLink(get_class($this), $this->GetKey(), $aAvailableFields);
	}


	// could be in the metamodel ?
	public static function IsValidPKey($value)
	{
		return ((string)$value === (string)(int)$value);
	}

	public function GetKey()
	{
		return $this->m_iKey;
	}
	public function SetKey($iNewKey)
	{
		if (!self::IsValidPKey($iNewKey))
		{
			throw new CoreException("An object id must be an integer value ($iNewKey)");
		}
		
		if ($this->m_bIsInDB && !empty($this->m_iKey) && ($this->m_iKey != $iNewKey))
		{
			throw new CoreException("Changing the key ({$this->m_iKey} to $iNewKey) on an object (class {".get_class($this).") wich already exists in the Database");
		}
		$this->m_iKey = $iNewKey;
	}
	/**
	 * Get the icon representing this object
	 * @param boolean $bImgTag If true the result is a full IMG tag (or an emtpy string if no icon is defined)
	 * @return string Either the full IMG tag ($bImgTag == true) or just the path to the icon file
	 */
	public function GetIcon($bImgTag = true)
	{
		return MetaModel::GetClassIcon(get_class($this), $bImgTag);
	}

	public function GetName()
	{
		$sNameAttCode = MetaModel::GetNameAttributeCode(get_class($this));
		if (empty($sNameAttCode))
		{
			return $this->m_iKey;
		}
		else
		{
			return $this->Get($sNameAttCode);
		}
	}

	public function GetState()
	{
		$sStateAttCode = MetaModel::GetStateAttributeCode(get_class($this));
		if (empty($sStateAttCode))
		{
			return '';
		}
		else
		{
			return $this->Get($sStateAttCode);
		}
	}

	public function GetStateLabel()
	{
		$sStateAttCode = MetaModel::GetStateAttributeCode(get_class($this));
		if (empty($sStateAttCode))
		{
			return '';
		}
		else
		{
			$sStateValue = $this->Get($sStateAttCode);
			return MetaModel::GetStateLabel(get_class($this), $sStateValue);
		}
	}

	public function GetStateDescription()
	{
		$sStateAttCode = MetaModel::GetStateAttributeCode(get_class($this));
		if (empty($sStateAttCode))
		{
			return '';
		}
		else
		{
			$sStateValue = $this->Get($sStateAttCode);
			return MetaModel::GetStateDescription(get_class($this), $sStateValue);
		}
	}
	/**
	 * Returns the set of flags (OPT_ATT_HIDDEN, OPT_ATT_READONLY, OPT_ATT_MANDATORY...)
	 * for the given attribute in the current state of the object
	 * @param string $sAttCode The code of the attribute
	 * @return integer Flags: the binary combination of the flags applicable to this attribute
	 */	 	  	 	
	public function GetAttributeFlags($sAttCode)
	{
		$iFlags = 0; // By default (if no life cycle) no flag at all
		$sStateAttCode = MetaModel::GetStateAttributeCode(get_class($this));
		if (!empty($sStateAttCode))
		{
			$iFlags = MetaModel::GetAttributeFlags(get_class($this), $this->Get($sStateAttCode), $sAttCode);
		}
		return $iFlags;
	}

	// check if the given (or current) value is suitable for the attribute
	// return true if successfull
	// return the error desciption otherwise
	public function CheckValue($sAttCode, $value = null)
	{
		if (!is_null($value))
		{
			$toCheck = $value;
		}
		else
		{
			$toCheck = $this->Get($sAttCode);
		}

		$oAtt = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		if (!$oAtt->IsWritable())
		{
			return true;
		}
		elseif ($oAtt->IsNull($toCheck))
		{
			if ($oAtt->IsNullAllowed())
			{
				return true;
			}
			else
			{
				return "Null not allowed";
			}
		}
		elseif ($oAtt->IsExternalKey())
		{
			if (!MetaModel::SkipCheckExtKeys())
			{
				$sTargetClass = $oAtt->GetTargetClass();
				$oTargetObj = MetaModel::GetObject($sTargetClass, $toCheck, false /*must be found*/, true /*allow all data*/);
				if (is_null($oTargetObj))
				{
					return "Target object not found ($sTargetClass::$toCheck)";
				}
			}
		}
		elseif ($oAtt->IsScalar())
		{
			$aValues = $oAtt->GetAllowedValues($this->ToArgs());
			if (count($aValues) > 0)
			{
				if (!array_key_exists($toCheck, $aValues))
				{
					return "Value not allowed [$toCheck]";
				}
			}
			if (!is_null($iMaxSize = $oAtt->GetMaxSize()))
			{
				$iLen = strlen($toCheck);
				if ($iLen > $iMaxSize)
				{
					return "String too long (found $iLen, limited to $iMaxSize)";
				}
			}
			if (!$oAtt->CheckFormat($toCheck))
			{
				return "Wrong format [$toCheck]";
			}
		}
		return true;
	}
	
	// check attributes together
	public function CheckConsistency()
	{
		return true;
	}
	
	// check integrity rules (before inserting or updating the object)
	// a displayable error is returned
	public function DoCheckToWrite()
	{
		$this->m_aCheckIssues = array();

		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode=>$oAttDef)
		{
			$res = $this->CheckValue($sAttCode);
			if ($res !== true)
			{
				// $res contains the error description
				$this->m_aCheckIssues[] = "Unexpected value for attribute '$sAttCode': $res";
			}
		}
		if (count($this->m_aCheckIssues) > 0)
		{
			// No need to check consistency between attributes if any of them has
			// an unexpected value
			return;
		}
		$res = $this->CheckConsistency();
		if ($res !== true)
		{
			// $res contains the error description
			$this->m_aCheckIssues[] = "Consistency rules not followed: $res";
		}
	}

	final public function CheckToWrite()
	{
		if (MetaModel::SkipCheckToWrite())
		{
			return array(true, array());
		}
		if (is_null($this->m_bCheckStatus))
		{
			$oKPI = new ExecutionKPI();
			$this->DoCheckToWrite();
			$oKPI->ComputeStats('CheckToWrite', get_class($this));
			if (count($this->m_aCheckIssues) == 0)
			{
				$this->m_bCheckStatus = true;
			}
			else
			{
				$this->m_bCheckStatus = false;
			}
		}
		return array($this->m_bCheckStatus, $this->m_aCheckIssues);
	}

	// check if it is allowed to delete the existing object from the database
	// a displayable error is returned
	public function CheckToDelete()
	{
		return true;
	}

	protected function ListChangedValues(array $aProposal)
	{
		$aDelta = array();
		foreach ($aProposal as $sAtt => $proposedValue)
		{
			if (!array_key_exists($sAtt, $this->m_aOrigValues))
			{
				// The value was not set
				$aDelta[$sAtt] = $proposedValue;
			}
			elseif(is_object($proposedValue))
			{
				// The value is an object, the comparison is not strict
				// #@# todo - should be even less strict => add verb on AttributeDefinition: Compare($a, $b)
				if ($this->m_aOrigValues[$sAtt] != $proposedValue)
				{
					$aDelta[$sAtt] = $proposedValue;
				}
			}
			else
			{
				// The value is a scalar, the comparison must be 100% strict
				if($this->m_aOrigValues[$sAtt] !== $proposedValue)
				{	
					//echo "$sAtt:<pre>\n";
					//var_dump($this->m_aOrigValues[$sAtt]);
					//var_dump($proposedValue);
					//echo "</pre>\n";
					$aDelta[$sAtt] = $proposedValue;
				}
			}
		}
		return $aDelta;
	} 

	// List the attributes that have been changed
	// Returns an array of attname => currentvalue
	public function ListChanges()
	{
		return $this->ListChangedValues($this->m_aCurrValues);
	}

	// Tells whether or not an object was modified
	public function IsModified()
	{
		$aChanges = $this->ListChanges();
		return (count($aChanges) != 0);
	}

	// used both by insert/update
	private function DBWriteLinks()
	{
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode=>$oAttDef)
		{
			if (!$oAttDef->IsLinkSet()) continue;

			$oLinks = $this->Get($sAttCode);
			$oLinks->Rewind();
			while ($oLinkedObject = $oLinks->Fetch())
			{
				$oLinkedObject->Set($oAttDef->GetExtKeyToMe(), $this->m_iKey);
				if ($oLinkedObject->IsModified())
				{
					$oLinkedObject->DBWrite();
				}
			}

			// Delete the objects that were initialy present and disappeared from the list
			// (if any)
			$oOriginalSet = $this->m_aOrigValues[$sAttCode];
			if ($oOriginalSet != null)
			{
				$aOriginalList = $oOriginalSet->ToArray();
				$aNewSet = $oLinks->ToArray();
				
				foreach($aOriginalList as $iId => $oObject)
				{
					if (!array_key_exists($iId, $aNewSet))
					{
						// It disappeared from the list
						$oObject->DBDelete();
					}
				}
			}
		}
	}

	private function DBInsertSingleTable($sTableClass)
	{
		$sTable = MetaModel::DBGetTable($sTableClass);
		// Abstract classes or classes having no specific attribute do not have an associated table
		if ($sTable == '') return;

		$sClass = get_class($this);

		// fields in first array, values in the second
		$aFieldsToWrite = array();
		$aValuesToWrite = array();
		
		if (!empty($this->m_iKey) && ($this->m_iKey >= 0))
		{
			// Add it to the list of fields to write
			$aFieldsToWrite[] = '`'.MetaModel::DBGetKey($sTableClass).'`';
			$aValuesToWrite[] = CMDBSource::Quote($this->m_iKey);
		}

		foreach(MetaModel::ListAttributeDefs($sTableClass) as $sAttCode=>$oAttDef)
		{
			// Skip this attribute if not defined in this table
			if (!MetaModel::IsAttributeOrigin($sTableClass, $sAttCode)) continue;
			$aAttColumns = $oAttDef->GetSQLValues($this->m_aCurrValues[$sAttCode]);
			foreach($aAttColumns as $sColumn => $sValue)
			{
				$aFieldsToWrite[] = "`$sColumn`"; 
				$aValuesToWrite[] = CMDBSource::Quote($sValue);
			}
		}

		if (count($aValuesToWrite) == 0) return false;

		$sInsertSQL = "INSERT INTO `$sTable` (".join(",", $aFieldsToWrite).") VALUES (".join(", ", $aValuesToWrite).")";

		$iNewKey = CMDBSource::InsertInto($sInsertSQL);
		// Note that it is possible to have a key defined here, and the autoincrement expected, this is acceptable in a non root class
		if (empty($this->m_iKey))
		{
			// Take the autonumber
			$this->m_iKey = $iNewKey;
		}
		return $this->m_iKey;
	}

	// Insert of record for the new object into the database
	// Returns the key of the newly created object
	public function DBInsertNoReload()
	{
		if ($this->m_bIsInDB)
		{
			throw new CoreException("The object already exists into the Database, you may want to use the clone function");
		}

		$sClass = get_class($this);
		$sRootClass = MetaModel::GetRootClass($sClass);

		// Ensure the update of the values (we are accessing the data directly)
		$this->DoComputeValues();
		$this->OnInsert();

		if ($this->m_iKey < 0)
		{
			// This was a temporary "memory" key: discard it so that DBInsertSingleTable will not try to use it!
			$this->m_iKey = null; 
		}

		// If not automatically computed, then check that the key is given by the caller
		if (!MetaModel::IsAutoIncrementKey($sRootClass))
		{
			if (empty($this->m_iKey))
			{
				throw new CoreWarning("Missing key for the object to write - This class is supposed to have a user defined key, not an autonumber", array('class' => $sRootClass));
			}
		}

		// Ultimate check - ensure DB integrity
		list($bRes, $aIssues) = $this->CheckToWrite();
		if (!$bRes)
		{
			throw new CoreException("Object not following integrity rules - it will not be written into the DB", array('class' => $sClass, 'id' => $this->GetKey(), 'issues' => $aIssues));
		}

		// First query built upon on the root class, because the ID must be created first
		$this->m_iKey = $this->DBInsertSingleTable($sRootClass);

		// Then do the leaf class, if different from the root class
		if ($sClass != $sRootClass)
		{
			$this->DBInsertSingleTable($sClass);
		}

		// Then do the other classes
		foreach(MetaModel::EnumParentClasses($sClass) as $sParentClass)
		{
			if ($sParentClass == $sRootClass) continue;
			$this->DBInsertSingleTable($sParentClass);
		}

		$this->DBWriteLinks();
		$this->m_bIsInDB = true;
		$this->m_bDirty = false;
		
		// Arg cache invalidated (in particular, it needs the object key -could be improved later)
		$this->m_aAsArgs = null;

		$this->AfterInsert();

		// Activate any existing trigger 
		$sClass = get_class($this);
		$oSet = new DBObjectSet(new DBObjectSearch('TriggerOnObjectCreate'));
		while ($oTrigger = $oSet->Fetch())
		{
			if (MetaModel::IsParentClass($oTrigger->Get('target_class'), $sClass))
			{
				$oTrigger->DoActivate($this->ToArgs('this'));
			}
		}

		return $this->m_iKey;
	}

	public function DBInsert()
	{
		$this->DBInsertNoReload();
		$this->Reload();
		return $this->m_iKey;
	}

	// Creates a copy of the current object into the database
	// Returns the id of the newly created object
	public function DBClone($iNewKey = null)
	{
		$this->m_bIsInDB = false;
		$this->m_iKey = $iNewKey;
		return $this->DBInsert();
	}
	
	/**
	 * This function is automatically called after cloning an object with the "clone" PHP language construct
	 * The purpose of this method is to reset the appropriate attributes of the object in
	 * order to make sure that the newly cloned object is really distinct from its clone
	 */
	public function __clone()
	{
		$this->m_bIsInDB = false;
		$this->m_bDirty = true;
		$this->m_iKey = self::GetNextTempId(get_class($this));
	}

	// Update a record
	public function DBUpdate()
	{
		if (!$this->m_bIsInDB)
		{
			throw new CoreException("DBUpdate: could not update a newly created object, please call DBInsert instead");
		}

		$this->DoComputeValues();
		$this->OnUpdate();

		$aChanges = $this->ListChanges();
		if (count($aChanges) == 0)
		{
			//throw new CoreWarning("Attempting to update an unchanged object");
			return;
		}

		// Ultimate check - ensure DB integrity
		list($bRes, $aIssues) = $this->CheckToWrite();
		if (!$bRes)
		{
			throw new CoreException("Object not following integrity rules - it will not be written into the DB", array('class' => get_class($this), 'id' => $this->GetKey(), 'issues' => $aIssues));
		}

		$bHasANewExternalKeyValue = false;
		foreach($aChanges as $sAttCode => $valuecurr)
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
			if ($oAttDef->IsExternalKey()) $bHasANewExternalKeyValue = true;
			if (!$oAttDef->IsDirectField()) unset($aChanges[$sAttCode]);
		}

		// Update scalar attributes
		if (count($aChanges) != 0)
		{
			$oFilter = new DBObjectSearch(get_class($this));
			$oFilter->AddCondition('id', $this->m_iKey, '=');
	
			$sSQL = MetaModel::MakeUpdateQuery($oFilter, $aChanges);
			CMDBSource::Query($sSQL);
		}

		$this->DBWriteLinks();
		$this->m_bDirty = false;

		$this->AfterUpdate();

		// Reload to get the external attributes
		if ($bHasANewExternalKeyValue)
		{
			$this->Reload();
		}

		return $this->m_iKey;
	}

	// Make the current changes persistent - clever wrapper for Insert or Update
	public function DBWrite()
	{
		if ($this->m_bIsInDB)
		{
			return $this->DBUpdate();
		}
		else
		{
			return $this->DBInsert();
		}
	}

	// Delete a record
	public function DBDelete()
	{
		$oFilter = new DBObjectSearch(get_class($this));
		$oFilter->AddCondition('id', $this->m_iKey, '=');

		$this->OnDelete();

		$sSQL = MetaModel::MakeDeleteQuery($oFilter);
		CMDBSource::Query($sSQL);

		$this->AfterDelete();

		$this->m_bIsInDB = false;
		$this->m_iKey = null;
	}

	public function EnumTransitions()
	{
		$sStateAttCode = MetaModel::GetStateAttributeCode(get_class($this));
		if (empty($sStateAttCode)) return array();

		$sState = $this->Get(MetaModel::GetStateAttributeCode(get_class($this)));
		return MetaModel::EnumTransitions(get_class($this), $sState);
	}

	public function ApplyStimulus($sStimulusCode)
	{
		$sStateAttCode = MetaModel::GetStateAttributeCode(get_class($this));
		if (empty($sStateAttCode)) return false;

		MyHelpers::CheckKeyInArray('object lifecycle stimulus', $sStimulusCode, MetaModel::EnumStimuli(get_class($this)));

		$aStateTransitions = $this->EnumTransitions();
		$aTransitionDef = $aStateTransitions[$sStimulusCode];

		// Change the state before proceeding to the actions, this is necessary because an action might
		// trigger another stimuli (alternative: push the stimuli into a queue)
		$sPreviousState = $this->Get($sStateAttCode);
		$sNewState = $aTransitionDef['target_state'];
		$this->Set($sStateAttCode, $sNewState);

		// $aTransitionDef is an
		//    array('target_state'=>..., 'actions'=>array of handlers procs, 'user_restriction'=>TBD

		$bSuccess = true;
		foreach ($aTransitionDef['actions'] as $sActionHandler)
		{
			// std PHP spec
			$aActionCallSpec = array($this, $sActionHandler);

			if (!is_callable($aActionCallSpec))
			{
				throw new CoreException("Unable to call action: ".get_class($this)."::$sActionHandler");
				return;
			}
			$bRet = call_user_func($aActionCallSpec, $sStimulusCode);
			// if one call fails, the whole is considered as failed
			if (!$bRet) $bSuccess = false;
		}

		// Change state triggers...
		$sClass = get_class($this);
		$sClassList = implode("', '", MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL));
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnStateLeave AS t WHERE t.target_class IN ('$sClassList') AND t.state='$sPreviousState'"));
		while ($oTrigger = $oSet->Fetch())
		{
			$oTrigger->DoActivate($this->ToArgs('this'));
		}

		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnStateEnter AS t WHERE t.target_class IN ('$sClassList') AND t.state='$sNewState'"));
		while ($oTrigger = $oSet->Fetch())
		{
			$oTrigger->DoActivate($this->ToArgs('this'));
		}

		return $bSuccess;
	}

	// Make standard context arguments
	// Note: Needs to be reviewed because it is currently called once per attribute when an object is written (CheckToWrite / CheckValue)
	//       Several options here:
	//       1) cache the result
	//       2) set only the object ref and resolve the values iif needed from contextual templates and queries (easy for the queries, not for the templates)
	public function ToArgs($sArgName = 'this')
	{
		if (is_null($this->m_aAsArgs))
		{
			$oKPI = new ExecutionKPI();
			$aScalarArgs = array();
			$aScalarArgs[$sArgName] = $this->GetKey();
			$aScalarArgs[$sArgName.'->id'] = $this->GetKey();
			$aScalarArgs[$sArgName.'->object()'] = $this;
			$aScalarArgs[$sArgName.'->hyperlink()'] = $this->GetHyperlink();
			// #@# Prototype for a user portal - to be dehardcoded later 
			$aScalarArgs[$sArgName.'->hyperlink(portal)'] = '../portal/index.php?operation=details&id='.$this->GetKey();
			$aScalarArgs[$sArgName.'->name()'] = $this->GetName();
		
			$sClass = get_class($this);
			foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				$aScalarArgs[$sArgName.'->'.$sAttCode] = $this->Get($sAttCode);
				if ($oAttDef->IsScalar())
				{
					// #@# Note: This has been proven to be quite slow, this can slow down bulk load
					$sAsHtml = $this->GetAsHtml($sAttCode);
					$aScalarArgs[$sArgName.'->html('.$sAttCode.')'] = $sAsHtml;
					$aScalarArgs[$sArgName.'->label('.$sAttCode.')'] = strip_tags($sAsHtml);
				}
			}
			$this->m_aAsArgs = $aScalarArgs;
			$oKPI->ComputeStats('ToArgs', get_class($this));
		}
		return $this->m_aAsArgs;
	}

	// To be optionaly overloaded
	protected function OnInsert()
	{
	}
	
	// To be optionaly overloaded
	protected function AfterInsert()
	{
	}

	// To be optionaly overloaded
	protected function OnUpdate()
	{
	}

	// To be optionaly overloaded
	protected function AfterUpdate()
	{
	}

	// To be optionaly overloaded
	protected function OnDelete()
	{
	}

	// To be optionaly overloaded
	protected function AfterDelete()
	{
	}

	// Return an empty set for the parent of all
	public static function GetRelationQueries($sRelCode)
	{
		return array();
	}
	
	public function GetRelatedObjects($sRelCode, $iMaxDepth = 99, &$aResults = array())
	{
		foreach (MetaModel::EnumRelationQueries(get_class($this), $sRelCode) as $sDummy => $aQueryInfo)
		{
			MetaModel::DbgTrace("object=".$this->GetKey().", depth=$iMaxDepth, rel=".$aQueryInfo["sQuery"]);
			$sQuery = $aQueryInfo["sQuery"];
			$bPropagate = $aQueryInfo["bPropagate"];
			$iDistance = $aQueryInfo["iDistance"];

			$iDepth = $bPropagate ? $iMaxDepth - 1 : 0;

			$oFlt = DBObjectSearch::FromOQL($sQuery);
			$oObjSet = new DBObjectSet($oFlt, array(), $this->ToArgs());
			while ($oObj = $oObjSet->Fetch())
			{
				$sRootClass = MetaModel::GetRootClass(get_class($oObj));
				$sObjKey = $oObj->GetKey();
				if (array_key_exists($sRootClass, $aResults))
				{
					if (array_key_exists($sObjKey, $aResults[$sRootClass]))
					{
						continue; // already visited, skip
					}
				}

				$aResults[$sRootClass][$sObjKey] = $oObj;
				if ($iDepth > 0)
				{
					$oObj->GetRelatedObjects($sRelCode, $iDepth, $aResults);
				}
			}
		}
		return $aResults;
	}

	public function GetReferencingObjects()
	{
		$aDependentObjects = array();
		$aRererencingMe = MetaModel::EnumReferencingClasses(get_class($this));
		foreach($aRererencingMe as $sRemoteClass => $aExtKeys)
		{
			foreach($aExtKeys as $sExtKeyAttCode => $oExtKeyAttDef)
			{
				// skip if this external key is behind an external field
				if (!$oExtKeyAttDef->IsExternalKey(EXTKEY_ABSOLUTE)) continue;

				$oSearch = new DBObjectSearch($sRemoteClass);
				$oSearch->AddCondition($sExtKeyAttCode, $this->GetKey(), '=');
				$oSet = new CMDBObjectSet($oSearch);
				if ($oSet->Count() > 0)
				{
					$aDependentObjects[$sRemoteClass][$sExtKeyAttCode] = array(
						'attribute' => $oExtKeyAttDef,
						'objects' => $oSet,
					);
				}
			}
		}
		return $aDependentObjects;
	}

	public function GetDeletionScheme()
	{
		$aDependentObjects = $this->GetReferencingObjects();
		$aDeletedObjs = array(); // [class][key] => structure
		$aResetedObjs = array(); // [class][key] => object
		foreach ($aDependentObjects as $sRemoteClass => $aPotentialDeletes)
		{
			foreach ($aPotentialDeletes as $sRemoteExtKey => $aData)
			{
				$oAttDef = $aData['attribute'];
				$iDeletePropagationOption = $oAttDef->GetDeletionPropagationOption();
				$oDepSet = $aData['objects'];
				$oDepSet->Rewind();
				while ($oDependentObj = $oDepSet->fetch())
				{
					$iId = $oDependentObj->GetKey();
					if ($oAttDef->IsNullAllowed())
					{
						// Optional external key, list to reset
						if (!array_key_exists($sRemoteClass, $aResetedObjs) || !array_key_exists($iId, $aResetedObjs[$sRemoteClass]))
						{
							$aResetedObjs[$sRemoteClass][$iId]['to_reset'] = $oDependentObj;
						}
						$aResetedObjs[$sRemoteClass][$iId]['attributes'][$sRemoteExtKey] = $oAttDef;
					}
					else
					{
						// Mandatory external key, list to delete
						if (array_key_exists($sRemoteClass, $aDeletedObjs) && array_key_exists($iId, $aDeletedObjs[$sRemoteClass]))
						{
							$iCurrentOption = $aDeletedObjs[$sRemoteClass][$iId];
							if ($iCurrentOption == DEL_AUTO)
							{
								// be conservative, take the new option
								// (DEL_MANUAL has precedence over DEL_AUTO)
								$aDeletedObjs[$sRemoteClass][$iId]['auto_delete'] = ($iDeletePropagationOption == DEL_AUTO); 
							}
							else
							{
								// DEL_MANUAL... leave it as is, it HAS to be verified anyway
							}
						}
						else
						{
							// First time we find the given object in the list
							// (and most likely case is that no other occurence will be found)
							$aDeletedObjs[$sRemoteClass][$iId]['to_delete'] = $oDependentObj;
							$aDeletedObjs[$sRemoteClass][$iId]['auto_delete'] = ($iDeletePropagationOption == DEL_AUTO); 
						}
					}
				}
			}
		}
		return array($aDeletedObjs, $aResetedObjs);
	}
}


?>
