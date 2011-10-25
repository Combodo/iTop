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
require_once('deletionplan.class.inc.php');


/**
 * A persistent object, as defined by the metamodel 
 *
 * @package     iTopORM
 */
abstract class DBObject
{
	private static $m_aMemoryObjectsByClass = array();

  	private static $m_aBulkInsertItems = array(); // class => array of ('table' => array of (array of <sql_value>))
  	private static $m_aBulkInsertCols = array(); // class => array of ('table' => array of <sql_column>)
  	private static $m_bBulkInsert = false;

	private $m_bIsInDB = false; // true IIF the object is mapped to a DB record
	private $m_iKey = null;
	private $m_aCurrValues = array();
	protected $m_aOrigValues = array();

	protected $m_aExtendedData = null;

	private $m_bDirty = false; // Means: "a modification is ongoing"
										// The object may have incorrect external keys, then any attempt of reload must be avoided
	private $m_bCheckStatus = null; // Means: the object has been verified and is consistent with integrity rules
													//        if null, then the check has to be performed again to know the status
	protected $m_bSecurityIssue = null;
	protected $m_aCheckIssues = null;
	protected $m_aDeleteIssues = null;

	protected $m_aAsArgs = null; // The current object as a standard argument (cache)

	private $m_bFullyLoaded = false; // Compound objects can be partially loaded
	private $m_aLoadedAtt = array(); // Compound objects can be partially loaded, array of sAttCode
	protected $m_oMasterReplicaSet = null; // Set of SynchroReplica related to this object

	// Use the MetaModel::NewObject to build an object (do we have to force it?)
	public function __construct($aRow = null, $sClassAlias = '', $aAttToLoad = null, $aExtendedDataSpec = null)
	{
		if (!empty($aRow))
		{
			$this->FromRow($aRow, $sClassAlias, $aAttToLoad, $aExtendedDataSpec);
			$this->m_bFullyLoaded = $this->IsFullyLoaded();
			return;
		}
		// Creation of a brand new object
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
				// Leave the flag unset (optimization) 
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
		$sRootClass = MetaModel::GetRootClass($sClass);
		if (!array_key_exists($sRootClass, self::$m_aMemoryObjectsByClass))
		{
			self::$m_aMemoryObjectsByClass[$sRootClass] = 0;
		}
		self::$m_aMemoryObjectsByClass[$sRootClass]++;
		return (- self::$m_aMemoryObjectsByClass[$sRootClass]);
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
			if (!isset($this->m_aLoadedAtt[$sAttCode]) || !$this->m_aLoadedAtt[$sAttCode])
			{
				return false;
			}
		}
		return true;
	}

	protected function Reload()
	{
		assert($this->m_bIsInDB);
		$aRow = MetaModel::MakeSingleRow(get_class($this), $this->m_iKey, false/*, $this->m_bAllowAllData*/);
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

	protected function FromRow($aRow, $sClassAlias = '', $aAttToLoad = null, $aExtendedDataSpec = null)
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

		$iPKey = $aRow[$sKeyField];
		if (!self::IsValidPKey($iPKey))
		{
			if (is_null($iPKey))
			{
				throw new CoreException("Missing object id in query result (found null)");
			}
			else
			{
				throw new CoreException("An object id must be an integer value ($iPKey)");
			}
		}
		$this->m_iKey = $iPKey;

		// Build the object from an array of "attCode"=>"value")
		//
		$bFullyLoaded = true; // ... set to false if any attribute is not found
		if (is_null($aAttToLoad) || !array_key_exists($sClassAlias, $aAttToLoad))
		{
			$aAttList = MetaModel::ListAttributeDefs(get_class($this));
		}
		else
		{
			$aAttList = $aAttToLoad[$sClassAlias];
		}
		
		foreach($aAttList as $sAttCode=>$oAttDef)
		{
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
		
		// Load extended data
		if ($aExtendedDataSpec != null)
		{
			$aExtendedDataSpec['table'];
			foreach($aExtendedDataSpec['fields'] as $sColumn)
			{
				$sColRef = $sClassAlias.'_extdata_'.$sColumn;
				if (array_key_exists($sColRef, $aRow))
				{
					$this->m_aExtendedData[$sColumn] = $aRow[$sColRef];
				}
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

		$realvalue = $oAttDef->MakeRealValue($value, $this);
		$this->m_aCurrValues[$sAttCode] = $realvalue;

		// The object has changed, reset caches
		$this->m_bCheckStatus = null;
		$this->m_aAsArgs = null;

		// Make sure we do not reload it anymore... before saving it
		$this->RegisterAsDirty();
	}

	public function GetLabel($sAttCode)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		return $oAttDef->GetLabel();
	}

	public function Get($sAttCode)
	{
		if (($iPos = strpos($sAttCode, '->')) === false)
		{
			return $this->GetStrict($sAttCode);
		}
		else
		{
			$sExtKeyAttCode = substr($sAttCode, 0, $iPos);
			$sRemoteAttCode = substr($sAttCode, $iPos + 2);
			if (!MetaModel::IsValidAttCode(get_class($this), $sExtKeyAttCode))
			{
				throw new CoreException("Unknown external key '$sExtKeyAttCode' for the class ".get_class($this));
			}
			$oKeyAttDef = MetaModel::GetAttributeDef(get_class($this), $sExtKeyAttCode);
			$sRemoteClass = $oKeyAttDef->GetTargetClass();
			$oRemoteObj = MetaModel::GetObject($sRemoteClass, $this->GetStrict($sExtKeyAttCode), false);
			if (is_null($oRemoteObj))
			{
				return '';
			}
			else
			{
				return $oRemoteObj->Get($sRemoteAttCode);
			}
		}
	}

	public function GetStrict($sAttCode)
	{
		if (!array_key_exists($sAttCode, MetaModel::ListAttributeDefs(get_class($this))))
		{
			throw new CoreException("Unknown attribute code '$sAttCode' for the class ".get_class($this));
		}
		if ($this->m_bIsInDB && !isset($this->m_aLoadedAtt[$sAttCode]) && !$this->m_bDirty)
		{
			// #@# non-scalar attributes.... handle that differently
			$this->Reload();
		}
		$value = $this->m_aCurrValues[$sAttCode];
		if ($value instanceof DBObjectSet)
		{
			$value->Rewind();
		}
		return $value; 
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
	 * Returns data loaded by the mean of a dynamic and explicit JOIN
	 */	 
	public function GetExtendedData()
	{
		return $this->m_aExtendedData;
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
	
	public function ComputeValues()
	{
	}

	// Compute scalar attributes that depend on any other type of attribute
	final public function DoComputeValues()
	{
		// TODO - use a flag rather than checking the call stack -> this will certainly accelerate things

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

	public function GetAsHTML($sAttCode)
	{
		$sClass = get_class($this);
		$oAtt = MetaModel::GetAttributeDef($sClass, $sAttCode);

		if ($oAtt->IsExternalKey(EXTKEY_ABSOLUTE))
		{
			//return $this->Get($sAttCode.'_friendlyname');
			$sTargetClass = $oAtt->GetTargetClass(EXTKEY_ABSOLUTE);
			$iTargetKey = $this->Get($sAttCode);
			$sLabel = $this->Get($sAttCode.'_friendlyname');
			return $this->MakeHyperLink($sTargetClass, $iTargetKey, $sLabel);
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
				$sEditValue = $this->Get($sAttCode.'_friendlyname');
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
		return $oAtt->GetAsXML($this->Get($sAttCode), $this);
	}

	public function GetAsCSV($sAttCode, $sSeparator = ',', $sTextQualifier = '"')
	{
		$oAtt = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		return $oAtt->GetAsCSV($this->Get($sAttCode), $sSeparator, $sTextQualifier, $this);
	}

	public function GetOriginalAsHTML($sAttCode)
	{
		$oAtt = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		return $oAtt->GetAsHTML($this->GetOriginal($sAttCode), $this);
	}

	public function GetOriginalAsXML($sAttCode)
	{
		$oAtt = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		return $oAtt->GetAsXML($this->GetOriginal($sAttCode), $this);
	}

	public function GetOriginalAsCSV($sAttCode, $sSeparator = ',', $sTextQualifier = '"')
	{
		$oAtt = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		return $oAtt->GetAsCSV($this->GetOriginal($sAttCode), $sSeparator, $sTextQualifier, $this);
	}

	protected static function MakeHyperLink($sObjClass, $sObjKey, $sLabel = '', $sUrlMakerClass = null, $bWithNavigationContext = true)
	{
		if ($sObjKey <= 0) return '<em>'.Dict::S('UI:UndefinedObject').'</em>'; // Objects built in memory have negative IDs

		// Safety net
		//
		if (empty($sLabel))
		{
			// If the object if not issued from a query but constructed programmatically
			// the label may be empty. In this case run a query to get the object's friendly name
			$oTmpObj = MetaModel::GetObject($sObjClass, $sObjKey, false);
			if (is_object($oTmpObj))
			{
				$sLabel = $oTmpObj->GetName();
			}
			else
			{
				// May happen in case the target object is not in the list of allowed values for this attribute
				$sLabel = "<em>$sObjClass::$sObjKey</em>";
			}
			//$sLabel = MetaModel::GetName($sObjClass)." #$sObjKey";
		}
		$sHint = MetaModel::GetName($sObjClass)."::$sObjKey";
		$sUrl = ApplicationContext::MakeObjectUrl($sObjClass, $sObjKey, $sUrlMakerClass, $bWithNavigationContext);
		if (strlen($sUrl) > 0)
		{
			return "<a href=\"$sUrl\" title=\"$sHint\">$sLabel</a>";
		}
		else
		{
			return $sLabel;
		}
	}

	public function GetHyperlink($sUrlMakerClass = null, $bWithNavigationContext = true)
	{
		return self::MakeHyperLink(get_class($this), $this->GetKey(), $this->GetName(), $sUrlMakerClass, $bWithNavigationContext);
	}
	
	public static function ComputeStandardUIPage($sClass)
	{
		static $aUIPagesCache = array(); // Cache to store the php page used to display each class of object
		if (!isset($aUIPagesCache[$sClass]))
		{
			$UIPage = false;
			if (is_callable("$sClass::GetUIPage"))
			{
				$UIPage = eval("return $sClass::GetUIPage();"); // May return false in case of error
			}
			$aUIPagesCache[$sClass] = $UIPage === false ? './UI.php' : $UIPage;
		}
		$sPage = $aUIPagesCache[$sClass];
		return $sPage;
	}

	public static function GetUIPage()
	{
		return 'UI.php';
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

	/**
	 * Gets the name of an object in a safe manner for displaying inside a web page
	 * @return string
	 */
	public function GetName()
	{
		return htmlentities($this->GetRawName(), ENT_QUOTES, 'UTF-8');
	}

	/**
	 * Gets the raw name of an object, this is not safe for displaying inside a web page
	 * since the " < > characters are not escaped and the name may contain some XSS script
	 * instructions.
	 * Use this function only for internal computations or for an output to a non-HTML destination
	 * @return string
	 */
	public function GetRawName()
	{
		return $this->Get('friendlyname');
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
	 * @param $sAttCode string $sAttCode The code of the attribute
	 * @param $aReasons array To store the reasons why the attribute is read-only (info about the synchro replicas)
	 * @param $sTargetState string The target state in which to evalutate the flags, if empty the current state will be used
	 * @return integer Flags: the binary combination of the flags applicable to this attribute
	 */	 	  	 	
	public function GetAttributeFlags($sAttCode, &$aReasons = array(), $sTargetState = '')
	{
		$iFlags = 0; // By default (if no life cycle) no flag at all
		$sStateAttCode = MetaModel::GetStateAttributeCode(get_class($this));
		if (!empty($sStateAttCode))
		{
			if ($sTargetState != '')
			{
				$iFlags = MetaModel::GetAttributeFlags(get_class($this), $sTargetState, $sAttCode);			
			}
			else
			{
				$iFlags = MetaModel::GetAttributeFlags(get_class($this), $this->Get($sStateAttCode), $sAttCode);
			}
		}
		$aReasons = array();
		$iSynchroFlags = $this->GetSynchroReplicaFlags($sAttCode, $aReasons);
		return $iFlags | $iSynchroFlags; // Combine both sets of flags
	}

	/**
	 * Returns the set of flags (OPT_ATT_HIDDEN, OPT_ATT_READONLY, OPT_ATT_MANDATORY...)
	 * for the given attribute for the current state of the object considered as an INITIAL state
	 * @param string $sAttCode The code of the attribute
	 * @return integer Flags: the binary combination of the flags applicable to this attribute
	 */	 	  	 	
	public function GetInitialStateAttributeFlags($sAttCode, &$aReasons = array())
	{
		$iFlags = 0;
		$sStateAttCode = MetaModel::GetStateAttributeCode(get_class($this));
		if (!empty($sStateAttCode))
		{
			$iFlags = MetaModel::GetInitialStateAttributeFlags(get_class($this), $this->Get($sStateAttCode), $sAttCode);
		}
		return $iFlags; // No need to care about the synchro flags since we'll be creating a new object anyway
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
			if ($oAtt->IsHierarchicalKey())
			{
				// This check cannot be deactivated since otherwise the user may break things by a CSV import of a bulk modify
				if ($toCheck == $this->GetKey())
				{
					return "An object can not be its own parent in a hierarchy (".$oAtt->Getlabel()." = $toCheck)";
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
		$this->DoComputeValues();

		$this->m_aCheckIssues = array();
		$aChanges = $this->ListChanges();

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

		// Synchronization: are we attempting to modify an attribute for which an external source is master?
		//
		if ($this->m_bIsInDB && $this->InSyncScope() && (count($aChanges) > 0))
		{
			foreach($aChanges as $sAttCode => $value)
			{
				$iFlags = $this->GetSynchroReplicaFlags($sAttCode, $aReasons);
				if ($iFlags & OPT_ATT_SLAVE)
				{
					// Note: $aReasonInfo['name'] could be reported (the task owning the attribute)
					$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
					$sAttLabel = $oAttDef->GetLabel();
					foreach($aReasons as $aReasonInfo)
					{
						// Todo: associate the attribute code with the error
						$this->m_aCheckIssues[] = Dict::Format('UI:AttemptingToSetASlaveAttribute_Name', $sAttLabel);
					}
				}
			}
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
		return array($this->m_bCheckStatus, $this->m_aCheckIssues, $this->m_bSecurityIssue);
	}

	// check if it is allowed to delete the existing object from the database
	// a displayable error is returned
	protected function DoCheckToDelete(&$oDeletionPlan)
	{
		$this->m_aDeleteIssues = array(); // Ok

		if ($this->InSyncScope())
		{
			$oReplicaSet = $this->GetMasterReplica();
			if ($oReplicaSet->Count() > 0)
			{
				while($aData = $oReplicaSet->FetchAssoc())
				{
					$oDataSource = $aData['datasource'];
					$oReplica = $aData['replica'];

					$oDeletionPlan->AddToDelete($oReplica, DEL_SILENT);

					if ($oDataSource->GetKey() == SynchroDataSource::GetCurrentTaskId())
					{
						// The current task has the right to delete the object
						continue;
					}
					
					if ($oReplica->Get('status_dest_creator') != 1)
					{
						// The object is not owned by the task
						continue;
					}

					$sLink = $oDataSource->GetName();
					$sUserDeletePolicy = $oDataSource->Get('user_delete_policy');
					switch($sUserDeletePolicy)
					{
					case 'nobody':
						$this->m_aDeleteIssues[] = Dict::Format('Core:Synchro:TheObjectCannotBeDeletedByUser_Source', $sLink);
						break;

					case 'administrators':
						if (!UserRights::IsAdministrator())
						{
							$this->m_aDeleteIssues[] = Dict::Format('Core:Synchro:TheObjectCannotBeDeletedByUser_Source', $sLink);
						}
						break;

					case 'everybody':
					default:
						// Ok
						break;
					}
				}
			}
		}
	}

  	final public function CheckToDelete(&$oDeletionPlan)
  	{
		$this->MakeDeletionPlan($oDeletionPlan);
		$oDeletionPlan->ComputeResults();
		return (!$oDeletionPlan->FoundStopper());
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
				$oLinkAttDef = MetaModel::GetAttributeDef(get_class($this), $sAtt);
				// The value is an object, the comparison is not strict
				if (!$oLinkAttDef->Equals($proposedValue, $this->m_aOrigValues[$sAtt]))
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
		if ($this->m_bIsInDB)
		{
			return $this->ListChangedValues($this->m_aCurrValues);
		}
		else
		{
			return $this->m_aCurrValues;
		}
	}

	// Tells whether or not an object was modified since last read (ie: does it differ from the DB ?)
	public function IsModified()
	{
		$aChanges = $this->ListChanges();
		return (count($aChanges) != 0);
	}

	public function Equals($oSibling)
	{
		if (get_class($oSibling) != get_class($this))
		{
			return false;
		}
		if ($this->GetKey() != $oSibling->GetKey())
		{
			return false;
		}
		if ($this->m_bIsInDB)
		{
			// If one has changed, then consider them as being different
			if ($this->IsModified() || $oSibling->IsModified())
			{
				return false;
			}
		}
		else
		{
			// Todo - implement this case (loop on every attribute)
			//foreach(MetaModel::ListAttributeDefs(get_class($this) as $sAttCode => $oAttDef)
			//{
					//if (!isset($this->m_CurrentValues[$sAttCode])) continue;
					//if (!isset($this->m_CurrentValues[$sAttCode])) continue;
					//if (!$oAttDef->Equals($this->m_CurrentValues[$sAttCode], $oSibling->m_CurrentValues[$sAttCode]))
					//{
						//return false;
					//}
			//}
			return false;
		}
		return true;
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

	// Note: this is experimental - it was designed to speed up the setup of iTop
	// Known limitations:
	//   - does not work with multi-table classes (issue with the unique id to maintain in several tables)
	//   - the id of the object is not updated
	static public final function BulkInsertStart()
	{
		self::$m_bBulkInsert = true;
	} 

	static public final function BulkInsertFlush()
	{
		if (!self::$m_bBulkInsert) return;

		foreach(self::$m_aBulkInsertCols as $sClass => $aTables)
		{
			foreach ($aTables as $sTable => $sColumns)
			{
				$sValues = implode(', ', self::$m_aBulkInsertItems[$sClass][$sTable]);
				$sInsertSQL = "INSERT INTO `$sTable` ($sColumns) VALUES $sValues";
				$iNewKey = CMDBSource::InsertInto($sInsertSQL);
			}
		}

		// Reset
		self::$m_aBulkInsertItems = array();
		self::$m_aBulkInsertCols = array();
		self::$m_bBulkInsert = false;
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

		$aHierarchicalKeys = array();
		
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
			if ($oAttDef->IsHierarchicalKey())
			{
				$aHierarchicalKeys[$sAttCode] = $oAttDef;
			}
		}

		if (count($aValuesToWrite) == 0) return false;

		if (MetaModel::DBIsReadOnly())
		{
			$iNewKey = -1;
		}
		else
		{
			if (self::$m_bBulkInsert)
			{
				if (!isset(self::$m_aBulkInsertCols[$sClass][$sTable]))
				{
					self::$m_aBulkInsertCols[$sClass][$sTable] = implode(', ', $aFieldsToWrite);
				}
				self::$m_aBulkInsertItems[$sClass][$sTable][] = '('.implode (', ', $aValuesToWrite).')';
				
				$iNewKey = 999999; // TODO - compute next id....
			}
			else
			{
				if (count($aHierarchicalKeys) > 0)
				{
					foreach($aHierarchicalKeys as $sAttCode => $oAttDef)
					{
						$aValues = MetaModel::HKInsertChildUnder($this->m_aCurrValues[$sAttCode], $oAttDef, $sTable);
						$aFieldsToWrite[] = '`'.$oAttDef->GetSQLRight().'`';
						$aValuesToWrite[] = $aValues[$oAttDef->GetSQLRight()];
						$aFieldsToWrite[] = '`'.$oAttDef->GetSQLLeft().'`';
						$aValuesToWrite[] = $aValues[$oAttDef->GetSQLLeft()];
					}
				}
				$sInsertSQL = "INSERT INTO `$sTable` (".join(",", $aFieldsToWrite).") VALUES (".join(", ", $aValuesToWrite).")";
				$iNewKey = CMDBSource::InsertInto($sInsertSQL);
			}
		}
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
			$sIssues = implode(', ', $aIssues);
			throw new CoreException("Object not following integrity rules", array('issues' => $sIssues, 'class' => get_class($this), 'id' => $this->GetKey()));
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
		$sClassList = implode("', '", MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL));
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnObjectCreate AS t WHERE t.target_class IN ('$sClassList')"));
		while ($oTrigger = $oSet->Fetch())
		{
			$oTrigger->DoActivate($this->ToArgs('this'));
		}

		return $this->m_iKey;
	}

	public function DBInsert()
	{
		$this->DBInsertNoReload();
		$this->Reload();
		return $this->m_iKey;
	}
	
	public function DBInsertTracked(CMDBChange $oVoid)
	{
		return $this->DBInsert();
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
			$sIssues = implode(', ', $aIssues);
			throw new CoreException("Object not following integrity rules", array('issues' => $sIssues, 'class' => get_class($this), 'id' => $this->GetKey()));
		}

		$bHasANewExternalKeyValue = false;
		$aHierarchicalKeys = array();
		foreach($aChanges as $sAttCode => $valuecurr)
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
			if ($oAttDef->IsExternalKey()) $bHasANewExternalKeyValue = true;
			if (!$oAttDef->IsDirectField()) unset($aChanges[$sAttCode]);
			if ($oAttDef->IsHierarchicalKey())
			{
				$aHierarchicalKeys[$sAttCode] = $oAttDef;
			}
		}

		if (!MetaModel::DBIsReadOnly())
		{
			// Update the left & right indexes for each hierarchical key
			foreach($aHierarchicalKeys as $sAttCode => $oAttDef)
			{
				$sTable = $sTable = MetaModel::DBGetTable(get_class($this), $sAttCode);
				$sSQL = "SELECT `".$oAttDef->GetSQLRight()."` AS `right`, `".$oAttDef->GetSQLLeft()."` AS `left` FROM `$sTable` WHERE id=".$this->GetKey();
				$aRes = CMDBSource::QueryToArray($sSQL);
				$iMyLeft = $aRes[0]['left'];
				$iMyRight = $aRes[0]['right'];
				$iDelta =$iMyRight - $iMyLeft + 1;
				MetaModel::HKTemporaryCutBranch($iMyLeft, $iMyRight, $oAttDef, $sTable);
				
				if ($aChanges[$sAttCode] == 0)
				{
					// No new parent, insert completely at the right of the tree
					$sSQL = "SELECT max(`".$oAttDef->GetSQLRight()."`) AS max FROM `$sTable`";
					$aRes = CMDBSource::QueryToArray($sSQL);
					if (count($aRes) == 0)
					{
						$iNewLeft = 1;
					}
					else
					{
						$iNewLeft = $aRes[0]['max']+1;
					}
				}
				else
				{
					// Insert at the right of the specified parent
					$sSQL = "SELECT `".$oAttDef->GetSQLRight()."` FROM `$sTable` WHERE id=".((int)$aChanges[$sAttCode]);
					$iNewLeft = CMDBSource::QueryToScalar($sSQL);
				}

				MetaModel::HKReplugBranch($iNewLeft, $iNewLeft + $iDelta - 1, $oAttDef, $sTable);

				$aHKChanges = array();
				$aHKChanges[$sAttCode] = $aChanges[$sAttCode];
				$aHKChanges[$oAttDef->GetSQLLeft()] = $iNewLeft;
				$aHKChanges[$oAttDef->GetSQLRight()] = $iNewLeft + $iDelta - 1;
				$aChanges[$sAttCode] = $aHKChanges; // the 3 values will be stored by MakeUpdateQuery below
			}
			
			// Update scalar attributes
			if (count($aChanges) != 0)
			{
				$oFilter = new DBObjectSearch(get_class($this));
				$oFilter->AddCondition('id', $this->m_iKey, '=');
		
				$sSQL = MetaModel::MakeUpdateQuery($oFilter, $aChanges);
				CMDBSource::Query($sSQL);
			}
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
	
	public function DBUpdateTracked(CMDBChange $oVoid)
	{
		return $this->DBUpdate();
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

	private function DBDeleteSingleTable($sTableClass)
	{
		$sTable = MetaModel::DBGetTable($sTableClass);
		// Abstract classes or classes having no specific attribute do not have an associated table
		if ($sTable == '') return;

		$sPKField = '`'.MetaModel::DBGetKey($sTableClass).'`';
		$sKey = CMDBSource::Quote($this->m_iKey);

		$sDeleteSQL = "DELETE FROM `$sTable` WHERE $sPKField = $sKey";
		CMDBSource::DeleteFrom($sDeleteSQL);
	}

	protected function DBDeleteSingleObject()
	{
		$this->OnDelete();

		if (!MetaModel::DBIsReadOnly())
		{
			foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
			{
				if ($oAttDef->IsHierarchicalKey())
				{
					// Update the left & right indexes for each hierarchical key
					$sTable = $sTable = MetaModel::DBGetTable(get_class($this), $sAttCode);
					$sSQL = "SELECT `".$oAttDef->GetSQLRight()."` AS `right`, `".$oAttDef->GetSQLLeft()."` AS `left` FROM `$sTable` WHERE id=".CMDBSource::Quote($this->m_iKey);
					$aRes = CMDBSource::QueryToArray($sSQL);
					$iMyLeft = $aRes[0]['left'];
					$iMyRight = $aRes[0]['right'];
					$iDelta =$iMyRight - $iMyLeft + 1;
					MetaModel::HKTemporaryCutBranch($iMyLeft, $iMyRight, $oAttDef, $sTable);

					// No new parent for now, insert completely at the right of the tree
					$sSQL = "SELECT max(`".$oAttDef->GetSQLRight()."`) AS max FROM `$sTable`";
					$aRes = CMDBSource::QueryToArray($sSQL);
					if (count($aRes) == 0)
					{
						$iNewLeft = 1;
					}
					else
					{
						$iNewLeft = $aRes[0]['max']+1;
					}
					MetaModel::HKReplugBranch($iNewLeft, $iNewLeft + $iDelta - 1, $oAttDef, $sTable);
				}
			}

			foreach(MetaModel::EnumParentClasses(get_class($this), ENUM_PARENT_CLASSES_ALL) as $sParentClass)
			{
				$this->DBDeleteSingleTable($sParentClass);
			}
		}

		$this->AfterDelete();

		$this->m_bIsInDB = false;
		$this->m_iKey = null;
	}

	// Delete an object... and guarantee data integrity
	//
	public function DBDelete(&$oDeletionPlan = null)
	{
		if (is_null($oDeletionPlan))
		{
			$oDeletionPlan = new DeletionPlan();
		}
		$this->MakeDeletionPlan($oDeletionPlan);
		$oDeletionPlan->ComputeResults();

		if ($oDeletionPlan->FoundStopper())
		{
			$aIssues = $oDeletionPlan->GetIssues();
			throw new DeleteException('Found issue(s)', array('target_class' => get_class($this), 'target_id' => $this->GetKey(), 'issues' => implode(', ', $aIssues)));	
		}
		else
		{
			foreach ($oDeletionPlan->ListDeletes() as $sClass => $aToDelete)
			{
				foreach ($aToDelete as $iId => $aData)
				{
					$oToDelete = $aData['to_delete'];
					// The deletion based on a deletion plan should not be done for each oject if the deletion plan is common (Trac #457)
					// because for each object we would try to update all the preceding ones... that are already deleted
					// A better approach would be to change the API to apply the DBDelete on the deletion plan itself... just once
					// As a temporary fix: delete only the objects that are still to be deleted...
					if ($oToDelete->m_bIsInDB)
					{
						$oToDelete->DBDeleteSingleObject();
					}
				}
			}

			foreach ($oDeletionPlan->ListUpdates() as $sClass => $aToUpdate)
			{
				foreach ($aToUpdate as $iId => $aData)
				{
					$oToUpdate = $aData['to_reset'];
					foreach ($aData['attributes'] as $sRemoteExtKey => $aRemoteAttDef)
					{
						$oToUpdate->Set($sRemoteExtKey, $aData['values'][$sRemoteExtKey]);
						$oToUpdate->DBUpdate();
					}
				}
			}
		}

		return $oDeletionPlan;
	}

	public function DBDeleteTracked(CMDBChange $oVoid, $bSkipStrongSecurity = null, &$oDeletionPlan = null)
	{
		$this->DBDelete($oDeletionPlan);
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
			$aScalarArgs[$sArgName.'->hyperlink()'] = $this->GetHyperlink('iTopStandardURLMaker', false);
			$aScalarArgs[$sArgName.'->hyperlink(portal)'] = $this->GetHyperlink('PortalURLMaker', false);
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
				// Do something for case logs... quick N' dirty...
				if ($aScalarArgs[$sArgName.'->'.$sAttCode] instanceof ormCaseLog)
				{
					$oCaseLog = $aScalarArgs[$sArgName.'->'.$sAttCode];
					$aScalarArgs[$sArgName.'->'.$sAttCode] = $oCaseLog->GetText();
					$aScalarArgs[$sArgName.'->head('.$sAttCode.')'] = $oCaseLog->GetLatestEntry();
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

	public function GetReferencingObjects($bAllowAllData = false)
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
				if ($bAllowAllData)
				{
					$oSearch->AllowAllData();
				}
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

	private function MakeDeletionPlan(&$oDeletionPlan, $aVisited = array(), $iDeleteOption = null)
	{
		$sClass = get_class($this);
		$iThisId = $this->GetKey();

		$iDeleteOption = $oDeletionPlan->AddToDelete($this, $iDeleteOption);

		if (array_key_exists($sClass, $aVisited))
		{
			if (in_array($iThisId, $aVisited[$sClass]))
			{
				return;
			}
		}
		$aVisited[$sClass] = $iThisId;

		if ($iDeleteOption == DEL_MANUAL)
		{
			// Stop the recursion here
			return;
		}
		// Check the node itself
		$this->DoCheckToDelete($oDeletionPlan);
		$oDeletionPlan->SetDeletionIssues($this, $this->m_aDeleteIssues, $this->m_bSecurityIssue);
	
		$aDependentObjects = $this->GetReferencingObjects(true /* allow all data */);
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
						if (($iDeletePropagationOption == DEL_MOVEUP) && ($oAttDef->IsHierarchicalKey()))
						{
							// Move the child up one level i.e. set the same parent as the current object
							$iParentId = $this->Get($oAttDef->GetCode());
							$oDeletionPlan->AddToUpdate($oDependentObj, $oAttDef, $iParentId);
						}
						else
						{
							$oDeletionPlan->AddToUpdate($oDependentObj, $oAttDef);
						}
					}
					else
					{
						// Mandatory external key, list to delete
						$oDependentObj->MakeDeletionPlan($oDeletionPlan, $aVisited, $iDeletePropagationOption);
					}
				}
			}
		}
	}

	/**
	 * Get all the synchro replica related to this object
	 * @param none
	 * @return DBObjectSet Set with two columns: R=SynchroReplica S=SynchroDataSource
	 */
	public function GetMasterReplica()
	{
		if ($this->m_oMasterReplicaSet == null)
		{
			//$aParentClasses = MetaModel::EnumParentClasses(get_class($this), ENUM_PARENT_CLASSES_ALL);
			//$sClassesList = "'".implode("','", $aParentClasses)."'";
			$sOQL = "SELECT replica,datasource FROM SynchroReplica AS replica JOIN SynchroDataSource AS datasource ON replica.sync_source_id=datasource.id WHERE replica.dest_class = :dest_class AND replica.dest_id = :dest_id";
			$oReplicaSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), array() /* order by*/, array('dest_class' => get_class($this), 'dest_id' => $this->GetKey()));
			$this->m_oMasterReplicaSet = $oReplicaSet;
		}
		else
		{
			$this->m_oMasterReplicaSet->Rewind();		
		}
		return $this->m_oMasterReplicaSet;
	}
	
	public function GetSynchroReplicaFlags($sAttCode, &$aReason)
	{
		$iFlags = OPT_ATT_NORMAL;
		$oSet = $this->GetMasterReplica();
		while($aData = $oSet->FetchAssoc())
		{
			if ($aData['datasource']->GetKey() == SynchroDataSource::GetCurrentTaskId())
			{
				// Ignore the current task (check to write => ok)
				continue;
			}
			// Assumption: $aData['datasource'] will not be null because the data source id is always set...
			$oReplica = $aData['replica'];
			$oSource = $aData['datasource'];
			$oAttrSet = $oSource->Get('attribute_list');
			while($oSyncAttr = $oAttrSet->Fetch())
			{
				if (($oSyncAttr->Get('attcode') == $sAttCode) && ($oSyncAttr->Get('update') == 1) && ($oSyncAttr->Get('update_policy') == 'master_locked'))
				{
					$iFlags |= OPT_ATT_SLAVE;
					$sUrl = $oSource->GetApplicationUrl($this, $oReplica);
					$aReason[] = array('name' => $oSource->GetName(), 'description' => $oSource->Get('description'), 'url_application' => $sUrl);
				}
			}
		}
		return $iFlags;
	}

	public function InSyncScope()
	{
		return true;

		// TODO - FINALIZE THIS OPTIMIZATION
		//
		// Optimization: cache the list of Data Sources and classes candidates for synchro
		//
		static $aSynchroClasses = null;
		if (is_null($aSynchroClasses))
		{
			$aSynchroClasses = array();
			$sOQL = "SELECT SynchroDataSource AS datasource";
			$oSourceSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), array() /* order by*/, array());
			while($oSource = $oSourceSet->Fetch())
			{
				$sTarget = $oSource->Get('scope_class');
				$aSynchroClasses[] = $oSource;
			}
		}
		// to be continued...
	}
}


?>
