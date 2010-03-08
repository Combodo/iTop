<?php

/**
 * ???
 * the class a persistent object must be derived from 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

require_once('metamodel.class.php');


/**
 * A persistent object, as defined by the metamodel 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
abstract class DBObject
{
	private static $m_aMemoryObjectsByClass = array();

	private $m_bIsInDB = false; // true IIF the object is mapped to a DB record
	private $m_iKey = null;
	private $m_aCurrValues = array();
	protected $m_aOrigValues = array();

	private $m_bDirty = false; // The object may have incorrect external keys, then any attempt of reload must be avoided
	private $m_bFullyLoaded = false; // Compound objects can be partially loaded
	private $m_aLoadedAtt = array(); // Compound objects can be partially loaded, array of sAttCode

	// Use the MetaModel::NewObject to build an object (do we have to force it?)
	public function __construct($aRow = null)
	{
		if (!empty($aRow))
		{
			$this->FromRow($aRow);
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

	protected function FromRow($aRow)
	{
		$this->m_iKey = null;
		$this->m_bIsInDB = true;
		$this->m_aCurrValues = array();
		$this->m_aOrigValues = array();
		$this->m_aLoadedAtt = array();

		// Get the key
		//
		$sKeyField = "id";
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
			if (array_key_exists($sAttCode, $aRow))
			{
				$value = $oAttDef->FromSQLToValue($aRow, $sAttCode);

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
			//throw new CoreWarning('Attempting to set the value for the internal attribute \"finalclass\"', array('current value'=>$this->Get('finalclass'), 'new value'=>$value));
			return;
		}
		if (!array_key_exists($sAttCode, MetaModel::ListAttributeDefs(get_class($this))))
		{
			throw new CoreException("Unknown attribute code '$sAttCode' for the class ".get_class($this));
		}
		$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		if ($this->m_bIsInDB && !$this->m_bFullyLoaded && !$this->m_bDirty)
		{
			// First time Set is called... ensure that the object gets fully loaded
			// Otherwise we would lose the values on a further Reload
			//           + consistency does not make sense !
			$this->Reload();
		}
		if($oAttDef->IsScalar() && !$oAttDef->IsNullAllowed() && is_null($value))
		{
			throw new CoreWarning("null not allowed for attribute '$sAttCode', setting default value");
			$this->m_aCurrValues[$sAttCode] = $oAttDef->GetDefaultValue();
			return;
		}
		if ($oAttDef->IsExternalKey() && is_object($value))
		{
			// Setting an external key with a whole object (instead of just an ID)
			// let's initialize also the external fields that depend on it
			// (useful when building objects in memory and not from a query)
			if ( (get_class($value) != $oAttDef->GetTargetClass()) && (!is_subclass_of($value, $oAttDef->GetTargetClass())))
			{
				throw new CoreWarning("Trying to set the value of '$sAttCode', to an object of class '".get_class($value)."', whereas it's an ExtKey to '".$oAttDef->GetTargetClass()."'. Ignored");
				$this->m_aCurrValues[$sAttCode] = $oAttDef->GetDefaultValue();
			}
			else
			{
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
			throw new CoreWarning("scalar not allowed for attribute '$sAttCode', setting default value (empty list)");
			$this->m_aCurrValues[$sAttCode] = $oAttDef->GetDefaultValue();
			return;
		}
		if($oAttDef->IsLinkSet())
		{
			if((get_class($value) != 'DBObjectSet') && !is_subclass_of($value, 'DBObjectSet'))
			{
				throw new CoreWarning("expecting a set of persistent objects (found a '".get_class($value)."'), setting default value (empty list)");
				$this->m_aCurrValues[$sAttCode] = $oAttDef->GetDefaultValue();
				return;
			}

			$oObjectSet = $value;
			$sSetClass = $oObjectSet->GetClass();
			$sLinkClass = $oAttDef->GetLinkedClass();
			// not working fine :-(   if (!is_subclass_of($sSetClass, $sLinkClass))
			if ($sSetClass != $sLinkClass)
			{
				throw new CoreWarning("expecting a set of '$sLinkClass' objects (found a set of '$sSetClass'), setting default value (empty list)");
				$this->m_aCurrValues[$sAttCode] = $oAttDef->GetDefaultValue();
				return;
			}
		}
		$this->m_aCurrValues[$sAttCode] = $oAttDef->MakeRealValue($value);
		$this->RegisterAsDirty(); // Make sure we do not reload it anymore... before saving it
	}
	
	public function Get($sAttCode)
	{
		if (!array_key_exists($sAttCode, MetaModel::ListAttributeDefs(get_class($this))))
		{
			throw new CoreException("Unknown attribute code '$sAttCode' for the class ".get_class($this));
		}
		if ($this->m_bIsInDB && !$this->m_aLoadedAtt[$sAttCode] && !$this->m_bDirty)
		{
			// #@# non-scalar attributes.... handle that differentely
			$this->Reload();
		}
		$this->ComputeFields();
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

	public function ComputeFields()
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
			$aStates = MetaModel::EnumStates(get_class($this));
			return $aStates[$this->Get($sStateAttCode)]['label'];
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
		if ($oAtt->IsExternalKey())
		{
			if (!$oAtt->IsNullAllowed() || ($toCheck != 0) )
			{
				try
				{
					$oTargetObj = MetaModel::GetObject($oAtt->GetTargetClass(), $toCheck);
					return true;
				}
				catch (CoreException $e)
				{
					return false;
				}
			}
		}
		elseif ($oAtt->IsWritable() && $oAtt->IsScalar())
		{
			$aValues = $oAtt->GetAllowedValues();
			if (count($aValues) > 0)
			{
				if (!array_key_exists($toCheck, $aValues))
				{
					return false;
				}
			}
		}
		return true;
	}
	
	// check attributes together
	public function CheckConsistency()
	{
		return true;
	}
	
	// check if it is allowed to record the new object into the database
	// a displayable error is returned
	// Note: checks the values and consistency
	public function CheckToInsert()
	{
		$aIssues = array();
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode=>$oAttDef)
		{
			if (!$this->CheckValue($sAttCode))
			{
				$aIssues[$sAttCode] = array(
					'issue' => 'unexpected value'
				);
			}
		}
		if (count($aIssues) > 0)
		{
			return array(false, $aIssues);
		}
		if (!$this->CheckConsistency())
		{
			return array(false, $aIssues);
		}
		return array(true, $aIssues);
	}

	// check if it is allowed to update the existing object into the database
	// a displayable error is returned
	// Note: checks the values and consistency
	public function CheckToUpdate()
	{
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode=>$oAttDef)
		{
			if (!$this->CheckValue($sAttCode)) return false;
		}
		if (!$this->CheckConsistency()) return false;
		return true;
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
			if (!array_key_exists($sAtt, $this->m_aOrigValues) || ($this->m_aOrigValues[$sAtt] !== $proposedValue))
			{
				$aDelta[$sAtt] = $proposedValue;
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
				$aToDelete = array_diff($aOriginalList, $aNewSet);
				foreach ($aToDelete as $iKey => $oObject)
				{
					$oObject->DBDelete();
				}
			}
		}
	}

	private function DBInsertSingleTable($sTableClass)
	{
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

		$sTable = MetaModel::DBGetTable($sTableClass);
		$sInsertSQL = "INSERT INTO $sTable (".join(",", $aFieldsToWrite).") VALUES (".join(", ", $aValuesToWrite).")";

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
		$this->ComputeFields();

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
				throw new CoreWarning("Missing key for the object to write - This class is supposed to have a user defined key, not an autonumber");
			}
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
			if (MetaModel::DBGetTable($sParentClass) == "") continue;
			$this->DBInsertSingleTable($sParentClass);
		}

		$this->DBWriteLinks();
		$this->m_bIsInDB = true;

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
		$this->m_bDirty = false;
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

	// Update a record
	public function DBUpdate()
	{
		if (!$this->m_bIsInDB)
		{
			throw new CoreException("DBUpdate: could not update a newly created object, please call DBInsert instead");
		}
		$aChanges = $this->ListChanges();
		if (count($aChanges) == 0)
		{
			throw new CoreWarning("Attempting to update an unchanged object");
			return;
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

		$sSQL = MetaModel::MakeDeleteQuery($oFilter);
		CMDBSource::Query($sSQL);

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
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnStateLeave AS t WHERE t.target_class='$sClass' AND t.state='$sPreviousState'"));
		while ($oTrigger = $oSet->Fetch())
		{
			$oTrigger->DoActivate($this->ToArgs('this'));
		}

		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnStateEnter AS t WHERE t.target_class='$sClass' AND t.state='$sNewState'"));
		while ($oTrigger = $oSet->Fetch())
		{
			$oTrigger->DoActivate($this->ToArgs('this'));
		}

		return $bSuccess;
	}

	// Make standard context arguments
	public function ToArgs($sArgName)
	{
		$aScalarArgs = array();
		$aScalarArgs[$sArgName] = $this->GetKey();
		$aScalarArgs[$sArgName.'->id'] = $this->GetKey();
		$aScalarArgs[$sArgName.'->object()'] = $this;
		$aScalarArgs[$sArgName.'->hyperlink()'] = $this->GetHyperlink();
		$aScalarArgs[$sArgName.'->name()'] = $this->GetName();
	
		$sClass = get_class($this);
		foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
		{
			$aScalarArgs[$sArgName.'->'.$sAttCode] = $this->Get($sAttCode);
		}
		return $aScalarArgs;
	}


	// Return an empty set for the parent of all
	public static function GetRelationQueries($sRelCode)
	{
		return array();
	}
	
	public function GetRelatedObjects($sRelCode, $iMaxDepth = 99, &$aResults = array())
	{
		foreach (MetaModel::GetLinkedSets($sClass) as $sAttCode => $oAttDef)
		{
			$aSupportedRelations = $oAttDef->GetSupportedRelations();
			if (!array_key_exists($sRelCode, $aSupportedRelations)) continue; //skip

			$bPropagate = true; // #@# Todo: discuss that setting
			$iDepth = $bPropagate ? $iMaxDepth - 1 : 0;

			$oNeighbors = $this->Get($sAttCode);
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
		
		return;
		
		// #@# todo : Discuss the Relations and the way they are defined (do we deprecate the queries ? what are the properties -e.g. depth- and where do we set them ?)
		foreach (MetaModel::EnumRelationQueries(get_class($this), $sRelCode) as $sDummy => $aQueryInfo)
		{
			MetaModel::DbgTrace("object=".$this->GetKey().", depth=$iMaxDepth, rel=".$aQueryInfo["sQuery"]);
			$sQuery = $aQueryInfo["sQuery"];
			$bPropagate = $aQueryInfo["bPropagate"];
			$iDistance = $aQueryInfo["iDistance"];

			$iDepth = $bPropagate ? $iMaxDepth - 1 : 0;

			$oFlt = DBObjectSearch::FromSibusQL($sQuery, array(), $this);
			$oObjSet = new DBObjectSet($oFlt);
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
				$oSearch->AddCondition($sExtKeyAttCode, $this->GetKey());
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
