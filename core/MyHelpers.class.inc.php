<?php

/**
 * MyHelpers
 * various dev/debug helpers, to cleanup or at least re-organize
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

class MyHelpers
{
	public static function CheckValueInArray($sDescription, $value, $aData)
	{
		if (!in_array($value, $aData))
		{
			self::HandleWrongValue($sDescription, $value, $aData);
		}
	}

	public static function CheckKeyInArray($sDescription, $key, $aData)
	{
		if (!array_key_exists($key, $aData))
		{
			self::HandleWrongValue($sDescription, $key, array_keys($aData));
		}
	}

	public static function HandleWrongValue($sDescription, $value, $aData)
	{
		if (count($aData) == 0)
		{
			$sArrayDesc = "{}";
		}
		else
		{
			$sArrayDesc = "{".implode(", ", $aData)."}";
		}
		// exit!
		trigger_error("Wrong value for $sDescription, found '$value' while expecting a value in $sArrayDesc", E_USER_ERROR);
	}

	// getmicrotime()
	// format sss.mmmuuupppnnn 
	public static function getmicrotime()
	{ 
		list($usec, $sec) = explode(" ",microtime()); 
		return ((float)$usec + (float)$sec); 
	}

	/*
	 * MakeSQLComment
	 * converts hash into text comment which we can use in a (mySQL) query
	 */
	public static function MakeSQLComment ($aHash)
	{
		if (empty($aHash)) return "";
		$sComment = "";
		{
			foreach($aHash as $sKey=>$sValue)
			{
				$sComment .= "\n-- ". $sKey ."=>" . $sValue;
			}
		}
		return $sComment;
	}

	public static function var_dump_html($aWords, $bFullDisplay = false)
	{
		echo "<pre>\n";
		if ($bFullDisplay)
		{
			print_r($aWords); // full dump!
		}
		else
		{
			var_dump($aWords); // truncate things when they are too big
		}
		echo "\n</pre>\n";
	}

	public static function arg_dump_html()
	{
		echo "<pre>\n";
		echo "GET:\n";
		var_dump($_GET);
		echo "POST:\n";
		var_dump($_POST);
		echo "\n</pre>\n";
	}

	public static function get_callstack_html($iLevelsToIgnore = 0, $aCallStack = null)
	{
		if ($aCallStack == null) $aCallStack = debug_backtrace();
		
		$aCallStack = array_slice($aCallStack, $iLevelsToIgnore);
	
		$aDigestCallStack = array();
		$bFirstLine = true;		
		foreach ($aCallStack as $aCallInfo)
		{
			$sLine = empty($aCallInfo['line']) ? "" : $aCallInfo['line'];
			$sFile = empty($aCallInfo['file']) ? "" : $aCallInfo['file'];
			$sClass = empty($aCallInfo['class']) ? "" : $aCallInfo['class'];
			$sType = empty($aCallInfo['type']) ? "" : $aCallInfo['type'];
			$sFunction = empty($aCallInfo['function']) ? "" : $aCallInfo['function'];

			if ($bFirstLine)
			{
				$bFirstLine = false;
				// For this line do not display the "function name" because
				// that will be the name of our error handler for sure !
				$sFunctionInfo = "N/A";
			}
			else
			{
				$args = '';
				if (empty($aCallInfo['args'])) $aCallInfo['args'] = array();
				foreach ($aCallInfo['args'] as $a)
				{
					if (!empty($args))
					{
						$args .= ', ';
					}
					switch (gettype($a))
					{
						case 'integer':
						case 'double':
						$args .= $a;
					break;
						case 'string':
						$a = Str::pure2html(self::beautifulstr($a, 1024, true, true));
						$args .= "\"$a\"";
						break;
					case 'array':
						$args .= 'Array('.count($a).')';
						break;
					case 'object':
						$args .= 'Object('.get_class($a).')';
						break;
					case 'resource':
						$args .= 'Resource('.strstr($a, '#').')';
						break;
					case 'boolean':
						$args .= $a ? 'True' : 'False';
						break;
					case 'NULL':
						$args .= 'Null';
						break;
					default:
						$args .= 'Unknown';
					}
				}
				$sFunctionInfo = "$sClass $sType $sFunction($args)";
			}
			$aDigestCallStack[] = array('File'=>$sFile, 'Line'=>$sLine, 'Function'=>$sFunctionInfo);
		}
		return self::make_table_from_assoc_array($aDigestCallStack);
	}

	public static function dump_callstack($iLevelsToIgnore = 0, $aCallStack = null)
	{
		return self::get_callstack_html($iLevelsToIgnore, $aCallStack);
	}

	///////////////////////////////////////////////////////////////////////////////
	// Source: New
	// Last modif: 2004/12/20 RQU
	///////////////////////////////////////////////////////////////////////////////
	public static function make_table_from_assoc_array(&$aData)
	{
		if (!is_array($aData)) trigger_error("make_table_from_assoc_array: Error - the passed argument is not an array", E_USER_ERROR);
		$aFirstRow = reset($aData);
		if (!is_array($aFirstRow)) trigger_error("make_table_from_assoc_array: Error - the passed argument is not a bi-dimensional array", E_USER_ERROR);
		$sOutput = "";
		$sOutput .= "<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"1\">\n";
	
		// Table header
		//
		$sOutput .= "   <TR CLASS=celltitle>\n";
		foreach ($aFirstRow as $fieldname=>$trash) {
			$sOutput .= "      <TD><B>".$fieldname."</B></TD>\n";
		}
		$sOutput .= "   </TR>\n";
	
		// Table contents
		//
		$iCount = 0;
		foreach ($aData as $aRow) {
			$sStyle = ($iCount++ % 2 ? "STYLE=\"background-color : #eeeeee\"" : "");
			$sOutput .= "   <TR $sStyle CLASS=cell>\n";
			foreach ($aRow as $data) {
				if (strlen($data) == 0) {
					$data = "&nbsp;";
				}
				$sOutput .= "      <TD>".$data."</TD>\n";
			}
			$sOutput .= "   </TR>\n";
		}
		
		$sOutput .= "</TABLE>\n";
		return $sOutput;
	}

	public static function debug_breakpoint($arg)
	{
		echo "<H1> Debug breakpoint </H1>\n";
		MyHelpers::var_dump_html($arg);
		MyHelpers::dump_callstack();
		exit;
	}
	public static function debug_breakpoint_notempty($arg)
	{
		if (empty($arg)) return;
		echo "<H1> Debug breakpoint (triggered on non-empty value) </H1>\n";
		MyHelpers::var_dump_html($arg);
		MyHelpers::dump_callstack();
		exit;
	}

	/**
	* utf8... converts non ASCII chars into '?'
	* Decided after some complex investigations, to have the tools work fine (Oracle+Perl vs mySQL+PHP...)
	*/
	public static function utf8($strText)
	{
		return iconv("WINDOWS-1252", "ASCII//TRANSLIT", $strText);
	}	

	/**
	* xmlentities()
	* ... same as htmlentities, but designed for xml !
	*/
	public static function xmlentities($string)
	{
		return str_replace( array( '&', '"', "'", '<', '>' ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;' ), $string );
	}

	/**
	* xmlencode()
	* Encodes a string so that for sure it can be output as an xml data string
	*/
	public static function xmlencode($string)
	{
		return xmlentities(iconv("UTF-8", "UTF-8//IGNORE",$string));
	}

	///////////////////////////////////////////////////////////////////////////////
	// Source: New - format strings for output
	// Last modif: 2005/01/18 RQU
	///////////////////////////////////////////////////////////////////////////////
	public static function beautifulstr($sLongString, $iMaxLen, $bShowLen=false, $bShowTooltip=true)
	{
		if (!is_string($sLongString)) trigger_error("beautifulstr: expect a string as 1st argument", E_USER_ERROR);
	
		// Nothing to do if the string is short
		if (strlen($sLongString) <= $iMaxLen) return $sLongString;
	
		// Truncate the string
		$sSuffix = "...";
		if ($bShowLen) {
			$sSuffix .= "(".strlen($sLongString)." chars)...";
		}
		$sOutput = substr($sLongString, 0, $iMaxLen - strlen($sSuffix)).$sSuffix;
		$sOutput = htmlspecialchars($sOutput);
	
		// Add tooltip if required
		//if ($bShowTooltip) {
		//	$oTooltip = new gui_tooltip($sLongString);
		//	$sOutput = "<SPAN ".$oTooltip->get_mouseOver_code().">".$sOutput."</SPAN>";
		//}
		return $sOutput;
	}
}

/**
Utility class: static methods for cleaning & escaping untrusted (i.e.
user-supplied) strings.
Any string can (usually) be thought of as being in one of these 'modes':
pure = what the user actually typed / what you want to see on the page /
      what is actually stored in the DB
gpc  = incoming GET, POST or COOKIE data
sql  = escaped for passing safely to RDBMS via SQL (also, data from DB
      queries and file reads if you have magic_quotes_runtime on--which
      is rare)
html = safe for html display (htmlentities applied)
Always knowing what mode your string is in--using these methods to
convert between modes--will prevent SQL injection and cross-site scripting.
This class refers to its own namespace (so it can work in PHP 4--there is no
self keyword until PHP 5). Do not change the name of the class w/o changing
all the internal references.
Example usage: a POST value that you want to query with:
$username = Str::gpc2sql($_POST['username']);
*/
//This sets SQL escaping to use slashes; for Sybase(/MSSQL)-style escaping
// ( ' --> '' ), set to true.
define('STR_SYBASE', false);
class Str
{
	public static function gpc2sql($gpc, $maxLength = false)
	{
		return self::pure2sql(self::gpc2pure($gpc), $maxLength);
	}
	public static function gpc2html($gpc, $maxLength = false)
	{
		return self::pure2html(self::gpc2pure($gpc), $maxLength);
	}
	public static function gpc2pure($gpc)
	{
		if (ini_get('magic_quotes_sybase')) $pure = str_replace("''", "'", $gpc);
		else                                $pure = get_magic_quotes_gpc() ? stripslashes($gpc) : $gpc;
		return $pure;
	}
	public static function html2pure($html)
	{
		return html_entity_decode($html);
	}
	public static function html2sql($html, $maxLength = false)
	{
		return self::pure2sql(self::html2pure($html), $maxLength);
	}
	public static function pure2html($pure, $maxLength = false)
	{
		return $maxLength
			? htmlentities(substr($pure, 0, $maxLength))
			: htmlentities($pure);
	}
	public static function pure2sql($pure, $maxLength = false)
	{
		if ($maxLength) $pure = substr($pure, 0, $maxLength);
		return (STR_SYBASE)
			? str_replace("'", "''", $pure)
			: addslashes($pure);
	}
	public static function sql2html($sql, $maxLength = false)
	{
		$pure = self::sql2pure($sql);
		if ($maxLength) $pure = substr($pure, 0, $maxLength);
		return self::pure2html($pure);
	}
	public static function sql2pure($sql)
	{
		return (STR_SYBASE)
			? str_replace("''", "'", $sql)
			: stripslashes($sql);
	}

	public static function xml2pure($xml)
	{
		// #@# - not implemented
		return $xml;
	}
	public static function pure2xml($pure)
	{
		return self::xmlencode($pure);
	}

	protected static function xmlentities($string)
	{
		return str_replace( array( '&', '"', "'", '<', '>' ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;' ), $string );
	}

	/**
	* xmlencode()
	* Encodes a string so that for sure it can be output as an xml data string
	*/
	protected static function xmlencode($string)
	{
		return self::xmlentities(iconv("ISO-8859-1", "UTF-8//IGNORE",$string));
	}

	public static function islowcase($sString)
	{
		return (strtolower($sString) == $sString);
	}
}

?>
