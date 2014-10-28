<?php
// Copyright (C) 2010-2014 Combodo SARL
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
 * Object set management
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * A set of persistent objects, could be heterogeneous as long as the objects in the set have a common ancestor class 
 *
 * @package     iTopORM
 */
class DBObjectSet
{
	protected $m_aAddedIds; // Ids of objects added (discrete lists)
	protected $m_aAddedObjects;
	protected $m_aArgs;
	protected $m_aAttToLoad;
	protected $m_aOrderBy;
	public $m_bLoaded;
	protected $m_iNumTotalDBRows;
	protected $m_iNumLoadedDBRows;
	protected $m_iCurrRow;
	protected $m_oFilter;
	protected $m_oSQLResult;

	/**
	 * Create a new set based on a Search definition.
	 * 
	 * @param DBObjectSearch $oFilter The search filter defining the objects which are part of the set (multiple columns/objects per row are supported)
	 * @param hash $aOrderBy Array of '[<classalias>.]attcode' => bAscending
	 * @param hash $aArgs Values to substitute for the search/query parameters (if any). Format: param_name => value
	 * @param hash $aExtendedDataSpec
	 * @param int $iLimitCount Maximum number of rows to load (i.e. equivalent to MySQL's LIMIT start, count)
	 * @param int $iLimitStart Index of the first row to load (i.e. equivalent to MySQL's LIMIT start, count)
	 */
	public function __construct(DBObjectSearch $oFilter, $aOrderBy = array(), $aArgs = array(), $aExtendedDataSpec = null, $iLimitCount = 0, $iLimitStart = 0)
	{
		$this->m_oFilter = $oFilter->DeepClone();
		$this->m_aAddedIds = array();
		$this->m_aOrderBy = $aOrderBy;
		$this->m_aArgs = $aArgs;
		$this->m_aAttToLoad = null;
		$this->m_aExtendedDataSpec = $aExtendedDataSpec;
		$this->m_iLimitCount = $iLimitCount;
		$this->m_iLimitStart = $iLimitStart;

		$this->m_iNumTotalDBRows = null; // Total number of rows for the query without LIMIT. null if unknown yet
		$this->m_iNumLoadedDBRows = 0; // Total number of rows LOADED in $this->m_oSQLResult via a SQL query. 0 by default
		$this->m_bLoaded = false; // true when the filter has been used OR the set is built step by step (AddObject...)
		$this->m_aAddedObjects = array(); // array of (row => array of (classalias) => object/null) storing the objects added "in memory"
		$this->m_iCurrRow = 0;
		$this->m_oSQLResult = null;
	}

	public function __destruct()
	{
		if (is_object($this->m_oSQLResult))
		{
			$this->m_oSQLResult->free();
		}
	}

	public function __toString()
	{
		$sRet = '';
		$this->Rewind();
		$sRet .= "Set (".$this->m_oFilter->ToOQL().")<br/>\n";
		$sRet .= "Query: <pre style=\"font-size: smaller; display:inline;\">".MetaModel::MakeSelectQuery($this->m_oFilter, array()).")</pre>\n";
		
		$sRet .= $this->Count()." records<br/>\n";
		if ($this->Count() > 0)
		{
			$sRet .= "<ul class=\"treeview\">\n";
			while ($oObj = $this->Fetch())
			{
				$sRet .= "<li>".$oObj->__toString()."</li>\n";
			}
			$sRet .= "</ul>\n";
		}
		return $sRet;
	}

	public function __clone()
	{
		$this->m_oFilter = $this->m_oFilter->DeepClone();

		$this->m_iNumTotalDBRows = null; // Total number of rows for the query without LIMIT. null if unknown yet
		$this->m_iNumLoadedDBRows = 0; // Total number of rows LOADED in $this->m_oSQLResult via a SQL query. 0 by default
		$this->m_bLoaded = false; // true when the filter has been used OR the set is built step by step (AddObject...)
		$this->m_iCurrRow = 0;
		$this->m_oSQLResult = null;
	}

	/**
	 * Called when unserializing a DBObjectSet
	 */
	public function __wakeup()
	{
		$this->m_iNumTotalDBRows = null; // Total number of rows for the query without LIMIT. null if unknown yet
		$this->m_iNumLoadedDBRows = 0; // Total number of rows LOADED in $this->m_oSQLResult via a SQL query. 0 by default
		$this->m_bLoaded = false; // true when the filter has been used OR the set is built step by step (AddObject...)
		$this->m_iCurrRow = 0;
		$this->m_oSQLResult = null;
	}
	/**
	 * Specify the subset of attributes to load (for each class of objects) before performing the SQL query for retrieving the rows from the DB
	 * 
	 * @param hash $aAttToLoad Format: alias => array of attribute_codes
	 * 
	 * @return void
	 */
	public function OptimizeColumnLoad($aAttToLoad)
	{
		if (is_null($aAttToLoad))
		{
			$this->m_aAttToLoad = null;
		}
		else
		{
			// Complete the attribute list with the attribute codes
			$aAttToLoadWithAttDef = array();
			foreach($this->m_oFilter->GetSelectedClasses() as $sClassAlias => $sClass)
			{
				$aAttToLoadWithAttDef[$sClassAlias] = array();
				if (array_key_exists($sClassAlias, $aAttToLoad))
				{
					$aAttList = $aAttToLoad[$sClassAlias];
					foreach($aAttList as $sAttToLoad)
					{
						$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttToLoad);
						$aAttToLoadWithAttDef[$sClassAlias][$sAttToLoad] = $oAttDef;
						if ($oAttDef->IsExternalKey())
						{
							// Add the external key friendly name anytime
							$oFriendlyNameAttDef = MetaModel::GetAttributeDef($sClass, $sAttToLoad.'_friendlyname');
							$aAttToLoadWithAttDef[$sClassAlias][$sAttToLoad.'_friendlyname'] = $oFriendlyNameAttDef;
						}
					}
				}
				// Add the friendly name anytime
				$oFriendlyNameAttDef = MetaModel::GetAttributeDef($sClass, 'friendlyname');
				$aAttToLoadWithAttDef[$sClassAlias]['friendlyname'] = $oFriendlyNameAttDef;

				// Make sure that the final class is requested anytime, whatever the specification (needed for object construction!)
				if (!MetaModel::IsStandaloneClass($sClass) && !array_key_exists('finalclass', $aAttToLoadWithAttDef[$sClassAlias]))
				{
					$aAttToLoadWithAttDef[$sClassAlias]['finalclass'] = MetaModel::GetAttributeDef($sClass, 'finalclass');
				}
			}

			$this->m_aAttToLoad = $aAttToLoadWithAttDef;
		}
	}

	/**
	 * Create a set (in-memory) containing just the given object
	 * 
	 * @param DBobject $oObject
	 * 
	 * @return DBObjectSet The singleton set
	 */
	static public function FromObject($oObject)
	{
		$oRetSet = self::FromScratch(get_class($oObject));
		$oRetSet->AddObject($oObject);
		return $oRetSet;
	}

	/**
	 * Create an empty set (in-memory), for the given class (and its subclasses) of objects
	 * 
	 * @param string $sClass The class (or an ancestor) for the objects to be added in this set
	 * 
	 * @return DBObject The empty set
	 */
	static public function FromScratch($sClass)
	{
		$oFilter = new DBObjectSearch($sClass);
		$oFilter->AddConditionExpression(new FalseExpression());
		$oRetSet = new self($oFilter);
		$oRetSet->m_bLoaded = true; // no DB load
		$oRetSet->m_iNumTotalDBRows = 0; // Nothing from the DB
		return $oRetSet;
	} 

	/**
	 * Create a set (in-memory) with just one column (i.e. one object per row) and filled with the given array of objects
	 * 
	 * @param string $sClass The class of the objects (must be a common ancestor to all objects in the set)
	 * @param array $aObjects The list of objects to add into the set
	 * 
	 * @return DBObjectSet
	 */
	static public function FromArray($sClass, $aObjects)
	{
		$oRetSet = self::FromScratch($sClass);
		$oRetSet->AddObjectArray($aObjects, $sClass);
		return $oRetSet;
	} 

	/**
	 * Create a set in-memory with several classes of objects per row (with one alias per "column")
	 * 
	 * Limitation:
	 * The filter/OQL query representing such a set can not be rebuilt (only the first column will be taken into account)
	 * 
	 * @param hash $aClasses Format: array of (alias => class)
	 * @param hash $aObjects Format: array of (array of (classalias => object))
	 * 
	 * @return DBObjectSet
	 */
	static public function FromArrayAssoc($aClasses, $aObjects)
	{
		// In a perfect world, we should create a complete tree of DBObjectSearch,
		// but as we lack most of the information related to the objects,
		// let's create one search definition corresponding only to the first column
		$sClass = reset($aClasses);
		$sAlias = key($aClasses);
		$oFilter = new CMDBSearchFilter($sClass, $sAlias);

		$oRetSet = new self($oFilter);
		$oRetSet->m_bLoaded = true; // no DB load
		$oRetSet->m_iNumTotalDBRows = 0; // Nothing from the DB
		
		foreach($aObjects as $rowIndex => $aObjectsByClassAlias)
		{
			$oRetSet->AddObjectExtended($aObjectsByClassAlias);
		}
		return $oRetSet;
	} 

	static public function FromLinkSet($oObject, $sLinkSetAttCode, $sExtKeyToRemote)
	{
		$oLinkAttCode = MetaModel::GetAttributeDef(get_class($oObject), $sLinkSetAttCode);
		$oExtKeyAttDef = MetaModel::GetAttributeDef($oLinkAttCode->GetLinkedClass(), $sExtKeyToRemote);
		$sTargetClass = $oExtKeyAttDef->GetTargetClass();

		$oLinkSet = $oObject->Get($sLinkSetAttCode);
		$aTargets = array();
		while ($oLink = $oLinkSet->Fetch())
		{
			$aTargets[] = MetaModel::GetObject($sTargetClass, $oLink->Get($sExtKeyToRemote));
		}

		return self::FromArray($sTargetClass, $aTargets);
	} 

	public function ToArray($bWithId = true)
	{
		$aRet = array();
		$this->Rewind();
		while ($oObject = $this->Fetch())
		{
			if ($bWithId)
			{
				$aRet[$oObject->GetKey()] = $oObject;
			}
			else
			{
				$aRet[] = $oObject;
			}
		}
		return $aRet;
	} 

	public function ToArrayOfValues()
	{
		if (!$this->m_bLoaded) $this->Load();
		$this->Rewind();

		$aSelectedClasses = $this->m_oFilter->GetSelectedClasses();

		$aRet = array();
		$iRow = 0;
		while($aObjects = $this->FetchAssoc())
		{
			foreach($aObjects as $sClassAlias => $oObject)
			{
				if (is_null($oObject))
				{
					$aRet[$iRow][$sClassAlias.'.'.'id'] = null;
				}
				else
				{
					$aRet[$iRow][$sClassAlias.'.'.'id'] = $oObject->GetKey();
				} 
				if (is_null($oObject))
				{
					$sClass = $aSelectedClasses[$sClassAlias];
				}
				else
				{
					$sClass = get_class($oObject);
				}
				foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
				{
					if ($oAttDef->IsScalar())
					{
						$sAttName = $sClassAlias.'.'.$sAttCode;
						if (is_null($oObject))
						{
							$aRet[$iRow][$sAttName] = null;
						}
						else
						{
							$aRet[$iRow][$sAttName] = $oObject->Get($sAttCode);
						}
					}
				}
			}
			$iRow++;
		}
		return $aRet;
	} 

	public function GetColumnAsArray($sAttCode, $bWithId = true)
	{
		$aRet = array();
		$this->Rewind();
		while ($oObject = $this->Fetch())
		{
			if ($bWithId)
			{
				$aRet[$oObject->GetKey()] = $oObject->Get($sAttCode);
			}
			else
			{
				$aRet[] = $oObject->Get($sAttCode);
			}
		}
		return $aRet;
	}

	/**
	 * Retrieve the DBObjectSearch corresponding to the objects present in this set
	 * 
	 * Limitation:
	 * This method will NOT work for sets with several columns (i.e. several objects per row)
	 * 
	 * @return DBObjectSearch
	 */
	public function GetFilter()
	{
		// Make sure that we carry on the parameters of the set with the filter
		$oFilter = $this->m_oFilter->DeepClone();
		// Note: the arguments found within a set can be object (but not in a filter)
		// That's why PrepareQueryArguments must be invoked there
		$oFilter->SetInternalParams(array_merge($oFilter->GetInternalParams(), MetaModel::PrepareQueryArguments($this->m_aArgs)));
		
		if (count($this->m_aAddedIds) == 0)
		{
			return $oFilter;
		}
		else
		{
			$oIdListExpr = ListExpression::FromScalars(array_keys($this->m_aAddedIds));
			$oIdExpr = new FieldExpression('id', $oFilter->GetClassAlias());
			$oIdInList = new BinaryExpression($oIdExpr, 'IN', $oIdListExpr);
			$oFilter->MergeConditionExpression($oIdInList);
			return $oFilter;
		}
	}

	/**
	 * The (common ancestor) class of the objects in the first column of this set
	 * 
	 * @return string The class of the objects in the first column
	 */
	public function GetClass()
	{
		return $this->m_oFilter->GetClass();
	}

	/**
	 * The alias for the class of the objects in the first column of this set
	 * 
	 * @return string The alias of the class in the first column
	 */
	public function GetClassAlias()
	{
		return $this->m_oFilter->GetClassAlias();
	}

	/**
	 * The list of all classes (one per column) which are part of this set
	 * 
	 * @return hash Format: alias => class
	 */
	public function GetSelectedClasses()
	{
		return $this->m_oFilter->GetSelectedClasses();
	}

	/**
	 * The root class (i.e. highest ancestor in the MeaModel class hierarchy) for the first column on this set
	 * 
	 * @return string The root class for the objects in the first column of the set
	 */
	public function GetRootClass()
	{
		return MetaModel::GetRootClass($this->GetClass());
	}

	/**
	 * The arguments used for building this set
	 * 
	 * @return hash Format: parameter_name => value
	 */
	public function GetArgs()
	{
		return $this->m_aArgs;
	}

	/**
	 * Sets the limits for loading the rows from the DB. Equivalent to MySQL's LIMIT start,count clause.
	 * @param int $iLimitCount The number of rows to load
	 * @param int $iLimitStart The index of the first row to load
	 */
	public function SetLimit($iLimitCount, $iLimitStart = 0)
	{
		$this->m_iLimitCount = $iLimitCount;
		$this->m_iLimitStart = $iLimitStart;
	}

	/**
	 * Sets the sort order for loading the rows from the DB. Changing the order by causes a Reload.
	 * 
	 * @param hash $aOrderBy Format: field_code => boolean (true = ascending, false = descending)
	 */
	public function SetOrderBy($aOrderBy)
	{
		if ($this->m_aOrderBy != $aOrderBy)
		{
			$this->m_aOrderBy = $aOrderBy;
			if ($this->m_bLoaded)
			{
				$this->m_bLoaded = false;
				$this->Load();
			}
		}
	}

	/**
	 * Returns the 'count' limit for loading the rows from the DB
	 * 
	 * @return int
	 */
	public function GetLimitCount()
	{
		return $this->m_iLimitCount;
	}

	/**
	 * Returns the 'start' limit for loading the rows from the DB
	 * 
	 * @return int
	 */
	public function GetLimitStart()
	{
		return $this->m_iLimitStart;
	}

	/**
	 * Get the sort order used for loading this set from the database
	 * 
	 * Limitation: the sort order has no effect on objects added in-memory
	 * 
	 * @return hash Format: field_code => boolean (true = ascending, false = descending)
	 */
	public function GetRealSortOrder()
	{
		// Get the class default sort order if not specified with the API
		//
		if (empty($this->m_aOrderBy))
		{
			return MetaModel::GetOrderByDefault($this->m_oFilter->GetClass());
		}
		else
		{
			return $this->m_aOrderBy;
		}
	}

	/**
	 * Loads the set from the database. Actually performs the SQL query to retrieve the records from the DB. 
	 */
	public function Load()
	{
		if ($this->m_bLoaded) return;
		// Note: it is mandatory to set this value now, to protect against reentrance
		$this->m_bLoaded = true;

		if ($this->m_iLimitCount > 0)
		{
			$sSQL = MetaModel::MakeSelectQuery($this->m_oFilter, $this->GetRealSortOrder(), $this->m_aArgs, $this->m_aAttToLoad, $this->m_aExtendedDataSpec, $this->m_iLimitCount, $this->m_iLimitStart);
		}
		else
		{
			$sSQL = MetaModel::MakeSelectQuery($this->m_oFilter, $this->GetRealSortOrder(), $this->m_aArgs, $this->m_aAttToLoad, $this->m_aExtendedDataSpec);
		}
		
		if (is_object($this->m_oSQLResult))
		{
			// Free previous resultset if any
			$this->m_oSQLResult->free();
			$this->m_oSQLResult = null;
		}
		$this->m_iNumTotalDBRows = null;
		
		$this->m_oSQLResult = CMDBSource::Query($sSQL);
		if ($this->m_oSQLResult === false) return;
		
		if (($this->m_iLimitCount == 0) && ($this->m_iLimitStart == 0))
		{
			$this->m_iNumTotalDBRows = $this->m_oSQLResult->num_rows;
		}
		$this->m_iNumLoadedDBRows = $this->m_oSQLResult->num_rows;
	}

	/**
	 * The total number of rows in this set. Independently of the SetLimit used for loading the set and taking into account the rows added in-memory.
	 * 
	 * May actually perform the SQL query SELECT COUNT... if the set was not previously loaded, or loaded with a SetLimit
	 * 
	 * @return int The total number of rows for this set.
	 */
	public function Count()
	{
		if (is_null($this->m_iNumTotalDBRows))
		{
			$sSQL = MetaModel::MakeSelectQuery($this->m_oFilter, array(), $this->m_aArgs, null, null, 0, 0, true);
			$resQuery = CMDBSource::Query($sSQL);
			if (!$resQuery) return 0;
	
			$aRow = CMDBSource::FetchArray($resQuery);
			CMDBSource::FreeResult($resQuery);
			$this->m_iNumTotalDBRows = $aRow['COUNT'];
		}
		return $this->m_iNumTotalDBRows + count($this->m_aAddedObjects); // Does it fix Trac #887 ??
	}
	
	/**
	 * Number of rows available in memory (loaded from DB + added in memory)
	 * 
	 * @return number The number of rows available for Fetch'ing
	 */
	protected function CountLoaded()
	{
		return $this->m_iNumLoadedDBRows + count($this->m_aAddedObjects);
	}

	/**
	 * Fetch the object (with the given class alias) at the current position in the set and move the cursor to the next position.
	 * 
	 * @param string $sRequestedClassAlias The class alias to fetch (if there are several objects/classes per row)
	 * @return DBObject The fetched object or null when at the end
	 */
	public function Fetch($sRequestedClassAlias = '')
	{
		if (!$this->m_bLoaded) $this->Load();

		if ($this->m_iCurrRow >= $this->CountLoaded())
		{
			return null;
		}
	
		if (strlen($sRequestedClassAlias) == 0)
		{
			$sRequestedClassAlias = $this->m_oFilter->GetClassAlias();
		}

		if ($this->m_iCurrRow < $this->m_iNumLoadedDBRows)
		{
			// Pick the row from the database
			$aRow = CMDBSource::FetchArray($this->m_oSQLResult);
			foreach ($this->m_oFilter->GetSelectedClasses() as $sClassAlias => $sClass)
			{
				if ($sRequestedClassAlias == $sClassAlias)
				{
					if (is_null($aRow[$sClassAlias.'id']))
					{
						$oRetObj = null;
					}
					else
					{
						$oRetObj = MetaModel::GetObjectByRow($sClass, $aRow, $sClassAlias, $this->m_aAttToLoad, $this->m_aExtendedDataSpec);
					}
					break;
				}
			}
		}
		else
		{
			// Pick the row from the objects added *in memory*
			$oRetObj = $this->m_aAddedObjects[$this->m_iCurrRow - $this->m_iNumLoadedDBRows][$sRequestedClassAlias];
		}
		$this->m_iCurrRow++;
		return $oRetObj;
	}

	/**
	 * Fetch the whole row of objects (if several classes have been specified in the query) and move the cursor to the next position
	 * 
	 * @return hash A hash with the format 'classAlias' => $oObj representing the current row of the set. Returns null when at the end.
	 */
	public function FetchAssoc()
	{
		if (!$this->m_bLoaded) $this->Load();

		if ($this->m_iCurrRow >= $this->CountLoaded())
		{
			return null;
		}
	
		if ($this->m_iCurrRow < $this->m_iNumLoadedDBRows)
		{
			// Pick the row from the database
			$aRow = CMDBSource::FetchArray($this->m_oSQLResult);
			$aRetObjects = array();
			foreach ($this->m_oFilter->GetSelectedClasses() as $sClassAlias => $sClass)
			{
				if (is_null($aRow[$sClassAlias.'id']))
				{
					$oObj = null;
				}
				else
				{
					$oObj = MetaModel::GetObjectByRow($sClass, $aRow, $sClassAlias, $this->m_aAttToLoad, $this->m_aExtendedDataSpec);
				}
				$aRetObjects[$sClassAlias] = $oObj;
			}
		}
		else
		{
			// Pick the row from the objects added *in memory*
			$aRetObjects = array();
			foreach ($this->m_oFilter->GetSelectedClasses() as $sClassAlias => $sClass)
			{
				$aRetObjects[$sClassAlias] = $this->m_aAddedObjects[$this->m_iCurrRow - $this->m_iNumLoadedDBRows][$sClassAlias];
			}
		}
		$this->m_iCurrRow++;
		return $aRetObjects;
	}

	/**
	 * Position the cursor (for iterating in the set) to the first position (equivalent to Seek(0))
	 */
	public function Rewind()
	{
		if ($this->m_bLoaded)
		{
			$this->Seek(0);
		}
	}

	/**
	 * Position the cursor (for iterating in the set) to the given position
	 * 
	 * @param int $iRow
	 */
	public function Seek($iRow)
	{
		if (!$this->m_bLoaded) $this->Load();

		$this->m_iCurrRow = min($iRow, $this->Count());
		if ($this->m_iCurrRow < $this->m_iNumLoadedDBRows)
		{
			$this->m_oSQLResult->data_seek($this->m_iCurrRow);
		}
		return $this->m_iCurrRow;
	}

	/**
	 * Add an object to the current set (in-memory only, nothing is written to the database)
	 * 
	 * Limitation:
	 * Sets with several objects per row are NOT supported
	 * 
	 * @param DBObject $oObject The object to add
	 * @param string $sClassAlias The alias for the class of the object
	 */
	public function AddObject($oObject, $sClassAlias = '')
	{
		if (!$this->m_bLoaded) $this->Load();

		if (strlen($sClassAlias) == 0)
		{
			$sClassAlias = $this->m_oFilter->GetClassAlias();
		}

		$iNextPos = count($this->m_aAddedObjects);
		$this->m_aAddedObjects[$iNextPos][$sClassAlias] = $oObject;
		if (!is_null($oObject))
		{
			$this->m_aAddedIds[$oObject->GetKey()] = true;
		}
	}

	/**
	 * Add a hash containig objects into the current set.
	 * 
	 * The expected format for the hash is: $aObjectArray[$idx][$sClassAlias] => $oObject
	 * Limitation:
	 * The aliases MUST match the ones used in the current set
	 * Only the ID of the objects associated to the first alias (column) is remembered.. in case we have to rebuild a filter
	 * 
	 * @param hash $aObjectArray
	 */
	protected function AddObjectExtended($aObjectArray)
	{
		if (!$this->m_bLoaded) $this->Load();

		$iNextPos = count($this->m_aAddedObjects);
		
		$sFirstAlias = $this->m_oFilter->GetClassAlias();

		foreach ($aObjectArray as $sClassAlias => $oObject)
		{
			$this->m_aAddedObjects[$iNextPos][$sClassAlias] = $oObject;
			
			if (!is_null($oObject) && ($sFirstAlias == $sClassAlias))
			{
				$this->m_aAddedIds[$oObject->GetKey()] = true;
			}
		}
	}

	/**
	 * Add an array of objects into the current set
	 * 
	 * Limitation:
	 * Sets with several classes per row are not supported (use AddObjectExtended instead)
	 * 
	 * @param array $aObjects The array of objects to add
	 * @param string $sClassAlias The Alias of the class for the added objects
	 */
	public function AddObjectArray($aObjects, $sClassAlias = '')
	{
		if (!$this->m_bLoaded) $this->Load();

		// #@# todo - add a check on the object class ?
		foreach ($aObjects as $oObj)
		{
			$this->AddObject($oObj, $sClassAlias);
		}
	}

	/**
	 * Append a given set to the current object. (This method used to be named Merge)
	 * 
	 * Limitation:
	 * The added objects are not checked for duplicates (i.e. one cann add several times the same object, or add an object already present in the set).
	 * 
	 * @param DBObjectSet $oObjectSet The set to append
	 * @throws CoreException
	 */
	public function Append(DBObjectSet $oObjectSet)
	{
		if ($this->GetRootClass() != $oObjectSet->GetRootClass())
		{
			throw new CoreException("Could not merge two objects sets if they don't have the same root class");
		}
		if (!$this->m_bLoaded) $this->Load();

		$oObjectSet->Seek(0);
		while ($oObject = $oObjectSet->Fetch())
		{
			$this->AddObject($oObject);
		}
	}

	/**
	 * Create a set containing the objects present in both the current set and another specified set
	 * 
	 * Limitations:
	 * Will NOT work if only a subset of the sets was loaded with SetLimit.
	 * Works only with sets made of objects loaded from the database since the comparison is based on the objects identifiers
	 * 
	 * @param DBObjectSet $oObjectSet The set to intersect with. The current position inside the set will be lost (= at the end)
	 * @throws CoreException
	 * @return DBObjectSet A new set of objects, containing the objects present in both sets (based on their identifier)
	 */
	public function CreateIntersect(DBObjectSet $oObjectSet)
	{
		if ($this->GetRootClass() != $oObjectSet->GetRootClass())
		{
			throw new CoreException("Could not 'intersect' two objects sets if they don't have the same root class");
		}
		if (!$this->m_bLoaded) $this->Load();

		$aId2Row = array();
		$iCurrPos = $this->m_iCurrRow; // Save the cursor
		$idx = 0;
		while($oObj = $this->Fetch())
		{
			$aId2Row[$oObj->GetKey()] = $idx;
			$idx++;
		}
		
		$oNewSet = DBObjectSet::FromScratch($this->GetClass());

		$oObjectSet->Seek(0);
		while ($oObject = $oObjectSet->Fetch())
		{
			if (array_key_exists($oObject->GetKey(), $aId2Row))
			{
				$oNewSet->AddObject($oObject);
			}
		}
		$this->Seek($iCurrPos); // Restore the cursor
		return $oNewSet;
	}

	/**
	 * Compare two sets of objects to determine if their content is identical or not.
	 * 
	 * Limitation:
	 * Works only on objects written to the DB, since we rely on their identifiers
	 * 
	 * @param DBObjectSet $oObjectSet
	 * @return boolean True if the sets are identical, false otherwise
	 */
	public function HasSameContents(DBObjectSet $oObjectSet)
	{
		if ($this->GetRootClass() != $oObjectSet->GetRootClass())
		{
			return false;
		}
		if ($this->Count() != $oObjectSet->Count())
		{
			return false;
		}
		
		$aId2Row = array();
		$bRet = true;
		$iCurrPos = $this->m_iCurrRow; // Save the cursor
		$idx = 0;
		
		// Optimization: we retain the first $iMaxObjects objects in memory
		// to speed up the comparison of small sets (see below: $oObject->Equals($oSibling))
		$iMaxObjects = 20;
		$aCachedObjects = array();
		while($oObj = $this->Fetch())
		{
			$aId2Row[$oObj->GetKey()] = $idx;
			if ($idx <= $iMaxObjects)
			{
				$aCachedObjects[$idx] = $oObj;
			}
			$idx++;
		}
		
		$oObjectSet->Rewind();
		while ($oObject = $oObjectSet->Fetch())
		{
			$iObjectKey = $oObject->GetKey();
			if ($iObjectKey < 0)
			{
				$bRet = false;
				break;
			}
			if (!array_key_exists($iObjectKey, $aId2Row))
			{
				$bRet = false;
				break;
			}
			$iRow = $aId2Row[$iObjectKey];
			if (array_key_exists($iRow, $aCachedObjects))
			{ 
				// Cache hit
				$oSibling = $aCachedObjects[$iRow];
			}
			else
			{
				// Go fetch it from the DB, unless it's an object added in-memory
				$oSibling = $this->GetObjectAt($iRow);
			}
			if (!$oObject->Equals($oSibling))
			{
				$bRet = false;
				break;
			}
		}
		$this->Seek($iCurrPos); // Restore the cursor
		return $bRet;
	}

	protected function GetObjectAt($iIndex)
	{
		if (!$this->m_bLoaded) $this->Load();
		
		// Save the current position for iteration
		$iCurrPos = $this->m_iCurrRow;
		
		$this->Seek($iIndex);
		$oObject = $this->Fetch();
		
		// Restore the current position for iteration
		$this->Seek($this->m_iCurrRow);
		
		return $oObject;
	}
	
	/**
	 * Build a new set (in memory) made of objects of the given set which are NOT present in the current set
	 * 
	 * Limitations:
	 * The objects inside the set must be written in the database since the comparison is based on their identifiers
	 * Sets with several objects per row are NOT supported
	 * 
	 * @param DBObjectSet $oObjectSet
	 * @throws CoreException
	 * 
	 * @return DBObjectSet The "delta" set.
	 */
	public function CreateDelta(DBObjectSet $oObjectSet)
	{
		if ($this->GetRootClass() != $oObjectSet->GetRootClass())
		{
			throw new CoreException("Could not 'delta' two objects sets if they don't have the same root class");
		}
		if (!$this->m_bLoaded) $this->Load();

		$aId2Row = array();
		$iCurrPos = $this->m_iCurrRow; // Save the cursor
		$idx = 0;
		while($oObj = $this->Fetch())
		{
			$aId2Row[$oObj->GetKey()] = $idx;
			$idx++;
		}

		$oNewSet = DBObjectSet::FromScratch($this->GetClass());

		$oObjectSet->Seek(0);
		while ($oObject = $oObjectSet->Fetch())
		{
			if (!array_key_exists($oObject->GetKey(), $aId2Row))
			{
				$oNewSet->AddObject($oObject);
			}
		}
		$this->Seek($iCurrPos); // Restore the cursor
		return $oNewSet;
	}

	/**
	 * Compute the "RelatedObjects" (for the given relation, as defined by MetaModel::GetRelatedObjects) for a whole set of DBObjects
	 * 
	 * @param string $sRelCode The code of the relation to use for the computation
	 * @param int $iMaxDepth Teh maximum recursion depth
	 * 
	 * @return Array An array containg all the "related" objects
	 */
	public function GetRelatedObjects($sRelCode, $iMaxDepth = 99)
	{
		$aRelatedObjs = array();

		$aVisited = array(); // optimization for consecutive calls of MetaModel::GetRelatedObjects
		$this->Seek(0);
		while ($oObject = $this->Fetch())
		{
			$aMore = $oObject->GetRelatedObjects($sRelCode, $iMaxDepth, $aVisited);
			foreach ($aMore as $sClass => $aRelated)
			{
				foreach ($aRelated as $iObj => $oObj)
				{
					if (!isset($aRelatedObjs[$sClass][$iObj]))
					{
						$aRelatedObjs[$sClass][$iObj] = $oObj;
					}
				}
			}
		}
		return $aRelatedObjs;
	}
	
	/**
	 * Builds an object that contains the values that are common to all the objects
	 * in the set. If for a given attribute, objects in the set have various values
	 * then the resulting object will contain null for this value.
	 * @param $aValues Hash Output: the distribution of the values, in the set, for each attribute
	 * @return DBObject The object with the common values
	 */
	public function ComputeCommonObject(&$aValues)
	{
		$sClass = $this->GetClass();
		$aList = MetaModel::ListAttributeDefs($sClass);
		$aValues = array();
		foreach($aList as $sAttCode => $oAttDef)
		{
			if ($oAttDef->IsScalar())
			{
				$aValues[$sAttCode] = array();
			}
		}
		$this->Rewind();
		while($oObj = $this->Fetch())
		{
			foreach($aList as $sAttCode => $oAttDef)
			{
				if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
				{
					$currValue = $oObj->Get($sAttCode);
					if (is_object($currValue)) continue; // Skip non scalar values...
					if(!array_key_exists($currValue, $aValues[$sAttCode]))
					{
						$aValues[$sAttCode][$currValue] = array('count' => 1, 'display' => $oObj->GetAsHTML($sAttCode)); 
					}
					else
					{
						$aValues[$sAttCode][$currValue]['count']++; 
					}
				}
			}
		}
		
		foreach($aValues as $sAttCode => $aMultiValues)
		{
			if (count($aMultiValues) > 1)
			{
				uasort($aValues[$sAttCode], 'HashCountComparison');
			}
		}
						
		
		// Now create an object that has values for the homogenous values only				
		$oCommonObj = new $sClass(); // @@ What if the class is abstract ?
		$aComments = array();

		$iFormId = cmdbAbstractObject::GetNextFormId(); // Identifier that prefixes all the form fields
		$sReadyScript = '';
		$aDependsOn = array();
		$sFormPrefix = '2_';
		foreach($aList as $sAttCode => $oAttDef)
		{
			if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
			{
				if ($oAttDef->GetEditClass() == 'One Way Password')
				{
					$oCommonObj->Set($sAttCode, null);
				}
				else
				{
					$iCount = count($aValues[$sAttCode]);
					if ($iCount == 1)
					{
						// Homogenous value
						reset($aValues[$sAttCode]);
						$aKeys = array_keys($aValues[$sAttCode]);
						$currValue = $aKeys[0]; // The only value is the first key
						$oCommonObj->Set($sAttCode, $currValue);
					}
					else
					{
						// Non-homogenous value
						$oCommonObj->Set($sAttCode, null);
					}
				}
			}
		}
		$this->Rewind();
		return $oCommonObj;
	}

	/**
	 * List the constant fields (and their value) in the given query
	 * @return Hash [Alias][AttCode] => value
	 */
	public function ListConstantFields()
	{
		$aScalarArgs = $this->ExpandArgs();
		$aConst = $this->m_oFilter->ListConstantFields();
				
		foreach($aConst as $sClassAlias => $aVals)
		{
			foreach($aVals as $sCode => $oExpr)
			{
				if (is_object($oExpr)) // Array_merge_recursive creates an array when the same key is present multiple times... ignore them
				{
					$oScalarExpr = $oExpr->GetAsScalar($aScalarArgs);
					$aConst[$sClassAlias][$sCode] = $oScalarExpr->GetValue();
				}
			}
		}
		return $aConst;		
	}
	
	protected function ExpandArgs()
	{
		$aScalarArgs = $this->m_oFilter->GetInternalParams();
		foreach($this->m_aArgs as $sArgName => $value)
		{
			if (MetaModel::IsValidObject($value))
			{
				if (strpos($sArgName, '->object()') === false)
				{
					// Lazy syntax - develop the object contextual parameters
					$aScalarArgs = array_merge($aScalarArgs, $value->ToArgsForQuery($sArgName));
				}
				else
				{
					// Leave as is
					$aScalarArgs[$sArgName] = $value;
				}
			}
			else
			{
				if (!is_array($value)) // Sometimes ExtraParams contains a mix (like defaults[]) so non scalar parameters are ignored
				{
					$aScalarArgs[$sArgName] = (string) $value;
				}
			}
		}
		$aScalarArgs['current_contact_id'] = UserRights::GetContactId();
		return $aScalarArgs;		
	}
	
	public function ApplyParameters()
	{
		$aScalarArgs = $this->ExpandArgs();
		$this->m_oFilter->ApplyParameters($aScalarArgs);
	}
}

/**
 * Helper function to perform a custom sort of a hash array
 */
function HashCountComparison($a, $b) // Sort descending on 'count'
{
    if ($a['count'] == $b['count'])
    {
        return 0;
    }
    return ($a['count'] > $b['count']) ? -1 : 1;
}
