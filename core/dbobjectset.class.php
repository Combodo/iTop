<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('dbobjectiterator.php');

/**
 * Object set management
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * A set of persistent objects
 *
 * Created against a DBObjectSearch with additional information not relevant for the  DBObjectSearch (ie: order, limit, ...)
 * This set could be heterogeneous as long as the objects in the set have a common ancestor class.
 *
 * @package     iTopORM
 * @api
 */
class DBObjectSet implements iDBObjectSetIterator
{
	/**
	 * @var array
	 */
	protected $m_aAddedIds; // Ids of objects added (discrete lists)
	/**
	 * @var array array of (row => array of (classalias) => object/null) storing the objects added "in memory"
	 */
	protected $m_aAddedObjects;
	/**
	 * @var array
	 */
	protected $m_aArgs;
	/**
	 * @var array
	 */
	protected $m_aAttToLoad;
	/**
	 * @var null|array
	 */
	protected $m_aExtendedDataSpec;
	/**
	 * @var int Maximum number of elements to retrieve
	 */
	protected $m_iLimitCount;
	/**
	 * @var int Offset from which elements should be retrieved
	 */
	protected $m_iLimitStart;
	/**
	 * @var array
	 */
	protected $m_aOrderBy;
	/**
	 * @var bool True when the filter has been used OR the set is built step by step (AddObject...)
	 */
	public $m_bLoaded;
	/**
	 * @var int Total number of rows for the query without LIMIT. null if unknown yet
	 */
	protected $m_iNumTotalDBRows;
	/**
	 * @var int Total number of rows LOADED in $this->m_oSQLResult via a SQL query. 0 by default
	 */
	protected $m_iNumLoadedDBRows;
	/**
	 * @var int
	 */
	protected $m_iCurrRow;
	/**
	 * @var DBSearch
	 */
	protected $m_oFilter;
	/**
	 * @var mysqli_result
	 */
	protected $m_oSQLResult;
	protected $m_bSort;

	/**
	 * Create a new set based on a Search definition.
     *
     * @api
	 * 
	 * @param DBSearch $oFilter The search filter defining the objects which are part of the set (multiple columns/objects per row are supported)
	 * @param array $aOrderBy Array of '[<classalias>.]attcode' => bAscending (true for ASC, false, for DESC)
	 *    Example : array('name' => true, 'id' => false)
	 * @param array $aArgs Values to substitute for the search/query parameters (if any). Format: param_name => value
	 * @param array $aExtendedDataSpec
	 * @param int $iLimitCount Maximum number of rows to load (i.e. equivalent to MySQL's LIMIT start, count)
	 * @param int $iLimitStart Index of the first row to load (i.e. equivalent to MySQL's LIMIT start, count)
	 * @param bool $bSort if false no order by is done
	 */
	public function __construct(DBSearch $oFilter, $aOrderBy = array(), $aArgs = array(), $aExtendedDataSpec = null, $iLimitCount = 0, $iLimitStart = 0, $bSort = true)
	{
		$this->m_oFilter = $oFilter->DeepClone();
		$this->m_aAddedIds = array();
		$this->m_aOrderBy = $aOrderBy;
		$this->m_aArgs = $aArgs;
		$this->m_aAttToLoad = null;
		$this->m_aExtendedDataSpec = $aExtendedDataSpec;
		$this->m_iLimitCount = $iLimitCount;
		$this->m_iLimitStart = $iLimitStart;
		$this->m_bSort = $bSort;

		$this->m_iNumTotalDBRows = null;
		$this->m_iNumLoadedDBRows = 0;
		$this->m_bLoaded = false;
		$this->m_aAddedObjects = array();
		$this->m_iCurrRow = 0;
		$this->m_oSQLResult = null;
	}

    /**
     * @internal
     */
	public function __destruct()
	{
		if (is_object($this->m_oSQLResult))
		{
			$this->m_oSQLResult->free();
		}
	}

    /**
     * @internal
     *
     * @return string
     *
     * @throws \Exception
     * @throws \CoreException
     * @throws \MissingQueryArgument
     */
	public function __toString()
	{
		$sRet = '';
		$this->Rewind();
		$sRet .= "Set (".$this->m_oFilter->ToOQL(true).")<br/>\n";
        $sRet .= "Query: <pre style=\"font-size: smaller; display:inline;\">".$this->m_oFilter->MakeSelectQuery().")</pre>\n";
		
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
		$this->Rewind();
		return $sRet;
	}

    /**
     * @internal
     */
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
     * @internal
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
     * @internal
     * @param $bShow
     */
	public function SetShowObsoleteData($bShow)
	{
		$this->m_oFilter->SetShowObsoleteData($bShow);
	}

    /**
     * @internal
     * @return bool
     */
	public function GetShowObsoleteData()
	{
		return $this->m_oFilter->GetShowObsoleteData();
	}

    /**
     * Specify the subset of attributes to load
     * this subset is specified for each class of objects,
     * this has to be done before the actual fetch.
     *
     * @api
     *
     * @param array $aAttToLoad Format: alias => array of attribute_codes
     *
     * @return void
     *
     * @throws \Exception
     * @throws \CoreException
     */
	public function OptimizeColumnLoad($aAttToLoad)
	{
		// Check that the structure is an array of array
		if (!is_array($aAttToLoad))
		{
			$this->m_aAttToLoad = null;
			trigger_error ( "OptimizeColumnLoad : wrong format actual :(".print_r($aAttToLoad, true)."). should be [alias=>[attributes]]",  E_USER_WARNING );
			return;
		}
		foreach ($aAttToLoad as $sAlias => $aAttCodes)
		{
			if (!is_array($aAttCodes))
			{
				$this->m_aAttToLoad = null;
				trigger_error ( "OptimizeColumnLoad : wrong format actual :(".print_r($aAttToLoad, true)."). should be [alias=>[attributes]]",  E_USER_WARNING );
				return;
			}
		}

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
					if ($oAttDef->IsExternalKey(EXTKEY_ABSOLUTE))
					{
						// Add the external key friendly name anytime
						$oFriendlyNameAttDef = MetaModel::GetAttributeDef($sClass, $sAttToLoad.'_friendlyname');
						$aAttToLoadWithAttDef[$sClassAlias][$sAttToLoad.'_friendlyname'] = $oFriendlyNameAttDef;

						if (MetaModel::IsArchivable($oAttDef->GetTargetClass(EXTKEY_ABSOLUTE)))
						{
							// Add the archive flag if necessary
							$oArchiveFlagAttDef = MetaModel::GetAttributeDef($sClass, $sAttToLoad.'_archive_flag');
							$aAttToLoadWithAttDef[$sClassAlias][$sAttToLoad.'_archive_flag'] = $oArchiveFlagAttDef;
						}

						if (MetaModel::IsObsoletable($oAttDef->GetTargetClass(EXTKEY_ABSOLUTE)))
						{
							// Add the obsolescence flag if necessary
							$oObsoleteFlagAttDef = MetaModel::GetAttributeDef($sClass, $sAttToLoad.'_obsolescence_flag');
							$aAttToLoadWithAttDef[$sClassAlias][$sAttToLoad.'_obsolescence_flag'] = $oObsoleteFlagAttDef;
						}
					}
				}
			}

			// Add the friendly name anytime
			$oFriendlyNameAttDef = MetaModel::GetAttributeDef($sClass, 'friendlyname');
			$aAttToLoadWithAttDef[$sClassAlias]['friendlyname'] = $oFriendlyNameAttDef;

			if (MetaModel::IsArchivable($sClass))
			{
				// Add the archive flag if necessary
				$oArchiveFlagAttDef = MetaModel::GetAttributeDef($sClass, 'archive_flag');
				$aAttToLoadWithAttDef[$sClassAlias]['archive_flag'] = $oArchiveFlagAttDef;
			}

			if (MetaModel::IsObsoletable($sClass))
			{
				// Add the obsolescence flag if necessary
				$oObsoleteFlagAttDef = MetaModel::GetAttributeDef($sClass, 'obsolescence_flag');
				$aAttToLoadWithAttDef[$sClassAlias]['obsolescence_flag'] = $oObsoleteFlagAttDef;
			}

			// Make sure that the final class is requested anytime, whatever the specification (needed for object construction!)
			if (!MetaModel::IsStandaloneClass($sClass) && !array_key_exists('finalclass', $aAttToLoadWithAttDef[$sClassAlias]))
			{
				$aAttToLoadWithAttDef[$sClassAlias]['finalclass'] = MetaModel::GetAttributeDef($sClass, 'finalclass');
			}
		}

		$this->m_aAttToLoad = $aAttToLoadWithAttDef;
	}

    /**
     * Create a set (in-memory) containing just the given object
     *
     * @internal
     *
     * @param \DBobject $oObject
     *
     * @return \DBObjectSet The singleton set
     *
     * @throws \Exception
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
     * @internal
     *
     * @param string $sClass The class (or an ancestor) for the objects to be added in this set
     *
     * @return \DBObjectSet The empty set
     *
     * @throws \Exception
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
     * @internal
     *
     * @param string $sClass The class of the objects (must be a common ancestor to all objects in the set)
     * @param array $aObjects The list of objects to add into the set
     *
     * @return \DBObjectSet
     *
     * @throws \Exception
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
     * **Limitation:**
     * The filter/OQL query representing such a set can not be rebuilt (only the first column will be taken into account)
     *
     * @internal
     *
     * @param array $aClasses Format: array of (alias => class)
     * @param array $aObjects Format: array of (array of (classalias => object))
     *
     * @return \DBObjectSet
     *
     * @throws \Exception
     */
	static public function FromArrayAssoc($aClasses, $aObjects)
	{
		// In a perfect world, we should create a complete tree of DBObjectSearch,
		// but as we lack most of the information related to the objects,
		// let's create one search definition corresponding only to the first column
		$sClass = reset($aClasses);
		$sAlias = key($aClasses);
		$oFilter = new DBObjectSearch($sClass, $sAlias);

		$oRetSet = new self($oFilter);
		$oRetSet->m_bLoaded = true; // no DB load
		$oRetSet->m_iNumTotalDBRows = 0; // Nothing from the DB
		
		foreach($aObjects as $rowIndex => $aObjectsByClassAlias)
		{
			$oRetSet->AddObjectExtended($aObjectsByClassAlias);
		}
		return $oRetSet;
	}

    /**
     *
     * @internal
     *
     * @param $oObject
     * @param string $sLinkSetAttCode
     * @param string $sExtKeyToRemote
     *
     * @return \DBObjectSet
     *
     * @throws \Exception
     * @throws \ArchivedObjectException
     * @throws \CoreException
     */static public function FromLinkSet($oObject, $sLinkSetAttCode, $sExtKeyToRemote)
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

    /**
     * Fetch all as array of DBObject
     *
     * Note: After calling this method, the set cursor will be at the end of the set. You might want to rewind it.
     *
     * @api
     *
     * @param bool $bWithId if true array key will be set to object id
     *
     * @return DBObject[]
     *
     * @throws \Exception
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
     * @throws \MySQLException
     */
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

    /**
     * Fetch all as a structured array
     *
     * Unlike ToArray, ToArrayOfValues return the objects as an array.
     * Only the scalar values will be presents (see AttributeDefinition::IsScalar())
     *
     * @api
     *
     * @return array[]
     *
     * @throws \Exception
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
     * @throws \MySQLException
     */
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

    /**
     * Note: After calling this method, the set cursor will be at the end of the set. You might want to rewind it.
     *
     * @param string $sAttCode
     * @param bool $bWithId
     *
     * @return array
     *
     * @throws \Exception
     * @throws \CoreException
     */
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
     * Retrieve the DBSearch corresponding to the objects present in this set
     *
     * Limitation:
     * This method will NOT work for sets with several columns (i.e. several objects per row)
     *
     * @return \DBObjectSearch
     *
     * @throws \CoreException
     */
	public function GetFilter()
	{
		// Make sure that we carry on the parameters of the set with the filter
		$oFilter = $this->m_oFilter->DeepClone();
		$oFilter->SetShowObsoleteData(true);
		// Note: the arguments found within a set can be object (but not in a filter)
		// That's why PrepareQueryArguments must be invoked there
		$oFilter->SetInternalParams(array_merge($oFilter->GetInternalParams(), $this->m_aArgs));
		
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
	 * @return array Format: alias => class
	 */
	public function GetSelectedClasses()
	{
		return $this->m_oFilter->GetSelectedClasses();
	}

    /**
     * The root class (i.e. highest ancestor in the MeaModel class hierarchy) for the first column on this set
     *
     * @return string The root class for the objects in the first column of the set
     *
     * @throws \CoreException
     */
	public function GetRootClass()
	{
		return MetaModel::GetRootClass($this->GetClass());
	}

	/**
	 * The arguments used for building this set
	 * 
	 * @return array Format: parameter_name => value
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
     * @param array $aOrderBy Format: [alias.]attcode => boolean (true = ascending, false = descending)
     *
     * @throws \MySQLException
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
     * Sets the sort order for loading the rows from the DB. Changing the order by causes a Reload.
     *
     * @param array $aAliases Format: alias => boolean (true = ascending, false = descending). If omitted, then it defaults to all the selected classes
     *
     * @throws \CoreException
     * @throws \MySQLException
     */
	public function SetOrderByClasses($aAliases = null)
	{
		if ($aAliases === null)
		{
			$aAliases = array();
			foreach ($this->GetSelectedClasses() as $sAlias => $sClass)
			{
				$aAliases[$sAlias] = true;
			}
		}

		$aAttributes = array();
		foreach ($aAliases as $sAlias => $bClassDirection)
		{
			foreach (MetaModel::GetOrderByDefault($this->m_oFilter->GetClassName($sAlias)) as $sAttCode => $bAttributeDirection)
			{
				$bDirection = $bClassDirection ? $bAttributeDirection : !$bAttributeDirection;
				$aAttributes[$sAlias.'.'.$sAttCode] = $bDirection;
			}
		}
		$this->SetOrderBy($aAttributes);
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
     * @return array Format: field_code => boolean (true = ascending, false = descending)
     *
     * @throws \CoreException
     */
	public function GetRealSortOrder()
	{
		if (!$this->m_bSort)
		{
			// No order by
			return array();
		}
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
     *
     * @throws \Exception
     * @throws \MySQLException
     */
	public function Load()
	{
		if ($this->m_bLoaded) return;
		// Note: it is mandatory to set this value now, to protect against reentrance
		$this->m_bLoaded = true;

		$sSQL = $this->_makeSelectQuery($this->m_aAttToLoad);
		
		if (is_object($this->m_oSQLResult))
		{
			// Free previous resultset if any
			$this->m_oSQLResult->free();
			$this->m_oSQLResult = null;
		}

		try
		{
            $oKPI = new ExecutionKPI();
			$this->m_oSQLResult = CMDBSource::Query($sSQL);
            $sOQL = $this->GetPseudoOQL($this->m_oFilter, $this->GetRealSortOrder(), $this->m_iLimitCount, $this->m_iLimitStart, false);
            $oKPI->ComputeStats('OQL Query Exec', $sOQL);
		} catch (MySQLException $e)
		{
			// 1116 = ER_TOO_MANY_TABLES
			// https://dev.mysql.com/doc/refman/5.7/en/error-messages-server.html#error_er_too_many_tables
			if ($e->getCode() != 1116)
			{
				throw $e;
			}

			// N.689 Workaround for the 61 max joins in MySQL : full lazy load !
			$aAttToLoad = array();
			foreach($this->m_oFilter->GetSelectedClasses() as $sClassAlias => $sClass)
			{
				$aAttToLoad[$sClassAlias] = array();
				$bIsAbstractClass = MetaModel::IsAbstract($sClass);
				$bIsClassWithChildren = MetaModel::HasChildrenClasses($sClass);
				if ($bIsAbstractClass || $bIsClassWithChildren)
				{
					// we need finalClass field at least to be able to instantiate the real corresponding object !
					$aAttToLoad[$sClassAlias]['finalclass'] = MetaModel::GetAttributeDef($sClass, 'finalclass');
				}
			}
			$sSQL = $this->_makeSelectQuery($aAttToLoad);
			$this->m_oSQLResult = CMDBSource::Query($sSQL); // may fail again
		}

		if ($this->m_oSQLResult === false) return;

		if ((($this->m_iLimitCount == 0) || ($this->m_iLimitCount > $this->m_oSQLResult->num_rows)) && ($this->m_iLimitStart == 0))
		{
			$this->m_iNumTotalDBRows = $this->m_oSQLResult->num_rows;
		}

		$this->m_iNumLoadedDBRows = $this->m_oSQLResult->num_rows;
	}

    /**
     * @param string[] $aAttToLoad
     *
     * @return string SQL query
     *
     * @throws \CoreException
     * @throws \MissingQueryArgument
     */
	private function _makeSelectQuery($aAttToLoad)
	{
		if ($this->m_iLimitCount > 0)
		{
			$sSQL = $this->m_oFilter->MakeSelectQuery($this->GetRealSortOrder(), $this->m_aArgs, $aAttToLoad,
				$this->m_aExtendedDataSpec, $this->m_iLimitCount, $this->m_iLimitStart);
		}
		else
		{
			$sSQL = $this->m_oFilter->MakeSelectQuery($this->GetRealSortOrder(), $this->m_aArgs, $aAttToLoad,
				$this->m_aExtendedDataSpec);
		}

		return $sSQL;
	}

    /**
     * The total number of rows in this set. Independently of the SetLimit used for loading the set and taking into
     * account the rows added in-memory.
     *
     * May actually perform the SQL query SELECT COUNT... if the set was not previously loaded, or loaded with a
     * SetLimit
     *
     * @api
     * @return int The total number of rows for this set.
     *
     * @throws \CoreException
     * @throws \MissingQueryArgument
     * @throws \MySQLException
     * @throws \MySQLHasGoneAwayException
     */
	public function Count(): int
	{
		if (is_null($this->m_iNumTotalDBRows))
		{
            $oKPI = new ExecutionKPI();
			$sSQL = $this->m_oFilter->MakeSelectQuery(array(), $this->m_aArgs, null, null, 0, 0, true);
			$resQuery = CMDBSource::Query($sSQL);
            $sOQL = $this->GetPseudoOQL($this->m_oFilter, array(), 0, 0, true);
            $oKPI->ComputeStats('OQL Query Exec', $sOQL);
			if (!$resQuery) return 0;

			$aRow = CMDBSource::FetchArray($resQuery);
			CMDBSource::FreeResult($resQuery);
			$this->m_iNumTotalDBRows = intval($aRow['COUNT']);
		}

		return $this->m_iNumTotalDBRows + count($this->m_aAddedObjects); // Does it fix Trac #887 ??
	}

    /**
     * @param \DBSearch $oFilter
     * @param array $aOrder
     * @param int $iLimitCount
     * @param int $iLimitStart
     * @param bool $bCount
     *
     * @return string
     */
    private function GetPseudoOQL($oFilter, $aOrder, $iLimitCount, $iLimitStart, $bCount)
    {
        $sOQL = '';
        if ($bCount) {
            $sOQL .= 'COUNT ';
        }
        $sOQL .= $oFilter->ToOQL();

        if ($iLimitCount > 0) {
            $sOQL .= ' LIMIT ';
            if ($iLimitStart > 0) {
                $sOQL .= "$iLimitStart, ";
            }
            $sOQL .= "$iLimitCount";
        }

        if (count($aOrder) > 0) {
            $sOQL .= ' ORDER BY ';
            $aOrderBy = [];
            foreach ($aOrder as $sAttCode => $bAsc) {
                $aOrderBy[] = $sAttCode.' '.($bAsc ? 'ASC' : 'DESC');
            }
            $sOQL .= implode(', ', $aOrderBy);
        }
        return $sOQL;
    }

	/**
	 * Check if the count exceeds a given limit
	 *
	 * @param $iLimit
	 *
	 * @return bool
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public function CountExceeds($iLimit)
	{
		if (is_null($this->m_iNumTotalDBRows))
		{
            $oKPI = new ExecutionKPI();
			$sSQL = $this->m_oFilter->MakeSelectQuery(array(), $this->m_aArgs, null, null, $iLimit + 2, 0, true);
			$resQuery = CMDBSource::Query($sSQL);
            $sOQL = $this->GetPseudoOQL($this->m_oFilter, array(), $iLimit + 2, 0, true);
            $oKPI->ComputeStats('OQL Query Exec', $sOQL);
			if ($resQuery)
			{
				$aRow = CMDBSource::FetchArray($resQuery);
				$iCount = intval($aRow['COUNT']);
				CMDBSource::FreeResult($resQuery);
			}
			else
			{
				$iCount = 0;
			}
        }
		else
		{
			$iCount = $this->m_iNumTotalDBRows;
		}

		return ($iCount > $iLimit);
	}

	/**
	 * Count only up to the given limit
	 *
	 * @param $iLimit
	 *
	 * @return int
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public function CountWithLimit($iLimit)
	{
		if (is_null($this->m_iNumTotalDBRows))
		{
            $oKPI = new ExecutionKPI();
			$sSQL = $this->m_oFilter->MakeSelectQuery(array(), $this->m_aArgs, null, null, $iLimit + 2, 0, true);
			$resQuery = CMDBSource::Query($sSQL);
            $sOQL = $this->GetPseudoOQL($this->m_oFilter, array(), $iLimit + 2, 0, true);
            $oKPI->ComputeStats('OQL Query Exec', $sOQL);
			if ($resQuery)
			{
				$aRow = CMDBSource::FetchArray($resQuery);
				CMDBSource::FreeResult($resQuery);
				$iCount = intval($aRow['COUNT']);
			}
			else
			{
				$iCount = 0;
			}
        }
		else
		{
			$iCount = $this->m_iNumTotalDBRows;
		}

		return $iCount;
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
     * Fetch an object (with the given class alias) at the current position in the set and move the cursor to the next position.
     *
     * @api
     *
     * @param string $sRequestedClassAlias The class alias to fetch (defaults to the first selected class)
     *
     * @return \DBObject The fetched object or null when at the end
     *
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
     * @throws \MySQLException
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
						try
						{
							$oRetObj = MetaModel::GetObjectByRow($sClass, $aRow, $sClassAlias, $this->m_aAttToLoad, $this->m_aExtendedDataSpec);
						}
						catch (CoreException $e)
						{
							$this->m_iCurrRow++;
							$oRetObj = $this->Fetch($sRequestedClassAlias);
						}
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
     * @api
     *
     * @return array An associative with the format 'classAlias' => $oObj representing the current row of the set. Returns null when at the end.
     *
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
     * @throws \MySQLException
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
	 *
	 * @api
	 *
	 * @throws \Exception
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
     *
     * @throws \CoreException
     * @throws \MissingQueryArgument
     * @throws \MySQLException
     * @throws \MySQLHasGoneAwayException
     * @since 3.1.0 N°4517 Now returns void for return type to match parent class and be compatible with PHP 8.1
     */
	public function Seek($iRow): void
	{
		if (!$this->m_bLoaded) $this->Load();

		$this->m_iCurrRow = min($iRow, $this->Count());
		if ($this->m_iCurrRow < $this->m_iNumLoadedDBRows)
		{
			$this->m_oSQLResult->data_seek($this->m_iCurrRow);
		}
	}

    /**
     * Add an object to the current set (in-memory only, nothing is written to the database)
     *
     * Limitation:
     * Sets with several objects per row are NOT supported
     *
     * @param \DBObject $oObject The object to add
     * @param string $sClassAlias The alias for the class of the object
     *
     * @throws \MySQLException
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
     * @param array $aObjectArray
     *
     * @throws \MySQLException
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
     *
     * @throws \MySQLException
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
	 * @param \DBObjectSet $oObjectSet The set to append
     *
	 * @throws \CoreException
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
     * @param \DBObjectSet $oObjectSet The set to intersect with. The current position inside the set will be lost (= at the end)
     *
     * @return \DBObjectSet A new set of objects, containing the objects present in both sets (based on their identifier)
     *
     * @throws \Exception
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
     * @throws \MissingQueryArgument
     * @throws \MySQLException
     * @throws \MySQLHasGoneAwayException
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
     * Works only for sets of 1 column (i.e. one class of object selected)
     *
     * @param \DBObjectSet $oObjectSet
     * @param array $aExcludeColumns The list of columns to exclude frop the comparison
     *
     * @return boolean True if the sets are identical, false otherwise
     *
     * @throws \CoreException
     */
	public function HasSameContents(DBObjectSet $oObjectSet, $aExcludeColumns = array())
	{	
		$oComparator = new DBObjectSetComparator($this, $oObjectSet, $aExcludeColumns);
		return $oComparator->SetsAreEquivalent();
	}

	/**
	 * Build a new set (in memory) made of objects of the given set which are NOT present in the current set
	 * 
	 * Limitations:
	 * The objects inside the set must be written in the database since the comparison is based on their identifiers
	 * Sets with several objects per row are NOT supported
	 * 
	 * @param \DBObjectSet $oObjectSet
	 * 
	 * @return \DBObjectSet The "delta" set.
	 *
	 * @throws \Exception
	 * @throws \CoreException
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
     * Compute the "RelatedObjects" (forward or "down" direction) for the set
     * for the specified relation
     *
     * @param string $sRelCode The code of the relation to use for the computation
     * @param int $iMaxDepth Maximum recursion depth
     * @param bool $bEnableRedundancy
     *
     * @return \RelationGraph The graph of all the related objects
     *
     * @throws \Exception
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
     * @throws \MySQLException
     */
	public function GetRelatedObjectsDown($sRelCode, $iMaxDepth = 99, $bEnableRedundancy = true)
	{
		$oGraph = new RelationGraph();
		$this->Rewind();
		while($oObj = $this->Fetch())
		{
			$oGraph->AddSourceObject($oObj);
		}
		$oGraph->ComputeRelatedObjectsDown($sRelCode, $iMaxDepth, $bEnableRedundancy);
		return $oGraph;
	}

    /**
     * Compute the "RelatedObjects" (reverse or "up" direction) for the set
     * for the specified relation
     *
     * @param string $sRelCode The code of the relation to use for the computation
     * @param int $iMaxDepth Maximum recursion depth
     * @param bool $bEnableRedundancy
     *
     * @return \RelationGraph The graph of all the related objects
     *
     * @throws \Exception
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
     * @throws \MySQLException
     */
	public function GetRelatedObjectsUp($sRelCode, $iMaxDepth = 99, $bEnableRedundancy = true)
	{
		$oGraph = new RelationGraph();
		$this->Rewind();
		while($oObj = $this->Fetch())
		{
			$oGraph->AddSinkObject($oObj);
		}
		$oGraph->ComputeRelatedObjectsUp($sRelCode, $iMaxDepth, $bEnableRedundancy);
		return $oGraph;
	}

    /**
     * Builds an object that contains the values that are common to all the objects
     * in the set. If for a given attribute, objects in the set have various values
     * then the resulting object will contain null for this value.
     *
     * @param array $aValues Hash Output: the distribution of the values, in the set, for each attribute
     *
     * @return \DBObject The object with the common values
     *
     * @throws \Exception
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
     * @throws \MySQLException
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
	 * @return array [Alias][AttCode] => value
	 */
	public function ListConstantFields()
	{
		// The complete list of arguments will include magic arguments (e.g. current_user->attcode)
		$aScalarArgs = MetaModel::PrepareQueryArguments($this->m_oFilter->GetInternalParams(), $this->m_aArgs, $this->m_oFilter->GetExpectedArguments());
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
	
	public function ApplyParameters()
	{
		$aAllArgs = MetaModel::PrepareQueryArguments($this->m_oFilter->GetInternalParams(), $this->m_aArgs, $this->m_oFilter->GetExpectedArguments());
		$this->m_oFilter->ApplyParameters($aAllArgs);
	}
}

/**
 * Helper function to perform a custom sort of a hash array
 *
 * @internal
 */
function HashCountComparison($a, $b) // Sort descending on 'count'
{
    if ($a['count'] == $b['count'])
    {
        return 0;
    }
    return ($a['count'] > $b['count']) ? -1 : 1;
}

/**
 * Helper class to compare the content of two DBObjectSets based on the fingerprints of the contained objects
 * The FIRST SET MUST BE LOADED FROM THE DATABASE, the second one can be a set of objects in memory
 * When computing the actual differences, the algorithm tries to preserve as much as possible the EXISTING
 * objects (i.e. prefers 'modified' to 'removed' + 'added')
 * 
 * LIMITATIONS:
 *  - only DBObjectSets with one column (i.e. one class of object selected) are supported
 *  - the first set must be the one loaded from the database
 *
 * @internal
 *
 * @package iTopORM
 *
 */
class DBObjectSetComparator
{
	protected $aFingerprints1;
	protected $aFingerprints2;
	protected $aIDs1;
	protected $aIDs2;
	protected $aExcludedColumns;

	/**
	 * @var iDBObjectSetIterator
	 */
	protected $oSet1;
	/**
	 * @var iDBObjectSetIterator
	 */
	protected $oSet2;

	protected $sAdditionalKeyColumn;
	protected $aAdditionalKeys;
	
	/**
	 * Initializes the comparator
	 * @param iDBObjectSetIterator $oSet1 The first set of objects to compare, or null
	 * @param iDBObjectSetIterator $oSet2 The second set of objects to compare, or null
	 * @param array $aExcludedColumns The list of columns (= attribute codes) to exclude from the comparison
	 * @param string $sAdditionalKeyColumn The attribute code of an additional column to be considered as a key indentifying the object (useful for n:n links)
	 */
	public function __construct(iDBObjectSetIterator $oSet1, iDBObjectSetIterator $oSet2, $aExcludedColumns = array(), $sAdditionalKeyColumn = null)
	{
		$this->aFingerprints1 = null;
		$this->aFingerprints2 = null;
		$this->aIDs1 = array();
		$this->aIDs2 = array();
		$this->aExcludedColumns = $aExcludedColumns;
		$this->sAdditionalKeyColumn = $sAdditionalKeyColumn;
		$this->aAdditionalKeys = null;
		$this->oSet1 = $oSet1;
		$this->oSet2 = $oSet2;		
	}

    /**
     * Builds the lists of fingerprints and initializes internal structures, if it was not already done
     *
     * @internal
     *
     * @throws \CoreException
     */
	protected function ComputeFingerprints()
	{
		if ($this->aFingerprints1 === null)
		{
			$this->aFingerprints1 = array();
			$this->aFingerprints2 = array();
			$this->aAdditionalKeys = array();
			
			if ($this->oSet1 !== null)
			{
				$this->oSet1->Rewind();
				while($oObj = $this->oSet1->Fetch())
				{
					$sFingerprint = $oObj->Fingerprint($this->aExcludedColumns);
					$this->aFingerprints1[$sFingerprint] = $oObj;
					if (!$oObj->IsNew())
					{
						$this->aIDs1[$oObj->GetKey()] = $oObj;
					}
				}
				$this->oSet1->Rewind();
			}
				
			if ($this->oSet2 !== null)
			{
				$this->oSet2->Rewind();
				while($oObj = $this->oSet2->Fetch())
				{
					$sFingerprint = $oObj->Fingerprint($this->aExcludedColumns);
					$this->aFingerprints2[$sFingerprint] = $oObj;
					if (!$oObj->IsNew())
					{
						$this->aIDs2[$oObj->GetKey()] = $oObj;
					}
					
					if ($this->sAdditionalKeyColumn !== null)
					{
						$this->aAdditionalKeys[$oObj->Get($this->sAdditionalKeyColumn)] = $oObj;
					}
				}
				$this->oSet2->Rewind();
			}
		}
	}

    /**
     * Tells if the sets are equivalent or not. Returns as soon as the first difference is found.
     *
     * @internal
     *
     * @return boolean true if the set have an equivalent content, false otherwise
     *
     * @throws \CoreException
     */
	public function SetsAreEquivalent()
	{
		if (($this->oSet1 === null) && ($this->oSet2 === null))
		{
			// Both sets are empty, they are equal
			return true;
		}
		else if (($this->oSet1 === null) || ($this->oSet2 === null))
		{
			// one of them is empty, they are different
			return false;
		}
		
		if (($this->oSet1->GetRootClass() != $this->oSet2->GetRootClass()) || ($this->oSet1->Count() != $this->oSet2->Count())) return false;
		
		$this->ComputeFingerprints();
		
		// Check that all objects in Set1 are also in Set2
		foreach($this->aFingerprints1 as $sFingerprint => $oObj)
		{
			if (!array_key_exists($sFingerprint, $this->aFingerprints2))
			{
				return false;
			}
		}
		
		// Vice versa
		// Check that all objects in Set2 are also in Set1
		foreach($this->aFingerprints2 as $sFingerprint => $oObj)
		{
			if (!array_key_exists($sFingerprint, $this->aFingerprints1))
			{
				return false;
			}
		}
		
		return true;
	}

    /**
     * Get the list of differences between the two sets. In ordeer to write back into the database only the minimum changes
     * THE FIRST SET MUST BE THE ONE LOADED FROM THE DATABASE
     *
     * @internal
     *
     * @return array 'added' => DBObject(s), 'removed' => DBObject(s), 'modified' => DBObjects(s)
     *
     * @throws \Exception
     * @throws \CoreException
     */
	public function GetDifferences()
	{
		$aResult = array('added' => array(), 'removed' => array(), 'modified' => array());
		$this->ComputeFingerprints();
		
		// Check that all objects in Set1 are also in Set2
		foreach($this->aFingerprints1 as $sFingerprint => $oObj)
		{
			// Beware: the elements from the first set MUST come from the database, otherwise the result will be irrelevant
			if ($oObj->IsNew()) throw new Exception('Cannot compute differences when elements from the first set are NOT in the database');
			if (array_key_exists($oObj->GetKey(), $this->aIDs2) && ($this->aIDs2[$oObj->GetKey()]->IsModified()))
			{
				// The very same object exists in both set, but was modified since its load
				$aResult['modified'][$oObj->GetKey()] = $this->aIDs2[$oObj->GetKey()];
			}
			else if (($this->sAdditionalKeyColumn !== null) && array_key_exists($oObj->Get($this->sAdditionalKeyColumn), $this->aAdditionalKeys))
			{
				// Special case for n:n links where the link is recreated between the very same 2 objects, but some of its attributes are modified
				// Let's consider this as a "modification" instead of "deletion" + "creation" in order to have a "clean" history for the objects
				$oDestObj = $this->aAdditionalKeys[$oObj->Get($this->sAdditionalKeyColumn)];
				$oCloneObj = $this->CopyFrom($oObj, $oDestObj);
				$aResult['modified'][$oObj->GetKey()] = $oCloneObj;
				// Mark this as processed, so that the pass on aFingerprints2 below ignores this object
				$sNewFingerprint = $oDestObj->Fingerprint($this->aExcludedColumns);
				$this->aFingerprints2[$sNewFingerprint] = $oCloneObj;
			}
			else if (!array_key_exists($sFingerprint, $this->aFingerprints2))
			{
				$aResult['removed'][] = $oObj;
			}
		}
		
		// Vice versa
		// Check that all objects in Set2 are also in Set1
		foreach($this->aFingerprints2 as $sFingerprint => $oObj)
		{
			if (array_key_exists($oObj->GetKey(), $this->aIDs1) && ($oObj->IsModified()))
			{
				// Already marked as modified above
				//$aResult['modified'][$oObj->GetKey()] = $oObj;
			}
			else if (!array_key_exists($sFingerprint, $this->aFingerprints1))
			{
				$aResult['added'][] = $oObj;
			}
		}
		return $aResult;
	}

    /**
     * Helper to clone (in memory) an object and to apply to it the values taken from a second object
     *
     * @internal
     *
     * @param \DBObject $oObjToClone
     * @param \DBObject $oObjWithValues
     *
     * @return \DBObject The modified clone
     *
     * @throws \ArchivedObjectException
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
     */
	protected function CopyFrom($oObjToClone, $oObjWithValues)
	{
		$oObj = MetaModel::GetObject(get_class($oObjToClone), $oObjToClone->GetKey());
		foreach(MetaModel::ListAttributeDefs(get_class($oObj)) as $sAttCode => $oAttDef)
		{
			if (!in_array($sAttCode, $this->aExcludedColumns) && $oAttDef->IsWritable())
			{
				$oObj->Set($sAttCode, $oObjWithValues->Get($sAttCode));
			}
		}
		return $oObj;
	}
}
