<?php
// Copyright (C) 2015-2017 Combodo SARL
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


require_once('dbobjectsearch.class.php');
require_once('dbunionsearch.class.php');

/**
 * An object search
 * 
 * Note: in the ancient times of iTop, a search was named after DBObjectSearch.
 *  When the UNION has been introduced, it has been decided to:
 *  - declare a hierarchy of search classes, with two leafs :
 *    - one class to cope with a single query (A JOIN B... WHERE...)
 *    - and the other to cope with several queries (query1 UNION query2)
 *  - in order to preserve forward/backward compatibility of the existing modules 
 *    - keep the name of DBObjectSearch even if it a little bit confusing
 *    - do not provide a type-hint for function parameters defined in the modules
 *    - leave the statements DBObjectSearch::FromOQL in the modules, though DBSearch is more relevant 
 *
 * @copyright   Copyright (C) 2015-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
 
abstract class DBSearch
{
	const JOIN_POINTING_TO = 0;
	const JOIN_REFERENCED_BY = 1;

	protected $m_bNoContextParameters = false;
	protected $m_aModifierProperties = array();
	protected $m_bArchiveMode = false;
	protected $m_bShowObsoleteData = true;

	public function __construct()
	{
		$this->Init();
	}

	protected function Init()
	{
		// Set the obsolete and archive modes to the default ones
		$this->m_bArchiveMode = utils::IsArchiveMode();
		$this->m_bShowObsoleteData = true;
	}

	/**
	 * Perform a deep clone (as opposed to "clone" which does copy a reference to the underlying objects)
	 *
	 * @return \DBSearch
	 **/	 	
	public function DeepClone()
	{
		return unserialize(serialize($this)); // Beware this serializes/unserializes the search and its parameters as well
	}

	abstract public function AllowAllData();
	abstract public function IsAllDataAllowed();

	public function SetArchiveMode($bEnable)
	{
		$this->m_bArchiveMode = $bEnable;
	}
	public function GetArchiveMode()
	{
		return $this->m_bArchiveMode;
	}

	public function SetShowObsoleteData($bShow)
	{
		$this->m_bShowObsoleteData = $bShow;
	}
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

	public function NoContextParameters() {$this->m_bNoContextParameters = true;}
	public function HasContextParameters() {return $this->m_bNoContextParameters;}

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

	abstract public function GetClassName($sAlias);
	abstract public function GetClass();
	abstract public function GetClassAlias();

	/**
	 * Change the class (only subclasses are supported as of now, because the conditions must fit the new class)
	 * Defaults to the first selected class (most of the time it is also the first joined class	 
	 */	 	
	abstract public function ChangeClass($sNewClass, $sAlias = null);
	abstract public function GetSelectedClasses();

	/**
	 * @param array $aSelectedClasses array of aliases
	 * @throws CoreException
	 */
	abstract public function SetSelectedClasses($aSelectedClasses);

	/**
	 * Change any alias of the query tree
	 *
	 * @param $sOldName
	 * @param $sNewName
	 * @return bool True if the alias has been found and changed
	 */
	abstract public function RenameAlias($sOldName, $sNewName);

	abstract public function IsAny();

	public function Describe(){return 'deprecated - use ToOQL() instead';}
	public function DescribeConditionPointTo($sExtKeyAttCode, $aPointingTo){return 'deprecated - use ToOQL() instead';}
	public function DescribeConditionRefBy($sForeignClass, $sForeignExtKeyAttCode){return 'deprecated - use ToOQL() instead';}
	public function DescribeConditionRelTo($aRelInfo){return 'deprecated - use ToOQL() instead';}
	public function DescribeConditions(){return 'deprecated - use ToOQL() instead';}
	public function __DescribeHTML(){return 'deprecated - use ToOQL() instead';}

	abstract public function ResetCondition();
	abstract public function MergeConditionExpression($oExpression);
	abstract public function AddConditionExpression($oExpression);
  	abstract public function AddNameCondition($sName);
	abstract public function AddCondition($sFilterCode, $value, $sOpCode = null);
	/**
	 * Specify a condition on external keys or link sets
	 * @param sAttSpec Can be either an attribute code or extkey->[sAttSpec] or linkset->[sAttSpec] and so on, recursively
	 *                 Example: infra_list->ci_id->location_id->country	 
	 * @param value The value to match (can be an array => IN(val1, val2...)
	 * @return void
	 */
	abstract public function AddConditionAdvanced($sAttSpec, $value);
	abstract public function AddCondition_FullText($sFullText);

	/**
	 * @param DBObjectSearch $oFilter
	 * @param $sExtKeyAttCode
	 * @param int $iOperatorCode
	 * @param null $aRealiasingMap array of <old-alias> => <new-alias>, for each alias that has changed
	 * @throws CoreException
	 * @throws CoreWarning
	 */
	abstract public function AddCondition_PointingTo(DBObjectSearch $oFilter, $sExtKeyAttCode, $iOperatorCode = TREE_OPERATOR_EQUALS, &$aRealiasingMap = null);

	/**
	 * @param DBObjectSearch $oFilter
	 * @param $sForeignExtKeyAttCode
	 * @param int $iOperatorCode
	 * @param null $aRealiasingMap array of <old-alias> => <new-alias>, for each alias that has changed
	 */
	abstract public function AddCondition_ReferencedBy(DBObjectSearch $oFilter, $sForeignExtKeyAttCode, $iOperatorCode = TREE_OPERATOR_EQUALS, &$aRealiasingMap = null);

	abstract public function Intersect(DBSearch $oFilter);

	/**
	 * @param DBSearch $oFilter
	 * @param integer $iDirection
	 * @param string $sExtKeyAttCode
	 * @param integer $iOperatorCode
	 * @param array &$RealisasingMap  Map of aliases from the attached query, that could have been renamed by the optimization process
	 * @return DBSearch
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

	abstract public function SetInternalParams($aParams);
	abstract public function GetInternalParams();
	abstract public function GetQueryParams($bExcludeMagicParams = true);
	abstract public function ListConstantFields();
	
	/**
	 * Turn the parameters (:xxx) into scalar values in order to easily
	 * serialize a search
	 */
	abstract public function ApplyParameters($aArgs);

    public function serialize($bDevelopParams = false, $aContextParams = null)
	{
		$sOql = $this->ToOql($bDevelopParams, $aContextParams);
		return rawurlencode(base64_encode(serialize(array($sOql, $this->GetInternalParams(), $this->m_aModifierProperties))));
	}

	/**
	 * @param string $sValue Serialized OQL query
	 *
	 * @return \DBSearch
	 */
	static public function unserialize($sValue)
	{
		$aData = unserialize(base64_decode(rawurldecode($sValue)));
		$sOql = $aData[0];
		$aParams = $aData[1];
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
     * Note : This has not be tested with UNION queries.
     *
     * @param DBSearch $oSearch
     * @param string $sAlias
     * @return DBObjectSearch
     */
    static public function CloneWithAlias(DBSearch $oSearch, $sAlias)
    {
        $oSearchWithAlias = new DBObjectSearch($oSearch->GetClass(), $sAlias);
        $oSearchWithAlias = $oSearchWithAlias->Intersect($oSearch);
        return $oSearchWithAlias;
    }

    abstract public function ToOQL($bDevelopParams = false, $aContextParams = null, $bWithAllowAllFlag = false);

	static protected $m_aOQLQueries = array();

	// Do not filter out depending on user rights
	// In particular when we are currently in the process of evaluating the user rights...
	static public function FromOQL_AllData($sQuery, $aParams = null)
	{
		$oRes = self::FromOQL($sQuery, $aParams);
		$oRes->AllowAllData();
		return $oRes;
	}

	/**
	 * @param string $sQuery
	 * @param array $aParams
	 * @return self
	 * @throws OQLException
	 */
	static public function FromOQL($sQuery, $aParams = null)
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

		if (!isset($oResultFilter))
		{
			$oKPI = new ExecutionKPI();

			$oOql = new OqlInterpreter($sQuery);
			$oOqlQuery = $oOql->ParseQuery();
	
			$oMetaModel = new ModelReflectionRuntime();
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

	// Alternative to object mapping: the data are transfered directly into an array
	// This is 10 times faster than creating a set of objects, and makes sense when optimization is required
	/**
	 * @param hash $aOrderBy Array of '[<classalias>.]attcode' => bAscending
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

	////////////////////////////////////////////////////////////////////////////
	//
	// Construction of the SQL queries
	//
	////////////////////////////////////////////////////////////////////////////
	protected static $m_aQueryStructCache = array();


	/** Generate a Group By SQL request from a search
	 * @param array $aArgs
	 * @param array $aGroupByExpr array('alias' => Expression)
	 * @param bool $bExcludeNullValues
	 * @param array $aSelectExpr array('alias' => Expression) Additional expressions added to the request
	 * @param array $aOrderBy array('alias' => bool) true = ASC false = DESC
	 * @param int $iLimitCount
	 * @param int $iLimitStart
	 * @return string SQL query generated
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

		$aScalarArgs = MetaModel::PrepareQueryArguments($aArgs, $this->GetInternalParams());
		try
		{
			$bBeautifulSQL = self::$m_bTraceQueries || self::$m_bDebugQuery || self::$m_bIndentQueries;
			$sRes = $oSQLQuery->RenderGroupBy($aScalarArgs, $bBeautifulSQL, $aOrderBy, $iLimitCount, $iLimitStart);
		}
		catch (Exception $e)
		{
			// Add some information...
			$e->addInfo('OQL', $this->ToOQL());
			throw $e;
		}
		$this->AddQueryTraceGroupBy($aArgs, $aGroupByExpr, $sRes);
		return $sRes;
	}


	/**
	 * @param array|hash $aOrderBy Array of '[<classalias>.]attcode' => bAscending
	 * @param array $aArgs
	 * @param null $aAttToLoad
	 * @param null $aExtendedDataSpec
	 * @param int $iLimitCount
	 * @param int $iLimitStart
	 * @param bool $bGetCount
	 * @return string
	 * @throws CoreException
	 * @throws Exception
	 * @throws MissingQueryArgument
	 */
	public function MakeSelectQuery($aOrderBy = array(), $aArgs = array(), $aAttToLoad = null, $aExtendedDataSpec = null, $iLimitCount = 0, $iLimitStart = 0, $bGetCount = false)
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
			$aScalarArgs = MetaModel::PrepareQueryArguments($aArgs, $this->GetInternalParams());
		}
		try
		{
			$bBeautifulSQL = self::$m_bTraceQueries || self::$m_bDebugQuery || self::$m_bIndentQueries;
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
		$this->AddQueryTraceSelect($aOrderBy, $aArgs, $aAttToLoad, $aExtendedDataSpec, $iLimitCount, $iLimitStart, $bGetCount, $sRes);
		return $sRes;
	}


	protected function GetSQLQuery($aOrderBy, $aArgs, $aAttToLoad, $aExtendedDataSpec, $iLimitCount, $iLimitStart, $bGetCount, $aGroupByExpr = null, $aSelectExpr = null)
	{
		$oSQLQuery = $this->GetSQLQueryStructure($aAttToLoad, $bGetCount, $aGroupByExpr, null, $aSelectExpr);
		$oSQLQuery->SetSourceOQL($this->ToOQL());

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

	public abstract function GetSQLQueryStructure(
		$aAttToLoad, $bGetCount, $aGroupByExpr = null, $aSelectedClasses = null, $aSelectExpr = null
	);

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

	public static function StartDebugQuery()
	{
		$aBacktrace = debug_backtrace();
		self::$m_bDebugQuery = true;
	}
	public static function StopDebugQuery()
	{
		self::$m_bDebugQuery = false;
	}
	
	public static function EnableQueryCache($bEnabled, $bUseAPC, $iTimeToLive = 3600)
	{
		self::$m_bQueryCacheEnabled = $bEnabled;
		self::$m_bUseAPCCache = $bUseAPC;
		self::$m_iQueryCacheTTL = $iTimeToLive;
	}
	public static function EnableQueryTrace($bEnabled)
	{
		self::$m_bTraceQueries = $bEnabled;
	}
	public static function EnableQueryIndentation($bEnabled)
	{
		self::$m_bIndentQueries = $bEnabled;
	}
	public static function EnableOptimizeQuery($bEnabled)
	{
		self::$m_bOptimizeQueries = $bEnabled;
	}


	protected function AddQueryTraceSelect($aOrderBy, $aArgs, $aAttToLoad, $aExtendedDataSpec, $iLimitCount, $iLimitStart, $bGetCount, $sSql)
	{
		if (self::$m_bTraceQueries)
		{
			$aQueryData = array(
				'type' => 'select',
				'filter' => $this,
				'order_by' => $aOrderBy,
				'args' => $aArgs,
				'att_to_load' => $aAttToLoad,
				'extended_data_spec' => $aExtendedDataSpec,
				'limit_count' => $iLimitCount,
				'limit_start' => $iLimitStart,
				'is_count' => $bGetCount
			);
			$sOql = $this->ToOQL(true, $aArgs);
			self::AddQueryTrace($aQueryData, $sOql, $sSql);
		}
	}
	
	protected function AddQueryTraceGroupBy($aArgs, $aGroupByExpr, $sSql)
	{
		if (self::$m_bTraceQueries)
		{
			$aQueryData = array(
				'type' => 'group_by',
				'filter' => $this,
				'args' => $aArgs,
				'group_by_expr' => $aGroupByExpr
			);
			$sOql = $this->ToOQL(true, $aArgs);
			self::AddQueryTrace($aQueryData, $sOql, $sSql);
		}
	}

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
	 * Experimental!
	 * todo: implement the change tracking
	 *
	 * @param $bArchive
	 * @throws Exception
	 */
	function DBBulkWriteArchiveFlag($bArchive)
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
			$oSet->OptimizeColumnLoad(array($this->GetClassAlias() => array('')));
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

	public function UpdateContextFromUser()
	{
		$this->SetShowObsoleteData(utils::ShowObsoleteData());
	}
}
