<?php
// Copyright (C) 2010-2018 Combodo SARL
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
 * DB Server abstraction
 *
 * @copyright   Copyright (C) 2010-2018 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('MyHelpers.class.inc.php');
require_once(APPROOT.'core/kpi.class.inc.php');

class MySQLException extends CoreException
{
	/**
	 * MySQLException constructor.
	 *
	 * @param string $sIssue
	 * @param array $aContext
	 * @param \Exception $oException
	 * @param \mysqli $oMysqli to use when working with a custom mysqli instance
	 */
	public function __construct($sIssue, $aContext, $oException = null, $oMysqli = null)
	{
		if ($oException != null)
		{
			$aContext['mysql_errno'] = $oException->getCode();
			$this->code = $oException->getCode();
			$aContext['mysql_error'] = $oException->getMessage();
		}
		else if ($oMysqli != null)
		{
			$aContext['mysql_errno'] = $oMysqli->errno;
			$this->code = $oMysqli->errno;
			$aContext['mysql_error'] = $oMysqli->error;
		}
		else
		{
			$aContext['mysql_errno'] = CMDBSource::GetErrNo();
			$this->code = CMDBSource::GetErrNo();
			$aContext['mysql_error'] = CMDBSource::GetError();
		}
		parent::__construct($sIssue, $aContext);
	}
}

/**
 * Class MySQLQueryHasNoResultException
 *
 * @since 2.5
 */
class MySQLQueryHasNoResultException extends MySQLException
{

}

/**
 * Class MySQLHasGoneAwayException
 *
 * @since 2.5
 * @see itop bug 1195
 * @see https://dev.mysql.com/doc/refman/5.7/en/gone-away.html
 */
class MySQLHasGoneAwayException extends MySQLException
{
	/**
	 * can not be a constant before PHP 5.6 (http://php.net/manual/fr/language.oop5.constants.php)
	 *
	 * @return int[]
	 */
	public static function getErrorCodes()
	{
		return array(
			2006,
			2013
		);
	}

	public function __construct($sIssue, $aContext)
	{
		parent::__construct($sIssue, $aContext, null);
	}
}


/**
 * CMDBSource
 * database access wrapper 
 *
 * @package     iTopORM
 */
class CMDBSource
{
	/**
	 * SQL charset & collation declaration for text columns
	 *
	 * @see https://dev.mysql.com/doc/refman/5.7/en/charset-column.html
	 * @since 2.5 #1001 switch to utf8mb4
	 */
	const SQL_STRING_COLUMNS_CHARSET_DEFINITION = ' CHARACTER SET '.DEFAULT_CHARACTER_SET.' COLLATE '.DEFAULT_COLLATION;

	protected static $m_sDBHost;
	protected static $m_sDBUser;
	protected static $m_sDBPwd;
	protected static $m_sDBName;
	protected static $m_sDBTlsKey;
	protected static $m_sDBTlsCert;
	protected static $m_sDBTlsCA;
	protected static $m_sDBTlsCaPath;
	protected static $m_sDBTlsCipher;
	protected static $m_bDBTlsVerifyServerCert;
	/** @var mysqli $m_oMysqli */
	protected static $m_oMysqli;

	/**
	 * @param Config $oConfig
	 *
	 * @throws \MySQLException
	 * @uses \CMDBSource::Init()
	 * @uses \CMDBSource::SetCharacterSet()
	 */
	public static function InitFromConfig($oConfig)
	{
		$sServer = $oConfig->Get('db_host');
		$sUser = $oConfig->Get('db_user');
		$sPwd = $oConfig->Get('db_pwd');
		$sSource = $oConfig->Get('db_name');
		$sTlsKey = $oConfig->Get('db_tls.key');
		$sTlsCert = $oConfig->Get('db_tls.cert');
		$sTlsCA = $oConfig->Get('db_tls.ca');
		$sTlsCaPath = $oConfig->Get('db_tls.capath');
		$sTlsCipher = $oConfig->Get('db_tls.cipher');
		$sTlsVerifyServerCert = $oConfig->Get('db_tls.verify_server_cert');

		self::Init($sServer, $sUser, $sPwd, $sSource, $sTlsKey, $sTlsCert, $sTlsCA, $sTlsCaPath, $sTlsCipher,
			$sTlsVerifyServerCert);

		$sCharacterSet = DEFAULT_CHARACTER_SET;
		$sCollation = DEFAULT_COLLATION;
		self::SetCharacterSet($sCharacterSet, $sCollation);
	}

	/**
	 * @param string $sServer
	 * @param string $sUser
	 * @param string $sPwd
	 * @param string $sSource database to use
	 * @param string $sTlsKey
	 * @param string $sTlsCert
	 * @param string $sTlsCA
	 * @param string $sTlsCaPath
	 * @param string $sTlsCipher
	 * @param bool $sTlsVerifyServerCert
	 *
	 * @throws \MySQLException
	 */
	public static function Init(
		$sServer, $sUser, $sPwd, $sSource = '', $sTlsKey = null, $sTlsCert = null, $sTlsCA = null, $sTlsCaPath = null,
		$sTlsCipher = null, $sTlsVerifyServerCert = false
	)
	{
		self::$m_sDBHost = $sServer;
		self::$m_sDBUser = $sUser;
		self::$m_sDBPwd = $sPwd;
		self::$m_sDBName = $sSource;
		self::$m_sDBTlsKey = empty($sTlsKey) ? null : $sTlsKey;
		self::$m_sDBTlsCert = empty($sTlsCert) ? null : $sTlsCert;
		self::$m_sDBTlsCA = empty($sTlsCA) ? null : $sTlsCA;
		self::$m_sDBTlsCaPath = empty($sTlsCaPath) ? null : $sTlsCaPath;
		self::$m_sDBTlsCipher = empty($sTlsCipher) ? null : $sTlsCipher;
		self::$m_bDBTlsVerifyServerCert = empty($sTlsVerifyServerCert) ? null : $sTlsVerifyServerCert;

		self::$m_oMysqli = self::GetMysqliInstance($sServer, $sUser, $sPwd, $sSource, $sTlsKey, $sTlsCert, $sTlsCA,
			$sTlsCaPath, $sTlsCipher, true, $sTlsVerifyServerCert);
	}

	/**
	 * @param string $sServer
	 * @param string $sUser
	 * @param string $sPwd
	 * @param string $sSource database to use
	 * @param string $sTlsKey
	 * @param string $sTlsCert
	 * @param string $sTlsCa
	 * @param string $sTlsCaPath
	 * @param string $sTlsCipher
	 * @param bool $bCheckTlsAfterConnection
	 * @param bool $bVerifyTlsServerCert Change the TLS flag used to connect : MYSQLI_CLIENT_SSL if true, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT if false (default)
	 *
	 * @return \mysqli
	 * @throws \MySQLException
	 */
	public static function GetMysqliInstance(
		$sServer, $sUser, $sPwd, $sSource = '', $sTlsKey = null, $sTlsCert = null, $sTlsCa = null, $sTlsCaPath = null,
		$sTlsCipher = null, $bCheckTlsAfterConnection = false, $bVerifyTlsServerCert = false
	) {
		$oMysqli = null;

		$sServer = null;
		$iPort = null;
		$bTlsEnabled = self::IsDbConnectionUsingTls($sTlsKey, $sTlsCert, $sTlsCa);
		self::InitServerAndPort(self::$m_sDBHost, $sServer, $iPort);

		$iFlags = null;

		// *some* errors (like connection errors) will throw mysqli_sql_exception instead of generating warnings printed to the output
		// but some other errors will still cause the query() method to return false !!!
		mysqli_report(MYSQLI_REPORT_STRICT);

		try
		{
			$oMysqli = new mysqli();
			$oMysqli->init();

			if ($bTlsEnabled)
			{
				$iFlags = ($bVerifyTlsServerCert)
					? MYSQLI_CLIENT_SSL
					: MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT;
				$oMysqli->ssl_set($sTlsKey, $sTlsCert, $sTlsCa, $sTlsCaPath, $sTlsCipher);
			}
			$oMysqli->real_connect($sServer, $sUser, $sPwd, '', $iPort,
				ini_get("mysqli.default_socket"), $iFlags);
		}
		catch(mysqli_sql_exception $e)
		{
			throw new MySQLException('Could not connect to the DB server', array('host' => $sServer, 'user' => $sUser), $e);
		}

		if ($bCheckTlsAfterConnection
			&& self::IsDbConnectionUsingTls($sTlsKey, $sTlsCert, $sTlsCa)
			&& !self::IsOpenedDbConnectionUsingTls($oMysqli))
		{
			throw new MySQLException("Connection to the database is not encrypted whereas it was opened using TLS parameters",
				null, null, $oMysqli);
		}

		if (!empty($sSource))
		{
			try
			{
				mysqli_report(MYSQLI_REPORT_STRICT); // Errors, in the next query, will throw mysqli_sql_exception
				$oMysqli->query("USE `$sSource`");
			}
			catch(mysqli_sql_exception $e)
			{
				throw new MySQLException('Could not select DB',
					array('host' => $sServer, 'user' => $sUser, 'db_name' => $sSource), $e);
			}
		}

		return $oMysqli;
	}

	/**
	 * @param string $sDbHost initial value ("p:domain:port" syntax)
	 * @param string $sServer server variable to update
	 * @param int $iPort port variable to update
	 */
	public static function InitServerAndPort($sDbHost, &$sServer, &$iPort)
	{
		$aConnectInfo = explode(':', $sDbHost);

		$bUsePersistentConnection = false;
		if (strcasecmp($aConnectInfo[0], 'p') == 0)
		{
			// we might have "p:" prefix to use persistent connections (see http://php.net/manual/en/mysqli.persistconns.php)
			$bUsePersistentConnection = true;
			$sServer = $aConnectInfo[0].':'.$aConnectInfo[1];
		}
		else
		{
			$sServer = $aConnectInfo[0];
		}

		$iConnectInfoCount = count($aConnectInfo);
		if ($bUsePersistentConnection && ($iConnectInfoCount == 3))
		{
			$iPort = $aConnectInfo[2];
		}
		else if (!$bUsePersistentConnection && ($iConnectInfoCount == 2))
		{
			$iPort = $aConnectInfo[1];
		}
		else
		{
			$iPort = 3306;
		}
	}

	/**
	 * @param \Config $oConfig
	 *
	 * @return boolean
	 */
	public static function IsDbConnectionInConfigUsingTls($oConfig)
	{
		$sTlsKey = $oConfig->Get('db_tls.key');
		$sTlsCert = $oConfig->Get('db_tls.cert');
		$sTlsCA = $oConfig->Get('db_tls.ca');

		return self::IsDbConnectionUsingTls($sTlsKey, $sTlsCert, $sTlsCA);
	}

	/**
	 * @param string $sTlsKey
	 * @param string $sTlsCert
	 * @param string $sTlsCA
	 *
	 * @return bool
	 */
	public static function IsDbConnectionUsingTls($sTlsKey, $sTlsCert, $sTlsCA)
	{
		return (!empty($sTlsKey) && !empty($sTlsCert) && !empty($sTlsCA));
	}

	/**
	 * <p>A DB connection can be opened transparently (no errors thrown) without being encrypted, whereas the TLS
	 * parameters were used.<br>
	 * This method can be called to ensure that the DB connection really uses TLS.
	 *
	 * <p>We're using this object connection : {@link self::$m_oMysqli}
	 *
	 * @param \mysqli $oMysqli
	 *
	 * @return boolean true if the connection was really established using TLS
	 * @throws \MySQLException
	 *
	 * @uses IsMySqlVarNonEmpty
	 */
	private static function IsOpenedDbConnectionUsingTls($oMysqli)
	{
		if (self::$m_oMysqli == null)
		{
			self::$m_oMysqli = $oMysqli;
		}

		$bNonEmptySslVersionVar = self::IsMySqlVarNonEmpty('ssl_version');
		$bNonEmptySslCipherVar = self::IsMySqlVarNonEmpty('ssl_cipher');

		return ($bNonEmptySslVersionVar && $bNonEmptySslCipherVar);
	}

	/**
	 * @param string $sVarName
	 *
	 * @return bool
	 * @throws \MySQLException
	 *
	 * @uses SHOW STATUS queries
	 */
	private static function IsMySqlVarNonEmpty($sVarName)
	{
		try
		{
			$sResult = self::QueryToScalar("SHOW SESSION STATUS LIKE '$sVarName'", 1);
		}
		catch (MySQLQueryHasNoResultException $e)
		{
			$sResult = null;
		}

		return (!empty($sResult));
	}

	public static function SetCharacterSet($sCharset = DEFAULT_CHARACTER_SET, $sCollation = DEFAULT_COLLATION)
	{
		if (strlen($sCharset) > 0)
		{
			if (strlen($sCollation) > 0)
			{
				self::Query("SET NAMES '$sCharset' COLLATE '$sCollation'");
			}
			else
			{
				self::Query("SET NAMES '$sCharset'");
			}
		}
	}

	public static function SetTimezone($sTimezone = null)
	{
		// Note: requires the installation of MySQL special tables,
		//       otherwise, only 'SYSTEM' or "+10:00' may be specified which is NOT sufficient because of day light saving times
		if (!is_null($sTimezone))
		{
			$sQuotedTimezone = self::Quote($sTimezone);
			self::Query("SET time_zone = $sQuotedTimezone");
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
			return @((bool)self::$m_oMysqli->query("USE `$sSource`"));
		}

	}

	public static function GetDBVersion()
	{
		$aVersions = self::QueryToCol('SELECT Version() as version', 'version');
		return $aVersions[0];
	}

	/**
	 * @param string $sSource
	 *
	 * @throws \MySQLException
	 */
	public static function SelectDB($sSource)
	{
		if (!((bool)self::$m_oMysqli->query("USE `$sSource`")))
		{
			throw new MySQLException('Could not select DB', array('db_name'=>$sSource));
		}
		self::$m_sDBName = $sSource;
	}

	/**
	 * @param string $sSource
	 *
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public static function CreateDB($sSource)
	{
		self::Query("CREATE DATABASE `$sSource` CHARACTER SET ".DEFAULT_CHARACTER_SET." COLLATE ".DEFAULT_COLLATION);
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

	/**
	 * @return \mysqli
	 */
	public static function GetMysqli()
	{
		return self::$m_oMysqli;
	}

	public static function GetErrNo()
	{
		if (self::$m_oMysqli->errno != 0)
		{
			return self::$m_oMysqli->errno;
		}
		else
		{
			return self::$m_oMysqli->connect_errno;
		}
	}

	public static function GetError()
	{
		if (self::$m_oMysqli->error != '')
		{
			return self::$m_oMysqli->error;
		}
		else
		{
			return self::$m_oMysqli->connect_error;
		}
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

		if (is_null($value))
		{
			return 'NULL';
		}

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
		if ($bAlways || is_string($value))
		{
			$value = $cQuoteStyle . self::$m_oMysqli->real_escape_string($value) . $cQuoteStyle;
		}
		return $value;
	}

	/**
	 * @param string $sSQLQuery
	 *
	 * @return \mysqli_result
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public static function Query($sSQLQuery)
	{
		$oKPI = new ExecutionKPI();
		try
		{
			$oResult = self::$m_oMysqli->query($sSQLQuery);
		}
		catch(mysqli_sql_exception $e)
		{
			throw new MySQLException('Failed to issue SQL query', array('query' => $sSQLQuery, $e));
		}
		$oKPI->ComputeStats('Query exec (mySQL)', $sSQLQuery);
		if ($oResult === false)
		{
			$aContext = array('query' => $sSQLQuery);

			$iMySqlErrorNo = self::$m_oMysqli->errno;
			$aMySqlHasGoneAwayErrorCodes = MySQLHasGoneAwayException::getErrorCodes();
			if (in_array($iMySqlErrorNo, $aMySqlHasGoneAwayErrorCodes))
			{
				throw new MySQLHasGoneAwayException(self::GetError(), $aContext);
			}

			throw new MySQLException('Failed to issue SQL query', $aContext);
		}
	
		return $oResult;
	}

	/**
	 * @param string $sTable
	 *
	 * @return int
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public static function GetNextInsertId($sTable)
	{
		$sSQL = "SHOW TABLE STATUS LIKE '$sTable'";
		$oResult = self::Query($sSQL);
		$aRow = $oResult->fetch_assoc();

		return $aRow['Auto_increment'];
	}

	public static function GetInsertId()
	{
		$iRes = self::$m_oMysqli->insert_id;
		if (is_null($iRes))
		{
			return 0;
		}
		return $iRes;
	}

	public static function InsertInto($sSQLQuery)
	{
		if (self::Query($sSQLQuery))
		{
			return self::GetInsertId();
		}
		return false;
	}

	public static function DeleteFrom($sSQLQuery)
	{
		self::Query($sSQLQuery);
	}

	/**
	 * @param string $sSql
	 * @param int $iCol beginning at 0
	 *
	 * @return string corresponding cell content on the first line
	 * @throws \MySQLException
	 * @throws \MySQLQueryHasNoResultException
	 */
	public static function QueryToScalar($sSql, $iCol = 0)
	{
		$oKPI = new ExecutionKPI();
		try
		{
			$oResult = self::$m_oMysqli->query($sSql);
		}
		catch(mysqli_sql_exception $e)
		{
			$oKPI->ComputeStats('Query exec (mySQL)', $sSql);
			throw new MySQLException('Failed to issue SQL query', array('query' => $sSql, $e));
		}
		$oKPI->ComputeStats('Query exec (mySQL)', $sSql);
		if ($oResult === false)
		{
			throw new MySQLException('Failed to issue SQL query', array('query' => $sSql));
		}

		if ($aRow = $oResult->fetch_array(MYSQLI_BOTH))
		{
			$res = $aRow[$iCol];
		}
		else
		{
			$oResult->free();
			throw new MySQLQueryHasNoResultException('Found no result for query', array('query' => $sSql));
		}
		$oResult->free();

		return $res;
	}


	/**
	 * @param string $sSql
	 *
	 * @return array
	 * @throws \MySQLException if query cannot be processed
	 */
	public static function QueryToArray($sSql)
	{
		$aData = array();
		$oKPI = new ExecutionKPI();
		try
		{
			$oResult = self::$m_oMysqli->query($sSql);
		}
		catch(mysqli_sql_exception $e)
		{
			$oKPI->ComputeStats('Query exec (mySQL)', $sSql);
			throw new MySQLException('Failed to issue SQL query', array('query' => $sSql, $e));
		}
		$oKPI->ComputeStats('Query exec (mySQL)', $sSql);
		if ($oResult === false)
		{
			throw new MySQLException('Failed to issue SQL query', array('query' => $sSql));
		}
				
		while ($aRow = $oResult->fetch_array(MYSQLI_BOTH))
		{
			$aData[] = $aRow;
		}
		$oResult->free();
		return $aData;
	}

	/**
	 * @param string $sSql
	 * @param int $col
	 *
	 * @return array
	 * @throws \MySQLException
	 */
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

	/**
	 * @param string $sSql
	 *
	 * @return array
	 * @throws \MySQLException if query cannot be processed
	 */
	public static function ExplainQuery($sSql)
	{
		$aData = array();
		try
		{
			$oResult = self::$m_oMysqli->query($sSql);
		}
		catch(mysqli_sql_exception $e)
		{
			throw new MySQLException('Failed to issue SQL query', array('query' => $sSql, $e));
		}
		if ($oResult === false)
		{
			throw new MySQLException('Failed to issue SQL query', array('query' => $sSql));
		}

		$aNames = self::GetColumns($oResult, $sSql);

		$aData[] = $aNames;
		while ($aRow = $oResult->fetch_array(MYSQLI_ASSOC))
		{
			$aData[] = $aRow;
		}
		$oResult->free();
		return $aData;
	}

	/**
	 * @param string $sSql
	 *
	 * @return string
	 * @throws \MySQLException if query cannot be processed
	 */
	public static function TestQuery($sSql)
	{
		try
		{
			$oResult = self::$m_oMysqli->query($sSql);
		}
		catch(mysqli_sql_exception $e)
		{
			throw new MySQLException('Failed to issue SQL query', array('query' => $sSql, $e));
		}
		if ($oResult === false)
		{
			throw new MySQLException('Failed to issue SQL query', array('query' => $sSql));
		}

		if (is_object($oResult))
		{
			$oResult->free();
		}
		return '';
	}

	public static function NbRows($oResult)
	{
		return $oResult->num_rows;
	}

	public static function AffectedRows()
	{
		return self::$m_oMysqli->affected_rows;
	}

	public static function FetchArray($oResult)
	{
		return $oResult->fetch_array(MYSQLI_ASSOC);
	}

	/**
	 * @param mysqli_result $oResult
	 * @param string $sSql
	 *
	 * @return string[]
	 * @throws \MySQLException
	 */
	public static function GetColumns($oResult, $sSql)
	{
		$aNames = array();
		for ($i = 0; $i < (($___mysqli_tmp = $oResult->field_count) ? $___mysqli_tmp : 0) ; $i++)
		{
			$meta = $oResult->fetch_field_direct($i);
			if (!$meta)
			{
				throw new MySQLException('mysql_fetch_field: No information available', array('query'=>$sSql, 'i'=>$i));
			}
			else
			{
				$aNames[] = $meta->name;
			}
		}
		return $aNames;
	}

	public static function Seek($oResult, $iRow)
	{
		return $oResult->data_seek($iRow);
	}

	public static function FreeResult($oResult)
	{
		$oResult->free(); /* returns void */
		return true;
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

	/**
	 * @param string $sTable
	 * @param string $sField
	 *
	 * @return bool|string
	 * @see \AttributeDefinition::GetSQLColumns()
	 */
	public static function GetFieldSpec($sTable, $sField)
	{
		$aTableInfo = self::GetTableInfo($sTable);
		if (empty($aTableInfo)) return false;
		if (!array_key_exists($sField, $aTableInfo["Fields"])) return false;
		$aFieldData = $aTableInfo["Fields"][$sField];

		$sRet = $aFieldData["Type"];

		$sColumnCharset = $aFieldData["Charset"];
		$sColumnCollation = $aFieldData["Collation"];
		if (!empty($sColumnCharset))
		{
			$sRet .= ' CHARACTER SET '.$sColumnCharset;
			$sRet .= ' COLLATE '.$sColumnCollation;
		}

		if ($aFieldData["Null"] == 'NO')
		{
			$sRet .= ' NOT NULL';
		}

		if (is_numeric($aFieldData["Default"]))
		{
			if (strtolower(substr($aFieldData["Type"], 0, 5)) == 'enum(')
			{
				// Force quotes to match the column declaration statement
				$sRet .= ' DEFAULT '.self::Quote($aFieldData["Default"], true);
			}
			else
			{
				$default = $aFieldData["Default"] + 0; // Coerce to a numeric variable
				$sRet .= ' DEFAULT '.self::Quote($default);
			}
		}
		elseif (is_string($aFieldData["Default"]) == 'string')
		{
			$sRet .= ' DEFAULT '.self::Quote($aFieldData["Default"]);
		}

		return $sRet;
	}

	public static function HasIndex($sTable, $sIndexId, $aFields = null)
	{
		$aTableInfo = self::GetTableInfo($sTable);
		if (empty($aTableInfo)) return false;
		if (!array_key_exists($sIndexId, $aTableInfo['Indexes'])) return false;

		if ($aFields == null)
		{
			// Just searching for the name
			return true;
		}

		// Compare the columns
		$sSearchedIndex = implode(',', $aFields);
		$sExistingIndex = implode(',', $aTableInfo['Indexes'][$sIndexId]);

		return ($sSearchedIndex == $sExistingIndex);
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
			&& (self::$m_aTablesInfo[strtolower($sTableName)] != null))
		{
			return;
		}

		// Create array entry, if table does not exist / has no columns
		self::$m_aTablesInfo[strtolower($sTableName)] = null;

		// Get table informations
		//   We were using SHOW COLUMNS FROM... but this don't return charset and collation info !
		//   so since 2.5 and #1001 (switch to utf8mb4) we're using INFORMATION_SCHEMA !
		$aFields = self::QueryToArray('SELECT * FROM information_schema.`COLUMNS`'
			.' WHERE table_schema = "'.self::$m_sDBName.'" AND table_name = "'.$sTableName.'";');
		foreach ($aFields as $aFieldData)
		{
			$sFieldName = $aFieldData["COLUMN_NAME"];
			self::$m_aTablesInfo[strtolower($sTableName)]["Fields"][$sFieldName] =
				array
				(
					"Name" => $sFieldName,
					"Type" => $aFieldData["COLUMN_TYPE"],
					"Null" => $aFieldData["IS_NULLABLE"],
					"Key" => $aFieldData["COLUMN_KEY"],
					"Default" => $aFieldData["COLUMN_DEFAULT"],
					"Extra" => $aFieldData["EXTRA"],
					"Charset" => $aFieldData["CHARACTER_SET_NAME"],
					"Collation" => $aFieldData["COLLATION_NAME"],
				);
		}

		if (!is_null(self::$m_aTablesInfo[strtolower($sTableName)]))
		{
			$aIndexes = self::QueryToArray("SHOW INDEXES FROM `$sTableName`");
			$aMyIndexes = array();
			foreach ($aIndexes as $aIndexColumn)
			{
				$aMyIndexes[$aIndexColumn['Key_name']][$aIndexColumn['Seq_in_index']-1] = $aIndexColumn['Column_name'];
			}
			self::$m_aTablesInfo[strtolower($sTableName)]["Indexes"] = $aMyIndexes;
		}
	}

	public static function GetTableInfo($sTable)
	{
		self::_TableInfoCacheInit($sTable);

		// perform a case insensitive match because on Windows the table names become lowercase :-(
		return self::$m_aTablesInfo[strtolower($sTable)];
	}

	/**
	 * @param string $sTable
	 *
	 * @return array
	 * @throws \MySQLException if query cannot be processed
	 */
	public static function DumpTable($sTable)
	{
		$sSql = "SELECT * FROM `$sTable`";
		try
		{
			$oResult = self::$m_oMysqli->query($sSql);
		}
		catch(mysqli_sql_exception $e)
		{
			throw new MySQLException('Failed to issue SQL query', array('query' => $sSql), $e);
		}
		if ($oResult === false)
		{
			throw new MySQLException('Failed to issue SQL query', array('query' => $sSql));
		}
		
		$aRows = array();
		while ($aRow = $oResult->fetch_array(MYSQLI_ASSOC))
		{
			$aRows[] = $aRow;
		}
		$oResult->free();
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


	/**
	 * Returns the privileges of the current user
	 * @return string privileges in a raw format
	 */	   	
	public static function GetRawPrivileges()
	{
		try
		{
			$oResult = self::Query('SHOW GRANTS'); // [ FOR CURRENT_USER()]
		}
		catch(MySQLException $e)
		{
			$iCode = self::GetErrNo();
			return "Current user not allowed to see his own privileges (could not access to the database 'mysql' - $iCode)";
		}

		$aRes = array();
		while ($aRow = $oResult->fetch_array(MYSQLI_NUM))
		{
			// so far, only one column...
			$aRes[] = implode('/', $aRow);
		}
		$oResult->free();
		// so far, only one line...
		return implode(', ', $aRes);
	}

	/**
	 * Determine the slave status of the server
	 * @return bool true if the server is slave 
	 */	   	
	public static function IsSlaveServer()
	{
		try
		{
			$oResult = self::Query('SHOW SLAVE STATUS');
		}
		catch(MySQLException $e)
		{
			throw new CoreException("Current user not allowed to check the status", array('mysql_error' => $e->getMessage()));
		}

		if ($oResult->num_rows == 0)
		{
			return false;
		}

		// Returns one single row anytime
		$aRow = $oResult->fetch_array(MYSQLI_ASSOC);
		$oResult->free();

		if (!isset($aRow['Slave_IO_Running']))
		{
			return false;
		}
		if (!isset($aRow['Slave_SQL_Running']))
		{
			return false;
		}

		// If at least one slave thread is running, then we consider that the slave is enabled
		if ($aRow['Slave_IO_Running'] == 'Yes')
		{
			return true;
		}
		if ($aRow['Slave_SQL_Running'] == 'Yes')
		{
			return true;
		}
		return false;
	}
}
