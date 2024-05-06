<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * @package     iTopORM
 * @api
 * @see DBObjectSearch::__construct()
 * @see DBUnionSearch::__construct()
 */
abstract class DBSearch
{
	/** @internal */
	const JOIN_POINTING_TO = 0;
	/** @internal */
	const JOIN_REFERENCED_BY = 1;

	protected $m_bNoContextParameters = false;
	/** @var array For {@see iQueryModifier} impl */
	protected $m_aModifierProperties = array();
	protected $m_bArchiveMode = false;
	protected $m_bShowObsoleteData = true;

	/**
	 * DBSearch constructor.
	 *
	 * @api
	 * @see DBSearch::FromOQL()
	 */
	public function __construct()
	{
		$this->Init();
	}

    /**
     * called by the constructor
     * @internal Set the obsolete and archive modes to the default ones
     */
	protected function Init()
	{
		$this->m_bArchiveMode = utils::IsArchiveMode();
		$this->m_bShowObsoleteData = true;
	}

	/**
	 * Perform a deep clone (as opposed to "clone" which does copy a reference to the underlying objects)
	 *
	 * @internal
	 *
	 * @return \DBSearch
	 **/
	public function DeepClone()
	{
		return unserialize(serialize($this)); // Beware this serializes/unserializes the search and its parameters as well
	}

	/**
	 * @api
	 * @see IsAllDataAllowed()
	 *
	 * @param bool $bAllowAllData whether or not some information should be hidden to the current user.
	 */
	abstract public function AllowAllData($bAllowAllData = true);

	/**
	 * Current state of AllowAllData
	 *
	 * @internal
	 * @see AllowAllData()
	 *
	 * @return mixed
	 */
	abstract public function IsAllDataAllowed();

	/**
	 * Should the archives be fetched
	 *
	 * @internal
	 *
	 * @param $bEnable
	 */
	public function SetArchiveMode($bEnable)
	{
		$this->m_bArchiveMode = $bEnable;
	}

    /**
     * @internal
     * @return bool
     */
	public function GetArchiveMode()
	{
		return $this->m_bArchiveMode;
	}

    /**
     * Should the obsolete data be fetched
     *
     * @internal
     * @param $bShow
     */
	public function SetShowObsoleteData($bShow)
	{
		$this->m_bShowObsoleteData = $bShow;
	}

    /**
     * @internal
     * @return bool
     */
	public function GetShowObsoleteData()
	{
		if ($this->m_bArchiveMode || $this->IsAllDataAllowed())
		{
			// Enable obsolete data too!
			$bRet = true;
		}
		else
		{
			$bRet = $this->m_bShowObsoleteData;
		}
		return $bRet;
	}

    /**
     * @internal
     */
	public function NoContextParameters() {$this->m_bNoContextParameters = true;}

    /**
     * @internal
     * @return bool
     */
	public function HasContextParameters() {return $this->m_bNoContextParameters;}

    /**
     * @internal
     *
     * @param $sPluginClass
     * @param $sProperty
     * @param $value
     */
	public function SetModifierProperty($sPluginClass, $sProperty, $value)
	{
		$this->m_aModifierProperties[$sPluginClass][$sProperty] = $value;
	}

    /**
     * @internal
     *
     * @param $sPluginClass
     *
     * @return array|mixed
     */
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

    /**
     * @internal
     * @param $sAlias
     *
     * @return mixed
     */
	abstract public function GetClassName($sAlias);

    /**
     * @internal
     * @return mixed
     */
	abstract public function GetClass();

    /**
     * @internal
     * @return mixed
     */
	abstract public function GetClassAlias();

	/**
	 * @return string
	 * @internal
	 */
	abstract public function GetFirstJoinedClass();

	/**
     * Change the class
     *
     * Defaults to the first selected class (most of the time it is also the first joined class
     * only subclasses are supported as of now, because the conditions must fit the new class
     *
     * @internal
     */
	abstract public function ChangeClass($sNewClass, $sAlias = null);

    /**
     * @internal
     * @return mixed
     */
	abstract public function GetSelectedClasses();

	/**
     * @internal
	 * @param array $aSelectedClasses array of aliases
	 * @throws CoreException
	 */
	abstract public function SetSelectedClasses($aSelectedClasses);

	/**
	 * Change any alias of the query tree
	 *
     * @internal
     *
	 * @param $sOldName
	 * @param $sNewName
	 * @return bool True if the alias has been found and changed
	 */
	abstract public function RenameAlias($sOldName, $sNewName);

	abstract public function RenameAliasesInNameSpace($aClassAliases, $aAliasTranslation = array());

	abstract public function TranslateConditions($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true);

	/**
	 * @internal
	 * @return mixed
	 */
	abstract public function IsAny();

	/**
	 * @internal
	 * @deprecated use ToOQL() instead
	 * @return string
	 */
	public function Describe()
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use ToOQL() instead');

		return 'deprecated - use ToOQL() instead';
	}

	/**
	 * @internal
	 * @deprecated use ToOQL() instead
	 * @return string
	 */
	public function DescribeConditionPointTo($sExtKeyAttCode, $aPointingTo)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use ToOQL() instead');

		return 'deprecated - use ToOQL() instead';
	}

	/**
	 * @internal
	 * @deprecated use ToOQL() instead
	 * @return string
	 */
	public function DescribeConditionRefBy($sForeignClass, $sForeignExtKeyAttCode)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use ToOQL() instead');

		return 'deprecated - use ToOQL() instead';
	}

	/**
	 * @internal
	 * @deprecated use ToOQL() instead
	 * @return string
	 */
	public function DescribeConditionRelTo($aRelInfo)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use ToOQL() instead');

		return 'deprecated - use ToOQL() instead';
	}

	/**
	 * @internal
	 * @deprecated use ToOQL() instead
	 * @return string
	 */
	public function DescribeConditions()
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use ToOQL() instead');

		return 'deprecated - use ToOQL() instead';
	}

	/**
	 * @internal
	 * @deprecated use ToOQL() instead
	 * @return string
	 */
	public function __DescribeHTML()
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use ToOQL() instead');

		return 'deprecated - use ToOQL() instead';
	}

	/**
	 * @internal
	 * @return mixed
	 */
	abstract public function ResetCondition();

	/**
	 * add $oExpression as a OR
	 *
	 * @api
	 * @see DBSearch::AddConditionExpression()
	 *
	 * @param Expression $oExpression
     *
     * @return mixed
     */
	abstract public function MergeConditionExpression($oExpression);

    /**
     * add $oExpression as a AND
     *
     * @api
     * @see DBSearch::MergeConditionExpression()
     *
     * @param Expression $oExpression
     *
     * @return mixed
     */
	abstract public function AddConditionExpression($oExpression);

    /**
     * Condition on the friendlyname
     *
     * Restrict the query to only the corresponding selected class' friendlyname
     *
     * @internal
     *
     * @param string $sName the desired friendlyname
     *
     * @return mixed
     */
  	abstract public function AddNameCondition($sName);

    /**
     * Add a condition
     *
     * This is the simplest way to express a AND condition. For complex use cases, use MergeConditionExpression or AddConditionExpression instead
     *
     * @api
     *
     * @param string $sFilterCode
     * @param mixed  $value
     * @param string $sOpCode operator to use : '=' (default), '!=', 'IN', 'NOT IN'
     *
     * @throws \CoreException
     *
     */
	abstract public function AddCondition($sFilterCode, $value, $sOpCode = null);
	/**
	 * Specify a condition on external keys or link sets
     *
     * @internal
     *
	 * @param string $sAttSpec Can be either an attribute code or extkey->[sAttSpec] or linkset->[sAttSpec] and so on, recursively
	 *                 Example: infra_list->ci_id->location_id->country	 
	 * @param mixed $value The value to match (can be an array => IN(val1, val2...)
	 * @return void
	 */
	abstract public function AddConditionAdvanced($sAttSpec, $value);

    /**
     * @internal
     *
     * @param string $sFullText
     *
     * @return mixed
     */
	abstract public function AddCondition_FullText($sFullText);

	abstract public function AddCondition_FullTextOnAttributes(array $aAttCodes, $sNeedle);

		/**
     * Perform a join, the remote class being matched by the mean of its primary key
     *
     * The join is performed
     *   * from the searched class, based on the $sExtKeyAttCode attribute
     *   * against the oFilter searched class, based on its primary key
     * Note : if several classes have already being joined (SELECT a join b ON...), the first joined class (a in the example) is considered as being the searched class.
     *
     * @api
     * @see AddCondition_ReferencedBy()
     *
	 * @param DBObjectSearch $oFilter
	 * @param string $sExtKeyAttCode
	 * @param int $iOperatorCode the comparison operator to use. For the list of all possible values, see the constant defined in core/oql/oqlquery.class.inc.php
	 * @param array|null $aRealiasingMap array of <old-alias> => <new-alias>, for each alias that has changed in the newly attached oFilter (in case of collisions between the two filters)
     *
	 * @throws CoreException
	 * @throws CoreWarning
	 */
	abstract public function AddCondition_PointingTo(DBObjectSearch $oFilter, $sExtKeyAttCode, $iOperatorCode = TREE_OPERATOR_EQUALS, &$aRealiasingMap = null);

	/**
     * Inverse operation of AddCondition_PointingTo
     *
     * The join is performed
     *   * from the olFilter searched class, based on the $sExtKeyAttCode attribute
     *   * against the searched class, based on its primary key
     * Note : if several classes have already being joined (SELECT a join b ON...), the first joined class (a in the example) is considered as being the searched class.
     *
     *
     * @api
     * @see AddCondition_PointingTo()
     *
	 * @param DBObjectSearch $oFilter
	 * @param $sForeignExtKeyAttCode
	 * @param int $iOperatorCode
	 * @param array|null $aRealiasingMap array of <old-alias> => <new-alias>, for each alias that has changed in the newly attached oFilter (in case of collisions between the two filters)
	 */
	abstract public function AddCondition_ReferencedBy(DBObjectSearch $oFilter, $sForeignExtKeyAttCode, $iOperatorCode = TREE_OPERATOR_EQUALS, &$aRealiasingMap = null);

	/**
	 * Filter this search with another search.
	 * Initial search is unmodified.
	 * The difference with Intersect, is that an alias can be provided,
	 * the filtered class does not need to be the first joined class,
	 * it can be any class of the search.
	 *
	 * @param string $sClassAlias class being filtered
	 * @param DBSearch $oFilter Filter to apply
	 *
	 * @return DBSearch The filtered search
	 * @throws \CoreException
	 */
	abstract public function Filter($sClassAlias, DBSearch $oFilter);

	/**
     * Filter the result
     *
     * The filter is performed by returning only the values in common with the given $oFilter
     * The impact on the resulting query performance/viability can be significant.
     * Only the first joined class can be filtered.
     *
     * @internal
     *
     * @param DBSearch $oFilter
     *
     * @return mixed
     */
	abstract public function Intersect(DBSearch $oFilter);

    /**
     * Perform a join
     *
     * The join is performed against $oFilter selected class using $sExtKeyAttCode of the current selected class
     *
     * @internal
     *
     * @param DBSearch   $oFilter        The join is performed against $oFilter selected class
     * @param integer    $iDirection     can be either DBSearch::JOIN_POINTING_TO or DBSearch::JOIN_REFERENCED_BY
     * @param string     $sExtKeyAttCode The join is performed against $sExtKeyAttCode whether it is compared against the current DBSearch or $oFilter depend of $iDirection
     * @param integer    $iOperatorCode  See DBSearch::AddCondition_PointingTo()
     * @param array|null $aRealiasingMap Map of aliases from the attached query, that could have been renamed by the optimization process
     *
     * @return DBSearch
     * @throws CoreException
     * @throws CoreWarning
     */
	public function Join(DBSearch $oFilter, $iDirection, $sExtKeyAttCode, $iOperatorCode = TREE_OPERATOR_EQUALS, &$aRealiasingMap = null)
	{
		$oSourceFilter = $this->DeepClone();
		$oRet = null;

		if ($oFilter instanceof DBUnionSearch)
		{
			$aSearches = array();
			foreach ($oFilter->GetSearches() as $oSearch)
			{
				$aSearches[] = $oSourceFilter->Join($oSearch, $iDirection, $sExtKeyAttCode, $iOperatorCode, $aRealiasingMap);
			}
			$oRet = new DBUnionSearch($aSearches);
		}
		else
		{
			/** @var \DBObjectSearch $oFilter */
			if ($iDirection === static::JOIN_POINTING_TO)
			{
				$oSourceFilter->AddCondition_PointingTo($oFilter, $sExtKeyAttCode, $iOperatorCode, $aRealiasingMap);
			}
			else
			{
				if ($iOperatorCode !== TREE_OPERATOR_EQUALS)
				{
					throw new Exception('Only TREE_OPERATOR_EQUALS  operator code is supported yet for AddCondition_ReferencedBy.');
				}
				$oSourceFilter->AddCondition_ReferencedBy($oFilter, $sExtKeyAttCode, TREE_OPERATOR_EQUALS, $aRealiasingMap);
			}
			$oRet = $oSourceFilter;
		}

		return $oRet;
	}

    /**
     * Set the internal params.
     *
     * If any params pre-existed, they are lost.
     *
     * @internal
     *
     * @param mixed[string] $aParams array of mixed params index by string name
     *
     * @return mixed
     */
	abstract public function SetInternalParams($aParams);

    /**
     * @internal
     * @return mixed
     */
	abstract public function GetInternalParams();

    /**
     * @internal
     *
     * @param bool $bExcludeMagicParams
     *
     * @return mixed
     */
	abstract public function GetQueryParams($bExcludeMagicParams = true);

    /**
     * @internal
     * @return mixed
     */
	abstract public function ListConstantFields();

	/**
     * Turn the parameters (:xxx) into scalar values
     *
     * The goal is to easily serialize a search
	 *
     * @internal
     *
	 * @param array $aArgs
	 *
	 * @return string
	 */
	abstract public function ApplyParameters($aArgs);

    /**
     * Convert a query to a string representation
     *
     * This operation can be revert back to a DBSearch using DBSearch::unserialize()
     *
     * @api
     * @see DBSearch::unserialize()
     *
     * @param bool  $bDevelopParams
     * @param array $aContextParams
     *
     * @return false|string
     * @throws ArchivedObjectException
     * @throws CoreException
     */
    public function serialize($bDevelopParams = false, $aContextParams = array())
	{
		$aQueryParams = $this->GetQueryParams();

		$aContextParams = array_merge($this->GetInternalParams(), $aContextParams);

		foreach($aQueryParams as $sParam => $sValue)
		{
			if (isset($aContextParams[$sParam]))
			{
				$aQueryParams[$sParam] = $aContextParams[$sParam];
			}
			elseif (($iPos = strpos($sParam, '->')) !== false)
			{
				$sParamName = substr($sParam, 0, $iPos);
				if (isset($aContextParams[$sParamName.'->object()']) || isset($aContextParams[$sParamName]))
				{
					$sAttCode = substr($sParam, $iPos + 2);
					/** @var \DBObject $oObj */
					$oObj = isset($aContextParams[$sParamName.'->object()']) ? $aContextParams[$sParamName.'->object()'] : $aContextParams[$sParamName];
					if ($oObj->IsModified())
					{
						if ($sAttCode == 'id')
						{
							$aQueryParams[$sParam] = $oObj->GetKey();
						}
						else
						{
							$aQueryParams[$sParam] = $oObj->Get($sAttCode);
						}
					}
					else
					{
						unset($aQueryParams[$sParam]);
						// For database objects, serialize only class, key
						$aQueryParams[$sParamName.'->id'] = $oObj->GetKey();
						$aQueryParams[$sParamName.'->class'] = get_class($oObj);
					}
				}
			}
		}

		$sOql = $this->ToOql($bDevelopParams, $aContextParams);
		return urlencode(json_encode(array($sOql, $aQueryParams, $this->m_aModifierProperties)));
	}

	/**
     * Convert a serialized query back to an instance of DBSearch
     *
     * @api
     *
	 * @param string $sValue Serialized OQL query
	 *
	 * @return \DBSearch
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	static public function unserialize($sValue)
	{
		$aData = json_decode(urldecode($sValue), true);
		if (is_null($aData))
		{
			throw new CoreException("Invalid filter parameter");
		}
		$sOql = $aData[0];
		$aParams = $aData[1];
		$aExtraParams = array();
		foreach($aParams as $sParam => $sValue)
		{
			if (($iPos = strpos($sParam, '->class')) !== false)
			{
				$sParamName = substr($sParam, 0, $iPos);
				if (isset($aParams[$sParamName.'->id']))
				{
					$sClass = $aParams[$sParamName.'->class'];
					$iKey = $aParams[$sParamName.'->id'];
					$oObj = MetaModel::GetObject($sClass, $iKey);
					$aExtraParams[$sParamName.'->object()'] = $oObj;
				}
			}
		}
		$aParams = array_merge($aExtraParams, $aParams);
		// We've tried to use gzcompress/gzuncompress, but for some specific queries
		// it was not working at all (See Trac #193)
		// gzuncompress was issuing a warning "data error" and the return object was null
		$oRetFilter = self::FromOQL($sOql, $aParams);
		$oRetFilter->m_aModifierProperties = $aData[2];
		return $oRetFilter;
	}

    /**
     * Create a new DBObjectSearch from $oSearch with a new alias $sAlias
     *
     * @internal Note : This has not be tested with UNION queries.
     *
     * @param DBSearch $oSearch
     * @param string   $sAlias
     *
     * @return DBObjectSearch
     * @throws CoreException
     */
    static public function CloneWithAlias(DBSearch $oSearch, $sAlias)
    {
        $oSearchWithAlias = new DBObjectSearch($oSearch->GetClass(), $sAlias);
        $oSearchWithAlias = $oSearchWithAlias->Intersect($oSearch);
        return $oSearchWithAlias;
    }

    /**
     * Convert the DBSearch to an OQL representation
     *
     * @api
     * @see DBSearch::FromOQL()
     *
     * @param bool $bDevelopParams
     * @param null $aContextParams
     * @param bool $bWithAllowAllFlag
     *
     * @return mixed
     */
    abstract public function ToOQL($bDevelopParams = false, $aContextParams = null, $bWithAllowAllFlag = false);

    /**
     * Export the DBSearch as a structure (array of arrays...) suitable for a conversion to JSON
     *
     * @internal
     *
     * @return mixed[string]
     */
    abstract public function ToJSON();

	static protected $m_aOQLQueries = array();

    /**
     * FromOQL with AllowAllData enabled
     *
     * The goal is to  not filter out depending on user rights.
     * In particular when we are currently in the process of evaluating the user rights...
     *
     * @internal
     * @see DBSearch::FromOQL()
     *
     * @param string $sQuery
     * @param null   $aParams
     *
     * @return DBSearch
     * @throws OQLException
     */
	static public function FromOQL_AllData($sQuery, $aParams = null)
	{
		$oRes = self::FromOQL($sQuery, $aParams);
		$oRes->AllowAllData();
		return $oRes;
	}

	/**
     * Create a new DBSearch from the given OQL.
     *
     * This is the simplest way to create a DBSearch.
     * For almost every cases, this is the easiest way.
     *
     * @api
     * @see DBSearch::ToOQL()
     *
	 * @param string $sQuery The OQL to convert to a DBSearch
	 * @param array $aParams array of <mixed> params index by <string> name
	 * @param ModelReflection|null $oMetaModel The MetaModel to use when checking the consistency of the OQL
     *
	 * @return DBObjectSearch|DBUnionSearch
     *
	 * @throws OQLException
	 */
	public static function FromOQL($sQuery, $aParams = null, ModelReflection $oMetaModel=null)
	{
		if (empty($sQuery))
		{
			return null;
		}

		// Query caching
		$sQueryId = md5($sQuery);
		$bOQLCacheEnabled = true;
		if ($bOQLCacheEnabled)
		{
			if (array_key_exists($sQueryId, self::$m_aOQLQueries))
			{
				// hit!
				$oResultFilter = self::$m_aOQLQueries[$sQueryId]->DeepClone();
			}
			elseif (self::$m_bUseAPCCache)
			{
				// Note: For versions of APC older than 3.0.17, fetch() accepts only one parameter
				//
				$sAPCCacheId = 'itop-'.MetaModel::GetEnvironmentId().'-dbsearch-cache-'.$sQueryId;
				$oKPI = new ExecutionKPI();
				$result = apc_fetch($sAPCCacheId);
				$oKPI->ComputeStats('Search APC (fetch)', $sQuery);
	
				if (is_object($result))
				{
					$oResultFilter = $result;
					self::$m_aOQLQueries[$sQueryId] = $oResultFilter->DeepClone();
				}
			}
		}

		/** @var DBObjectSearch | null $oResultFilter */
		if (!isset($oResultFilter))
		{
			$oKPI = new ExecutionKPI();

			$oOql = new OqlInterpreter($sQuery);
			$oOqlQuery = $oOql->ParseQuery();
	
			if ($oMetaModel === null)
			{
				$oMetaModel = new ModelReflectionRuntime();
			}
			$oOqlQuery->Check($oMetaModel, $sQuery); // Exceptions thrown in case of issue
	
			$oResultFilter = $oOqlQuery->ToDBSearch($sQuery);

			$oKPI->ComputeStats('Parse OQL', $sQuery);
	
			if ($bOQLCacheEnabled)
			{
				self::$m_aOQLQueries[$sQueryId] = $oResultFilter->DeepClone();

				if (self::$m_bUseAPCCache)
				{
					$oKPI = new ExecutionKPI();
					apc_store($sAPCCacheId, $oResultFilter, self::$m_iQueryCacheTTL);
					$oKPI->ComputeStats('Search APC (store)', $sQueryId);
				}
			}
		}

		if (!is_null($aParams))
		{
			$oResultFilter->SetInternalParams($aParams);
		}

		// Set the default fields
		$oResultFilter->Init();

		return $oResultFilter;
	}

	/**
     * Fetch the result has an array structure.
     *
	 * Alternative to object mapping: the data are transfered directly into an array
	 * This is 10 times faster than creating a set of objects, and makes sense when optimization is required
     * But this speed comes at the cost of not obtaining the easy to manipulates DBObject instances but simple array structure.
     *
     * @internal
	 *
	 * @param array $aColumns The columns you'd like to fetch.
	 * @param array $aOrderBy Array of '[<classalias>.]attcode' => bAscending
	 * @param array $aArgs
	 *
	 * @return array|void
     *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public function ToDataArray($aColumns = array(), $aOrderBy = array(), $aArgs = array())
	{
		$sSQL = $this->MakeSelectQuery($aOrderBy, $aArgs);
		$resQuery = CMDBSource::Query($sSQL);
		if (!$resQuery)
		{
			return;
		}

        if (count($aColumns) == 0)
        {
            $aColumns = array_keys(MetaModel::ListAttributeDefs($this->GetClass()));
            // Add the standard id (as first column)
            array_unshift($aColumns, 'id');
		}

		$aQueryCols = CMDBSource::GetColumns($resQuery, $sSQL);

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

    /**
     * Selects a column ($sAttCode) from the specified class ($sClassAlias - default main class) of the DBsearch object and gives the result as an array
     * @param string $sAttCode
     * @param string|null $sClassAlias
     *
     * @return array
     * @throws ConfigException
     * @throws CoreException
     * @throws MissingQueryArgument
     * @throws MySQLException
     * @throws MySQLHasGoneAwayException
     */
    public function SelectAttributeToArray(string $sAttCode, ?string $sClassAlias = null):array
    {
       if(is_null($sClassAlias)) {
           $sClassAlias = $this->GetClassAlias();
       }

       $sClass = $this->GetClass();
       if($sAttCode === 'id'){
           $aAttToLoad[$sClassAlias]=[];
       } else {
           $aAttToLoad[$sClassAlias][$sAttCode] = MetaModel::GetAttributeDef($sClass, $sAttCode);
       }

        $sSQL = $this->MakeSelectQuery([], [], $aAttToLoad);
        $resQuery = CMDBSource::Query($sSQL);
        if (!$resQuery)
        {
            return [];
        }

        $sColName = $sClassAlias.$sAttCode;

        $aRes = [];
        while ($aRow = CMDBSource::FetchArray($resQuery))
        {
            $aMappedRow = array();
            if($sAttCode === 'id') {
                $aMappedRow[$sAttCode] = $aRow[$sColName];
            } else {
                $aMappedRow[$sAttCode] = $aAttToLoad[$sClassAlias][$sAttCode]->FromSQLToValue($aRow, $sColName);
            }
            $aRes[] = $aMappedRow;
        }
        CMDBSource::FreeResult($resQuery);
        return $aRes;
    }

	////////////////////////////////////////////////////////////////////////////
	//
	// Construction of the SQL queries
	//
	////////////////////////////////////////////////////////////////////////////
	protected static $m_aQueryStructCache = array();


    /**
     * Generate a Group By SQL query from the current search
     *
     * @internal
     *
	 * @param array $aArgs
	 * @param array $aGroupByExpr array('alias' => Expression)
	 * @param bool $bExcludeNullValues
	 * @param array $aSelectExpr array('alias' => Expression) Additional expressions added to the request
	 * @param array $aOrderBy array('alias' => bool) true = ASC false = DESC
	 * @param int $iLimitCount
	 * @param int $iLimitStart
     *
	 * @return string SQL query generated
     *
	 * @throws Exception
	 */
	public function MakeGroupByQuery($aArgs, $aGroupByExpr, $bExcludeNullValues = false, $aSelectExpr = array(), $aOrderBy = array(), $iLimitCount = 0, $iLimitStart = 0)
	{
		// Sanity check
		foreach($aGroupByExpr as $sAlias => $oExpr)
		{
			if (!($oExpr instanceof Expression))
			{
				throw new CoreException("Wrong parameter for 'Group By' for [$sAlias] (an array('alias' => Expression) is awaited)");
			}
		}
		foreach($aSelectExpr as $sAlias => $oExpr)
		{
			if (array_key_exists($sAlias, $aGroupByExpr))
			{
				throw new CoreException("Alias collision between 'Group By' and 'Select Expressions' [$sAlias]");
			}
			if (!($oExpr instanceof Expression))
			{
				throw new CoreException("Wrong parameter for 'Select Expressions' for [$sAlias] (an array('alias' => Expression) is awaited)");
			}
		}
		foreach($aOrderBy as $sAlias => $bAscending)
		{
			if (!array_key_exists($sAlias, $aGroupByExpr) && !array_key_exists($sAlias, $aSelectExpr) && ($sAlias != '_itop_count_'))
			{
				$aAllowedAliases = array_keys($aSelectExpr);
				$aAllowedAliases = array_merge($aAllowedAliases,  array_keys($aGroupByExpr));
				$aAllowedAliases[] = '_itop_count_';
				throw new CoreException("Wrong alias [$sAlias] for 'Order By'. Allowed values are: ", null, implode(", ", $aAllowedAliases));
			}
			if (!is_bool($bAscending))
			{
				throw new CoreException("Wrong direction in ORDER BY spec, found '$bAscending' and expecting a boolean value for '$sAlias''");
			}
		}

		if ($bExcludeNullValues)
		{
			// Null values are not handled (though external keys set to 0 are allowed)
			$oQueryFilter = $this->DeepClone();
			foreach ($aGroupByExpr as $oGroupByExp)
			{
				$oNull = new FunctionExpression('ISNULL', array($oGroupByExp));
				$oNotNull = new BinaryExpression($oNull, '!=', new TrueExpression());
				$oQueryFilter->AddConditionExpression($oNotNull);
			}
		}
		else
		{
			$oQueryFilter = $this;
		}

		$aAttToLoad = array();
		$oSQLQuery = $oQueryFilter->GetSQLQuery(array(), $aArgs, $aAttToLoad, null, 0, 0, false, $aGroupByExpr, $aSelectExpr);

		$aScalarArgs = MetaModel::PrepareQueryArguments($aArgs, $this->GetInternalParams(), $this->GetExpectedArguments());
		try
		{
			$bBeautifulSQL = self::$m_bTraceQueries || self::$m_bDebugQuery || self::$m_bIndentQueries;
			$sRes = $oSQLQuery->RenderGroupBy($aScalarArgs, $bBeautifulSQL, $aOrderBy, $iLimitCount, $iLimitStart);
		}
		// Catch CoreException to add info before throwing again
		// Other exceptions will be thrown directly
		catch (CoreException $e)
		{
			// Add some information...
			$e->addInfo('OQL', $this->ToOQL());
			throw $e;
		}
		$this->AddQueryTraceGroupBy($aArgs, $aGroupByExpr, $bExcludeNullValues, $aSelectExpr, $aOrderBy, $iLimitCount, $iLimitStart, $sRes);
		return $sRes;
	}

	/**
	 * Generate a SQL query from the current search
	 *
	 * @param array $aOrderBy Array of '[<classalias>.]attcode' => bAscending
	 * @param array $aArgs
	 * @param null $aAttToLoad
	 * @param null $aExtendedDataSpec
	 * @param int $iLimitCount
	 * @param int $iLimitStart
	 * @param bool $bGetCount
	 * @param bool $bBeautifulSQL
	 *
	 * @return string
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @internal
	 *
	 */
	public function MakeSelectQuery($aOrderBy = array(), $aArgs = array(), $aAttToLoad = null, $aExtendedDataSpec = null, $iLimitCount = 0, $iLimitStart = 0, $bGetCount = false, $bBeautifulSQL = true)
	{
		// Check the order by specification, and prefix with the class alias
		// and make sure that the ordering columns are going to be selected
		//
		$sClass = $this->GetClass();
		$sClassAlias = $this->GetClassAlias();
		$aOrderSpec = array();
		foreach ($aOrderBy as $sFieldAlias => $bAscending)
		{
			if (!is_bool($bAscending))
			{
				throw new CoreException("Wrong direction in ORDER BY spec, found '$bAscending' and expecting a boolean value");
			}

			$iDotPos = strpos($sFieldAlias, '.');
			if ($iDotPos === false)
			{
				$sAttClass = $sClass;
				$sAttClassAlias = $sClassAlias;
				$sAttCode = $sFieldAlias;
			}
			else
			{
				$sAttClassAlias = substr($sFieldAlias, 0, $iDotPos);
				$sAttClass = $this->GetClassName($sAttClassAlias);
				$sAttCode = substr($sFieldAlias, $iDotPos + 1);
			}

			if ($sAttCode != 'id')
			{
				MyHelpers::CheckValueInArray('field name in ORDER BY spec', $sAttCode, MetaModel::GetAttributesList($sAttClass));

				$oAttDef = MetaModel::GetAttributeDef($sAttClass, $sAttCode);
				foreach($oAttDef->GetOrderBySQLExpressions($sAttClassAlias) as $sSQLExpression)
				{
					$aOrderSpec[$sSQLExpression] = $bAscending;
				}
			}
			else
			{
				$aOrderSpec['`'.$sAttClassAlias.$sAttCode.'`'] = $bAscending;
			}

			// Make sure that the columns used for sorting are present in the loaded columns
			if (!is_null($aAttToLoad) && !isset($aAttToLoad[$sAttClassAlias][$sAttCode]))
			{
				$aAttToLoad[$sAttClassAlias][$sAttCode] = MetaModel::GetAttributeDef($sAttClass, $sAttCode);
			}			
		}

		$oSQLQuery = $this->GetSQLQuery($aOrderBy, $aArgs, $aAttToLoad, $aExtendedDataSpec, $iLimitCount, $iLimitStart, $bGetCount);

		if ($this->m_bNoContextParameters)
		{
			// Only internal parameters
			$aScalarArgs = $this->GetInternalParams();
		}
		else
		{
			// The complete list of arguments will include magic arguments (e.g. current_user->attcode)
			$aScalarArgs = MetaModel::PrepareQueryArguments($aArgs, $this->GetInternalParams(), $this->GetExpectedArguments());
		}
		try
		{
			$sRes = $oSQLQuery->RenderSelect($aOrderSpec, $aScalarArgs, $iLimitCount, $iLimitStart, $bGetCount, $bBeautifulSQL);
			if ($sClassAlias == '_itop_')
			{
				IssueLog::Info('SQL Query (_itop_): '.$sRes);
			}
		}
		catch (MissingQueryArgument $e)
		{
			// Add some information...
			$e->addInfo('OQL', $this->ToOQL());
			throw $e;
		}
		$this->AddQueryTraceSelect($oSQLQuery->GetSourceOQL(), $aOrderBy, $aScalarArgs, $aAttToLoad, $aExtendedDataSpec, $iLimitCount, $iLimitStart, $bGetCount, $sRes);
		return $sRes;
	}

	/**
	 * @param bool $bMustHaveOneResultMax if true will throw a CoreOqlMultipleResultsFound if multiple results
	 * @param array $aOrderBy
	 * @param array $aSearchParams
	 *
	 * @return null|\DBObject query result
	 * @throws \CoreOqlMultipleResultsForbiddenException if multiple results found and parameter enforce the check
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 *
	 * @since 2.7.0 N°2555
	 */
	public function GetFirstResult($bMustHaveOneResultMax = true, $aOrderBy = array(), $aSearchParams = array())
	{
		$oSet = new DBObjectSet($this, array(), $aSearchParams, null, 2);
		$oFirstResult = $oSet->Fetch();
		if ($oFirstResult === null) // useless but here for readability ;)
		{
			return null;
		}

		if ($bMustHaveOneResultMax)
		{
			$oSecondResult = $oSet->Fetch();
			if ($oSecondResult !== null)
			{
				throw new CoreOqlMultipleResultsForbiddenException(
					'Search returned multiple results, this is forbidden. Query was: '.$this->ToOQL());
			}
		}

		return $oFirstResult;
	}

    /**
     * @internal
     * @return mixed
     */
	protected abstract function IsDataFiltered();

    /**
     * @internal
     * @return mixed
     */
	protected abstract function SetDataFiltered();

	/**
	 * @param      $aOrderBy
	 * @param      $aArgs
	 * @param      $aAttToLoad
	 * @param      $aExtendedDataSpec
	 * @param      $iLimitCount
	 * @param      $iLimitStart
	 * @param      $bGetCount
	 * @param null $aGroupByExpr
	 * @param null $aSelectExpr
	 *
	 * @return SQLObjectQuery
	 * @throws \CoreException
	 * @internal
	 *
	 */
	protected function GetSQLQuery($aOrderBy, $aArgs, $aAttToLoad, $aExtendedDataSpec, $iLimitCount, $iLimitStart, $bGetCount, $aGroupByExpr = null, $aSelectExpr = null)
	{
		$oSearch = $this;
		if (!$this->IsAllDataAllowed() && !$this->IsDataFiltered())
		{
			foreach ($this->GetSelectedClasses() as $sClassAlias => $sClass)
			{
				$oVisibleObjects = UserRights::GetSelectFilter($sClass, $this->GetModifierProperties('UserRightsGetSelectFilter'));
				if ($oVisibleObjects === false)
				{
					// Make sure this is a valid search object, saying NO for all
					$oVisibleObjects = DBObjectSearch::FromEmptySet($sClass);
				}
				if (is_object($oVisibleObjects))
				{
					$oVisibleObjects->AllowAllData();
					$oSearch = $oSearch->Filter($sClassAlias, $oVisibleObjects);
					$oSearch->SetDataFiltered();
				}
			}
		}

		if (is_array($aGroupByExpr))
		{
			foreach($aGroupByExpr as $sAlias => $oGroupByExp)
			{
				/** @var \Expression $oGroupByExp */

				$aFields = $oGroupByExp->ListRequiredFields();
				foreach($aFields as $sFieldAlias)
				{
					$aMatches = array();
					if (preg_match('/^([^.]+)\\.([^.]+)$/', $sFieldAlias, $aMatches))
					{
						$sFieldClass = $this->GetClassName($aMatches[1]);
						$oAttDef = MetaModel::GetAttributeDef($sFieldClass, $aMatches[2]);
						if ( $oAttDef instanceof iAttributeNoGroupBy)
						{
							throw new Exception("Grouping on '$sFieldClass' fields is not supported.");
						}
					}
				}
			}
		}

		$oSQLQuery = $oSearch->GetSQLQueryStructure($aAttToLoad, $bGetCount, $aGroupByExpr, null, $aSelectExpr);
		$oSQLQuery->SetSourceOQL($oSearch->ToOQL());

		// Join to an additional table, if required...
		//
		if ($aExtendedDataSpec != null)
		{
			$sTableAlias = '_extended_data_';
			$aExtendedFields = array();
			foreach($aExtendedDataSpec['fields'] as $sColumn)
			{
				$sColRef = $this->GetClassAlias().'_extdata_'.$sColumn;
				$aExtendedFields[$sColRef] = new FieldExpressionResolved($sColumn, $sTableAlias);
			}
			$oSQLQueryExt = new SQLObjectQuery($aExtendedDataSpec['table'], $sTableAlias, $aExtendedFields);
			$oSQLQuery->AddInnerJoin($oSQLQueryExt, 'id', $aExtendedDataSpec['join_key'] /*, $sTableAlias*/);
		}
		
		return $oSQLQuery;
	}

    /**
     * @internal
     *
     * @param      $aAttToLoad
     * @param      $bGetCount
     * @param null $aGroupByExpr
     * @param null $aSelectedClasses
     * @param null $aSelectExpr
     *
     * @return mixed
     */
	public abstract function GetSQLQueryStructure(
		$aAttToLoad, $bGetCount, $aGroupByExpr = null, $aSelectedClasses = null, $aSelectExpr = null
	);

    /**
     * Shortcut to add efficient IN condition
     *
     * @internal
     *
     * @param      $sFilterCode
     * @param      $aValues
     * @param bool $bPositiveMatch if true a `IN` is performed, if false, a `NOT IN` is performed
     *
     * @return mixed
     */
	public abstract function AddConditionForInOperatorUsingParam($sFilterCode, $aValues, $bPositiveMatch = true);

	/**
     * @internal
	 * @return string a unique param name
	 */
	protected function GenerateUniqueParamName() {
		return str_replace('.', '', 'param_'.microtime(true).rand(0,100));
	}

	////////////////////////////////////////////////////////////////////////////
	//
	// Cache/Trace/Log queries
	//
	////////////////////////////////////////////////////////////////////////////
	protected static $m_bDebugQuery = false;
	protected static $m_aQueriesLog = array();
	protected static $m_bQueryCacheEnabled = false;
	protected static $m_bUseAPCCache = false;
	protected static $m_iQueryCacheTTL = 3600;
	protected static $m_bTraceQueries = false;
	protected static $m_bIndentQueries = false;
	protected static $m_bOptimizeQueries = false;

    /**
     * @internal
     */
	public static function StartDebugQuery()
	{
		$aBacktrace = debug_backtrace();
		self::$m_bDebugQuery = true;
	}

    /**
     * @internal
     */
	public static function StopDebugQuery()
	{
		self::$m_bDebugQuery = false;
	}

    /**
     * @internal
     *
     * @param bool $bEnabled
     * @param bool $bUseAPC
     * @param int  $iTimeToLive
     */
	public static function EnableQueryCache($bEnabled, $bUseAPC, $iTimeToLive = 3600)
	{
		self::$m_bQueryCacheEnabled = $bEnabled;
		self::$m_bUseAPCCache = $bUseAPC;
		self::$m_iQueryCacheTTL = $iTimeToLive;
	}

    /**
     * @internal
     * @param $bEnabled
     */
	public static function EnableQueryTrace($bEnabled)
	{
		self::$m_bTraceQueries = $bEnabled;
	}

    /**
     * @internal
     * @param $bEnabled
     */
	public static function EnableQueryIndentation($bEnabled)
	{
		self::$m_bIndentQueries = $bEnabled;
	}

    /**
     * @internal
     * @param $bEnabled
     */
	public static function EnableOptimizeQuery($bEnabled)
	{
		self::$m_bOptimizeQueries = $bEnabled;
	}

	/**
	 * @param $sOql
	 * @param $aOrderBy
	 * @param $aArgs
	 * @param $aAttToLoad
	 * @param $aExtendedDataSpec
	 * @param $iLimitCount
	 * @param $iLimitStart
	 * @param $bGetCount
	 * @param $sSql
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @internal
	 *
	 */
	protected function AddQueryTraceSelect($sOql, $aOrderBy, $aArgs, $aAttToLoad, $aExtendedDataSpec, $iLimitCount, $iLimitStart, $bGetCount, $sSql)
	{
		if (self::$m_bTraceQueries)
		{
			$aQueryData = array(
				'type' => 'select',
				'order_by' => $aOrderBy,
				'att_to_load' => $aAttToLoad,
				'limit_count' => $iLimitCount,
				'limit_start' => $iLimitStart,
				'is_count' => $bGetCount
			);

			DBSearch::EnableQueryTrace(false);
			$aQueryData['oql'] = $this->ToOQL(true, $aArgs);
			DBSearch::EnableQueryTrace(true);

			if (!empty($aAttToLoad))
			{
				$aAttToLoadNames = array();
				foreach ($aAttToLoad as $sClass => $aAttributes)
				{
					$aAttToLoadNames[$sClass] = array();
					foreach ($aAttributes as $sAttCode => $oAttDef)
					{
						$aAttToLoadNames[$sClass][] = $sAttCode;
					}
				}
			}
			else
			{
				$aAttToLoadNames = null;
			}
			$aQueryData['att_to_load'] = $aAttToLoadNames;

			$hLogFile = @fopen(APPROOT.'log/oql_records.txt', 'a');
			if ($hLogFile !== false)
			{
				flock($hLogFile,LOCK_EX);
				fwrite($hLogFile,serialize($aQueryData)."\n");
				fflush($hLogFile);
				flock($hLogFile,LOCK_UN);
				fclose($hLogFile);
			}
		}
	}

	/**
	 * @param $aArgs
	 * @param $aGroupByExpr
	 * @param $bExcludeNullValues
	 * @param $aSelectExpr
	 * @param $aOrderBy
	 * @param $iLimitCount
	 * @param $iLimitStart
	 * @param $sSql
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @internal
	 *
	 */
	protected function AddQueryTraceGroupBy($aArgs, $aGroupByExpr, $bExcludeNullValues, $aSelectExpr, $aOrderBy, $iLimitCount, $iLimitStart, $sSql)
	{
		if (self::$m_bTraceQueries)
		{
			$aQueryData = array(
				'type' => 'group_by',
				'order_by' => $aOrderBy,
				'group_by_expr' => $aGroupByExpr,
				'exclude_null_values' => $bExcludeNullValues,
				'select_expr' => $aSelectExpr,
				'limit_count' => $iLimitCount,
				'limit_start' => $iLimitStart,
			);

			$aQueryData['oql'] = $this->ToOQL(true, $aArgs);
			$aQueryData['group_by_expr'] = Expression::ConvertArrayToOQL($aQueryData['group_by_expr'], $aArgs);
			$aQueryData['select_expr'] = Expression::ConvertArrayToOQL($aQueryData['select_expr'], $aArgs);

			$hLogFile = @fopen(APPROOT.'log/oql_group_by_records.txt', 'a');
			if ($hLogFile !== false)
			{
				flock($hLogFile,LOCK_EX);
				fwrite($hLogFile,serialize($aQueryData)."\n");
				fflush($hLogFile);
				flock($hLogFile,LOCK_UN);
				fclose($hLogFile);
			}
		}
	}

    /**
     * @internal
     *
     * @param $aQueryData
     * @param $sOql
     * @param $sSql
     *
     * @throws MySQLException
     */
	protected static function AddQueryTrace($aQueryData, $sOql, $sSql)
	{
		if (self::$m_bTraceQueries)
		{
			$sQueryId = md5(serialize($aQueryData));
			$sMySQLQueryId = md5($sSql);
			if(!isset(self::$m_aQueriesLog[$sQueryId]))
			{
				self::$m_aQueriesLog[$sQueryId]['data'] = serialize($aQueryData);
				self::$m_aQueriesLog[$sQueryId]['oql'] = $sOql;
				self::$m_aQueriesLog[$sQueryId]['hits'] = 1;
			}
			else
			{
				self::$m_aQueriesLog[$sQueryId]['hits']++;
			}
			if(!isset(self::$m_aQueriesLog[$sQueryId]['queries'][$sMySQLQueryId]))
			{
				self::$m_aQueriesLog[$sQueryId]['queries'][$sMySQLQueryId]['sql'] = $sSql;
				self::$m_aQueriesLog[$sQueryId]['queries'][$sMySQLQueryId]['count'] = 1;
				$iTableCount = count(CMDBSource::ExplainQuery($sSql));
				self::$m_aQueriesLog[$sQueryId]['queries'][$sMySQLQueryId]['table_count'] = $iTableCount;
			}
			else
			{
				self::$m_aQueriesLog[$sQueryId]['queries'][$sMySQLQueryId]['count']++;
			}
		}
	}

    /**
     * @internal
     */
	public static function RecordQueryTrace()
	{
		if (!self::$m_bTraceQueries)
		{
			return;
		}

		$iOqlCount = count(self::$m_aQueriesLog);
		$iSqlCount = 0;
		foreach (self::$m_aQueriesLog as $sQueryId => $aOqlData)
		{
			$iSqlCount += $aOqlData['hits'];
		}
		$sHtml = "<h2>Stats on SELECT queries: OQL=$iOqlCount, SQL=$iSqlCount</h2>\n";
		foreach (self::$m_aQueriesLog as $sQueryId => $aOqlData)
		{
			$sOql = $aOqlData['oql'];
			$sHits = $aOqlData['hits'];

			$sHtml .= "<p><b>$sHits</b> hits for OQL query: $sOql</p>\n";
			$sHtml .= "<ul id=\"ClassesRelationships\" class=\"treeview\">\n";
			foreach($aOqlData['queries'] as $aSqlData)
			{
				$sQuery = $aSqlData['sql'];
				$sSqlHits = $aSqlData['count'];
				$iTableCount = $aSqlData['table_count'];
				$sHtml .= "<li><b>$sSqlHits</b> hits for SQL ($iTableCount tables): <pre style=\"font-size:60%\">$sQuery</pre></li>\n";
			}
			$sHtml .= "</ul>\n";
		}

		$sLogFile = 'queries.latest';
		file_put_contents(APPROOT.'data/'.$sLogFile.'.html', $sHtml);

		$sLog = "<?php\n\$aQueriesLog = ".var_export(self::$m_aQueriesLog, true).";";
		file_put_contents(APPROOT.'data/'.$sLogFile.'.log', $sLog);

		// Cumulate the queries
		$sAllQueries = APPROOT.'data/queries.log';
		if (file_exists($sAllQueries))
		{
			// Merge the new queries into the existing log
			include($sAllQueries);
			$aQueriesLog = array();
			foreach (self::$m_aQueriesLog as $sQueryId => $aOqlData)
			{
				if (!array_key_exists($sQueryId, $aQueriesLog))
				{
					$aQueriesLog[$sQueryId] = $aOqlData;
				}
			}
		}
		else
		{
			$aQueriesLog = self::$m_aQueriesLog;
		}
		$sLog = "<?php\n\$aQueriesLog = ".var_export($aQueriesLog, true).";";
		file_put_contents($sAllQueries, $sLog);
	}

    /**
     * @internal
     * @param $value
     */
	protected static function DbgTrace($value)
	{
		if (!self::$m_bDebugQuery)
		{
			return;
		}
		$aBacktrace = debug_backtrace();
		$iCallStackPos = count($aBacktrace) - self::$m_bDebugQuery;
		$sIndent = ""; 
		for ($i = 0 ; $i < $iCallStackPos ; $i++)
		{
			$sIndent .= " .-=^=-. ";
		}
		$aCallers = array();
		foreach($aBacktrace as $aStackInfo)
		{
			$aCallers[] = $aStackInfo["function"];
		}
		$sCallers = "Callstack: ".implode(', ', $aCallers);
		$sFunction = "<b title=\"$sCallers\">".$aBacktrace[1]["function"]."</b>";

		if (is_object($value))
		{
			echo "$sIndent$sFunction:\n<pre>\n";
			print_r($value);
			echo "</pre>\n";
		}
		else
		{
			echo "$sIndent$sFunction: $value<br/>\n";
		}
	}

	/**
	 * Updates archive_flag and archive_date fields in the whole class hierarchy
	 *
	 * @see \DBObject::DBWriteArchiveFlag()
	 *
	 * @param boolean $bArchive
	 *
	 * @throws Exception
	 * @todo implement the change tracking
	 */
	public function DBBulkWriteArchiveFlag($bArchive)
	{
		$sClass = $this->GetClass();
		if (!MetaModel::IsArchivable($sClass))
		{
			throw new Exception($sClass.' is not an archivable class');
		}

		$iFlag = $bArchive ? 1 : 0;

		$oSet = new DBObjectSet($this);
		if (MetaModel::IsStandaloneClass($sClass))
		{
			$oSet->OptimizeColumnLoad(array($this->GetClassAlias() => array()));
			$aIds = array($sClass => $oSet->GetColumnAsArray('id'));
		}
		else
		{
			$oSet->OptimizeColumnLoad(array($this->GetClassAlias() => array('finalclass')));
			$aTemp = $oSet->GetColumnAsArray('finalclass');
			$aIds = array();
			foreach ($aTemp as $iObjectId => $sObjectClass)
			{
				$aIds[$sObjectClass][$iObjectId] = $iObjectId;
			}
		}
		foreach ($aIds as $sFinalClass => $aObjectIds)
		{
			$sIds = implode(', ', $aObjectIds);

			$sArchiveRoot = MetaModel::GetAttributeOrigin($sFinalClass, 'archive_flag');
			$sRootTable = MetaModel::DBGetTable($sArchiveRoot);
			$sRootKey = MetaModel::DBGetKey($sArchiveRoot);
			$aJoins = array("`$sRootTable`");
			$aUpdates = array();
			foreach (MetaModel::EnumParentClasses($sFinalClass, ENUM_PARENT_CLASSES_ALL) as $sParentClass)
			{
				if (!MetaModel::IsValidAttCode($sParentClass, 'archive_flag'))
				{
					continue;
				}

				$sTable = MetaModel::DBGetTable($sParentClass);
				$aUpdates[] = "`$sTable`.`archive_flag` = $iFlag";
				if ($sParentClass == $sArchiveRoot)
				{
					if ($bArchive)
					{
						// Set the date (do not change it)
						$sDate = '"'.date(AttributeDate::GetSQLFormat()).'"';
						$aUpdates[] = "`$sTable`.`archive_date` = coalesce(`$sTable`.`archive_date`, $sDate)";
					}
					else
					{
						// Reset the date
						$aUpdates[] = "`$sTable`.`archive_date` = null";
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
			$sUpdateQuery = "UPDATE $sJoins SET $sValues WHERE `$sRootTable`.`$sRootKey` IN ($sIds)";
			CMDBSource::Query($sUpdateQuery);
		}
	}

    /**
     * @internal
     */
	public function UpdateContextFromUser()
	{
		$this->SetShowObsoleteData(utils::ShowObsoleteData());
	}

	/**
	 * To ease the debug of filters
	 * @internal
	 *
	 * @return string
	 *
	 */
	public function __toString()
	{
		return $this->ToOQL(true);
	}

	/**
	 * @return array{\VariableExpression}
	 *
	 * @deprecated use DBSearch::GetExpectedArguments() instead
	 */
	public function ListParameters(): array
	{
		return $this->GetExpectedArguments();
	}

	/**
	 * Get parameters from the condition expression(s)
	 *
	 * @return array{\VariableExpression}
	 */
	abstract function GetExpectedArguments(): array;
}
