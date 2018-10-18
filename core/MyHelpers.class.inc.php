<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * Various dev/debug helpers
 * TODO: cleanup or at least re-organize
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * MyHelpers
 *
 * @package     iTopORM
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
		throw new CoreException("Wrong value for $sDescription, found '$value' while expecting a value in $sArrayDesc");
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

	public static function var_dump_string($var)
	{
		ob_start();
		print_r($var);
		$sRet = ob_get_clean();
		return $sRet;
	}

	protected static function first_diff_line($s1, $s2)
	{
		$aLines1 = explode("\n", $s1);
		$aLines2 = explode("\n", $s2);
		for ($i = 0 ; $i < min(count($aLines1), count($aLines2)) ; $i++)
		{
			if ($aLines1[$i] != $aLines2[$i]) return $i;
		}
		return false;
	}

	protected static function highlight_line($sMultiline, $iLine, $sHighlightStart = '<b>', $sHightlightEnd = '</b>')
	{
		$aLines = explode("\n", $sMultiline);
		$aLines[$iLine] = $sHighlightStart.$aLines[$iLine].$sHightlightEnd;
		return implode("\n", $aLines);
	}

	protected static function first_diff($s1, $s2)
	{
		// do not work fine with multiline strings
		$iLen1 = strlen($s1);
		$iLen2 = strlen($s2);
		for ($i = 0 ; $i < min($iLen1, $iLen2) ; $i++)
		{
			if ($s1[$i] !== $s2[$i]) return $i;
		}
		return false;
	}

	protected static function last_diff($s1, $s2)
	{
		// do not work fine with multiline strings
		$iLen1 = strlen($s1);
		$iLen2 = strlen($s2);
		for ($i = 0 ; $i < min(strlen($s1), strlen($s2)) ; $i++)
		{
			if ($s1[$iLen1 - $i - 1] !== $s2[$iLen2 - $i - 1]) return array($iLen1 - $i, $iLen2 - $i);
		}
		return false;
	}

	protected static function text_cmp_html($sText1, $sText2, $sHighlight)
	{
		$iDiffPos = self::first_diff_line($sText1, $sText2);
		$sDisp1 = self::highlight_line($sText1, $iDiffPos, '<div style="'.$sHighlight.'">', '</div>');
		$sDisp2 = self::highlight_line($sText2, $iDiffPos, '<div style="'.$sHighlight.'">', '</div>');
		echo "<table style=\"valign=top;\">\n";
		echo "<tr>\n";
		echo "<td><pre>$sDisp1</pre></td>\n";
		echo "<td><pre>$sDisp2</pre></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}

	protected static function string_cmp_html($s1, $s2, $sHighlight)
	{
		$iDiffPos = self::first_diff($s1, $s2);
		if ($iDiffPos === false)
		{
			echo "strings are identical";
			return;
		}
		$sStart = substr($s1, 0, $iDiffPos);

		$aLastDiff = self::last_diff($s1, $s2);
		$sEnd = substr($s1, $aLastDiff[0]);

		$sMiddle1 = substr($s1, $iDiffPos, $aLastDiff[0] - $iDiffPos);
		$sMiddle2 = substr($s2, $iDiffPos, $aLastDiff[1] - $iDiffPos);
		
		echo "<p>$sStart<span style=\"$sHighlight\">$sMiddle1</span>$sEnd</p>\n";
		echo "<p>$sStart<span style=\"$sHighlight\">$sMiddle2</span>$sEnd</p>\n";
	}

	protected static function object_cmp_html($oObj1, $oObj2, $sHighlight)
	{
		$sObj1 = self::var_dump_string($oObj1);
		$sObj2 = self::var_dump_string($oObj2);
		return self::text_cmp_html($sObj1, $sObj2, $sHighlight);
	}

	public static function var_cmp_html($var1, $var2, $sHighlight = 'color:red; font-weight:bold;')
	{
		if (is_object($var1))
		{
			return self::object_cmp_html($var1, $var2, $sHighlight);
		}
		else if (count(explode("\n", $var1)) > 1)
		{
			// multiline string
			return self::text_cmp_html($var1, $var2, $sHighlight);
		}
		else
		{
			return self::string_cmp_html($var1, $var2, $sHighlight);
		}
	}

	public static function get_callstack($iLevelsToIgnore = 0, $aCallStack = null)
	{
		if ($aCallStack == null) $aCallStack = debug_backtrace();
		
		$aCallStack = array_slice($aCallStack, $iLevelsToIgnore);
	
		$aDigestCallStack = array();
		$bFirstLine = true;		
		foreach ($aCallStack as $aCallInfo)
		{
			$sLine = empty($aCallInfo['line']) ? "" : $aCallInfo['line'];
			$sFile = empty($aCallInfo['file']) ? "" : $aCallInfo['file'];
			if ($sFile != '')
			{
				$sFile = str_replace('\\', '/', $sFile);
				$sAppRoot = str_replace('\\', '/', APPROOT);
				$iPos = strpos($sFile, $sAppRoot);
				if ($iPos !== false)
				{
					$sFile = substr($sFile, strlen($sAppRoot));
				}
			}
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
						$a = Str::pure2html(self::beautifulstr($a, 64, true, false));
						$args .= "\"$a\"";
						break;
					case 'array':
						$args .= 'array('.count($a).')';
						break;
					case 'object':
						$args .= 'Object('.get_class($a).')';
						break;
					case 'resource':
						$args .= 'Resource('.strstr($a, '#').')';
						break;
					case 'boolean':
						$args .= $a ? 'true' : 'false';
						break;
					case 'NULL':
						$args .= 'null';
						break;
					default:
						$args .= 'Unknown';
					}
				}
				$sFunctionInfo = "$sClass$sType$sFunction($args)";
			}
			$aDigestCallStack[] = array('File'=>$sFile, 'Line'=>$sLine, 'Function'=>$sFunctionInfo);
		}
		return $aDigestCallStack;
	}

	public static function get_callstack_html($iLevelsToIgnore = 0, $aCallStack = null)
	{
		$aDigestCallStack = self::get_callstack($iLevelsToIgnore, $aCallStack);
		return self::make_table_from_assoc_array($aDigestCallStack);
	}

	public static function dump_callstack($iLevelsToIgnore = 0, $aCallStack = null)
	{
		return self::get_callstack_html($iLevelsToIgnore, $aCallStack);
	}

	public static function get_callstack_text($iLevelsToIgnore = 0, $aCallStack = null)
	{
		$aDigestCallStack = self::get_callstack($iLevelsToIgnore, $aCallStack);
		$aRes = array();
		foreach ($aDigestCallStack as $aCall)
		{
			$aRes[] = $aCall['File'].' at '.$aCall['Line'].', '.$aCall['Function'];
		}
		return implode("\n", $aRes);
	}

	///////////////////////////////////////////////////////////////////////////////
	// Source: New
	// Last modif: 2004/12/20 RQU
	///////////////////////////////////////////////////////////////////////////////
	public static function make_table_from_assoc_array(&$aData)
	{
		if (!is_array($aData)) throw new CoreException("make_table_from_assoc_array: Error - the passed argument is not an array");
		$aFirstRow = reset($aData);
		if (count($aData) == 0) return '';
		if (!is_array($aFirstRow)) throw new CoreException("make_table_from_assoc_array: Error - the passed argument is not a bi-dimensional array");
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
		if (!is_string($sLongString)) throw new CoreException("beautifulstr: expect a string as 1st argument");
	
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
		// Check for HTML entities, but be careful the DB is in UTF-8
		return $maxLength                                         
			? htmlentities(substr($pure, 0, $maxLength), ENT_QUOTES, 'UTF-8')
			: htmlentities($pure, ENT_QUOTES, 'UTF-8');
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
		return self::xmlentities(iconv("UTF-8", "UTF-8//IGNORE",$string));
	}

	public static function islowcase($sString)
	{
		return (strtolower($sString) == $sString);
	}
}

?>
