<?php

/**
 * CMDBSource
 * database access wrapper 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

require_once('MyHelpers.class.inc.php');

class MySQLException extends CoreException
{
	public function __construct($sIssue, $aContext)
	{
		$aContext['mysql_error'] = mysql_error();
		parent::__construct($sIssue, $aContext);
	}
}


class CMDBSource
{
	protected static $m_sDBHost;
	protected static $m_sDBUser;
	protected static $m_sDBPwd;
	protected static $m_sDBName;
	protected static $m_resDBLink;

	public static function Init($sServer, $sUser, $sPwd, $sSource = '')
	{
		self::$m_sDBHost = $sServer;
		self::$m_sDBUser = $sUser;
		self::$m_sDBPwd = $sPwd;
		self::$m_sDBName = $sSource;
		if (!self::$m_resDBLink = @mysql_pconnect($sServer, $sUser, $sPwd))
		{
			throw new MySQLException('Could not connect to the DB server', array('host'=>$sServer));
		}
		if (!empty($sSource))
		{
			if (!mysql_select_db($sSource, self::$m_resDBLink))
			{
				throw new MySQLException('Could not select DB', array('db_name'=>$sSource));
			}
		}
	}

	public static function ListDB()
	{
		$aDBs = self::QueryToCol('SHOW DATABASES', 'Database');
		// Show Database does return the DB names in lower case
		return $aDBs;
	}

	public static function IsDB($sSource)
	{
		try
		{
			$aDBs = self::ListDB();
			foreach($aDBs as $sDBName)
			{
			// perform a case insensitive test because on Windows the table names become lowercase :-(
				if (strtolower($sDBName) == strtolower($sSource)) return true;
			}
			return false;
		}
		catch(Exception $e)
		{
			// In case we don't have rights to enumerate the databases
			// Let's try to connect directly
			return @mysql_select_db($sSource, self::$m_resDBLink);
		}

	}

	public static function GetDBVersion()
	{
		$aVersions = self::QueryToCol('SELECT Version() as version', 'version');
		return $aVersions[0];
	}
	
	public static function SelectDB($sSource)
	{
		if (!mysql_select_db($sSource, self::$m_resDBLink))
		{
			throw new MySQLException('Could not select DB', array('db_name'=>$sSource));
		}
		self::$m_sDBName = $sSource;
	}

	public static function CreateDB($sSource)
	{
		self::Query("CREATE DATABASE `$sSource`");
		self::SelectDB($sSource);
	}

	public static function DropDB($sDBToDrop = '')
	{
		if (empty($sDBToDrop))
		{
			$sDBToDrop = self::$m_sDBName;
		}
		self::Query("DROP DATABASE `$sDBToDrop`");
		if ($sDBToDrop == self::$m_sDBName)
		{
			self::$m_sDBName = '';
		}
	}

	public static function CreateTable($sQuery)
	{
		$res = self::Query($sQuery);
		self::_TablesInfoCacheReset(); // reset the table info cache!
		return $res;
	}

	public static function DropTable($sTable)
	{
		$res = self::Query("DROP TABLE `$sTable`");
		self::_TablesInfoCacheReset(true); // reset the table info cache!
		return $res;
	}

	public static function DBHost() {return self::$m_sDBHost;}
	public static function DBUser() {return self::$m_sDBUser;}
	public static function DBPwd() {return self::$m_sDBPwd;}
	public static function DBName() {return self::$m_sDBName;}

	public static function Quote($value, $bAlways = false, $cQuoteStyle = "'")
	{
		// Quote variable and protect against SQL injection attacks
		// Code found in the PHP documentation: quote_smart($value)

		// bAlways should be set to true when the purpose is to create a IN clause,
		// otherwise and if there is a mix of strings and numbers, the clause
		// would always be false

		if (is_array($value))
		{
			$aRes = array();
			foreach ($value as $key => $itemvalue)
			{
				$aRes[$key] = self::Quote($itemvalue, $bAlways, $cQuoteStyle);
			}
			return $aRes;
		}

		// Stripslashes
		if (get_magic_quotes_gpc())
		{
			$value = stripslashes($value);
		}
		// Quote if not a number or a numeric string
		if ($bAlways || !is_numeric($value))
		{
			$value = $cQuoteStyle . mysql_real_escape_string($value, self::$m_resDBLink) . $cQuoteStyle;
		}
		return $value;
	}

	public static function Query($sSQLQuery)
	{
		// Add info into the query as a comment, for easier error tracking
	  	// disabled until we need it really!
		//
		//$aTraceInf['file'] = __FILE__;
		// $sSQLQuery .= MyHelpers::MakeSQLComment($aTraceInf);
	  
		$mu_t1 = MyHelpers::getmicrotime();
		$result = mysql_query($sSQLQuery, self::$m_resDBLink);
		if (!$result) 
		{
			throw new MySQLException('Failed to issue SQL query', array('query' => $sSQLQuery));
		}
		$mu_t2 = MyHelpers::getmicrotime();
		// #@# todo - query_trace($sSQLQuery, $mu_t2 - $mu_t1);
	
		return $result;
	}

	public static function GetNextInsertId($sTable)
	{
		$sSQL = "SHOW TABLE STATUS LIKE '$sTable'";
		$result = self::Query($sSQL);
		$aRow = mysql_fetch_assoc($result);
		$iNextInsertId = $aRow['Auto_increment'];
		return $iNextInsertId;
	}

	public static function GetInsertId()
	{
		return mysql_insert_id(self::$m_resDBLink);
	}
	public static function InsertInto($sSQLQuery)
	{
		if (self::Query($sSQLQuery))
		{
			return self::GetInsertId();
		}
		return false;
	}

	public static function QueryToArray($sSql)
	{
		$aData = array();
		$result = mysql_query($sSql, self::$m_resDBLink);
		if (!$result)
		{
			throw new MySQLException('Failed to issue SQL query', array('query' => $sSql));
		}
		while ($aRow = mysql_fetch_array($result, MYSQL_BOTH))
		{
			$aData[] = $aRow;
		}
		mysql_free_result($result);
		return $aData;
	}

	public static function QueryToCol($sSql, $col)
	{
		$aColumn = array();
		$aData = self::QueryToArray($sSql);
		foreach($aData as $aRow)
		{
			@$aColumn[] = $aRow[$col];
		}
		return $aColumn;
	}

	public static function ExplainQuery($sSql)
	{
		$aData = array();
		$result = mysql_query("EXPLAIN $sSql", self::$m_resDBLink);
		if (!$result)
		{
			throw new MySQLException('Failed to issue SQL query', array('query' => $sSql));
		}

		$aNames = array();
		for ($i = 0; $i < mysql_num_fields($result) ; $i++)
		{
		   $meta = mysql_fetch_field($result, $i);
		   if (!$meta)
		   {
				throw new MySQLException('mysql_fetch_field: No information available', array('query'=>$sSql, 'i'=>$i));
		   }
		   else
		   {
		   		$aNames[] = $meta->name;
		   }
		}

		$aData[] = $aNames;
		while ($aRow = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$aData[] = $aRow;
		}
		mysql_free_result($result);
		return $aData;
	}

	public static function TestQuery($sSql)
	{
		$result = mysql_query("EXPLAIN $sSql", self::$m_resDBLink);
		if (!$result)
		{
			return mysql_error();
		}

		mysql_free_result($result);
		return '';
	}

	public static function NbRows($result)
	{
		return mysql_num_rows($result);
	}

	public static function FetchArray($result)
	{
		return mysql_fetch_array($result, MYSQL_ASSOC);
	}

	public static function Seek($result, $iRow)
	{
		return mysql_data_seek($result, $iRow);
	}

	public static function FreeResult($result)
	{
		return mysql_free_result($result);
	}

	public static function IsTable($sTable)
	{
		$aTableInfo = self::GetTableInfo($sTable);
		return (!empty($aTableInfo));
	}

	public static function IsKey($sTable, $iKey)
	{
		$aTableInfo = self::GetTableInfo($sTable);
		if (empty($aTableInfo)) return false;
		if (!array_key_exists($iKey, $aTableInfo["Fields"])) return false;
		$aFieldData = $aTableInfo["Fields"][$iKey];
		if (!array_key_exists("Key", $aFieldData)) return false;
		return ($aFieldData["Key"] == "PRI"); 
	}

	public static function IsAutoIncrement($sTable, $sField)
	{
		$aTableInfo = self::GetTableInfo($sTable);
		if (empty($aTableInfo)) return false;
		if (!array_key_exists($sField, $aTableInfo["Fields"])) return false;
		$aFieldData = $aTableInfo["Fields"][$sField];
		if (!array_key_exists("Extra", $aFieldData)) return false;
		//MyHelpers::debug_breakpoint($aFieldData);
		return (strstr($aFieldData["Extra"], "auto_increment")); 
	}

	public static function IsField($sTable, $sField)
	{
		$aTableInfo = self::GetTableInfo($sTable);
		if (empty($aTableInfo)) return false;
		if (!array_key_exists($sField, $aTableInfo["Fields"])) return false;
		return true;
	}

	public static function IsNullAllowed($sTable, $sField)
	{
		$aTableInfo = self::GetTableInfo($sTable);
		if (empty($aTableInfo)) return false;
		if (!array_key_exists($sField, $aTableInfo["Fields"])) return false;
		$aFieldData = $aTableInfo["Fields"][$sField];
		return (strtolower($aFieldData["Null"]) == "yes");
	}

	public static function GetFieldType($sTable, $sField)
	{
		$aTableInfo = self::GetTableInfo($sTable);
		if (empty($aTableInfo)) return false;
		if (!array_key_exists($sField, $aTableInfo["Fields"])) return false;
		$aFieldData = $aTableInfo["Fields"][$sField];
		return ($aFieldData["Type"]);
	}

	public static function HasIndex($sTable, $sField)
	{
		$aTableInfo = self::GetTableInfo($sTable);
		if (empty($aTableInfo)) return false;
		if (!array_key_exists($sField, $aTableInfo["Fields"])) return false;
		$aFieldData = $aTableInfo["Fields"][$sField];
		// $aFieldData could be 'PRI' for the primary key, or 'MUL', or ?
		return (strlen($aFieldData["Key"]) > 0);
	}

	// Returns an array of (fieldname => array of field info)
	public static function GetTableFieldsList($sTable)
	{
		assert(!empty($sTable));
		
		$aTableInfo = self::GetTableInfo($sTable);
		if (empty($aTableInfo)) return array(); // #@# or an error ?

		return array_keys($aTableInfo["Fields"]);
	}
	
	// Cache the information about existing tables, and their fields
	private static $m_aTablesInfo = array();
	private static function _TablesInfoCacheReset()
	{
		self::$m_aTablesInfo = array();
	}
	private static function _TableInfoCacheInit($sTableName)
	{
		if (isset(self::$m_aTablesInfo[strtolower($sTableName)])
			&& (self::$m_aTablesInfo[strtolower($sTableName)] != null)) return;

		try
		{
			// Check if the table exists
			$aFields = self::QueryToArray("SHOW COLUMNS FROM `$sTableName`");
			// Note: without backticks, you get an error with some table names (e.g. "group")
			foreach ($aFields as $aFieldData)
			{
				$sFieldName = $aFieldData["Field"];
				self::$m_aTablesInfo[strtolower($sTableName)]["Fields"][$sFieldName] =
					array
					(
						"Name"=>$aFieldData["Field"],
						"Type"=>$aFieldData["Type"],
						"Null"=>$aFieldData["Null"],
						"Key"=>$aFieldData["Key"],
						"Default"=>$aFieldData["Default"],
						"Extra"=>$aFieldData["Extra"]
					);
			}
		}
		catch(MySQLException $e)
		{
			// Table does not exist
			self::$m_aTablesInfo[strtolower($sTableName)] = null;
		}
	}
	//public static function EnumTables()
	//{
	//	self::_TablesInfoCacheInit();
	//	return array_keys(self::$m_aTablesInfo);
	//}
	public static function GetTableInfo($sTable)
	{
		self::_TableInfoCacheInit($sTable);

		// perform a case insensitive match because on Windows the table names become lowercase :-(
		//foreach(self::$m_aTablesInfo as $sTableName => $aInfo)
		//{
		//	if (strtolower($sTableName) == strtolower($sTable))
		//	{
		//		return $aInfo;
		//	}
		//}
		return self::$m_aTablesInfo[strtolower($sTable)];
		//return null;
	}

	public static function DumpTable($sTable)
	{
		$sSql = "SELECT * FROM `$sTable`";
		$result = mysql_query($sSql, self::$m_resDBLink);
		if (!$result)
		{
			throw new MySQLException('Failed to issue SQL query', array('query' => $sSql));
		}

		$aRows = array();
		while ($aRow = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$aRows[] = $aRow;
		}
		mysql_free_result($result);
		return $aRows;
	}
	
	/**
	 * Returns the value of the specified server variable
	 * @param string $sVarName Name of the server variable
	 * @return mixed Current value of the variable
	 */	   	
	public static function GetServerVariable($sVarName)
	{
		$result = '';
		$sSql = "SELECT @@$sVarName as theVar";
		$aRows = self::QueryToArray($sSql);
		if (count($aRows) > 0)
		{
			$result = $aRows[0]['theVar'];
		}
		return $result;
	}
}


?>
