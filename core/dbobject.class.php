<?php
// Copyright (C) 2010-2019 Combodo SARL
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
 * All objects to be displayed in the application (either as a list or as details)
 * must implement this interface.
 *
 * @internal this interface is implemented by DBObject, you should extends DBObject instead of implementing iDisplay
 */
interface iDisplay
{

	/**
	 * Maps the given context parameter name to the appropriate filter/search code for this class
	 * @param string $sContextParam Name of the context parameter, i.e. 'org_id'
	 * @return string Filter code, i.e. 'customer_id'
	 */
	public static function MapContextParam($sContextParam);
	/**
	 * This function returns a 'hilight' CSS class, used to hilight a given row in a table
	 * There are currently (i.e defined in the CSS) 4 possible values HILIGHT_CLASS_CRITICAL,
	 * HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE
	 * To Be overridden by derived classes
	 * @param void
	 * @return String The desired higlight class for the object/row
	 */
	public function GetHilightClass();
	/**
	 * Returns the relative path to the page that handles the display of the object
	 * @return string
	 */
	public static function GetUIPage();
	/**
	 * Displays the details of the object
	 */
	public function DisplayDetails(WebPage $oPage, $bEditMode = false);
}

/**
 * Class dbObject: the root of persistent classes
 *
 * @copyright   Copyright (C) 2010-2016 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('metamodel.class.php');
require_once('deletionplan.class.inc.php');
require_once('mutex.class.inc.php');


/**
 * A persistent object, as defined by the metamodel 
 *
 * @package     iTopORM
 * @api
 * @overwritable-hook
 */
abstract class DBObject implements iDisplay
{
	private static $m_aMemoryObjectsByClass = array();

	/** @var array class => array of ('table' => array of (array of <sql_value>)) */
	private static $m_aBulkInsertItems = array();
	/** @var array class => array of ('table' => array of <sql_column>) */
	private static $m_aBulkInsertCols = array();
  	private static $m_bBulkInsert = false;

	/** @var bool true IF the object is mapped to a DB record */
	protected $m_bIsInDB = false;
	protected $m_iKey = null;
	/** @var array attcode => value : corresponding current value (the new value passed to {@see DBObject::Set()}). Reset during {@see DBObject::DBUpdate()} */
	private $m_aCurrValues = array();
	/** @var array attcode => value : previous values before the {@see DBObject::Set()} call. Array is reset at the end of {@see DBObject::DBUpdate()} */
	protected $m_aOrigValues = array();

	protected $m_aExtendedData = null;

    /**
     * @var bool Is dirty (true) if a modification is ongoing.
     *
     * @internal The object may have incorrect external keys, then any attempt of reload must be avoided
     */
	private $m_bDirty = false;

	/**
	 * @var boolean|null true if the object has been verified and is consistent with integrity rules.
	 *           If null, then the check has to be performed again to know the status
	 * @see CheckToWrite()
	 */
	private $m_bCheckStatus = null;
	/**
	 * @var null|boolean true if cannot be saved because of security reason
	 * @see CheckToWrite()
	 */
	protected $m_bSecurityIssue = null;
	/**
	 * @var null|string[] list of issues preventing DB write
	 * @see CheckToWrite()
	 */
	protected $m_aCheckIssues = null;
	/**
	 * @var null|string[] list of warnings thrown during DB write
	 * @see CheckToWrite()
	 * @since 2.6.0 N°659 uniqueness constraints
	 */
	protected $m_aCheckWarnings = null;
	protected $m_aDeleteIssues = null;

	/** @var bool Compound objects can be partially loaded */
	private $m_bFullyLoaded = false;
	/** @var array Compound objects can be partially loaded, array of sAttCode */
	private $m_aLoadedAtt = array();
	/** @var array list of (potentially) modified sAttCodes */
	protected $m_aTouchedAtt = array();
	/**
	 * @var array real modification status
	 * for each attCode can be:
	 *   * unset => don't know,
	 *   * true => modified,
	 *   * false => not modified (the same value as the original value was set)
	 */
	protected $m_aModifiedAtt = array();
	/**
	 * @var array attname => value : value before the last {@see DBObject::Set()} call. Set at the beginning of {@see DBObject::DBUpdate()}.
	 * @see DBObject::ListPreviousValuesForUpdatedAttributes() getter for this attribute
	 * @since 2.7.0 N°2293
	 */
	protected $m_aPreviousValuesForUpdatedAttributes;
	/**
	 * @var array Set of Synch data related to this object
	 * <ul>
	 * <li>key: sourceId
	 * <li>value : array of source, attributes, replica
	 * </ul>
	 *
	 * @see #GetSynchroData
	 */
	protected $m_aSynchroData = null;
	protected $m_sHighlightCode = null;
	protected $m_aCallbacks = array();


    /**
     * DBObject constructor.
     *
     * You should preferably use MetaModel::NewObject() instead of this constructor.
     * The whole collection of parameters is [*optional*] please refer to DBObjectSet::FromRow()
     *
     * @internal The availability of this method is not guaranteed in the long term, you should preferably use MetaModel::NewObject().
     * @see MetaModel::NewObject()
     *
     * @param null|array   $aRow                If given : DBObjectSet::FromRow() will be used to fetch the object
     * @param string       $sClassAlias
     * @param null|array   $aAttToLoad
     * @param null|array   $aExtendedDataSpec
     *
     * @throws CoreException
     */
	public function __construct($aRow = null, $sClassAlias = '', $aAttToLoad = null, $aExtendedDataSpec = null)
	{
		if (!empty($aRow))
		{
			$this->FromRow($aRow, $sClassAlias, $aAttToLoad, $aExtendedDataSpec);
			$this->m_bFullyLoaded = $this->IsFullyLoaded();
			$this->m_aTouchedAtt = array();
			$this->m_aModifiedAtt = array();
			return;
		}
		// Creation of a brand new object
		//

		$this->m_iKey = self::GetNextTempId(get_class($this));

		// set default values
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode=>$oAttDef)
		{
			$this->m_aCurrValues[$sAttCode] = $this->GetDefaultValue($sAttCode);
			$this->m_aOrigValues[$sAttCode] = null;
			if ($oAttDef->IsExternalField() || ($oAttDef instanceof AttributeFriendlyName))
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

		$this->UpdateMetaAttributes();
	}

	/**
	 * Update meta-attributes depending on the given attribute list
	 *
     * @internal
     *
     * @param array|null $aAttCodes List of att codes
     *
	 * @throws \CoreException
	 */
	protected function UpdateMetaAttributes($aAttCodes = null)
	{
		if (is_null($aAttCodes))
		{
			$aAttCodes = MetaModel::GetAttributesList(get_class($this));
		}
		foreach ($aAttCodes as $sAttCode)
		{
			foreach (MetaModel::ListMetaAttributes(get_class($this), $sAttCode) as $sMetaAttCode => $oMetaAttDef)
			{
				/** @var \AttributeMetaEnum $oMetaAttDef */
				$this->_Set($sMetaAttCode, $oMetaAttDef->MapValue($this));
			}
		}
	}

    /**
     * Mark the object as dirty
     *
     * Once dirty the object may be written to the DB, it is NOT possible to reload it
     * or at least not possible to reload it the same way
     *
     * @internal
     */
	public function RegisterAsDirty()
	{
		$this->m_bDirty = true;
	}

    /**
     * Whether the object is already persisted in DB or not.
     * 
     * @api
     * 
     * @return bool
     */
	public function IsNew()
	{
		return (!$this->m_bIsInDB);
	}

    /**
     * Returns an Id for memory objects
     * 
     * @internal
     * 
     * @param string $sClass
     *
     * @return int
     * @throws CoreException
     */
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

    /**
     * HTML String representation of the object
     *
     * Only a few meaningful information will be returned.
     * This representation is for debugging purposes, and is subject to change.
     * The returned string is raw HTML
     *
     * @return string
     * @throws CoreException
     */
	public function __toString()
	{
        $sRet = '';
        $sClass = get_class($this);
        $sRootClass = MetaModel::GetRootClass($sClass);
        $iPKey = $this->GetKey();
        $sFriendlyname = $this->GetAsHTML('friendlyname');
        $sRet .= "<b title=\"$sRootClass\">$sClass</b>::$iPKey ($sFriendlyname)<br/>\n";
        return $sRet;
	}
	
    /**
     * Alias of DBObject::Reload()
     *
     * Restore initial values
     *
     * @see Reload()
     *
     * @throws CoreException
     */
	public function DBRevert()
	{
		$this->Reload();
	}

    /**
     * Is the current instance fully or partially loaded.
     *
     * This method compute the state in realtime.
     * In almost every case it is preferable to use DBObject::m_bFullyLoaded.
     *
     * @internal
     * @see m_bFullyLoaded
     * 
     * @return bool
     * @throws CoreException
     */
	protected function IsFullyLoaded()
	{
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode=>$oAttDef)
		{
			if (!$oAttDef->LoadInObject()) continue;
			if (!isset($this->m_aLoadedAtt[$sAttCode]) || !$this->m_aLoadedAtt[$sAttCode])
			{
				return false;
			}
		}
		return true;
	}

	/**
     * Reload the object from the DB.
     *
     * This is mostly used after a lazy load (automatically performed by the framework)
     * This will erase any pending changes.
     *
	 * @param bool $bAllowAllData @deprecated This parameter is ignored!!
	 *
	 * @throws CoreException
	 */
	public function Reload($bAllowAllData = false)
	{
		assert($this->m_bIsInDB);
		$aRow = MetaModel::MakeSingleRow(get_class($this), $this->m_iKey, false /* must be found */, true /* AllowAllData */);
		if (empty($aRow))
		{
            $sErrorMessage = "Failed to reload object of class '".get_class($this)."', id = ".$this->m_iKey.', DBIsReadOnly = '.(int) MetaModel::DBIsReadOnly();

		    IssueLog::Error("$sErrorMessage:\n".MyHelpers::get_callstack_text(1));
            throw new CoreException("$sErrorMessage (see the log for more information)");

		}
		$this->FromRow($aRow);

		// Process linked set attributes
		//
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode=>$oAttDef)
		{
			if (!$oAttDef->IsLinkSet()) continue;

			$this->m_aCurrValues[$sAttCode] = $oAttDef->GetDefaultValue($this);
			$this->m_aOrigValues[$sAttCode] = clone $this->m_aCurrValues[$sAttCode];
			$this->m_aLoadedAtt[$sAttCode] = true;
		}

		$this->m_bFullyLoaded = true;
		$this->m_aTouchedAtt = array();
		$this->m_aModifiedAtt = array();
	}

    /**
     * Initialize the instance against a given structured array
     *
     * @internal
     * @see GetExtendedData() extended data
     *
     * @param array        $aRow                an array under the form: `<AttributeCode> => <value>`
     * @param string       $sClassAlias         if not null, it is preprended to the `<AttributeCode>` part of $aRow
     * @param null|array   $aAttToLoad          List of attribute that will be fetched against the database anyway
     * @param null|array   $aExtendedDataSpec   List of attribute that will be marked as DBObject::GetExtendedData()
     *
     * @return bool
     * @throws CoreException
     */
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

			if (!$oAttDef->LoadInObject()) continue;

			unset($value);
			$bIsDefined = false;
			if ($oAttDef->LoadFromDB())
			{
				// Note: we assume that, for a given attribute, if it can be loaded,
				// then one column will be found with an empty suffix, the others have a suffix
				// Take care: the function isset will return false in case the value is null,
				// which is something that could happen on open joins
				$sAttRef = $sClassAlias.$sAttCode;

				if (array_key_exists($sAttRef, $aRow))
				{
					$value = $oAttDef->FromSQLToValue($aRow, $sAttRef);
					$bIsDefined = true;
				}
			}
			else
			{
				/** @var \AttributeCustomFields $oAttDef */
				$value = $oAttDef->ReadValue($this);
				$bIsDefined = true;
			}

			if ($bIsDefined)
			{
				$this->m_aCurrValues[$sAttCode] = $value;
				if (is_object($value))
				{
					$this->m_aOrigValues[$sAttCode] = clone $value;
				}
				else
				{
					$this->m_aOrigValues[$sAttCode] = $value;
				}
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

    /**
     * Protected raw Setter
     *
     * This method is an internal plumbing : it sets the value without doing any of the required processes.
     * The exposed API Setter is DBObject::Set()
     *
     * @internal
     * @see Set()
     * 
     * @param string $sAttCode
     * @param mixed $value
     */
	protected function _Set($sAttCode, $value)
	{
		$this->m_aCurrValues[$sAttCode] = $value;
		$this->m_aTouchedAtt[$sAttCode] = true;
		unset($this->m_aModifiedAtt[$sAttCode]);
	}


    /**
     * Attributes setter
     *
     * Set $sAttCode to $value.
     * The value must be valid according to the type of attribute.
     * The value will not be recorded into the DB until DBObject::DBWrite() is called.
     *
     * @api
     * @see DBWrite()
     *
     * @param string $sAttCode
     * @param mixed $value
     *
     * @return bool
     * @throws CoreException
     * @throws CoreUnexpectedValue
     */
	public function Set($sAttCode, $value)
	{
		if ($sAttCode == 'finalclass')
		{
			// Ignore it - this attribute is set upon object creation and that's it
			return false;
		}

		$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);

		if (!$oAttDef->IsWritable())
		{
			$sClass = get_class($this);
			throw new Exception("Attempting to set the value on the read-only attribute $sClass::$sAttCode");
		}

		if ($this->m_bIsInDB && !$this->m_bFullyLoaded && !$this->m_bDirty)
		{
			// First time Set is called... ensure that the object gets fully loaded
			// Otherwise we would lose the values on a further Reload
			//           + consistency does not make sense !
			$this->Reload();
		}

		if ($oAttDef->IsExternalKey())
		{
			if (is_object($value))
			{
				// Setting an external key with a whole object (instead of just an ID)
				// let's initialize also the external fields that depend on it
				// (useful when building objects in memory and not from a query)
				/** @var \AttributeExternalKey $oAttDef */
				if ( (get_class($value) != $oAttDef->GetTargetClass()) && (!is_subclass_of($value, $oAttDef->GetTargetClass())))
				{
					throw new CoreUnexpectedValue("Trying to set the value of '$sAttCode', to an object of class '".get_class($value)."', whereas it's an ExtKey to '".$oAttDef->GetTargetClass()."'. Ignored");
				}

				foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sCode => $oDef)
				{
					/** @var \AttributeExternalField $oDef */
					if ($oDef->IsExternalField() && ($oDef->GetKeyAttCode() == $sAttCode))
					{
						/** @var \DBObject $value */
						$this->m_aCurrValues[$sCode] = $value->Get($oDef->GetExtAttCode());
						$this->m_aLoadedAtt[$sCode] = true;
					}
				}
			}
			else if ($this->m_aCurrValues[$sAttCode] != $value)
			{
				// Setting an external key, but no any other information is available...
				// Invalidate the corresponding fields so that they get reloaded in case they are needed (See Get())
				foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sCode => $oDef)
				{
					/** @var \AttributeExternalKey $oDef */
					if ($oDef->IsExternalField() && ($oDef->GetKeyAttCode() == $sAttCode))
					{
						$this->m_aCurrValues[$sCode] = $this->GetDefaultValue($sCode);
						unset($this->m_aLoadedAtt[$sCode]);
					}
				}
			}
		}
		if ($oAttDef->IsLinkSet() && ($value != null))
		{
			$realvalue = clone $this->m_aCurrValues[$sAttCode];
			$realvalue->UpdateFromCompleteList($value);
		}
		else
		{
			$realvalue = $oAttDef->MakeRealValue($value, $this);
		}
		$this->_Set($sAttCode, $realvalue);

		$this->UpdateMetaAttributes(array($sAttCode));

		// The object has changed, reset caches
		$this->m_bCheckStatus = null;

		// Make sure we do not reload it anymore... before saving it
		$this->RegisterAsDirty();

		// This function is eligible as a lifecycle action: returning true upon success is a must
		return true;
	}

	/**
     * Helper to set a value only if it is currently undefined
     *
     * Call Set() only of the internal representation of the attribute is null.
     *
     * @api
     * @see Set()
     *
	 * @param string $sAttCode
	 * @param mixed $value
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \Exception
	 * @since 2.6.0
	 */
	public function SetIfNull($sAttCode, $value)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		$oCurrentValue = $this->Get($sAttCode);
		if ($oAttDef->IsNull($oCurrentValue))
		{
			$this->Set($sAttCode, $value);
		}
	}

    /**
     * Helper to set a value that fits the attribute max size
     *
     * compare $sValue against the field's max size in the database, and truncate it's ending in order to make it fit.
     * If $sValue is short enough, nothing is done.
     *
     * @api
     *
     * @param string $sAttCode
     * @param string $sValue
     *
     * @throws CoreException
     * @throws CoreUnexpectedValue
     */
	public function SetTrim($sAttCode, $sValue)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		$iMaxSize = $oAttDef->GetMaxSize();
		if ($iMaxSize && (strlen($sValue) > $iMaxSize))
		{
			$sValue = substr($sValue, 0, $iMaxSize);
		}
		$this->Set($sAttCode, $sValue);
	}

    /**
     * Get the label of an attribute.
     * 
     * Shortcut to the field's AttributeDefinition->GetLabel()
     *
     * @api
     * 
     * @param string $sAttCode
     *
     * @return string
     *
     * @throws Exception
     */
	public function GetLabel($sAttCode)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		return $oAttDef->GetLabel();
	}

	/**
	 * Getter : get a value from the current object of from a related object
	 *
	 * Get the value of the attribute $sAttCode
	 * This call may involve an object reload if the object was not completely loaded (lazy loading)
	 *
	 * @api
	 *
	 * @param string $sAttCode Could be an extended attribute code in the form extkey_id->anotherkey_id->remote_attr
	 *
	 * @return mixed|string
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \Exception
	 */
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

			$oExtFieldAtt = MetaModel::FindExternalField(get_class($this), $sExtKeyAttCode, $sRemoteAttCode);
			if (!is_null($oExtFieldAtt))
			{
				/** @var \AttributeExternalField $oExtFieldAtt */
				return $this->GetStrict($oExtFieldAtt->GetCode());
			}
			else
			{
				$oKeyAttDef = MetaModel::GetAttributeDef(get_class($this), $sExtKeyAttCode);
				/** @var \AttributeExternalKey $oKeyAttDef */
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
	}

    /**
     * Getter : get values from the current object
     *
     * @internal
     * @see Get
     * 
     * @param string $sAttCode
     *
     * @return int|mixed|null
     * @throws ArchivedObjectException
     * @throws CoreException
     */
	public function GetStrict($sAttCode)
	{
		if ($sAttCode == 'id')
		{
			return $this->m_iKey;
		}

		$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);

		if (!$oAttDef->LoadInObject())
		{
			$value = $oAttDef->GetValue($this);
		}
		else
		{
			if (isset($this->m_aLoadedAtt[$sAttCode]))
			{
				// Standard case... we have the information directly
			}
			elseif ($this->m_bIsInDB && !$this->m_bDirty)
			{
				// Lazy load (polymorphism): complete by reloading the entire object
				// #@# non-scalar attributes.... handle that differently?
				$oKPI = new ExecutionKPI();
				$this->Reload();
				$oKPI->ComputeStats('Reload', get_class($this).'/'.$sAttCode);
			}
			elseif ($sAttCode == 'friendlyname')
			{
				// The friendly name is not computed and the object is dirty
				// Todo: implement the computation of the friendly name based on sprintf()
				// 
				$this->m_aCurrValues[$sAttCode] = '';
			}
			else
			{
				// Not loaded... is it related to an external key?
				if ($oAttDef->IsExternalField())
				{
					// Let's get the object and compute all of the corresponding attributes
					// (i.e not only the requested attribute)
					//
					/** @var \AttributeExternalField $oAttDef */
					$sExtKeyAttCode = $oAttDef->GetKeyAttCode();
	
					if (($iRemote = $this->Get($sExtKeyAttCode)) && ($iRemote > 0)) // Objects in memory have negative IDs
					{
						$oExtKeyAttDef = MetaModel::GetAttributeDef(get_class($this), $sExtKeyAttCode);
						// Note: "allow all data" must be enabled because the external fields are always visible
						//       to the current user even if this is not the case for the remote object
						//       This is consistent with the behavior of the lists
						/** @var \AttributeExternalKey $oExtKeyAttDef */
						$oRemote = MetaModel::GetObject($oExtKeyAttDef->GetTargetClass(), $iRemote, true, true);
					}
					else
					{
						$oRemote = null;
					}
	
					foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sCode => $oDef)
					{
						/** @var \AttributeExternalField $oDef */
						if ($oDef->IsExternalField() && ($oDef->GetKeyAttCode() == $sExtKeyAttCode))
						{
							if ($oRemote)
							{
								$this->m_aCurrValues[$sCode] = $oRemote->Get($oDef->GetExtAttCode());
							}
							else
							{
								$this->m_aCurrValues[$sCode] = $this->GetDefaultValue($sCode);
							}
							$this->m_aLoadedAtt[$sCode] = true;
						}
					}
				}
			}
			$value = $this->m_aCurrValues[$sAttCode];
		}

		if ($value instanceof ormLinkSet)
		{
			$value->Rewind();
		}
		return $value; 
	}

    /**
     * @see  \DBObject::ListPreviousValuesForUpdatedAttributes() to get previous values anywhere in the CRUD stack
     * @see https://www.itophub.io/wiki/page?id=latest%3Acustomization%3Asequence_crud iTop CRUD stack documentation
     *
     * @param string $sAttCode
     *
     * @return mixed|null the value as it was before changed with {@see DBObject::Set()}.
     *        Returns null if the attribute wasn't changed.
     *        Values are reset during {@see DBObject::DBUpdate()}
     *
     * @throws CoreException if the attribute is unknown for the current object
     * @uses DBObject::$m_aOrigValues
     */
	public function GetOriginal($sAttCode)
	{
		if (!array_key_exists($sAttCode, MetaModel::ListAttributeDefs(get_class($this))))
		{
			throw new CoreException("Unknown attribute code '$sAttCode' for the class ".get_class($this));
		}
		$aOrigValues = $this->m_aOrigValues;
		return isset($aOrigValues[$sAttCode]) ? $aOrigValues[$sAttCode] : null;
	}

    /**
     * Returns the default value of the $sAttCode.
     *
     * Returns the default value of the given attribute.
     * 
     * @internal
     *
     * @param string $sAttCode
     *
     * @return mixed
     *
     * @throws Exception
     */
	public function GetDefaultValue($sAttCode)
    {
        $oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
        return $oAttDef->GetDefaultValue($this);
    }

	/**
	 * Returns data loaded by the mean of a dynamic and explicit JOIN
     *
     * @internal
     *
     * @return array|null
	 */	 
	public function GetExtendedData()
	{
		return $this->m_aExtendedData;
	}
	
	/**
     * Set the HighlightCode
     *
     * Switch to $sCode if it has a greater rank than the current code
     *
     * @internal
     * @used-by DBObject::ComputeHighlightCode()
     * @see m_sHighlightCode
     *
	 * @param string $sCode
	 *
	 * @return void
	 */
	protected function SetHighlightCode($sCode)
	{
		$aHighlightScale = MetaModel::GetHighlightScale(get_class($this));
		$fCurrentRank = 0.0;
		if (($this->m_sHighlightCode !== null) && array_key_exists($this->m_sHighlightCode, $aHighlightScale))
		{
			$fCurrentRank = $aHighlightScale[$this->m_sHighlightCode]['rank'];
		}
				
		if (array_key_exists($sCode, $aHighlightScale))
		{
			$fRank = $aHighlightScale[$sCode]['rank'];
			if ($fRank > $fCurrentRank)
			{
				$this->m_sHighlightCode = $sCode;
			}
		}
	}
	
	/**
	 * Get the current HighlightCode
     * 
     * @internal
     * @used-by DBObject::ComputeHighlightCode()
     * 
	 * @return string|null The Hightlight code (null if none set, meaning rank = 0)
	 */
	protected function GetHighlightCode()
	{
		return $this->m_sHighlightCode;
	}

    /**
     * Compute the highlightCode
     * 
     * @example When TTR, then TTR of a UserRequest is greater thant a defined scale, the item is highlighted in the listings
     *
     * @interal
     *
     * @return string|null The Hightlight code (null if none set, meaning rank = 0)
     *
     * @throws ArchivedObjectException
     * @throws CoreException
     */
	protected function ComputeHighlightCode()
	{
		// First if the state defines a HiglightCode, apply it
		$sState = $this->GetState();
		if ($sState != '')
		{
			$sCode = MetaModel::GetHighlightCode(get_class($this), $sState);
			$this->SetHighlightCode($sCode);
		}
		// The check for each StopWatch if a HighlightCode is effective
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
		{
			if ($oAttDef instanceof AttributeStopWatch)
			{
				$oStopWatch = $this->Get($sAttCode);
				$sCode = $oStopWatch->GetHighlightCode();
				if ($sCode !== '')
				{
					$this->SetHighlightCode($sCode);
				}
			}
		}
		return $this->GetHighlightCode();
	}

    /**
     * Updates the value of an external field by (re)loading the object
     * corresponding to the external key and getting the value from it
     *
     * UNUSED ?
     * 
     * @internal
     * @todo: check if this is dead code.
     *
     * @param string $sAttCode Attribute code of the external field to update
     *
     * @return void
     *
     * @throws ArchivedObjectException
     * @throws CoreException
     * @throws CoreUnexpectedValue
     */
	protected function UpdateExternalField($sAttCode)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		if ($oAttDef->IsExternalField())
		{
			/** @var \AttributeExternalField $oAttDef */
			$sTargetClass = $oAttDef->GetTargetClass();
			$objkey = $this->Get($oAttDef->GetKeyAttCode());
			// Note: "allow all data" must be enabled because the external fields are always visible
			//       to the current user even if this is not the case for the remote object
			//       This is consistent with the behavior of the lists
			$oObj = MetaModel::GetObject($sTargetClass, $objkey, true, true);
			if (is_object($oObj))
			{
				$value = $oObj->Get($oAttDef->GetExtAttCode());
				$this->Set($sAttCode, $value);
			}
		}
	}

	/**
	 * Overridable callback
     *
	 * @internal this method is elligible to the "overwritable-hook" tag. But it is willingly excluded.
     * @used-by DoComputeValues()
	 */
	public function ComputeValues()
	{
	}

	/**
	 * Compute scalar attributes that depend on any other type of attribute
     * 
     * if you want to customize this behaviour, overwrite @see ComputeValues()
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 *
	 * @internal
	 */
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
		
		// Set the "null-not-allowed" datetimes (and dates) whose value is not initialized
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
		{
			// AttributeDate is derived from AttributeDateTime
			if (($oAttDef instanceof AttributeDateTime) && (!$oAttDef->IsNullAllowed()) && ($this->Get($sAttCode) == $oAttDef->GetNullValue()))
			{
				$this->Set($sAttCode, date($oAttDef->GetInternalFormat()));
			}
		}
		
		$this->ComputeValues();
	}

    /**
     * @api
     * 
     * @param string $sAttCode
     * @param bool   $bLocalize
     *
     * @return string $sAttCode formatted as HTML for the console details forms (when viewing, not when editing !)
     *          The returned string is already escaped, and as such is protected against XSS
     *          The markup relies on a few assumptions (CSS) that could change without notice
     *
     * @throws ArchivedObjectException
     * @throws CoreException
     * @throws DictExceptionMissingString
     *
     * @see \Combodo\iTop\Form\Field\Field for rendering in portal forms
     */
	public function GetAsHTML($sAttCode, $bLocalize = true)
	{
		$sClass = get_class($this);
		$oAtt = MetaModel::GetAttributeDef($sClass, $sAttCode);

		if ($oAtt->IsExternalKey(EXTKEY_ABSOLUTE))
		{
			//return $this->Get($sAttCode.'_friendlyname');
			/** @var \AttributeExternalKey $oAtt */
			$sTargetClass = $oAtt->GetTargetClass(EXTKEY_ABSOLUTE);
			$iTargetKey = $this->Get($sAttCode);
			if ($iTargetKey < 0)
			{
				// the key points to an object that exists only in memory... no hyperlink points to it yet
				return '';
			}
			else
			{
				$sHtmlLabel = htmlentities($this->Get($sAttCode.'_friendlyname'), ENT_QUOTES, 'UTF-8');
				$bArchived = $this->IsArchived($sAttCode);
				$bObsolete = $this->IsObsolete($sAttCode);
				return $this->MakeHyperLink($sTargetClass, $iTargetKey, $sHtmlLabel, null, true, $bArchived, $bObsolete);
			}
		}

		// That's a standard attribute (might be an ext field or a direct field, etc.)
		return $oAtt->GetAsHTML($this->Get($sAttCode), $this, $bLocalize);
	}

    /**
     * Get the value as it must be in the edit areas (forms)
     * 
     * Makes a raw text representation of the value.
     *
     * @internal
     * 
     * @param string $sAttCode
     *
     * @return int|mixed|string
     *
     * @throws ArchivedObjectException
     * @throws CoreException
     */
	public function GetEditValue($sAttCode)
	{
		$sClass = get_class($this);
		$oAtt = MetaModel::GetAttributeDef($sClass, $sAttCode);

		if ($oAtt->IsExternalKey())
		{
			/** @var \AttributeExternalKey $oAtt */
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
			$sEditValue = $oAtt->GetEditValue($this->Get($sAttCode), $this);
		}
		return $sEditValue;
	}

    /**
     * Get $sAttCode formatted as XML
     * 
     * The returned value is a text that is suitable for insertion into an XML node.
     * Depending on the type of attribute, the returned text is either:
     *   * A literal, with XML entities already escaped,
     *   * XML
     *
     * @api
     * 
     * @param string $sAttCode
     * @param bool   $bLocalize
     *
     * @return mixed
     *
     * @throws ArchivedObjectException
     * @throws CoreException
     */
	public function GetAsXML($sAttCode, $bLocalize = true)
	{
		$oAtt = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		return $oAtt->GetAsXML($this->Get($sAttCode), $this, $bLocalize);
	}

    /**
     * Get $sAttCode formatted as CSV
     *
     * @api
     *
     * @param string $sAttCode
     * @param string $sSeparator
     * @param string $sTextQualifier
     * @param bool   $bLocalize
     * @param bool   $bConvertToPlainText
     *
     * @return string
     *
     * @throws ArchivedObjectException
     * @throws CoreException
     */
	public function GetAsCSV($sAttCode, $sSeparator = ',', $sTextQualifier = '"', $bLocalize = true, $bConvertToPlainText = false)
	{
		$oAtt = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		return $oAtt->GetAsCSV($this->Get($sAttCode), $sSeparator, $sTextQualifier, $this, $bLocalize, $bConvertToPlainText);
	}

    /**
     * 
     * @see GetAsHTML()
     * @see GetOriginal()
     * 
     * @param string $sAttCode
     * @param bool   $bLocalize
     *
     * @return string
     * @throws CoreException
     */
	public function GetOriginalAsHTML($sAttCode, $bLocalize = true)
	{
		$oAtt = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		return $oAtt->GetAsHTML($this->GetOriginal($sAttCode), $this, $bLocalize);
	}

    /**
     *
     * @see GetAsXML()
     * @see GetOriginal()
     *
     * @param string $sAttCode
     * @param bool   $bLocalize
     *
     * @return mixed
     * @throws CoreException
     */
	public function GetOriginalAsXML($sAttCode, $bLocalize = true)
	{
		$oAtt = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		return $oAtt->GetAsXML($this->GetOriginal($sAttCode), $this, $bLocalize);
	}

    /**
     *
     * @see GetAsCSV()
     * @see GetOriginal()
     *
     * @param string $sAttCode
     * @param string $sSeparator
     * @param string $sTextQualifier
     * @param bool   $bLocalize
     * @param bool   $bConvertToPlainText
     *
     * @return string
     * @throws CoreException
     */
	public function GetOriginalAsCSV($sAttCode, $sSeparator = ',', $sTextQualifier = '"', $bLocalize = true, $bConvertToPlainText = false)
	{
		$oAtt = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		return $oAtt->GetAsCSV($this->GetOriginal($sAttCode), $sSeparator, $sTextQualifier, $this, $bLocalize, $bConvertToPlainText);
	}

    /**
     * Return an hyperlink pointing to  <$sObjClass, $sObjKey>
     *
     * @internal
     *
     * @param string      $sObjClass
     * @param string      $sObjKey
     * @param string      $sHtmlLabel Label with HTML entities escaped (< escaped as &lt;)
     * @param null|string $sUrlMakerClass if not null, the class must expose a public method ''MakeObjectUrl(string $sObjClass, string $sObjKey)''
     * @param bool        $bWithNavigationContext
     * @param bool        $bArchived
     * @param bool        $bObsolete
     *
     * @return string the HTML markup pointing to  <$sObjClass, $sObjKey>
     *
     * @throws \ArchivedObjectException
     * @throws \CoreException
     * @throws \DictExceptionMissingString
     */
	public static function MakeHyperLink($sObjClass, $sObjKey, $sHtmlLabel = '', $sUrlMakerClass = null, $bWithNavigationContext = true, $bArchived = false, $bObsolete = false)
	{
		if ($sObjKey <= 0) return '<em>'.Dict::S('UI:UndefinedObject').'</em>'; // Objects built in memory have negative IDs

		// Safety net
		//
		if (empty($sHtmlLabel))
		{
			// If the object if not issued from a query but constructed programmatically
			// the label may be empty. In this case run a query to get the object's friendly name
			$oTmpObj = MetaModel::GetObject($sObjClass, $sObjKey, false);
			if (is_object($oTmpObj))
			{
				$sHtmlLabel = $oTmpObj->GetName();
			}
			else
			{
				// May happen in case the target object is not in the list of allowed values for this attribute
				$sHtmlLabel = "<em>$sObjClass::$sObjKey</em>";
			}
		}
		$sHint = MetaModel::GetName($sObjClass)."::$sObjKey";
		$sUrl = ApplicationContext::MakeObjectUrl($sObjClass, $sObjKey, $sUrlMakerClass, $bWithNavigationContext);

		$bClickable = !$bArchived || utils::IsArchiveMode();
		if ($bArchived)
		{
			$sSpanClass = 'archived';
			$sFA = 'fa-archive object-archived';
			$sHint = Dict::S('ObjectRef:Archived');
		}
		elseif ($bObsolete)
		{
			$sSpanClass = 'obsolete';
			$sFA = 'fa-eye-slash object-obsolete';
			$sHint = Dict::S('ObjectRef:Obsolete');
		}
		else
		{
			$sSpanClass = '';
			$sFA = '';
		}
		if ($sFA == '')
		{
			$sIcon = '';
		}
		else
		{
			if ($bClickable)
			{
				$sIcon = "<span class=\"object-ref-icon fas $sFA fa-1x fa-fw\"></span>";
			}
			else
			{
				$sIcon = "<span class=\"object-ref-icon-disabled fas $sFA fa-1x fa-fw\"></span>";
			}
		}

		if ($bClickable && (strlen($sUrl) > 0))
		{
			$sHLink = "<a class=\"object-ref-link\" href=\"$sUrl\">$sIcon$sHtmlLabel</a>";
		}
		else
		{
			$sHLink = $sIcon.$sHtmlLabel;
		}
		$sRet = "<span class=\"object-ref $sSpanClass\" title=\"$sHint\">$sHLink</span>";
		return $sRet;
	}

    /**
     * Return an hyperlink pointing to the current DBObject
     *
     * @api
     *
     * @param string $sUrlMakerClass
     * @param bool   $bWithNavigationContext
     * @param string $sLabel
     *
     * @return string
     *
     * @throws ArchivedObjectException
     * @throws CoreException
     * @throws DictExceptionMissingString
     */
	public function GetHyperlink($sUrlMakerClass = null, $bWithNavigationContext = true, $sLabel = null)
	{
	    if($sLabel === null)
        {
            $sLabel = $this->GetName();
        }
		$bArchived = $this->IsArchived();
		$bObsolete = $this->IsObsolete();
		return self::MakeHyperLink(get_class($this), $this->GetKey(), $sLabel, $sUrlMakerClass, $bWithNavigationContext, $bArchived, $bObsolete);
	}

    /**
     * @internal
     * 
     * @param string $sClass
     *
     * @return mixed
     */
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

    /**
     * @internal
     *
     * @return string
     */
	public static function GetUIPage()
	{
		return 'UI.php';
	}




    /**
     * Whether $value is valid as a primary key
     *
     * @internal
     *
     * @param string $value
     *
     * @return bool
     */
	public static function IsValidPKey($value)
	{
	    // this function could be in the metamodel ?
		return ((string)$value === (string)(int)$value);
	}

    /**
     * Primary key Getter
     *
     * Get the id
     *
     * @api
     * 
     * @return int|null
     */
	public function GetKey()
	{
		return $this->m_iKey;
	}

    /**
     * Primary key Setter
     * Usable only for not yet persisted DBObjects
     * 
     * @internal
     *
     * @param int $iNewKey the desired identifier
     *
     * @throws CoreException
     */
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
     * 
     * @api
     *
     * @param boolean $bImgTag If true the result is a full IMG tag (or an empty string if no icon is defined)
     *
     * @return string Either the full IMG tag ($bImgTag == true) or just the URL to the icon file
     * @throws ArchivedObjectException
     * @throws CoreException
     */
	public function GetIcon($bImgTag = true)
	{
		$sCode = $this->ComputeHighlightCode();
		if($sCode != '')
		{
			$aHighlightScale = MetaModel::GetHighlightScale(get_class($this));
			if (array_key_exists($sCode, $aHighlightScale))
			{
				$sIconUrl = $aHighlightScale[$sCode]['icon'];
				if($bImgTag)
				{
					return "<img src=\"$sIconUrl\" style=\"vertical-align:middle\"/>";
				}
				else
				{
					return $sIconUrl;
				}
			}
		}		
		return MetaModel::GetClassIcon(get_class($this), $bImgTag);
	}

	/**
	 * Get the label of a class
	 *
	 * Returns the label as defined in the dictionary for the language of the current user
     *
     * @api 
     *
	 * @return string (empty for default name scheme)
	 */
	public static function GetClassName($sClass)
	{
		$sStringCode = 'Class:'.$sClass;
		return Dict::S($sStringCode, str_replace('_', ' ', $sClass));
	}

	/**
	 * Get the description of a class
	 *
	 * Returns the label as defined in the dictionary for the language of the current user
     *
     * @internal
     *
	 * @param string $sClass
	 *
	 * @return string
	 */
	final static public function GetClassDescription($sClass)
	{
		$sStringCode = 'Class:'.$sClass.'+';
		return Dict::S($sStringCode, '');
	}

	/**
	 * Helper to get the friendly name in a safe manner for displaying inside a web page
	 *
     * @api
	 *
	 * @return string
	 * @throws \CoreException
	 */
	public function GetName()
	{
		return htmlentities($this->GetRawName(), ENT_QUOTES, 'UTF-8');
	}

	/**
     * Helper to get the friendly name
     *
     * This is not safe for displaying inside a web page since the " < > characters are not escaped.
     * In example, the name may contain some XSS script instructions.
	 * Use this function only for internal computations or for an output to a non-HTML destination
	 *
     * @api
     *
	 * @return string
	 * @throws \CoreException
	 */
	public function GetRawName()
	{
		return $this->Get('friendlyname');
	}

	/**
     * Helper to get the state
     * 
     * @api
     *
	 * @return mixed|string '' if no state attribute, object representing its value otherwise
	 * @throws \CoreException
	 */
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

    /**
     * Get the label of the current state
     * 
     * @api
     * 
     * @return mixed|string
     *
     * @throws ArchivedObjectException
     * @throws CoreException
     */
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

    /**
     * Get the description of the state
     *
     * @api
     *
     * @return mixed|string
     * @throws ArchivedObjectException
     * @throws CoreException
     */
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
	 * Define attributes read-only from the end-user perspective
	 *
	 * @return array|null List of attcodes
	 */	 	  	 	
	public static function GetReadOnlyAttributes()
	{
		return null;
	}


	/**
	 * Get predefined objects
     * 
	 * The predefined objects will be synchronized with the DB at each install/upgrade
     * As soon as a class has predefined objects, then nobody can create nor delete objects
     *
     * @internal
     *
	 * @return array An array of id => array of attcode => php value(so-called "real value": integer, string, ormDocument, DBObjectSet, etc.)
	 */	 	  	 	
	public static function GetPredefinedObjects()
	{
		return null;
	}

	/**
     * Get the flags for the given state
     *
     * @overwritable-hook You can extend this method in order to provide your own logic. If you do so, rely on the parent as a fallback if you have uncovered $sAttCode
     *
	 * @param string $sAttCode $sAttCode The code of the attribute
	 * @param array  $aReasons To store the reasons why the attribute is read-only (info about the synchro replicas)
	 * @param string $sTargetState The target state in which to evalutate the flags, if empty the current state will be used
	 *
	 * @return integer the binary combination of flags for the given attribute in the given state of the object.
	 * Values can be one of the OPT_ATT_HIDDEN, OPT_ATT_READONLY, OPT_ATT_MANDATORY, ... (see define in metamodel.class.php)
	 * Combine multiple values using the "|" operator, for example `OPT_ATT_READONLY | OPT_ATT_HIDDEN`.
     *
	 * @throws \CoreException
	 *
	 * @see GetInitialStateAttributeFlags for creation
	 */
	public function GetAttributeFlags($sAttCode, &$aReasons = array(), $sTargetState = '')
	{
		$iFlags = 0; // By default (if no life cycle) no flag at all

		$aReadOnlyAtts = $this->GetReadOnlyAttributes();
		if (($aReadOnlyAtts != null) && (in_array($sAttCode, $aReadOnlyAtts)))
		{
			return OPT_ATT_READONLY;
		}

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
		$iSynchroFlags = 0;
		if ($this->InSyncScope())
		{
			$iSynchroFlags = $this->GetSynchroReplicaFlags($sAttCode, $aReasons);
			if ($iSynchroFlags & OPT_ATT_SLAVE)
			{
				$iSynchroFlags |= OPT_ATT_READONLY;
			}
		}
		return $iFlags | $iSynchroFlags; // Combine both sets of flags
	}

    /**
     * Whether the attribute is read-only
     *
     * @internal
     *
     * @param string $sAttCode
     * @param array  $aReasons To store the reasons why the attribute is read-only (info about the synchro replicas)
     *
     * @return int Values can be one of the OPT_ATT_HIDDEN, OPT_ATT_READONLY, OPT_ATT_MANDATORY, ... (see define in metamodel.class.php)
     *
     * @throws \CoreException
     */
	public function IsAttributeReadOnlyForCurrentState($sAttCode, &$aReasons = array())
	{
		$iAttFlags = $this->GetAttributeFlags($sAttCode, $aReasons);

		return ($iAttFlags & OPT_ATT_READONLY);
	}

    /**
     * Returns the set of flags (OPT_ATT_HIDDEN, OPT_ATT_READONLY, OPT_ATT_MANDATORY...)
     * for the given attribute in a transition
     *
     * @internal
     *
     * @param string $sAttCode     $sAttCode The code of the attribute
     * @param string $sStimulus    The stimulus code to apply
     * @param array|null $aReasons To store the reasons why the attribute is read-only (info about the synchro replicas)
     * @param string $sOriginState The state from which to apply $sStimulus, if empty current state will be used
     *
     * @return integer Flags: the binary combination of the flags applicable to this attribute
     * @throws ArchivedObjectException
     * @throws CoreException
     */
    public function GetTransitionFlags($sAttCode, $sStimulus, &$aReasons = array(), $sOriginState = '')
    {
        $iFlags = 0; // By default (if no lifecycle) no flag at all

        $sStateAttCode = MetaModel::GetStateAttributeCode(get_class($this));
        // If no state attribute, there is no lifecycle
        if (empty($sStateAttCode))
        {
            return $iFlags;
        }

        // Retrieving current state if necessary
        if ($sOriginState === '')
        {
            $sOriginState = $this->Get($sStateAttCode);
        }

        // Retrieving attribute flags
        $iAttributeFlags = $this->GetAttributeFlags($sAttCode, $aReasons, $sOriginState);

        // Retrieving transition flags
        $iTransitionFlags = MetaModel::GetTransitionFlags(get_class($this), $sOriginState, $sStimulus, $sAttCode);

        // Merging transition flags with attribute flags
        $iFlags = $iTransitionFlags | $iAttributeFlags;

        return $iFlags;
    }

    /**
     * Returns an array of attribute codes (with their flags) when $sStimulus is applied on the object in the $sOriginState state.
     * Note: Attributes (and flags) from the target state and the transition are combined.
     *
     * @internal
     * 
     * @param string $sStimulus
     * @param string $sOriginState Default is current state
     *
     * @return array
     * @throws CoreException
     */
    public function GetTransitionAttributes($sStimulus, $sOriginState = null)
    {
        $sObjClass = get_class($this);

        // Defining current state as origin state if not specified
        if($sOriginState === null)
        {
            $sOriginState = $this->GetState();
        }

        $aAttributes = MetaModel::GetTransitionAttributes($sObjClass, $sStimulus, $sOriginState);

        return $aAttributes;
    }

	/**
	 * @param string $sAttCode The code of the attribute
	 * @param array $aReasons
	 *
     * @overwritable-hook You can extend this method in order to provide your own logic
     *
	 * @return integer The binary combination of the flags for the given attribute for the current state of the object considered as an INITIAL state.
	 * Values can be one of the OPT_ATT_HIDDEN, OPT_ATT_READONLY, OPT_ATT_MANDATORY, ... (see define in metamodel.class.php)
     *
	 * @throws \CoreException
	 *
	 * @see GetAttributeFlags when modifying the object
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

	/**
	 * Check if the given (or current) value is suitable for the attribute
	 *
     * @api
     * @api-advanced
     *
	 * @param string $sAttCode
	 * @param boolean|string $value true if successful, the error description otherwise
	 *
	 * @return bool|string
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \OQLException
	 *
	 */
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
				/** @var \AttributeExternalKey $oAtt */
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
				$aValues = $oAtt->GetAllowedValues(array('this' => $this));
				if (!array_key_exists($toCheck, $aValues))
				{
					return "Value not allowed [$toCheck]";
				}
			}
		}
		elseif ($oAtt instanceof AttributeTagSet)
		{
			if (is_string($toCheck))
			{
				$oTag = new ormTagSet(get_class($this), $sAttCode, $oAtt->GetMaxItems());
				try
				{
					$oTag->SetValues(explode(' ', $toCheck));
				} catch (Exception $e)
				{
					return "Tag value '$toCheck' is not a valid tag list";
				}

				return true;
			}

			if ($toCheck instanceof ormTagSet)
			{
				return true;
			}

			return "Bad type";
		}
		elseif (($oAtt instanceof AttributeClassAttCodeSet) || ($oAtt instanceof AttributeEnumSet))
		{
			if (is_string($toCheck))
			{
				$oTag = new ormSet(get_class($this), $sAttCode, $oAtt->GetMaxItems());
				try
				{
					$aValues = array();
					foreach(explode(',', $toCheck) as $sValue)
					{
						$aValues[] = trim($sValue);
					}
					$oTag->SetValues($aValues);
				} catch (Exception $e)
				{
					return "Set value '$toCheck' is not a valid set";
				}

				return true;
			}

			if ($toCheck instanceof ormSet)
			{
				return true;
			}

			return "Bad type";
		}
		elseif ($oAtt->IsScalar())
		{
			$aValues = $oAtt->GetAllowedValues($this->ToArgsForQuery());
			if (is_array($aValues) && (count($aValues) > 0))
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
		else
		{
			return $oAtt->CheckValue($this, $toCheck);
		}
		return true;
	}

	/**
	 * check attributes together
	 *
     * @overwritable-hook You can extend this method in order to provide your own logic.
     * 
	 * @return bool 
	 */
	public function CheckConsistency()
	{
		return true;
	}

	/**
     * @internal
     * 
	 * @throws \CoreException
	 * @throws \OQLException
     *
	 * @since 2.6.0 N°659 uniqueness constraint
	 * @api
	 */
	protected function DoCheckUniqueness()
	{
		$sCurrentClass = get_class($this);
		$aUniquenessRules = MetaModel::GetUniquenessRules($sCurrentClass);

		foreach ($aUniquenessRules as $sUniquenessRuleId => $aUniquenessRuleProperties)
		{
			if ($aUniquenessRuleProperties['disabled'] === true)
			{
				continue;
			}

			// No iTopMutex so there might be concurrent access !
			// But the necessary lock would have a high performance cost :(
			$bHasDuplicates = $this->HasObjectsInDbForUniquenessRule($sUniquenessRuleId, $aUniquenessRuleProperties);
			if ($bHasDuplicates)
			{
				$bIsBlockingRule = $aUniquenessRuleProperties['is_blocking'];
				if (is_null($bIsBlockingRule))
				{
					$bIsBlockingRule = true;
				}

				$sErrorMessage = $this->GetUniquenessRuleMessage($sUniquenessRuleId);

				if ($bIsBlockingRule)
				{
					$this->m_aCheckIssues[] = $sErrorMessage;
					continue;
				}
				$this->m_aCheckWarnings[] = $sErrorMessage;
				continue;
			}
		}
	}

	/**
     *
     * @internal
     *
	 * @param string $sUniquenessRuleId
	 *
	 * @return string dict key : Class:$sClassName/UniquenessRule:$sUniquenessRuleId if none then will use Core:UniquenessDefaultError
	 * Dictionary keys can contain "$this" placeholders
	 *
	 * @since 2.6.0 N°659 uniqueness constraint
	 */
	protected function GetUniquenessRuleMessage($sUniquenessRuleId)
	{
		$sCurrentClass = get_class($this);
		$sClass = MetaModel::GetRootClassForUniquenessRule($sUniquenessRuleId, $sCurrentClass);
		$sMessageKey = "Class:$sClass/UniquenessRule:$sUniquenessRuleId";
		$sTemplate = Dict::S($sMessageKey, '');

		if (empty($sTemplate))
		{
			// we could add also a specific message if user is admin ("dict key is missing")
			return Dict::Format('Core:UniquenessDefaultError', $sUniquenessRuleId);
		}

		$oString = new TemplateString($sTemplate);

		return $oString->Render(array('this' => $this));
	}

	/**
     *
     * @internal
     *
	 * @param string $sUniquenessRuleId uniqueness rule ID
	 * @param array $aUniquenessRuleProperties uniqueness rule properties
	 *
	 * @return bool
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	protected function HasObjectsInDbForUniquenessRule($sUniquenessRuleId, $aUniquenessRuleProperties)
	{
		$oUniquenessQuery = $this->GetSearchForUniquenessRule($sUniquenessRuleId, $aUniquenessRuleProperties);
		$oUniquenessDuplicates = new DBObjectSet($oUniquenessQuery);
		$bHasDuplicates = $oUniquenessDuplicates->CountExceeds(0);

		return $bHasDuplicates;
	}

	/**
     * @internal
     *
	 * @param string $sUniquenessRuleId uniqueness rule ID
	 * @param array $aUniquenessRuleProperties uniqueness rule properties
	 *
	 * @return \DBSearch
	 * @throws \CoreException
	 * @throws \OQLException
	 * @since 2.6.0 N°659 uniqueness constraint
	 * @api
	 */
	protected function GetSearchForUniquenessRule($sUniquenessRuleId, $aUniquenessRuleProperties)
	{
		$sRuleRootClass = $aUniquenessRuleProperties['root_class'];
		$sOqlUniquenessQuery = "SELECT $sRuleRootClass";
		if (!(empty($sUniquenessFilter = $aUniquenessRuleProperties['filter'])))
		{
			$sOqlUniquenessQuery .= ' WHERE '.$sUniquenessFilter;
		}
		/** @var \DBObjectSearch $oUniquenessQuery */
		$oUniquenessQuery = DBObjectSearch::FromOQL($sOqlUniquenessQuery);

		if (!$this->IsNew())
		{
			$oUniquenessQuery->AddCondition('id', $this->GetKey(), '<>');
		}

		foreach ($aUniquenessRuleProperties['attributes'] as $sAttributeCode)
		{
			$attributeValue = $this->Get($sAttributeCode);
			$oUniquenessQuery->AddCondition($sAttributeCode, $attributeValue, '=');
		}

		$aChildClassesWithRuleDisabled = MetaModel::GetChildClassesWithDisabledUniquenessRule($sRuleRootClass, $sUniquenessRuleId);
		if (!empty($aChildClassesWithRuleDisabled))
		{
			$oUniquenessQuery->AddConditionForInOperatorUsingParam('finalclass', $aChildClassesWithRuleDisabled, false);
		}

		return $oUniquenessQuery;
	}

	/**
	 * Check integrity rules (before inserting or updating the object)
	 *
     * **This method is not meant to be called directly, use DBObject::CheckToWrite()!**
	 * Errors should be inserted in $m_aCheckIssues and $m_aCheckWarnings arrays
     *
     * @overwritable-hook You can extend this method in order to provide your own logic.
     * @see CheckToWrite()
     * @see $m_aCheckIssues
     * @see $m_aCheckWarnings
     *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \OQLException
	 *
	 */
	public function DoCheckToWrite()
	{
		$this->DoComputeValues();

		$this->DoCheckUniqueness();

		$aChanges = $this->ListChanges();

		foreach($aChanges as $sAttCode => $value)
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
					if (!empty($aReasons))
					{
						// Todo: associate the attribute code with the error
						$this->m_aCheckIssues[] = Dict::Format('UI:AttemptingToSetASlaveAttribute_Name', $sAttLabel);
					}
				}
			}
		}
	}

	/**
     *
     * @api
     * @api-advanced
     *
	 * @return array containing :
	 *   * $m_bCheckStatus
	 *   * $m_aCheckIssues
	 *   * $m_bSecurityIssue
	 *
     * @see $m_bCheckStatus
     * @see $m_aCheckIssues
     * @see $m_bSecurityIssue
     *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \OQLException
	 *
	 */
	final public function CheckToWrite()
	{
		if (MetaModel::SkipCheckToWrite())
		{
			return array(true, array());
		}
		if (is_null($this->m_bCheckStatus))
		{
			$this->m_aCheckIssues = array();

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

	/**
	 * Check if it is allowed to delete the existing object from the database
	 *
	 * an array of displayable error is added in {@see DBObject::$m_aDeleteIssues}
	 *
     * @internal 
     *
	 * @param \DeletionPlan $oDeletionPlan
	 *
	 * @throws \CoreException
	 */
	protected function DoCheckToDelete(&$oDeletionPlan)
	{
		$this->m_aDeleteIssues = array(); // Ok

		if ($this->InSyncScope())
		{

			foreach ($this->GetSynchroData() as $iSourceId => $aSourceData)
			{
				foreach ($aSourceData['replica'] as $oReplica)
				{
					$oDeletionPlan->AddToDelete($oReplica, DEL_SILENT);
				}
				/** @var \SynchroDataSource $oDataSource */
				$oDataSource = $aSourceData['source'];
				if ($oDataSource->GetKey() == SynchroExecution::GetCurrentTaskId())
				{
					// The current task has the right to delete the object
					continue;
				}
				$oReplica = reset($aSourceData['replica']); // Take the first one
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

    /**
     * @internal
     *
     * @param \DeletionPlan $oDeletionPlan
     *
     * @return bool
     * @throws CoreException
     */
	public function CheckToDelete(&$oDeletionPlan)
  	{
		$this->MakeDeletionPlan($oDeletionPlan);
		$oDeletionPlan->ComputeResults();
		return (!$oDeletionPlan->FoundStopper());
	}

    /**
     * @internal
     *
     * @param array $aProposal
     *
     * @return array
     * @throws Exception
     */
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
			elseif(!array_key_exists($sAtt, $this->m_aTouchedAtt) || (array_key_exists($sAtt, $this->m_aModifiedAtt) && $this->m_aModifiedAtt[$sAtt] == false))
			{
				// This attCode was never set, cannot be modified
				// or the same value - as the original value - was set, and has been verified as equivalent to the original value
				continue;
			}
			else if (array_key_exists($sAtt, $this->m_aModifiedAtt) && $this->m_aModifiedAtt[$sAtt] == true)
			{
				// We already know that the value is really modified
				$aDelta[$sAtt] = $proposedValue;
			}
			elseif(is_object($proposedValue))
			{
				$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAtt);
				// The value is an object, the comparison is not strict
				if (!$oAttDef->Equals($this->m_aOrigValues[$sAtt], $proposedValue))
				{
					$aDelta[$sAtt] = $proposedValue;
					$this->m_aModifiedAtt[$sAtt] = true; // Really modified
				}
				else
				{
					$this->m_aModifiedAtt[$sAtt] = false; // Not really modified
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
					$this->m_aModifiedAtt[$sAtt] = true; // Really modified
				}
				else
				{
					$this->m_aModifiedAtt[$sAtt] = false; // Not really modified
				}
			}
		}
		return $aDelta;
	}

	/**
	 * @api
	 * @api-advanced
	 *
	 * @see  \DBObject::ListPreviousValuesForUpdatedAttributes() to get previous values anywhere in the CRUD stack
	 * @see https://www.itophub.io/wiki/page?id=latest%3Acustomization%3Asequence_crud iTop CRUD stack documentation
	 * @return array attname => currentvalue List the attributes that have been changed using {@see DBObject::Set()}.
	 *         Reset during {@see DBObject::DBUpdate()}
	 * @throws Exception
	 * @uses m_aCurrValues
     */
	public function ListChanges()
	{
		if ($this->m_bIsInDB)
		{
			return $this->ListChangedValues($this->m_aCurrValues);
		}

		return $this->m_aCurrValues;
	}

	/**
	 * @api
	 * @api-advanced
	 *
	 * To be used during the {@link \DBObject::DBUpdate()} call stack.
	 *
	 * To get values that were set to the changed fields, simply use {@link \DBObject::Get()}
	 *
	 * @see  \DBObject::ListChanges() old method, but using data that are reset during DBObject::DBUpdate
	 * @return array attname => value : value that was present before the last {@see DBObject::Set()} call.
	 *       This array is set at the beginning of {@see DBObject::DBpdate()} using {@see DBObject::InitPreviousValuesForUpdatedAttributes()}.
	 * @uses m_aPreviousValuesForUpdatedAttributes
	 * @since 2.7.0 N°2293
	 */
	public function ListPreviousValuesForUpdatedAttributes()
	{
		if (empty($this->m_aPreviousValuesForUpdatedAttributes))
		{
			return array();
		}

		return $this->m_aPreviousValuesForUpdatedAttributes;
	}

	
    /**
     * Whether or not an object was modified since last read from the DB
     * (ie: does it differ from the DB ?)
     * 
     * @api
     *
     * @return bool
     */
	public function IsModified()
	{
		$aChanges = $this->ListChanges();
		return (count($aChanges) != 0);
	}

	/**
     * Whether or not $oSibling is equal to the current DBObject
     * 
	 * @param DBObject $oSibling
	 *
	 * @return bool
	 */
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

	/**
	 * Used only by insert, Meant to be overloaded
     * 
     * @overwritable-hook You can extend this method in order to provide your own logic.
	 */
	protected function OnObjectKeyReady()
    {
    }

	/**
	 * used both by insert/update
	 *
     * @internal
     *
	 * @throws \CoreException
	 */
	private function DBWriteLinks()
	{
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
		{
			if (!$oAttDef->IsLinkSet()) continue;
			if (!array_key_exists($sAttCode, $this->m_aTouchedAtt)) continue;
			if (array_key_exists($sAttCode, $this->m_aModifiedAtt) && ($this->m_aModifiedAtt[$sAttCode] == false)) continue;

			/** @var \ormLinkSet $oLinkSet */
			$oLinkSet = $this->m_aCurrValues[$sAttCode];
			$oLinkSet->DBWrite($this);
		}
	}

	/**
	 * Used both by insert/update
	 *
     * @internal
     *
	 * @throws \CoreException
	 */
	private function WriteExternalAttributes()
	{
		foreach (MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
		{
			if (!$oAttDef->LoadInObject()) continue;
			if ($oAttDef->LoadFromDB()) continue;
			if (!array_key_exists($sAttCode, $this->m_aTouchedAtt)) continue;
			if (array_key_exists($sAttCode, $this->m_aModifiedAtt) && ($this->m_aModifiedAtt[$sAttCode] == false)) continue;
			/** @var \AttributeCustomFields $oAttDef */
			$oAttDef->WriteValue($this, $this->m_aCurrValues[$sAttCode]);
		}
	}


    /**
     * Note: this is experimental - it was designed to speed up the setup of iTop
     * Known limitations:
     * - does not work with multi-table classes (issue with the unique id to maintain in several tables)
     * - the id of the object is not updated
     *
     * @internal
     * @experimental
     */
	static public final function BulkInsertStart()
	{
		self::$m_bBulkInsert = true;
	}

    /**
     *
     * @internal
     * @experimental
     */
	static public final function BulkInsertFlush()
	{
		if (!self::$m_bBulkInsert) return;

		foreach(self::$m_aBulkInsertCols as $sClass => $aTables)
		{
			foreach ($aTables as $sTable => $sColumns)
			{
				$sValues = implode(', ', self::$m_aBulkInsertItems[$sClass][$sTable]);
				$sInsertSQL = "INSERT INTO `$sTable` ($sColumns) VALUES $sValues";
				CMDBSource::InsertInto($sInsertSQL);
			}
		}

		// Reset
		self::$m_aBulkInsertItems = array();
		self::$m_aBulkInsertCols = array();
		self::$m_bBulkInsert = false;
	}

	/**
	 * Persists new object in the DB
     *
     * @internal
	 *
	 * @param string $sTableClass
	 *
	 * @return bool|int false if nothing to persist (no change), new key value otherwise
	 * @throws \CoreException
	 * @throws \MySQLException
	 */
	private function DBInsertSingleTable($sTableClass)
	{
		$sTable = MetaModel::DBGetTable($sTableClass);
		// Abstract classes or classes having no specific attribute do not have an associated table
		if ($sTable == '') { return false; }

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
			if (!MetaModel::IsAttributeOrigin($sTableClass, $sAttCode) && !$oAttDef->CopyOnAllTables())
			{
				continue;
			}
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

		if (count($aValuesToWrite) == 0) { return false; }

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

	/**
	 * Persists object to new records in the DB
	 *
	 * @return int key of the newly created object
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException if {@see DBObject::CheckToWrite()} returns issues
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 *
	 * @internal
	 *
	 */
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
			throw new CoreCannotSaveObjectException(array('issues' => $aIssues, 'class' => get_class($this), 'id' => $this->GetKey()));
		}

		// Stop watches
		$sState = $this->GetState();
		if ($sState != '')
		{
			foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				if ($oAttDef instanceof AttributeStopWatch)
				{
					if (in_array($sState, $oAttDef->GetStates()))
					{
						// Start the stop watch and compute the deadlines
						/** @var \ormStopWatch $oSW */
						$oSW = $this->Get($sAttCode);
						$oSW->Start($this, $oAttDef);
						$oSW->ComputeDeadlines($this, $oAttDef);
						$this->Set($sAttCode, $oSW);
					}
				}
			}
		}

		$bIsTransactionEnabled = MetaModel::GetConfig()->Get('db_core_transactions_enabled');
		try
		{
			if ($bIsTransactionEnabled)
			{
				CMDBSource::Query('START TRANSACTION');
			}

			// First query built upon on the root class, because the ID must be created first
			$this->m_iKey = $this->DBInsertSingleTable($sRootClass);

			// Then do the leaf class, if different from the root class
			if ($sClass != $sRootClass)
			{
				$this->DBInsertSingleTable($sClass);
			}

			// Then do the other classes
			foreach (MetaModel::EnumParentClasses($sClass) as $sParentClass)
			{
				if ($sParentClass == $sRootClass)
				{
					continue;
				}
				$this->DBInsertSingleTable($sParentClass);
			}

			$this->OnObjectKeyReady();

			$this->DBWriteLinks();
			$this->WriteExternalAttributes();

			if ($bIsTransactionEnabled)
			{
				CMDBSource::Query('COMMIT');
			}
		}
		catch (Exception $e)
		{
			if ($bIsTransactionEnabled)
			{
				CMDBSource::Query('ROLLBACK');
			}
			throw $e;
		}

		$this->m_bIsInDB = true;
		$this->m_bDirty = false;
		foreach ($this->m_aCurrValues as $sAttCode => $value)
		{
			if (is_object($value))
			{
				$value = clone $value;
			}
			$this->m_aOrigValues[$sAttCode] = $value;
		}

		$this->AfterInsert();

		// Activate any existing trigger 
		$sClass = get_class($this);
		$aParams = array('class_list' => MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL));
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnObjectCreate AS t WHERE t.target_class IN (:class_list)"), array(), $aParams);
		while ($oTrigger = $oSet->Fetch())
		{
			/** @var \Trigger $oTrigger */
			try
			{
				$oTrigger->DoActivate($this->ToArgs('this'));
			}
			catch(Exception $e)
			{
				utils::EnrichRaisedException($oTrigger, $e);
			}
		}

		$this->RecordObjCreation();

		return $this->m_iKey;
	}

    /**
     * @internal
     *
     * @param array $aAuthorizedExtKeys
     * @param array $aStatements
     * @param string $sTableClass
     *
     * @throws CoreException
     * @throws MySQLException
     */
	protected function MakeInsertStatementSingleTable($aAuthorizedExtKeys, &$aStatements, $sTableClass)
	{
		$sTable = MetaModel::DBGetTable($sTableClass);
		// Abstract classes or classes having no specific attribute do not have an associated table
		if ($sTable == '') return;

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
			// Skip link set that can still be undefined though the object is 100% loaded
			if ($oAttDef->IsLinkSet()) continue;

			$value = $this->m_aCurrValues[$sAttCode];
			if ($oAttDef->IsExternalKey())
			{
				/** @var \AttributeExternalKey $oAttDef */
				$sTargetClass = $oAttDef->GetTargetClass();
				if (is_array($aAuthorizedExtKeys))
				{
					if (!array_key_exists($sTargetClass, $aAuthorizedExtKeys) || !array_key_exists($value, $aAuthorizedExtKeys[$sTargetClass]))
					{
						$value = 0;
					}
				}
			}
			$aAttColumns = $oAttDef->GetSQLValues($value);
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

		if (count($aValuesToWrite) == 0) return;

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
		$aStatements[] = "INSERT INTO `$sTable` (".join(",", $aFieldsToWrite).") VALUES (".join(", ", $aValuesToWrite).");";
	}

    /**
     * @internal
     *
     * @param array $aAuthorizedExtKeys
     * @param array $aStatements
     *
     * @throws CoreException
     * @throws MySQLException
     */
	public function MakeInsertStatements($aAuthorizedExtKeys, &$aStatements)
	{
		$sClass = get_class($this);
		$sRootClass = MetaModel::GetRootClass($sClass);

		// First query built upon on the root class, because the ID must be created first
		$this->MakeInsertStatementSingleTable($aAuthorizedExtKeys, $aStatements, $sRootClass);

		// Then do the leaf class, if different from the root class
		if ($sClass != $sRootClass)
		{
			$this->MakeInsertStatementSingleTable($aAuthorizedExtKeys, $aStatements, $sClass);
		}

		// Then do the other classes
		foreach(MetaModel::EnumParentClasses($sClass) as $sParentClass)
		{
			if ($sParentClass == $sRootClass) continue;
			$this->MakeInsertStatementSingleTable($aAuthorizedExtKeys, $aStatements, $sParentClass);
		}
	}

	/**
	 * Persist an object to the DB, for the first time
	 *
     * @api
     * @see DBWrite
     *
	 * @return int|null inserted object key
     *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function DBInsert()
	{
	    $this->DBInsertNoReload();

        if (MetaModel::DBIsReadOnly())
        {
            $sClass = get_class($this);
            $sErrorMessage = "Cannot Insert object of class '$sClass' because of an ongoing maintenance: the database is in ReadOnly mode";

            IssueLog::Error("$sErrorMessage\n".MyHelpers::get_callstack_text(1));
            throw new CoreException("$sErrorMessage (see the log for more information)");
        }

		$this->Reload();
		return $this->m_iKey;
	}

    /**
     * @internal
     *
     * @deprecated 2.7.0 N°2361 simply use {@see DBObject::DBInsert()} instead, that will automatically create and persist a CMDBChange object.
     *    If you need to persist your own, call {@see CMDBObject::SetCurrentChange()} before.
     *
     * @param CMDBChange $oChange
     *
     * @return int|null
     * @throws CoreException
     */
	public function DBInsertTracked(CMDBChange $oChange)
	{
		CMDBObject::SetCurrentChange($oChange);
		return $this->DBInsert();
	}

    /**
     * @internal
     *
     * @deprecated 2.7.0 N°2361 simply use {@see DBObject::DBInsertNoReload()} instead, that will automatically create and persist a CMDBChange object.
     *    If you need to persist your own, call {@see CMDBObject::SetCurrentChange()} before.
     *
     * @param CMDBChange $oChange
     *
     * @return int
     * @throws ArchivedObjectException
     * @throws CoreCannotSaveObjectException
     * @throws CoreException
     * @throws CoreUnexpectedValue
     * @throws CoreWarning
     * @throws MySQLException
     * @throws OQLException
     */
	public function DBInsertTrackedNoReload(CMDBChange $oChange)
	{
		CMDBObject::SetCurrentChange($oChange);
		return $this->DBInsertNoReload();
	}

    /**
     * Creates a copy of the current object into the database
     *
     * @internal
     *
     * @param null $iNewKey
     *
     * @return int|null the id of the newly created object
     *
     * @throws ArchivedObjectException
     * @throws CoreCannotSaveObjectException
     * @throws CoreException
     * @throws CoreUnexpectedValue
     * @throws CoreWarning
     * @throws MySQLException
     * @throws OQLException
     */
	public function DBClone($iNewKey = null)
	{
		$this->m_bIsInDB = false;
		$this->m_iKey = $iNewKey;
		$ret = $this->DBInsert();
		$this->RecordObjCreation();
		return $ret;
	}
	
	/**
	 * This function is automatically called after cloning an object with the "clone" PHP language construct
	 * The purpose of this method is to reset the appropriate attributes of the object in
	 * order to make sure that the newly cloned object is really distinct from its clone
     *
     * @internal
	 */
	public function __clone()
	{
		$this->m_bIsInDB = false;
		$this->m_bDirty = true;
		$this->m_iKey = self::GetNextTempId(get_class($this));
	}

	/**
	 * Update an object in DB
	 *
	 * @api
	 * @see DBObject::DBWrite()
	 *
	 * @return int object key
	 *
	 * @throws \CoreException
	 * @throws \CoreCannotSaveObjectException if CheckToWrite() returns issues
	 * @throws \Exception
	 */
	public function DBUpdate()
	{
		if (!$this->m_bIsInDB)
		{
			throw new CoreException("DBUpdate: could not update a newly created object, please call DBInsert instead");
		}
		// Protect against reentrance (e.g. cascading the update of ticket logs)
		static $aUpdateReentrance = array();
		$sKey = get_class($this).'::'.$this->GetKey();
		if (array_key_exists($sKey, $aUpdateReentrance))
		{
			return false;
		}
		$aUpdateReentrance[$sKey] = true;

		$this->InitPreviousValuesForUpdatedAttributes();

		try
		{
			$this->DoComputeValues();
			// Stop watches
			$sState = $this->GetState();
			if ($sState != '')
			{
				foreach (MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
				{
					if ($oAttDef instanceof AttributeStopWatch)
					{
						if (in_array($sState, $oAttDef->GetStates()))
						{
							// Compute or recompute the deadlines
							/** @var \ormStopWatch $oSW */
							$oSW = $this->Get($sAttCode);
							$oSW->ComputeDeadlines($this, $oAttDef);
							$this->Set($sAttCode, $oSW);
						}
					}
				}
			}
			$this->OnUpdate();

			$aChanges = $this->ListChanges();
			if (count($aChanges) == 0)
			{
				// Attempting to update an unchanged object
				unset($aUpdateReentrance[$sKey]);

				return $this->m_iKey;
			}

			// Ultimate check - ensure DB integrity
			list($bRes, $aIssues) = $this->CheckToWrite();
			if (!$bRes)
			{
				throw new CoreCannotSaveObjectException(array(
					'issues' => $aIssues,
					'class' => get_class($this),
					'id' => $this->GetKey()
				));
			}

			// Save the original values (will be reset to the new values when the object get written to the DB)
			$aOriginalValues = $this->m_aOrigValues;

			// Activate any existing trigger
			$sClass = get_class($this);
			$aParams = array('class_list' => MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL));
			$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnObjectUpdate AS t WHERE t.target_class IN (:class_list)"),
				array(), $aParams);
			while ($oTrigger = $oSet->Fetch())
			{
				/** @var \Trigger $oTrigger */
				try
				{
					$oTrigger->DoActivate($this->ToArgs('this'));
				}
				catch(Exception $e)
				{
					utils::EnrichRaisedException($oTrigger, $e);
				}
			}

			$bHasANewExternalKeyValue = false;
			$aHierarchicalKeys = array();
			$aDBChanges = array();
			foreach ($aChanges as $sAttCode => $valuecurr)
			{
				$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
				if ($oAttDef->IsExternalKey())
				{
					$bHasANewExternalKeyValue = true;
				}
				if ($oAttDef->IsBasedOnDBColumns())
				{
					$aDBChanges[$sAttCode] = $aChanges[$sAttCode];
				}
				if ($oAttDef->IsHierarchicalKey())
				{
					$aHierarchicalKeys[$sAttCode] = $oAttDef;
				}
			}

			$iTransactionRetry = 1;
			$bIsTransactionEnabled = MetaModel::GetConfig()->Get('db_core_transactions_enabled');
			if ($bIsTransactionEnabled)
			{
				$iIsTransactionRetryCount = MetaModel::GetConfig()->Get('db_core_transactions_retry_count');
				$iIsTransactionRetryDelay = MetaModel::GetConfig()->Get('db_core_transactions_retry_delay_ms');
				$iTransactionRetry = $iIsTransactionRetryCount;
			}
			while ($iTransactionRetry > 0)
			{
				try
				{
					$iTransactionRetry--;
					if ($bIsTransactionEnabled)
					{
						CMDBSource::Query('START TRANSACTION');
					}
					if (!MetaModel::DBIsReadOnly())
					{
						// Update the left & right indexes for each hierarchical key
						foreach ($aHierarchicalKeys as $sAttCode => $oAttDef)
						{
							$sTable = $sTable = MetaModel::DBGetTable(get_class($this), $sAttCode);
							$sSQL = "SELECT `".$oAttDef->GetSQLRight()."` AS `right`, `".$oAttDef->GetSQLLeft()."` AS `left` FROM `$sTable` WHERE id=".$this->GetKey();
							$aRes = CMDBSource::QueryToArray($sSQL);
							$iMyLeft = $aRes[0]['left'];
							$iMyRight = $aRes[0]['right'];
							$iDelta = $iMyRight - $iMyLeft + 1;
							MetaModel::HKTemporaryCutBranch($iMyLeft, $iMyRight, $oAttDef, $sTable);

							if ($aDBChanges[$sAttCode] == 0)
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
									$iNewLeft = $aRes[0]['max'] + 1;
								}
							}
							else
							{
								// Insert at the right of the specified parent
								$sSQL = "SELECT `".$oAttDef->GetSQLRight()."` FROM `$sTable` WHERE id=".((int)$aDBChanges[$sAttCode]);
								$iNewLeft = CMDBSource::QueryToScalar($sSQL);
							}

							MetaModel::HKReplugBranch($iNewLeft, $iNewLeft + $iDelta - 1, $oAttDef, $sTable);

							$aHKChanges = array();
							$aHKChanges[$sAttCode] = $aDBChanges[$sAttCode];
							$aHKChanges[$oAttDef->GetSQLLeft()] = $iNewLeft;
							$aHKChanges[$oAttDef->GetSQLRight()] = $iNewLeft + $iDelta - 1;
							$aDBChanges[$sAttCode] = $aHKChanges; // the 3 values will be stored by MakeUpdateQuery below
						}

						// Update scalar attributes
						if (count($aDBChanges) != 0)
						{
							$oFilter = new DBObjectSearch(get_class($this));
							$oFilter->AddCondition('id', $this->m_iKey, '=');
							$oFilter->AllowAllData();

							$sSQL = $oFilter->MakeUpdateQuery($aDBChanges);
							CMDBSource::Query($sSQL);
						}
					}
					$this->DBWriteLinks();
					$this->WriteExternalAttributes();

					// following lines are resetting changes (so after this {@see DBObject::ListChanges()} won't return changes anymore)
					// new values are already in the object (call {@see DBObject::Get()} to get them)
					// call {@see DBObject::ListPreviousValuesForUpdatedAttributes()} to get changed fields and previous values
					$this->m_bDirty = false;
					$this->m_aTouchedAtt = array();
					$this->m_aModifiedAtt = array();

					if (count($aChanges) != 0)
					{
						$this->RecordAttChanges($aChanges, $aOriginalValues);
					}

					if ($bIsTransactionEnabled)
					{
						CMDBSource::Query('COMMIT');
					}
					break;
				}
				catch (MySQLException $e)
				{
					if ($bIsTransactionEnabled)
					{
						CMDBSource::Query('ROLLBACK');
						if ($e->getCode() == 1213)
						{
							// Deadlock found when trying to get lock; try restarting transaction
							IssueLog::Error($e->getMessage());
							if ($iTransactionRetry > 0)
							{
								// wait and retry
								IssueLog::Error("Update TRANSACTION Retrying...");
								usleep(random_int(1, 5) * 1000 * $iIsTransactionRetryDelay * ($iIsTransactionRetryCount - $iTransactionRetry));
								continue;
							}
							else
							{
								IssueLog::Error("Update Deadlock TRANSACTION prevention failed.");
							}
						}
					}
					$aErrors = array($e->getMessage());
					throw new CoreCannotSaveObjectException(array(
						'id' => $this->GetKey(),
						'class' => get_class($this),
						'issues' => $aErrors
					));
				}
				catch (CoreCannotSaveObjectException $e)
				{
					if ($bIsTransactionEnabled)
					{
						CMDBSource::Query('ROLLBACK');
					}
					throw $e;
				}
				catch (Exception $e)
				{
					if ($bIsTransactionEnabled)
					{
						CMDBSource::Query('ROLLBACK');
					}
					$aErrors = array($e->getMessage());
					throw new CoreCannotSaveObjectException(array(
						'id' => $this->GetKey(),
						'class' => get_class($this),
						'issues' => $aErrors
					));
				}
			}

			try
			{
				$this->AfterUpdate();

				// Reload to get the external attributes
				if ($bHasANewExternalKeyValue)
				{
					$this->Reload(true /* AllowAllData */);
				}
				else
				{
					// Reset original values although the object has not been reloaded
					foreach ($this->m_aLoadedAtt as $sAttCode => $bLoaded)
					{
						if ($bLoaded)
						{
							$value = $this->m_aCurrValues[$sAttCode];
							$this->m_aOrigValues[$sAttCode] = is_object($value) ? clone $value : $value;
						}
					}
				}
			}
			catch (Exception $e)
			{
				$aErrors = array($e->getMessage());
				throw new CoreCannotSaveObjectException(array('id' => $this->GetKey(), 'class' => get_class($this), 'issues' => $aErrors));
			}
		}
		finally
		{
			unset($aUpdateReentrance[$sKey]);
		}

		return $this->m_iKey;
	}

	/**
	 * @internal
	 * Save updated fields previous values for {@see DBObject::DBUpdate()} callbacks
	 * @see DBObject::ListPreviousValuesForUpdatedAttributes() to get the data in the callbacks
	 * @uses ListChanges
	 * @uses m_aOrigValues
	 * @uses m_aPreviousValuesForUpdatedAttributes
	 * @since 2.7.0 N°2293
	 * @throws \Exception
	 */
	private function InitPreviousValuesForUpdatedAttributes()
	{
		$aChanges= $this->ListChanges();
		if (empty($aChanges))
		{
			$this->m_aPreviousValuesForUpdatedAttributes = array();
			return;
		}

		$aPreviousValuesForUpdatedAttributes = array_intersect_key($this->m_aOrigValues, $aChanges);

		$this->m_aPreviousValuesForUpdatedAttributes = $aPreviousValuesForUpdatedAttributes;
	}

    /**
     *
     * @internal
     *
     * @deprecated 2.7.0 N°2361 simply use {@see DBObject::DBUpdate()} instead, that will automatically create and persist a CMDBChange object.
     *    If you need to persist your own, call {@see CMDBObject::SetCurrentChange()} before.
     *
     * @param CMDBChange $oChange
     *
     * @return int
     * @throws CoreCannotSaveObjectException
     * @throws CoreException
     */
	public function DBUpdateTracked(CMDBChange $oChange)
	{
		CMDBObject::SetCurrentChange($oChange);
		return $this->DBUpdate();
	}

	/**
	 * Make the current changes persistent - clever wrapper for Insert or Update
     *
     * @api
	 *
	 * @return int
     *
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 */
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

    /**
     * @internal
     *
     * @param string $sTableClass
     *
     * @throws CoreException
     * @throws MySQLException
     */
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

    /**
     * @internal
     *
     * @throws ArchivedObjectException
     * @throws CoreException
     * @throws CoreUnexpectedValue
     * @throws MySQLException
     * @throws MySQLHasGoneAwayException
     * @throws OQLException
     */
	protected function DBDeleteSingleObject()
	{
		if (MetaModel::DBIsReadOnly())
		{
			return;
		}

		$this->OnDelete();

		// Activate any existing trigger
		$sClass = get_class($this);
		$aParams = array('class_list' => MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL));
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnObjectDelete AS t WHERE t.target_class IN (:class_list)"), array(),
			$aParams);
		while ($oTrigger = $oSet->Fetch())
		{
			/** @var \Trigger $oTrigger */
			try
			{
				$oTrigger->DoActivate($this->ToArgs('this'));
			}
			catch(Exception $e)
			{
				utils::EnrichRaisedException($oTrigger, $e);
			}
		}

		$this->RecordObjDeletion($this->m_iKey); // May cause a reload for storing history information

		foreach (MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
		{
			if ($oAttDef->IsHierarchicalKey())
			{
				// Update the left & right indexes for each hierarchical key
				$sTable = $sTable = MetaModel::DBGetTable(get_class($this), $sAttCode);
				/** @var \AttributeHierarchicalKey $oAttDef */
				$sSQL = "SELECT `".$oAttDef->GetSQLRight()."` AS `right`, `".$oAttDef->GetSQLLeft()."` AS `left` FROM `$sTable` WHERE id=".CMDBSource::Quote($this->m_iKey);
				$aRes = CMDBSource::QueryToArray($sSQL);
				$iMyLeft = $aRes[0]['left'];
				$iMyRight = $aRes[0]['right'];
				$iDelta = $iMyRight - $iMyLeft + 1;
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
					$iNewLeft = $aRes[0]['max'] + 1;
				}
				MetaModel::HKReplugBranch($iNewLeft, $iNewLeft + $iDelta - 1, $oAttDef, $sTable);
			}
			elseif (!$oAttDef->LoadFromDB())
			{
				/** @var \AttributeCustomFields $oAttDef */
				$oAttDef->DeleteValue($this);
			}
		}
		$iTransactionRetry = 1;
		$bIsTransactionEnabled = MetaModel::GetConfig()->Get('db_core_transactions_enabled');
		if ($bIsTransactionEnabled)
		{
			$iIsTransactionRetryCount = MetaModel::GetConfig()->Get('db_core_transactions_retry_count');
			$iIsTransactionRetryDelay = MetaModel::GetConfig()->Get('db_core_transactions_retry_delay_ms');
			$iTransactionRetry = $iIsTransactionRetryCount;
		}
		while ($iTransactionRetry > 0)
		{
			try
			{
				$iTransactionRetry--;
				if ($bIsTransactionEnabled)
				{
					CMDBSource::Query('START TRANSACTION');
				}
				foreach (MetaModel::EnumParentClasses(get_class($this), ENUM_PARENT_CLASSES_ALL) as $sParentClass)
				{
					$this->DBDeleteSingleTable($sParentClass);
				}
				if ($bIsTransactionEnabled)
				{
					CMDBSource::Query('COMMIT');
				}
				break;
			}
			catch (MySQLException $e)
			{
				if ($bIsTransactionEnabled)
				{
					CMDBSource::Query('ROLLBACK');
					if ($e->getCode() == 1213)
					{
						// Deadlock found when trying to get lock; try restarting transaction
						IssueLog::Error($e->getMessage());
						if ($iTransactionRetry > 0)
						{
							// wait and retry
							IssueLog::Error("Delete TRANSACTION Retrying...");
							usleep(random_int(1, 5) * 1000 * $iIsTransactionRetryDelay * ($iIsTransactionRetryCount - $iTransactionRetry));
							continue;
						}
						else
						{
							IssueLog::Error("Delete Deadlock TRANSACTION prevention failed.");
						}
					}
				}
				throw $e;
			}
		}

		$this->AfterDelete();

		$this->m_bIsInDB = false;
		// Fix for N°926: do NOT reset m_iKey as it can be used to have it for reporting purposes (see the REST service to delete
		// objects, reported as bug N°926)
		// Thought the key is not reset, using DBInsert or DBWrite will create an object having the same characteristics and a new ID. DBUpdate is protected
	}

    /**
     * Delete an object
     *
     * First, checks if the object can be deleted regarding database integrity.
     * If the answer is yes, it performs any required cleanup (delete other objects or reset external keys) in addition to the object
     * deletion.
     * 
     * @api
     *
     * @param \DeletionPlan $oDeletionPlan Do not use: aims at dealing with recursion
     *
     * @return DeletionPlan The detailed description of cleanup operation that have been performed
     *
     * @throws ArchivedObjectException
     * @throws CoreCannotSaveObjectException
     * @throws CoreException
     * @throws CoreUnexpectedValue
     * @throws DeleteException
     * @throws MySQLException
     * @throws MySQLHasGoneAwayException
     * @throws OQLException
     */
	public function DBDelete(&$oDeletionPlan = null)
	{
		static $iLoopTimeLimit = null;
		if ($iLoopTimeLimit == null)
		{
			$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
		}
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


		// Getting and setting time limit are not symetric:
		// www.php.net/manual/fr/function.set-time-limit.php#72305
		$iPreviousTimeLimit = ini_get('max_execution_time');

		foreach ($oDeletionPlan->ListDeletes() as $sClass => $aToDelete)
		{
			foreach ($aToDelete as $iId => $aData)
			{
				/** @var \DBObject $oToDelete */
				$oToDelete = $aData['to_delete'];
				// The deletion based on a deletion plan should not be done for each object if the deletion plan is common (Trac #457)
				// because for each object we would try to update all the preceding ones... that are already deleted
				// A better approach would be to change the API to apply the DBDelete on the deletion plan itself... just once
				// As a temporary fix: delete only the objects that are still to be deleted...
				if ($oToDelete->m_bIsInDB)
				{
					set_time_limit($iLoopTimeLimit);
					$oToDelete->DBDeleteSingleObject();
				}
			}
		}

		foreach ($oDeletionPlan->ListUpdates() as $sClass => $aToUpdate)
		{
			foreach ($aToUpdate as $iId => $aData)
			{
				$oToUpdate = $aData['to_reset'];
				/** @var \DBObject $oToUpdate */
				foreach ($aData['attributes'] as $sRemoteExtKey => $aRemoteAttDef)
				{
					$oToUpdate->Set($sRemoteExtKey, $aData['values'][$sRemoteExtKey]);
					set_time_limit($iLoopTimeLimit);
					$oToUpdate->DBUpdate();
				}
			}
		}

		set_time_limit($iPreviousTimeLimit);

		return $oDeletionPlan;
	}

    /**
     * @internal
     *
     * @deprecated 2.7.0 N°2361 simply use {@see DBObject::DBDelete()} instead.
     *    If you need to persist your own, call {@see CMDBObject::SetCurrentChange()} before.
     *
     * @param CMDBChange $oChange
     * @param boolean $bSkipStrongSecurity
     * @param \DeletionPlan $oDeletionPlan
     *
     * @throws ArchivedObjectException
     * @throws CoreCannotSaveObjectException
     * @throws CoreException
     * @throws CoreUnexpectedValue
     * @throws DeleteException
     * @throws MySQLException
     * @throws MySQLHasGoneAwayException
     * @throws OQLException
     */
	public function DBDeleteTracked(CMDBChange $oChange, $bSkipStrongSecurity = null, &$oDeletionPlan = null)
	{
		CMDBObject::SetCurrentChange($oChange);
		$this->DBDelete($oDeletionPlan);
	}

    /**
     * @internal
     *
     * @return array
     *
     * @throws ArchivedObjectException
     * @throws CoreException
     */
	public function EnumTransitions()
	{
		$sStateAttCode = MetaModel::GetStateAttributeCode(get_class($this));
		if (empty($sStateAttCode)) return array();

		$sState = $this->Get($sStateAttCode);
		return MetaModel::EnumTransitions(get_class($this), $sState);
	}

    /**
     * Helper to reset a stop-watch
     * Suitable for use as a lifecycle action
     *
     * @api
     *
     * @param string $sAttCode
     *
     * @return bool
     *
     * @throws ArchivedObjectException
     * @throws CoreException
     * @throws CoreUnexpectedValue
     */
	public function ResetStopWatch($sAttCode)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		if (!$oAttDef instanceof AttributeStopWatch)
		{
			throw new CoreException("Invalid stop watch id: '$sAttCode'");
		}
		$oSW = $this->Get($sAttCode);
		$oSW->Reset($this, $oAttDef);
		$this->Set($sAttCode, $oSW);
		return true;
	}

	/**
	 * Apply a stimulus (workflow)
     *
     * @api
     *
	 * @param string  $sStimulusCode
	 * @param bool $bDoNotWrite
     *
	 * @return bool
     *
	 * @throws CoreException
	 * @throws CoreUnexpectedValue
	 */
	public function ApplyStimulus($sStimulusCode, $bDoNotWrite = false)
	{
		$sClass = get_class($this);
		$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
		if (empty($sStateAttCode))
		{
			throw new CoreException('No lifecycle for the class '.$sClass);
		}

		MyHelpers::CheckKeyInArray('object lifecycle stimulus', $sStimulusCode, MetaModel::EnumStimuli($sClass));

		$aStateTransitions = $this->EnumTransitions();
		if (!array_key_exists($sStimulusCode, $aStateTransitions))
		{
			// This stimulus has no effect in the current state... do nothing
			IssueLog::Error("$sClass: Transition $sStimulusCode is not allowed in ".$this->Get($sStateAttCode));
			return false;
		}

		// save current object values in case of an action failure (in memory rollback)
		$aBackupValues = array();
		foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)	{
			if (isset($this->m_aCurrValues[$sAttCode])) {
				$value = $this->m_aCurrValues[$sAttCode];
				if (is_object($value)) {
					$aBackupValues[$sAttCode] = clone $value;
				} else {
					$aBackupValues[$sAttCode] = $value;
				}
			}
		}

		$aTransitionDef = $aStateTransitions[$sStimulusCode];

		// Change the state before proceeding to the actions, this is necessary because an action might
		// trigger another stimuli (alternative: push the stimuli into a queue)
		$sPreviousState = $this->Get($sStateAttCode);
		$sNewState = $aTransitionDef['target_state'];
		$this->Set($sStateAttCode, $sNewState);

		// $aTransitionDef is an
		//    array('target_state'=>..., 'actions'=>array of handlers procs, 'user_restriction'=>TBD

		$bSuccess = true;
		foreach ($aTransitionDef['actions'] as $actionHandler)
		{
			if (is_string($actionHandler))
			{
				// Old (pre-2.1.0 modules) action definition without any parameter
				$aActionCallSpec = array($this, $actionHandler);
				$sActionDesc = $sClass.'::'.$actionHandler;

				if (!is_callable($aActionCallSpec))
				{
					throw new CoreException("Unable to call action: $sClass::$actionHandler");
				}
				$bRet = call_user_func($aActionCallSpec, $sStimulusCode);
			}
			else // if (is_array($actionHandler))
			{
				// New syntax: 'verb' and typed parameters
				$sAction = $actionHandler['verb'];
				$sActionDesc = "$sClass::$sAction";
				$aParams = array();
				foreach($actionHandler['params'] as $aDefinition)
				{
					$sParamType = array_key_exists('type', $aDefinition) ? $aDefinition['type'] : 'string';
					switch($sParamType)
					{
						case 'int':
							$value = (int)$aDefinition['value'];
							break;

						case 'float':
							$value = (float)$aDefinition['value'];
							break;

						case 'bool':
							$value = (bool)$aDefinition['value'];
							break;

						case 'reference':
							$value = ${$aDefinition['value']};
							break;

						case 'string':
						default:
							$value = (string)$aDefinition['value'];
					}
					$aParams[] = $value;
				}
				$aCallSpec = array($this, $sAction);
				$bRet = call_user_func_array($aCallSpec, $aParams);
			}
			// if one call fails, the whole is considered as failed
			// (in case there is no returned value, null is obtained and means "ok")
			if ($bRet === false)
			{
				IssueLog::Info("Lifecycle action $sActionDesc returned false on object #$sClass:".$this->GetKey());
				$bSuccess = false;
			}
		}
		if ($bSuccess)
		{
			// Stop watches
			foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				if ($oAttDef instanceof AttributeStopWatch)
				{
					$oSW = $this->Get($sAttCode);
					if (in_array($sNewState, $oAttDef->GetStates()))
					{
						$oSW->Start($this, $oAttDef);
					}
					else
					{
						$oSW->Stop($this, $oAttDef);
					}
					$this->Set($sAttCode, $oSW);
				}
			}

			if (!$bDoNotWrite)
			{
				$this->DBWrite();
			}

			// Change state triggers...
			$aParams = array(
				'class_list' => MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL),
				'previous_state' => $sPreviousState,
				'new_state' => $sNewState);
			$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnStateLeave AS t WHERE t.target_class IN (:class_list) AND t.state=:previous_state"), array(), $aParams);
			while ($oTrigger = $oSet->Fetch())
			{
				/** @var \Trigger $oTrigger */
				try
				{
					$oTrigger->DoActivate($this->ToArgs('this'));
				}
				catch(Exception $e)
				{
					utils::EnrichRaisedException($oTrigger, $e);
				}
			}

			$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnStateEnter AS t WHERE t.target_class IN (:class_list) AND t.state=:new_state"), array(), $aParams);
			while ($oTrigger = $oSet->Fetch())
			{
				/** @var \Trigger $oTrigger */
				try{
					$oTrigger->DoActivate($this->ToArgs('this'));
				}
				catch(Exception $e)
				{
					utils::EnrichRaisedException($oTrigger, $e);
				}
			}
		}
		else
		{
			// At least one action failed, rollback the object value to its previous value
			foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				$this->m_aCurrValues[$sAttCode] = $aBackupValues[$sAttCode];
			}
		}

		return $bSuccess;
	}

	/**
	 * Helper to recover the default value (aka when an object is being created)
     * Suitable for use as a lifecycle action
     *
     * @api
     *
	 */	 	
	public function Reset($sAttCode)
	{
		$this->Set($sAttCode, $this->GetDefaultValue($sAttCode));
		return true;
	}

	/**
     * Helper to copy the value of an attribute to another one
     * Suitable for use as a lifecycle action
     *
     * @api
	 */	 	
	public function Copy($sDestAttCode, $sSourceAttCode)
	{
		$oTypeValueToCopy = MetaModel::GetAttributeDef(get_class($this), $sSourceAttCode);
		$oTypeValueDest = MetaModel::GetAttributeDef(get_class($this), $sDestAttCode);
		if ($oTypeValueToCopy instanceof AttributeText && $oTypeValueDest instanceof AttributeText)
		{
			if ($oTypeValueToCopy->GetFormat() == $oTypeValueDest->GetFormat())
			{
				$sValueToCopy = $this->Get($sSourceAttCode);
			}
			else
			{
				if ($oTypeValueToCopy->GetFormat() == 'text')// and $oTypeValueDest->GetFormat()=='HTML'
				{
					$sValueToCopy = $this->GetAsHTML($sSourceAttCode);
				}
				else
				{// $oTypeValueToCopy->GetFormat() == 'HTML' and $oTypeValueDest->GetFormat()=='Text'
					$sValueToCopy = utils::HtmlToText($this->Get($sSourceAttCode));
				}
			}
		}
		else
		{
			$sValueToCopy = $this->Get($sSourceAttCode);
		}
		$this->Set($sDestAttCode, $sValueToCopy);

		return true;
	}

    /**
     * Helper to set the current date/time for the given attribute
     * Suitable for use as a lifecycle action
     *
     * @api
     *
     * @param string $sAttCode
     *
     * @return bool
     *
     * @throws CoreException
     * @throws CoreUnexpectedValue
     */
	public function SetCurrentDate($sAttCode)
	{
		$this->Set($sAttCode, time());
		return true;
	}

    /**
     * Helper to set the current logged in user for the given attribute
     * Suitable for use as a lifecycle action
     *
     * @api
     *
     * @param string $sAttCode
     *
     * @return bool
     *
     * @throws CoreException
     * @throws CoreUnexpectedValue
     */
	public function SetCurrentUser($sAttCode)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		if ($oAttDef instanceof AttributeString)
		{
			// Note: the user friendly name is the contact friendly name if a contact is attached to the logged in user
			$this->Set($sAttCode, UserRights::GetUserFriendlyName());
		}
		else
		{
			if ($oAttDef->IsExternalKey())
			{
				/** @var \AttributeExternalKey $oAttDef */
				if ($oAttDef->GetTargetClass() != 'User')
				{
					throw new Exception("SetCurrentUser: the attribute $sAttCode must be an external key to 'User', found '".$oAttDef->GetTargetClass()."'");
				}
			}
			$this->Set($sAttCode, UserRights::GetUserId());
		}
		return true;
	}

    /**
     * Helper to set the current logged in CONTACT for the given attribute
     * Suitable for use as a lifecycle action
     *
     * @api
     *
     * @param string $sAttCode
     *
     * @return bool
     * @throws ArchivedObjectException
     * @throws CoreException
     * @throws CoreUnexpectedValue
     */
	public function SetCurrentPerson($sAttCode)
	{
		$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
		if ($oAttDef instanceof AttributeString)
		{
			$iPerson = UserRights::GetContactId();
			if ($iPerson == 0)
			{
				$this->Set($sAttCode, '');
			}
			else
			{
				$oPerson = MetaModel::GetObject('Person', $iPerson);
				$this->Set($sAttCode, $oPerson->Get('friendlyname'));
			}
		}
		else
		{
			if ($oAttDef->IsExternalKey())
			{
				/** @var \AttributeExternalKey $oAttDef */
				if (!MetaModel::IsParentClass($oAttDef->GetTargetClass(), 'Person'))
				{
					throw new Exception("SetCurrentContact: the attribute $sAttCode must be an external key to 'Person' or any other class above 'Person', found '".$oAttDef->GetTargetClass()."'");
				}
			}
			$this->Set($sAttCode, UserRights::GetContactId());
		}
		return true;
	}

    /**
     * Helper to set the time elapsed since a reference point
     * Suitable for use as a lifecycle action
     *
     * @api
     *
     * @param string      $sAttCode
     * @param string      $sRefAttCode
     * @param string|null $sWorkingTimeComputer
     *
     * @return bool
     * @throws ArchivedObjectException
     * @throws CoreException
     * @throws CoreUnexpectedValue
     */
	public function SetElapsedTime($sAttCode, $sRefAttCode, $sWorkingTimeComputer = null)
	{
		if (is_null($sWorkingTimeComputer))
		{
			$sWorkingTimeComputer = class_exists('SLAComputation') ? 'SLAComputation' : 'DefaultWorkingTimeComputer';
		}
		$oComputer = new $sWorkingTimeComputer();
		$aCallSpec = array($oComputer, 'GetOpenDuration');
		if (!is_callable($aCallSpec))
		{
			throw new CoreException("Unknown class/verb '$sWorkingTimeComputer/GetOpenDuration'");
		}

		$iStartTime = AttributeDateTime::GetAsUnixSeconds($this->Get($sRefAttCode));
		$oStartDate = new DateTime('@'.$iStartTime); // setTimestamp not available in PHP 5.2
		$oEndDate = new DateTime(); // now

		if (class_exists('WorkingTimeRecorder'))
		{
			$sClass = get_class($this);
			WorkingTimeRecorder::Start($this, time(), "DBObject-SetElapsedTime-$sAttCode-$sRefAttCode", 'Core:ExplainWTC:ElapsedTime', array("Class:$sClass/Attribute:$sAttCode"));
		}
		$iElapsed = call_user_func($aCallSpec, $this, $oStartDate, $oEndDate);
		if (class_exists('WorkingTimeRecorder'))
		{
			WorkingTimeRecorder::End();
		}

		$this->Set($sAttCode, $iElapsed);
		return true;
	}


    /**
     * Create query parameters (SELECT ... WHERE service = :this->service_id)
     * to be used with the APIs DBObjectSearch/DBObjectSet
     *
     * Starting 2.0.2 the parameters are computed on demand, at the lowest level,
     * in VariableExpression::Render()
     *
     * @internal
     *
     * @param string $sArgName
     *
     * @return array
     */
	public function ToArgsForQuery($sArgName = 'this')
	{
		return array($sArgName.'->object()' => $this);
	}

    /**
     * Create template placeholders: now equivalent to ToArgsForQuery since the actual
     * template placeholders are computed on demand.
     *
     * @internal
     *
     * @param string $sArgName
     *
     * @return array
     */
	public function ToArgs($sArgName = 'this')
	{
		return $this->ToArgsForQuery($sArgName);
	}

    /**
     * Get various representations of the value, for insertion into a template (e.g. in Notifications)
     *
     * @internal
     *
     * @param string $sPlaceholderAttCode
     *
     * @return int|mixed|string|null
     * @throws ArchivedObjectException
     * @throws CoreException
     * @throws DictExceptionMissingString
     */
	public function GetForTemplate($sPlaceholderAttCode)
	{
		$ret = null;
		if (preg_match('/^([^-]+)-(>|&gt;)(.+)$/', $sPlaceholderAttCode, $aMatches)) // Support both syntaxes: this->xxx or this-&gt;xxx for HTML compatibility
		{
			$sExtKeyAttCode = $aMatches[1];
			$sRemoteAttCode = $aMatches[3];
			if (!MetaModel::IsValidAttCode(get_class($this), $sExtKeyAttCode))
			{
				throw new CoreException("Unknown attribute '$sExtKeyAttCode' for the class ".get_class($this));
			}
			
			$oKeyAttDef = MetaModel::GetAttributeDef(get_class($this), $sExtKeyAttCode);
			if (!$oKeyAttDef instanceof AttributeExternalKey)
			{
				throw new CoreException("'$sExtKeyAttCode' is not an external key of the class ".get_class($this));
			}
			$sRemoteClass = $oKeyAttDef->GetTargetClass();
			$oRemoteObj = MetaModel::GetObject($sRemoteClass, $this->GetStrict($sExtKeyAttCode), false);
			if (is_null($oRemoteObj))
			{
				$ret = Dict::S('UI:UndefinedObject');
			}
			else
			{
				// Recurse
				$ret  = $oRemoteObj->GetForTemplate($sRemoteAttCode);
			}
		}
		else 
		{
			switch($sPlaceholderAttCode)
			{
				case 'id':
				$ret = $this->GetKey();
				break;
				
				case 'name()':
				$ret = $this->GetName();
				break;

				default:
				if (preg_match('/^([^(]+)\\((.*)\\)$/', $sPlaceholderAttCode, $aMatches))
				{
					$sVerb = $aMatches[1];
					$sAttCode = $aMatches[2];
				}
				else
				{
					$sVerb = '';
					$sAttCode = $sPlaceholderAttCode;
				}

				if ($sVerb == 'hyperlink')
				{
					$sPortalId = ($sAttCode === '') ? 'console' : $sAttCode;
					if (!array_key_exists($sPortalId, self::$aPortalToURLMaker))
					{
						throw new Exception("Unknown portal id '$sPortalId' in placeholder '$sPlaceholderAttCode''");
					}
					$ret = $this->GetHyperlink(self::$aPortalToURLMaker[$sPortalId], false);
				}
				else
				{
					$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
					$ret = $oAttDef->GetForTemplate($this->Get($sAttCode), $sVerb, $this);
				}
			}
			if ($ret === null)
			{
				$ret = '';
			}
		}
		return $ret;
	}

	static protected $aPortalToURLMaker = array('console' => 'iTopStandardURLMaker', 'portal' => 'PortalURLMaker');

	/**
	 * Associate a portal to a class that implements iDBObjectURLMaker,
	 * and which will be invoked with placeholders like $this->org_id->hyperlink(portal)$
	 *
     * @internal
     *
	 * @param string $sPortalId Identifies the portal. Conventions: the main portal is 'console', The user requests portal is 'portal'.
	 * @param string $sUrlMakerClass
	 */
	static public function RegisterURLMakerClass($sPortalId, $sUrlMakerClass)
	{
		self::$aPortalToURLMaker[$sPortalId] = $sUrlMakerClass;
	}

	/**
	 * this method is called before the object is inserted into DB.
     *
     *
	 * @overwritable-hook You can extend this method in order to provide your own logic.
	 */
	protected function OnInsert()
	{
	}

    /**
     * this method is called after the object is inserted into DB.
     *
     * @overwritable-hook You can extend this method in order to provide your own logic.
     */
	protected function AfterInsert()
	{
	}

    /**
     * this method is called before the object is updated into DB.
     *
     * @overwritable-hook You can extend this method in order to provide your own logic.
     */
	protected function OnUpdate()
	{
	}

    /**
     * @overwritable-hook You can extend this method in order to provide your own logic.
     *
     * This method is called after the object is updated into DB, and just before the {@see DBObject::Reload()} call.
     *
     * Warning : do not use {@see DBObject::ListChanges()} as it will return an empty array !
     * Use instead {@see DBObject::ListPreviousValuesForUpdatedAttributes()} to get modified fields and their previous values,
     * and {@see DBObject::Get()} to get the persisted value for a given attribute.
     *
     * @since 2.7.0 N°2293 can access object changes by calling {@see DBObject::ListPreviousValuesForUpdatedAttributes()}
     */
	protected function AfterUpdate()
	{
	}

    /**
     * this method is called before the object is deleted into DB.
     *
     * @overwritable-hook You can extend this method in order to provide your own logic.
     */
	protected function OnDelete()
	{
	}

    /**
     * this method is called after the object is deleted into DB.
     *
     * @overwritable-hook You can extend this method in order to provide your own logic.
     */
	protected function AfterDelete()
	{
	}


	/**
	 * Common to the recording of link set changes (add/remove/modify)
	 *
     * @internal
     *
	 * @param $iLinkSetOwnerId
	 * @param \AttributeLinkedSet $oLinkSet
	 * @param $sChangeOpClass
	 * @param array $aOriginalValues
	 *
	 * @return \DBObject|null
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	private function PrepareChangeOpLinkSet($iLinkSetOwnerId, $oLinkSet, $sChangeOpClass, $aOriginalValues = null)
	{
		if ($iLinkSetOwnerId <= 0)
		{
			return null;
		}

		if (!is_subclass_of($oLinkSet->GetHostClass(), 'CMDBObject'))
		{
			// The link set owner class does not keep track of its history
			return null;
		}

		// Determine the linked item class and id
		//
		if ($oLinkSet->IsIndirect())
		{
			// The "item" is on the other end (N-N links)
			/** @var \AttributeLinkedSetIndirect $oLinkSet */
			$sExtKeyToRemote = $oLinkSet->GetExtKeyToRemote();
			$oExtKeyToRemote = MetaModel::GetAttributeDef(get_class($this), $sExtKeyToRemote);
			/** @var \AttributeExternalKey $oExtKeyToRemote */
			$sItemClass = $oExtKeyToRemote->GetTargetClass();
			if ($aOriginalValues)
			{
				// Get the value from the original values
				$iItemId = $aOriginalValues[$sExtKeyToRemote];
			}
			else
			{
				$iItemId = $this->Get($sExtKeyToRemote);
			}
		}
		else
		{
			// I am the "item" (1-N links)
			$sItemClass = get_class($this);
			$iItemId = $this->GetKey();
		}

		// Get the remote object, to determine its exact class
		// Possible optimization: implement a tool in MetaModel, to get the final class of an object (not always querying + query reduced to a select on the root table!
		$oOwner = MetaModel::GetObject($oLinkSet->GetHostClass(), $iLinkSetOwnerId, false);
		if ($oOwner)
		{
			$sLinkSetOwnerClass = get_class($oOwner);
			
			$oMyChangeOp = MetaModel::NewObject($sChangeOpClass);
			$oMyChangeOp->Set("objclass", $sLinkSetOwnerClass);
			$oMyChangeOp->Set("objkey", $iLinkSetOwnerId);
			$oMyChangeOp->Set("attcode", $oLinkSet->GetCode());
			$oMyChangeOp->Set("item_class", $sItemClass);
			$oMyChangeOp->Set("item_id", $iItemId);
			return $oMyChangeOp;
		}
		else
		{
			// Depending on the deletion order, it may happen that the id is already invalid... ignore
			return null;
		}
	}

	/**
	 * This object has been created/deleted, record that as a change in link sets pointing to this (if any)
	 *
	 * @internal
	 */
	private function RecordLinkSetListChange($bAdd = true)
	{
		foreach(MetaModel::GetTrackForwardExternalKeys(get_class($this)) as $sExtKeyAttCode => $oLinkSet)
		{
			/** @var \AttributeLinkedSet $oLinkSet */
			if (($oLinkSet->GetTrackingLevel() & LINKSET_TRACKING_LIST) == 0) continue;
			
			$iLinkSetOwnerId  = $this->Get($sExtKeyAttCode);
			$oMyChangeOp = $this->PrepareChangeOpLinkSet($iLinkSetOwnerId, $oLinkSet, 'CMDBChangeOpSetAttributeLinksAddRemove');
			if ($oMyChangeOp)
			{
				if ($bAdd)
				{
					$oMyChangeOp->Set("type", "added");
				}
				else
				{
					$oMyChangeOp->Set("type", "removed");
				}
				$oMyChangeOp->DBInsertNoReload();
			}
		}
	}

	/**
	 * @internal
	 */
	protected function RecordObjCreation()
	{
		$this->RecordLinkSetListChange(true);
	}

    /**
     * @internal
     */
	protected function RecordObjDeletion($objkey)
	{
		$this->RecordLinkSetListChange(false);
	}

    /**
     * @internal
     */
	protected function RecordAttChanges(array $aValues, array $aOrigValues)
	{
		foreach(MetaModel::GetTrackForwardExternalKeys(get_class($this)) as $sExtKeyAttCode => $oLinkSet)
		{

			if (array_key_exists($sExtKeyAttCode, $aValues))
			{
				/** @var \AttributeLinkedSet $oLinkSet */
				if (($oLinkSet->GetTrackingLevel() & LINKSET_TRACKING_LIST) == 0) continue;

				// Keep track of link added/removed
				//
				$iLinkSetOwnerNext = $aValues[$sExtKeyAttCode];
				$oMyChangeOp = $this->PrepareChangeOpLinkSet($iLinkSetOwnerNext, $oLinkSet, 'CMDBChangeOpSetAttributeLinksAddRemove');
				if ($oMyChangeOp)
				{
					$oMyChangeOp->Set("type", "added");
					$oMyChangeOp->DBInsertNoReload();
				}

				$iLinkSetOwnerPrevious = $aOrigValues[$sExtKeyAttCode];
				$oMyChangeOp = $this->PrepareChangeOpLinkSet($iLinkSetOwnerPrevious, $oLinkSet, 'CMDBChangeOpSetAttributeLinksAddRemove', $aOrigValues);
				if ($oMyChangeOp)
				{
					$oMyChangeOp->Set("type", "removed");
					$oMyChangeOp->DBInsertNoReload();
				}
			}
			else
			{
				// Keep track of link changes
				//
				if (($oLinkSet->GetTrackingLevel() & LINKSET_TRACKING_DETAILS) == 0) continue;
				
				$iLinkSetOwnerId  = $this->Get($sExtKeyAttCode);
				$oMyChangeOp = $this->PrepareChangeOpLinkSet($iLinkSetOwnerId, $oLinkSet, 'CMDBChangeOpSetAttributeLinksTune');
				if ($oMyChangeOp)
				{
					$oMyChangeOp->Set("link_id", $this->GetKey());
					$oMyChangeOp->DBInsertNoReload();
				}
			}
		}
	}


    /**
     * implement relations
     *
     * Return an empty set for the parent of all
     *
     * this way of implementing the relations suffers limitations (not handling the redundancy)
     * and you should consider defining those things in XML
     *
     * @internal
     * @deprecated
     */
	public static function GetRelationQueries($sRelCode)
	{
		return array();
	}
	
    /**
     * Reserved: do not overload
     *
     * @internal
     */
	public static function GetRelationQueriesEx($sRelCode)
	{
		return array();
	}

	/**
	 * Use GetRelatedObjectsDown/Up instead to take redundancy into account
     *
     * @internal
     * @deprecated
	 */
	public function GetRelatedObjects($sRelCode, $iMaxDepth = 99, &$aResults = array())
	{
		// Temporary patch: until the impact analysis GUI gets rewritten,
		// let's consider that "depends on" is equivalent to "impacts/up"
		// The current patch has been implemented in DBObject and MetaModel
		$sHackedRelCode = $sRelCode;
		$bDown = true;
		if ($sRelCode == 'depends on')
		{
			$sHackedRelCode = 'impacts';
			$bDown = false;
		}
		foreach (MetaModel::EnumRelationQueries(get_class($this), $sHackedRelCode, $bDown) as $sDummy => $aQueryInfo)
		{
			$sQuery = $bDown ? $aQueryInfo['sQueryDown'] : $aQueryInfo['sQueryUp'];
			//$bPropagate = $aQueryInfo["bPropagate"];
			//$iDepth = $bPropagate ? $iMaxDepth - 1 : 0;
			$iDepth = $iMaxDepth - 1;

			// Note: the loop over the result set has been written in an unusual way for error reporting purposes
			// In the case of a wrong query parameter name, the error occurs on the first call to Fetch,
			// thus we need to have this first call into the try/catch, but
			// we do NOT want to nest the try/catch for the error message to be clear
			try
			{
				$oFlt = DBObjectSearch::FromOQL($sQuery);
				$oObjSet = new DBObjectSet($oFlt, array(), $this->ToArgsForQuery());
				$oObj = $oObjSet->Fetch();
			}
			catch (Exception $e)
			{
				$sClassOfDefinition = $aQueryInfo['_legacy_'] ? get_class($this).'(or a parent)::GetRelationQueries()' : $aQueryInfo['sDefinedInClass'];
				throw new Exception("Wrong query for the relation $sRelCode/$sClassOfDefinition/{$aQueryInfo['sNeighbour']}: ".$e->getMessage());
			}
			if ($oObj)
			{
				do
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
				while ($oObj = $oObjSet->Fetch());
			}
		}
		return $aResults;
	}

    /**
     * Compute the "RelatedObjects" (forward or "down" direction) for the object
     * for the specified relation
     *
     * @internal
     *
     * @param string $sRelCode  The code of the relation to use for the computation
     * @param int    $iMaxDepth Maximum recursion depth
     * @param bool   $bEnableRedundancy
     *
     * @return RelationGraph The graph of all the related objects
     * @throws CoreException
     */
	public function GetRelatedObjectsDown($sRelCode, $iMaxDepth = 99, $bEnableRedundancy = true)
	{
		$oGraph = new RelationGraph();
		$oGraph->AddSourceObject($this);
		$oGraph->ComputeRelatedObjectsDown($sRelCode, $iMaxDepth, $bEnableRedundancy);
		return $oGraph;
	}

    /**
     * Compute the "RelatedObjects" (reverse or "up" direction) for the object
     * for the specified relation
     *
     * @internal
     *
     * @param string $sRelCode  The code of the relation to use for the computation
     * @param int    $iMaxDepth Maximum recursion depth
     * @param bool   $bEnableRedundancy
     *
     * @return RelationGraph The graph of all the related objects
     * @throws CoreException
     */
	public function GetRelatedObjectsUp($sRelCode, $iMaxDepth = 99, $bEnableRedundancy = true)
	{
		$oGraph = new RelationGraph();
		$oGraph->AddSourceObject($this);
		$oGraph->ComputeRelatedObjectsUp($sRelCode, $iMaxDepth, $bEnableRedundancy);
		return $oGraph;
	}

	/**
	 * @internal
	 *
	 * @param bool $bAllowAllData
	 *
	 * @return array keys : attribute (AttributeDefinition), objects (set of linked objects)
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	protected function GetReferencingObjects($bAllowAllData = false)
	{
		$aDependentObjects = array();
		$aRererencingMe = MetaModel::EnumReferencingClasses(get_class($this));
		foreach($aRererencingMe as $sRemoteClass => $aExtKeys)
		{
			foreach($aExtKeys as $sExtKeyAttCode => $oExtKeyAttDef)
			{
				// skip if this external key is behind an external field
				/** @var \AttributeDefinition $oExtKeyAttDef */
				if (!$oExtKeyAttDef->IsExternalKey(EXTKEY_ABSOLUTE)) continue;

				$oSearch = new DBObjectSearch($sRemoteClass);
				$oSearch->AddCondition($sExtKeyAttCode, $this->GetKey(), '=');
				if ($bAllowAllData)
				{
					$oSearch->AllowAllData();
				}
				$oSet = new CMDBObjectSet($oSearch);
				if ($oSet->CountExceeds(0))
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

	/**
     * @internal
     *
	 * @param \DeletionPlan $oDeletionPlan
	 * @param array $aVisited
	 * @param int $iDeleteOption
	 *
	 * @throws \CoreException
	 */
	private function MakeDeletionPlan(&$oDeletionPlan, $aVisited = array(), $iDeleteOption = null)
	{
		static $iLoopTimeLimit = null;
		if ($iLoopTimeLimit == null)
		{
			$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
		}
		$sClass = get_class($this);
		$iThisId = $this->GetKey();

		$oDeletionPlan->AddToDelete($this, $iDeleteOption);

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

		// Getting and setting time limit are not symetric:
		// www.php.net/manual/fr/function.set-time-limit.php#72305
		$iPreviousTimeLimit = ini_get('max_execution_time');

		foreach ($aDependentObjects as $sRemoteClass => $aPotentialDeletes)
		{
			foreach ($aPotentialDeletes as $sRemoteExtKey => $aData)
			{
				set_time_limit($iLoopTimeLimit);

				/** @var \AttributeExternalKey $oAttDef */
				$oAttDef = $aData['attribute'];
				$iDeletePropagationOption = $oAttDef->GetDeletionPropagationOption();
				/** @var \DBObjectSet $oDepSet */
				$oDepSet = $aData['objects'];
				$oDepSet->Rewind();
				while ($oDependentObj = $oDepSet->fetch())
				{
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
		set_time_limit($iPreviousTimeLimit);
	}

	/**
	 * Caching relying on an object set is not efficient since 2.0.3
	 * Use GetSynchroData instead
	 *
	 * Get all the synchro replica related to this object
     *
     * @internal
     * @deprecated
	 *
	 * @return DBObjectSet Set with two columns: R=SynchroReplica S=SynchroDataSource
	 * @throws \OQLException
	 */
	public function GetMasterReplica()
	{
		$sOQL = "SELECT replica,datasource FROM SynchroReplica AS replica JOIN SynchroDataSource AS datasource ON replica.sync_source_id=datasource.id WHERE replica.dest_class = :dest_class AND replica.dest_id = :dest_id";
		$oReplicaSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), array() /* order by*/, array('dest_class' => get_class($this), 'dest_id' => $this->GetKey()));
		return $oReplicaSet;
	}

	/**
	 * Get all the synchro data related to this object
     *
     * @internal
	 *
	 * @return array of data_source_id => array
	 *   * 'source' => $oSource,
	 *   * 'attributes' => array of $oSynchroAttribute
	 *   * 'replica' => array of $oReplica (though only one should exist, misuse of the data sync can have this consequence)
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function GetSynchroData()
	{
		if (is_null($this->m_aSynchroData))
		{
			$sOQL = "SELECT replica,datasource FROM SynchroReplica AS replica JOIN SynchroDataSource AS datasource ON replica.sync_source_id=datasource.id WHERE replica.dest_class = :dest_class AND replica.dest_id = :dest_id";
			$oReplicaSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), array() /* order by*/, array('dest_class' => get_class($this), 'dest_id' => $this->GetKey()));
			$this->m_aSynchroData = array();
			while($aData = $oReplicaSet->FetchAssoc())
			{
				/** @var \DBObject[] $aData */
				$iSourceId = $aData['datasource']->GetKey();
				if (!array_key_exists($iSourceId, $this->m_aSynchroData))
				{
					$aAttributes = array();
					$oAttrSet = $aData['datasource']->Get('attribute_list');
					while($oSyncAttr = $oAttrSet->Fetch())
					{
						/** @var \DBObject $oSyncAttr */
						$aAttributes[$oSyncAttr->Get('attcode')] = $oSyncAttr;
					}
					$this->m_aSynchroData[$iSourceId] = array(
						'source' => $aData['datasource'],
						'attributes' => $aAttributes,
						'replica' => array()
					);
				}
				// Assumption: $aData['datasource'] will not be null because the data source id is always set...
				$this->m_aSynchroData[$iSourceId]['replica'][] = $aData['replica'];
			}
		}
		return $this->m_aSynchroData;
	}

    /**
     *
     * @internal
     *
     * @param string $sAttCode
     * @param array $aReason
     *
     * @return int
     * @throws ArchivedObjectException
     * @throws CoreException
     * @throws CoreUnexpectedValue
     * @throws MySQLException
     * @throws OQLException
     */
	public function GetSynchroReplicaFlags($sAttCode, &$aReason)
	{
		$iFlags = OPT_ATT_NORMAL;
		foreach ($this->GetSynchroData() as $iSourceId => $aSourceData)
		{
			if ($iSourceId == SynchroExecution::GetCurrentTaskId())
			{
				// Ignore the current task (check to write => ok)
				continue;
			}
			// Assumption: one replica - take the first one!
			$oReplica = reset($aSourceData['replica']);
			$oSource = $aSourceData['source'];
			if (array_key_exists($sAttCode, $aSourceData['attributes']))
			{
				/** @var \DBObject $oSyncAttr */
				$oSyncAttr = $aSourceData['attributes'][$sAttCode];
				if (($oSyncAttr->Get('update') == 1) && ($oSyncAttr->Get('update_policy') == 'master_locked'))
				{
					$iFlags |= OPT_ATT_SLAVE;
					/** @var \SynchroDataSource $oSource */
					$sUrl = $oSource->GetApplicationUrl($this, $oReplica);
					$aReason[] = array('name' => $oSource->GetName(), 'description' => $oSource->Get('description'), 'url_application' => $sUrl);
				}
			}
		}
		return $iFlags;
	}

	/**
     *
     * @internal
     *
	 * @return bool true if this object is used in a data synchro
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @internal
	 * @see \SynchroDataSource
	 */
	public function InSyncScope()
	{
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
				$aSynchroClasses[] = $sTarget;
			}
		}
		
		foreach($aSynchroClasses as $sClass)
		{
			if ($this instanceof $sClass)
			{
				return true;
			}
		}
		return false;
	}
	/////////////////////////////////////////////////////////////////////////
	//
	// Experimental iDisplay implementation
	//
	/////////////////////////////////////////////////////////////////////////

    /**
     * @internal
     *
     * @param string $sContextParam
     *
     * @return string|null
     */
	public static function MapContextParam($sContextParam)
	{
		return null;
	}

    /**
     * @internal
     *
     * @return String
     * @throws ArchivedObjectException
     * @throws CoreException
     */
	public function GetHilightClass()
	{
		$sCode = $this->ComputeHighlightCode();
		if($sCode != '')
		{
			$aHighlightScale = MetaModel::GetHighlightScale(get_class($this));
			if (array_key_exists($sCode, $aHighlightScale))
			{
				return $aHighlightScale[$sCode]['color'];
			}
		}
		return HILIGHT_CLASS_NONE;
	}

    /**
     * @internal
     *
     * @param WebPage $oPage
     * @param bool    $bEditMode
     *
     * @throws ArchivedObjectException
     * @throws CoreException
     * @throws DictExceptionMissingString
     */
	public function DisplayDetails(WebPage $oPage, $bEditMode = false)
	{
		$oPage->add('<h1>'.MetaModel::GetName(get_class($this)).': '.$this->GetName().'</h1>');
		$aValues = array();
		$aList = MetaModel::FlattenZList(MetaModel::GetZListItems(get_class($this), 'details'));
		if (empty($aList))
		{
			$aList = array_keys(MetaModel::ListAttributeDefs(get_class($this)));
		}
		foreach($aList as $sAttCode)
		{
			$aValues[$sAttCode] = array('label' => MetaModel::GetLabel(get_class($this), $sAttCode), 'value' => $this->GetAsHTML($sAttCode));
		}
		$oPage->details($aValues);
	}

    /**
     * Computes a text-like fingerprint identifying the content of the object
     * but excluding the specified columns
     *
     * @internal
     *
     * @param $aExcludedColumns array The list of columns to exclude
     *
     * @return string
     * @throws ArchivedObjectException
     * @throws CoreException
     */
	public function Fingerprint($aExcludedColumns = array())
	{
		$sFingerprint = '';
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
		{
			if (!in_array($sAttCode, $aExcludedColumns))
			{
				if ($oAttDef->IsPartOfFingerprint())
				{
					$sFingerprint .= chr(0).$oAttDef->Fingerprint($this->Get($sAttCode));
				}
			}
		}
		return $sFingerprint;
	}

	/**
	 * Execute a set of scripted actions onto the current object
	 * See ExecAction for the syntax and features of the scripted actions
     *
     * @internal
	 *
	 * @param $aActions array of statements (e.g. "set(name, Made after $source->name$)")
	 * @param $aSourceObjects array of Alias => Context objects (Convention: some statements require the 'source' element
	 * @throws Exception
	 */
	public function ExecActions($aActions, $aSourceObjects)
	{
		foreach($aActions as $sAction)
		{
			try
			{
				if (preg_match('/^(\S*)\s*\((.*)\)$/ms', $sAction, $aMatches)) // multiline and newline matched by a dot
				{
					$sVerb = trim($aMatches[1]);
					$sParams = $aMatches[2];

					// the coma is the separator for the parameters
					// comas can be escaped: \,
					$sParams = str_replace(array("\\\\", "\\,"), array("__backslash__", "__coma__"), $sParams);
					$sParams = trim($sParams);

					if (strlen($sParams) == 0)
					{
						$aParams = array();
					}
					else
					{
						$aParams = explode(',', $sParams);
						foreach ($aParams as &$sParam)
						{
							$sParam = str_replace(array("__backslash__", "__coma__"), array("\\", ","), $sParam);
							$sParam = trim($sParam);
						}
					}
					$this->ExecAction($sVerb, $aParams, $aSourceObjects);
				}
				else
				{
					throw new Exception("Invalid syntax");
				}
			}
			catch(Exception $e)
			{
				throw new Exception('Action: '.$sAction.' - '.$e->getMessage());
			}
		}
	}

	/**
	 * Helper to copy an attribute between two objects (in memory)
	 * Originally designed for ExecAction()
     *
     * @internal
	 *
	 * @param \DBObject $oSourceObject
	 * @param $sSourceAttCode
	 * @param $sDestAttCode
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function CopyAttribute($oSourceObject, $sSourceAttCode, $sDestAttCode)
	{
		if ($sSourceAttCode == 'id')
		{
			$oSourceAttDef = null;
		}
		else
		{
			if (!MetaModel::IsValidAttCode(get_class($this), $sDestAttCode))
			{
				throw new Exception("Unknown attribute ".get_class($this)."::".$sDestAttCode);
			}
			if (!MetaModel::IsValidAttCode(get_class($oSourceObject), $sSourceAttCode))
			{
				throw new Exception("Unknown attribute ".get_class($oSourceObject)."::".$sSourceAttCode);
			}

			$oSourceAttDef = MetaModel::GetAttributeDef(get_class($oSourceObject), $sSourceAttCode);
		}
		if (is_object($oSourceAttDef) && $oSourceAttDef->IsLinkSet())
		{
			// The copy requires that we create a new object set (the semantic of DBObject::Set is unclear about link sets)
			/** @var \AttributeLinkedSet $oSourceAttDef */
			$oDestSet = DBObjectSet::FromScratch($oSourceAttDef->GetLinkedClass());
			$oSourceSet = $oSourceObject->Get($sSourceAttCode);
			$oSourceSet->Rewind();
			/** @var \DBObject $oSourceLink */
			while ($oSourceLink = $oSourceSet->Fetch())
			{
				// Clone the link
				$sLinkClass = get_class($oSourceLink);
				$oLinkClone = MetaModel::NewObject($sLinkClass);
				foreach(MetaModel::ListAttributeDefs($sLinkClass) as $sAttCode => $oAttDef)
				{
					// As of now, ignore other attribute (do not attempt to recurse!)
					if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
					{
						$oLinkClone->Set($sAttCode, $oSourceLink->Get($sAttCode));
					}
				}

				// Not necessary - this will be handled by DBObject
				// $oLinkClone->Set($oSourceAttDef->GetExtKeyToMe(), 0);
				$oDestSet->AddObject($oLinkClone);
			}
			$this->Set($sDestAttCode, $oDestSet);
		}
		else
		{
			$this->Set($sDestAttCode, $oSourceObject->Get($sSourceAttCode));
		}
	}

	/**
	 * Execute a scripted action onto the current object
	 *    - clone (att1, att2, att3, ...)
	 *    - clone_scalars ()
	 *    - copy (source_att, dest_att)
	 *    - reset (att)
	 *    - nullify (att)
	 *    - set (att, value (placeholders $source->att$ or $current_date$, or $current_contact_id$, ...))
	 *    - append (att, value (placeholders $source->att$ or $current_date$, or $current_contact_id$, ...))
	 *    - add_to_list (source_key_att, dest_att)
	 *    - add_to_list (source_key_att, dest_att, lnk_att, lnk_att_value)
	 *    - apply_stimulus (stimulus)
	 *    - call_method (method_name)
     *
     *
     * @internal
	 *
	 * @param $sVerb string Any of the verb listed above (e.g. "set")
	 * @param $aParams array of strings (e.g. array('name', 'copied from $source->name$')
	 * @param $aSourceObjects array of Alias => Context objects (Convention: some statements require the 'source' element
	 * @throws CoreException
	 * @throws CoreUnexpectedValue
	 * @throws Exception
	 */
	public function ExecAction($sVerb, $aParams, $aSourceObjects)
	{
		switch($sVerb)
		{
			case 'clone':
				if (!array_key_exists('source', $aSourceObjects))
				{
					throw new Exception('Missing conventional "source" object');
				}
				$oObjectToRead = $aSourceObjects['source'];
				foreach($aParams as $sAttCode)
				{
					$this->CopyAttribute($oObjectToRead, $sAttCode, $sAttCode);
				}
				break;

			case 'clone_scalars':
				if (!array_key_exists('source', $aSourceObjects))
				{
					throw new Exception('Missing conventional "source" object');
				}
				$oObjectToRead = $aSourceObjects['source'];
				foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
				{
					if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
					{
						$this->CopyAttribute($oObjectToRead, $sAttCode, $sAttCode);
					}
				}
				break;

			case 'copy':
				if (!array_key_exists('source', $aSourceObjects))
				{
					throw new Exception('Missing conventional "source" object');
				}
				$oObjectToRead = $aSourceObjects['source'];
				if (!array_key_exists(0, $aParams))
				{
					throw new Exception('Missing argument #1: source attribute');
				}
				$sSourceAttCode = $aParams[0];
				if (!array_key_exists(1, $aParams))
				{
					throw new Exception('Missing argument #2: target attribute');
				}
				$sDestAttCode = $aParams[1];
				$this->CopyAttribute($oObjectToRead, $sSourceAttCode, $sDestAttCode);
				break;

			case 'reset':
				if (!array_key_exists(0, $aParams))
				{
					throw new Exception('Missing argument #1: target attribute');
				}
				$sAttCode = $aParams[0];
				if (!MetaModel::IsValidAttCode(get_class($this), $sAttCode))
				{
					throw new Exception("Unknown attribute ".get_class($this)."::".$sAttCode);
				}
				$this->Set($sAttCode, $this->GetDefaultValue($sAttCode));
				break;

			case 'nullify':
				if (!array_key_exists(0, $aParams))
				{
					throw new Exception('Missing argument #1: target attribute');
				}
				$sAttCode = $aParams[0];
				if (!MetaModel::IsValidAttCode(get_class($this), $sAttCode))
				{
					throw new Exception("Unknown attribute ".get_class($this)."::".$sAttCode);
				}
				$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
				$this->Set($sAttCode, $oAttDef->GetNullValue());
				break;

			case 'set':
				if (!array_key_exists(0, $aParams))
				{
					throw new Exception('Missing argument #1: target attribute');
				}
				$sAttCode = $aParams[0];
				if (!MetaModel::IsValidAttCode(get_class($this), $sAttCode))
				{
					throw new Exception("Unknown attribute ".get_class($this)."::".$sAttCode);
				}
				if (!array_key_exists(1, $aParams))
				{
					throw new Exception('Missing argument #2: value to set');
				}
				$sRawValue = $aParams[1];
				$aContext = array();
				foreach ($aSourceObjects as $sAlias => $oObject)
				{
					$aContext = array_merge($aContext, $oObject->ToArgs($sAlias));
				}
				$aContext['current_contact_id'] = UserRights::GetContactId();
				$aContext['current_contact_friendlyname'] = UserRights::GetUserFriendlyName();
				$aContext['current_date'] = date(AttributeDate::GetSQLFormat());
				$aContext['current_time'] = date(AttributeDateTime::GetSQLTimeFormat());
				$sValue = MetaModel::ApplyParams($sRawValue, $aContext);
				$this->Set($sAttCode, $sValue);
				break;

			case 'append':
				if (!array_key_exists(0, $aParams))
				{
					throw new Exception('Missing argument #1: target attribute');
				}
				$sAttCode = $aParams[0];
				if (!MetaModel::IsValidAttCode(get_class($this), $sAttCode))
				{
					throw new Exception("Unknown attribute ".get_class($this)."::".$sAttCode);
				}
				if (!array_key_exists(1, $aParams))
				{
					throw new Exception('Missing argument #2: value to append');
				}
				$sRawAddendum = $aParams[1];
				$aContext = array();
				foreach ($aSourceObjects as $sAlias => $oObject)
				{
					$aContext = array_merge($aContext, $oObject->ToArgs($sAlias));
				}
				$aContext['current_contact_id'] = UserRights::GetContactId();
				$aContext['current_contact_friendlyname'] = UserRights::GetUserFriendlyName();
				$aContext['current_date'] = date(AttributeDate::GetSQLFormat());
				$aContext['current_time'] = date(AttributeDateTime::GetSQLTimeFormat());
				$sAddendum = MetaModel::ApplyParams($sRawAddendum, $aContext);
				$this->Set($sAttCode, $this->Get($sAttCode).$sAddendum);
				break;

			case 'add_to_list':
				if (!array_key_exists('source', $aSourceObjects))
				{
					throw new Exception('Missing conventional "source" object');
				}
				$oObjectToRead = $aSourceObjects['source'];
				if (!array_key_exists(0, $aParams))
				{
					throw new Exception('Missing argument #1: source attribute');
				}
				$sSourceKeyAttCode = $aParams[0];
				if (($sSourceKeyAttCode != 'id') && !MetaModel::IsValidAttCode(get_class($oObjectToRead), $sSourceKeyAttCode))
				{
					throw new Exception("Unknown attribute ".get_class($oObjectToRead)."::".$sSourceKeyAttCode);
				}
				if (!array_key_exists(1, $aParams))
				{
					throw new Exception('Missing argument #2: target attribute (link set)');
				}
				$sTargetListAttCode = $aParams[1]; // indirect !!!
				if (!MetaModel::IsValidAttCode(get_class($this), $sTargetListAttCode))
				{
					throw new Exception("Unknown attribute ".get_class($this)."::".$sTargetListAttCode);
				}
				if (isset($aParams[2]) && isset($aParams[3]))
				{
					$sRoleAttCode = $aParams[2];
					$sRoleValue = $aParams[3];
				}

				$iObjKey = $oObjectToRead->Get($sSourceKeyAttCode);
				if ($iObjKey > 0)
				{
					$oLinkSet = $this->Get($sTargetListAttCode);

					/** @var \AttributeLinkedSetIndirect $oListAttDef */
					$oListAttDef = MetaModel::GetAttributeDef(get_class($this), $sTargetListAttCode);
					/** @var \AttributeLinkedSet $oListAttDef */
					$oLnk = MetaModel::NewObject($oListAttDef->GetLinkedClass());
					$oLnk->Set($oListAttDef->GetExtKeyToRemote(), $iObjKey);
					if (isset($sRoleAttCode))
					{
						if (!MetaModel::IsValidAttCode(get_class($oLnk), $sRoleAttCode))
						{
							throw new Exception("Unknown attribute ".get_class($oLnk)."::".$sRoleAttCode);
						}
						$oLnk->Set($sRoleAttCode, $sRoleValue);
					}
					$oLinkSet->AddObject($oLnk);
					$this->Set($sTargetListAttCode, $oLinkSet);
				}
				break;

			case 'apply_stimulus':
				if (!array_key_exists(0, $aParams))
				{
					throw new Exception('Missing argument #1: stimulus');
				}
				$sStimulus = $aParams[0];
				$this->ApplyStimulus($sStimulus);
				break;

			case 'call_method':
				if (!array_key_exists('source', $aSourceObjects))
				{
					throw new Exception('Missing conventional "source" object');
				}
				$oObjectToRead = $aSourceObjects['source'];
				if (!array_key_exists(0, $aParams))
				{
					throw new Exception('Missing argument #1: method name');
				}
				$sMethod = $aParams[0];
				$aCallSpec = array($this, $sMethod);
				if (!is_callable($aCallSpec))
				{
					throw new Exception("Unknown method ".get_class($this)."::".$sMethod.'()');
				}
				// Note: $oObjectToRead has been preserved when adding $aSourceObjects, so as to remain backward compatible with methods having only 1 parameter ($oObjectToRead�
				call_user_func($aCallSpec, $oObjectToRead, $aSourceObjects);
				break;

			default:
				throw new Exception("Invalid verb");
		}
	}

    /**
     * Is the object archived
     *
     * @api
     *
     * @param string|null $sKeyAttCode
     *
     * @return bool
     * @throws ArchivedObjectException
     * @throws CoreException
     */
	public function IsArchived($sKeyAttCode = null)
	{
		$bRet = false;
		$sFlagAttCode = is_null($sKeyAttCode) ? 'archive_flag' : $sKeyAttCode.'_archive_flag';
		if (MetaModel::IsValidAttCode(get_class($this), $sFlagAttCode) && $this->Get($sFlagAttCode))
		{
			$bRet = true;
		}
		return $bRet;
	}

    /**
     * Is the object obsolete
     *
     * @param string|null $sKeyAttCode
     *
     * @return bool
     * @throws ArchivedObjectException
     * @throws CoreException
     */
	public function IsObsolete($sKeyAttCode = null)
	{
		$bRet = false;
		$sFlagAttCode = is_null($sKeyAttCode) ? 'obsolescence_flag' : $sKeyAttCode.'_obsolescence_flag';
		if (MetaModel::IsValidAttCode(get_class($this), $sFlagAttCode) && $this->Get($sFlagAttCode))
		{
			$bRet = true;
		}
		return $bRet;
	}

	/**
     * @internal
     *
	 * <p>Sets the <code>archive_flag</code> <b>For all of the class hierarchy</b><br>
	 * Also update the <code>archive_date</code> :
	 * <ul>
	 * <li>if $bArchive==false  archive_date become null
	 * <li>if $bArchive==true  && $archive_date == null archive_date take the current date
	 * </ul>
	 *
	 * <p>Can be used to fix database inconsistencies on archive_flag field.
	 *
	 * @see \DBSearch::DBBulkWriteArchiveFlag()
	 *
	 * @param boolean $bArchive if true then sets archive_flag and archive_date flags
	 *
	 * @throws Exception
	 */
	protected function DBWriteArchiveFlag($bArchive)
	{
		if (!MetaModel::IsArchivable(get_class($this)))
		{
			throw new Exception(get_class($this).' is not an archivable class');
		}

		$iFlag = $bArchive ? 1 : 0;
		$sDate = $bArchive ? '"'.date(AttributeDate::GetSQLFormat()).'"' : 'null';

		$sClass = get_class($this);
		$sArchiveRoot = MetaModel::GetAttributeOrigin($sClass, 'archive_flag');
		$sRootTable = MetaModel::DBGetTable($sArchiveRoot);
		$sRootKey = MetaModel::DBGetKey($sArchiveRoot);
		$aJoins = array("`$sRootTable`");
		$aUpdates = array();
		foreach (MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL) as $sParentClass)
		{
			if (!MetaModel::IsValidAttCode($sParentClass, 'archive_flag')) continue;

			$sTable = MetaModel::DBGetTable($sParentClass);
			$aUpdates[] = "`$sTable`.`archive_flag` = $iFlag";
			if ($sParentClass == $sArchiveRoot)
			{
				if (!$bArchive || $this->Get('archive_date') == '')
				{
					// Erase or set the date (do not change it)
					$aUpdates[] = "`$sTable`.`archive_date` = $sDate";
				}
			}
			else
			{
				$sKey = MetaModel::DBGetKey($sParentClass);
				$aJoins[] = "`$sTable` ON `$sTable`.`$sKey` = `$sRootTable`.`$sRootKey`";
			}
		}
		$sJoins = implode(' INNER JOIN ', $aJoins);
		$sValues = implode(', ', $aUpdates);
		$sUpdateQuery = "UPDATE $sJoins SET $sValues WHERE `$sRootTable`.`$sRootKey` = ".$this->GetKey();
		CMDBSource::Query($sUpdateQuery);
	}

	/**
	 * @throws Exception
	 * @uses DBWriteArchiveFlag
	 */
	public function DBArchive()
	{
		$this->DBWriteArchiveFlag(true);
		$this->m_aCurrValues['archive_flag'] = true;
		$this->m_aOrigValues['archive_flag'] = true;
	}

    /**
     * @throws Exception
     * @uses DBWriteArchiveFlag
     */
	public function DBUnarchive()
	{
		$this->DBWriteArchiveFlag(false);
		$this->m_aCurrValues['archive_flag'] = false;
		$this->m_aOrigValues['archive_flag'] = false;
		$this->m_aCurrValues['archive_date'] = null;
		$this->m_aOrigValues['archive_date'] = null;
	}


    /**
     * @internal
     *
     * @param string $sClass Needs to be an instanciable class
     *
     * @return DBObject
     * @throws CoreException
     * @throws CoreUnexpectedValue
     */
	public static function MakeDefaultInstance($sClass)
	{
		$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
		$oObj = MetaModel::NewObject($sClass);
		if (!empty($sStateAttCode))
		{
			$sTargetState = MetaModel::GetDefaultState($sClass);
			$oObj->Set($sStateAttCode, $sTargetState);
		}
		return $oObj;
	}

	/**
	 * Complete a new object with data from context
     *
	 * @overwritable-hook You can extend this method in order to provide your own logic.
	 *
	 * @see https://www.itophub.io/wiki/page?id=latest%3Acustomization%3Aform_prefill wiki tutorial
	 *
	 * @param array $aContextParam Context used for creation form prefilling. Contains those keys :
	 * <ul>
	 *   <li>string 'dest_class'
	 *   <li>string 'origin' either console or portal
	 *   <li>DBObject 'source_obj' fixed only when creating an external key object
	 * </ul>
	 *
	 * @since 2.5.0 N°729
	 */
	public function PrefillCreationForm(&$aContextParam)
	{
	}

	/**
	 * Complete an object after a state transition with data from context
	 *
	 * @overwritable-hook You can extend this method in order to provide your own logic.
     *
	 * @see https://www.itophub.io/wiki/page?id=latest%3Acustomization%3Aform_prefill wiki tutorial
	 *
	 * @param array $aContextParam Context used for creation form prefilling. Contains those keys :
	 * <ul>
	 *   <li>array 'expected_attributes' provides display flags on attributes in the form
	 *   <li>string 'origin' either console or portal
	 *   <li>string 'stimulus' provide the applied stimulus
	 * </ul>
	 *
	 * @since 2.5.0 N°729
	 */
	public function PrefillTransitionForm(&$aContextParam)
	{
	}

	/**
	 * Complete a filter data from context (Called on source object)
	 *
	 * @overwritable-hook You can extend this method in order to provide your own logic.
	 *
	 * @see https://www.itophub.io/wiki/page?id=latest%3Acustomization%3Aform_prefill wiki tutorial
	 *
	 * @param array $aContextParam Context used for creation form prefilling. Contains :
	 * <ul>
	 *   <li>string 'dest_class'
	 *   <li>DBObjectSearch 'filter'
	 *   <li>string 'user' login string of the connected user
	 *   <li>string 'origin' either 'console' or 'portal'
	 * </ul>
	 *
	 * @since 2.5.0 N°729
	 */
	public function PrefillSearchForm(&$aContextParam)
	{
	}

	/**
	 * Prefill a creation / stimulus change / search form according to context, current state of an object, stimulus.. $sOperation
	 *
	 * @internal
	 *
	 * @param array $aContextParam Context used for creation form prefilling
	 *
	 * @param string $sOperation Operation identifier
	 *
	 * @since 2.5.0 N°729
	 */
	public function PrefillForm($sOperation, &$aContextParam)
	{
		switch($sOperation){
			case 'creation_from_0':
			case 'creation_from_extkey':
			case 'creation_from_editinplace':
				$this->PrefillCreationForm($aContextParam);
				break;
			case 'state_change':
				$this->PrefillTransitionForm($aContextParam);
				break;
			case 'search':
				$this->PrefillSearchForm($aContextParam);
				break;
			default:
				break;
		}
	}
}

