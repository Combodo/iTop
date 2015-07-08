<?php
// Copyright (C) 2015 Combodo SARL
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


define('TREE_OPERATOR_EQUALS', 0);
define('TREE_OPERATOR_BELOW', 1);
define('TREE_OPERATOR_BELOW_STRICT', 2);
define('TREE_OPERATOR_NOT_BELOW', 3);
define('TREE_OPERATOR_NOT_BELOW_STRICT', 4);
define('TREE_OPERATOR_ABOVE', 5);
define('TREE_OPERATOR_ABOVE_STRICT', 6);
define('TREE_OPERATOR_NOT_ABOVE', 7);
define('TREE_OPERATOR_NOT_ABOVE_STRICT', 8);

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
 * @copyright   Copyright (C) 2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
 
abstract class DBSearch
{
	protected $m_bDataFiltered = false;
	protected $m_aModifierProperties = array();

	// By default, some information may be hidden to the current user
	// But it may happen that we need to disable that feature
	protected $m_bAllowAllData = false;

	public function __construct()
	{
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

	abstract public function AddCondition_PointingTo(DBObjectSearch $oFilter, $sExtKeyAttCode, $iOperatorCode = TREE_OPERATOR_EQUALS);
	abstract public function AddCondition_ReferencedBy(DBObjectSearch $oFilter, $sForeignExtKeyAttCode);
	abstract public function Intersect(DBSearch $oFilter);

	abstract public function SetInternalParams($aParams);
	abstract public function GetInternalParams();
	abstract public function GetQueryParams();
	abstract public function ListConstantFields();
	
	/**
	 * Turn the parameters (:xxx) into scalar values in order to easily
	 * serialize a search
	 */
	abstract public function ApplyParameters($aArgs);
	
	public function serialize($bDevelopParams = false, $aContextParams = null)
	{
		$sOql = $this->ToOql($bDevelopParams, $aContextParams);
		return base64_encode(serialize(array($sOql, $this->GetInternalParams(), $this->m_aModifierProperties)));
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

	abstract public function ToOQL($bDevelopParams = false, $aContextParams = null);

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
				$oClone->SetInternalParams($aParams);
			}
			return $oClone;
		}

		$oOql = new OqlInterpreter($sQuery);
		$oOqlQuery = $oOql->ParseQuery();

		$oMetaModel = new ModelReflectionRuntime();
		$oOqlQuery->Check($oMetaModel, $sQuery); // Exceptions thrown in case of issue

		$oResultFilter = $oOqlQuery->ToDBSearch($sQuery);

		if (!is_null($aParams))
		{
			$oResultFilter->SetInternalParams($aParams);
		}

		if ($bOQLCacheEnabled)
		{
			self::$m_aOQLQueries[$sQuery] = $oResultFilter->DeepClone();
		}

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

	////////////////////////////////////////////////////////////////////////////
	//
	// Construction of the SQL queries
	//
	////////////////////////////////////////////////////////////////////////////
	protected static $m_aQueryStructCache = array();


	public function MakeGroupByQuery($aArgs, $aGroupByExpr, $bExcludeNullValues = false)
	{
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
		$oSQLQuery = $oQueryFilter->GetSQLQuery(array(), $aArgs, $aAttToLoad, null, 0, 0, false, $aGroupByExpr);

		$aScalarArgs = array_merge(MetaModel::PrepareQueryArguments($aArgs), $this->GetInternalParams());
		try
		{
			$bBeautifulSQL = self::$m_bTraceQueries || self::$m_bDebugQuery || self::$m_bIndentQueries;
			$sRes = $oSQLQuery->RenderGroupBy($aScalarArgs, $bBeautifulSQL);
		}
		catch (MissingQueryArgument $e)
		{
			// Add some information...
			$e->addInfo('OQL', $this->ToOQL());
			throw $e;
		}
		$this->AddQueryTraceGroupBy($aArgs, $aGroupByExpr, $sRes);
		return $sRes;
	}


	/**
	 * @param hash $aOrderBy Array of '[<classalias>.]attcode' => bAscending
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

		$aScalarArgs = array_merge(MetaModel::PrepareQueryArguments($aArgs), $this->GetInternalParams());
		try
		{
			$bBeautifulSQL = self::$m_bTraceQueries || self::$m_bDebugQuery || self::$m_bIndentQueries;
			$sRes = $oSQLQuery->RenderSelect($aOrderSpec, $aScalarArgs, $iLimitCount, $iLimitStart, $bGetCount, $bBeautifulSQL);
			if ($sClassAlias == '_itop_')
			{
				echo $sRes."<br/>\n";
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


	protected function GetSQLQuery($aOrderBy, $aArgs, $aAttToLoad, $aExtendedDataSpec, $iLimitCount, $iLimitStart, $bGetCount, $aGroupByExpr = null)
	{
		// Hide objects that are not visible to the current user
		//
		$oSearch = $this;
		if (!$this->IsAllDataAllowed() && !$this->IsDataFiltered())
		{
			$oVisibleObjects = UserRights::GetSelectFilter($this->GetClass(), $this->GetModifierProperties('UserRightsGetSelectFilter'));
			if ($oVisibleObjects === false)
			{
				// Make sure this is a valid search object, saying NO for all
				$oVisibleObjects = DBObjectSearch::FromEmptySet($this->GetClass());
			}
			if (is_object($oVisibleObjects))
			{
				$oSearch = $this->Intersect($oVisibleObjects);
				$oSearch->SetDataFiltered();
			}
			else
			{
				// should be true at this point, meaning that no additional filtering
				// is required
			}
		}

		// Compute query modifiers properties (can be set in the search itself, by the context, etc.)
		//
		$aModifierProperties = MetaModel::MakeModifierProperties($oSearch);

		// Create a unique cache id
		//
		if (self::$m_bQueryCacheEnabled || self::$m_bTraceQueries)
		{
			// Need to identify the query
			$sOqlQuery = $oSearch->ToOql();

			if (count($aModifierProperties))
			{
				array_multisort($aModifierProperties);
				$sModifierProperties = json_encode($aModifierProperties);
			}
			else
			{
				$sModifierProperties = '';
			}

			$sRawId = $sOqlQuery.$sModifierProperties;
			if (!is_null($aAttToLoad))
			{
				$sRawId .= json_encode($aAttToLoad);
			}
			if (!is_null($aGroupByExpr))
			{
				foreach($aGroupByExpr as $sAlias => $oExpr)
				{
					$sRawId .= 'g:'.$sAlias.'!'.$oExpr->Render();
				}
			}
			$sRawId .= $bGetCount;
			$sOqlId = md5($sRawId);
		}
		else
		{
			$sOqlQuery = "SELECTING... ".$oSearch->GetClass();
			$sOqlId = "query id ? n/a";
		}


		// Query caching
		//
		if (self::$m_bQueryCacheEnabled)
		{
			// Warning: using directly the query string as the key to the hash array can FAIL if the string
			// is long and the differences are only near the end... so it's safer (but not bullet proof?)
			// to use a hash (like md5) of the string as the key !
			//
			// Example of two queries that were found as similar by the hash array:
			// SELECT SLT JOIN lnkSLTToSLA AS L1 ON L1.slt_id=SLT.id JOIN SLA ON L1.sla_id = SLA.id JOIN lnkContractToSLA AS L2 ON L2.sla_id = SLA.id JOIN CustomerContract ON L2.contract_id = CustomerContract.id WHERE SLT.ticket_priority = 1 AND SLA.service_id = 3 AND SLT.metric = 'TTO' AND CustomerContract.customer_id = 2
			// and	
			// SELECT SLT JOIN lnkSLTToSLA AS L1 ON L1.slt_id=SLT.id JOIN SLA ON L1.sla_id = SLA.id JOIN lnkContractToSLA AS L2 ON L2.sla_id = SLA.id JOIN CustomerContract ON L2.contract_id = CustomerContract.id WHERE SLT.ticket_priority = 1 AND SLA.service_id = 3 AND SLT.metric = 'TTR' AND CustomerContract.customer_id = 2
			// the only difference is R instead or O at position 285 (TTR instead of TTO)...
			//
			if (array_key_exists($sOqlId, self::$m_aQueryStructCache))
			{
				// hit!

				$oSQLQuery = unserialize(serialize(self::$m_aQueryStructCache[$sOqlId]));
				// Note: cloning is not enough because the subtree is made of objects
			}
			elseif (self::$m_bUseAPCCache)
			{
				// Note: For versions of APC older than 3.0.17, fetch() accepts only one parameter
				//
				$sOqlAPCCacheId = 'itop-'.MetaModel::GetEnvironmentId().'-query-cache-'.$sOqlId;
				$oKPI = new ExecutionKPI();
				$result = apc_fetch($sOqlAPCCacheId);
				$oKPI->ComputeStats('Query APC (fetch)', $sOqlQuery);

				if (is_object($result))
				{
					$oSQLQuery = $result;
					self::$m_aQueryStructCache[$sOqlId] = $oSQLQuery;
				}
			}
		}

		if (!isset($oSQLQuery))
		{
			$oKPI = new ExecutionKPI();
			$oSQLQuery = $oSearch->MakeSQLQuery($aAttToLoad, $bGetCount, $aModifierProperties, $aGroupByExpr);
			$oSQLQuery->SetSourceOQL($sOqlQuery);
			$oKPI->ComputeStats('MakeSQLQuery', $sOqlQuery);

			if (self::$m_bQueryCacheEnabled)
			{
				if (self::$m_bUseAPCCache)
				{
					$oKPI = new ExecutionKPI();
					apc_store($sOqlAPCCacheId, $oSQLQuery, self::$m_iQueryCacheTTL);
					$oKPI->ComputeStats('Query APC (store)', $sOqlQuery);
				}

				self::$m_aQueryStructCache[$sOqlId] = $oSQLQuery->DeepClone();
			}
		}

		// Join to an additional table, if required...
		//
		if ($aExtendedDataSpec != null)
		{
			$sTableAlias = '_extended_data_';
			$aExtendedFields = array();
			foreach($aExtendedDataSpec['fields'] as $sColumn)
			{
				$sColRef = $oSearch->GetClassAlias().'_extdata_'.$sColumn;
				$aExtendedFields[$sColRef] = new FieldExpressionResolved($sColumn, $sTableAlias);
			}
			$oSQLQueryExt = new SQLObjectQuery($aExtendedDataSpec['table'], $sTableAlias, $aExtendedFields);
			$oSQLQuery->AddInnerJoin($oSQLQueryExt, 'id', $aExtendedDataSpec['join_key'] /*, $sTableAlias*/);
		}
		
		return $oSQLQuery;
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
		if (!self::$m_bTraceQueries) return;

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
		if (!self::$m_bDebugQuery) return;
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

		if (is_string($value))
		{
			echo "$sIndent$sFunction: $value<br/>\n";
		}
		else if (is_object($value))
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
}
