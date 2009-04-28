<?php
/**
 * Helper class to allow modal-style dialog box in an html form
 *
 * Possible improvement: do not use _SESSION for the caller's data,
 *  instead set a member variable with caller information
 *  and take the opportunity of the first edit button to place the information
 *  into a hidden field   
 *
 * Usage:
 */

define('DLGSTACK_OK', 1);
define('DLGSTACK_CANCEL', 2);

//session_name("dialogstack");
session_start();


class dialogstack
{
	private static $m_bCurrPageDeclared = false;
	/**
	 * Declare the current page as being a dialog issuer, potentially pop...
	 */
	static public function DeclareCaller($sTitle)
	{
		self::$m_bCurrPageDeclared = false;
		$_SESSION['dialogstack_calleruri'] = $_SERVER["REQUEST_URI"];
		$_SESSION['dialogstack_callertitle'] = $sTitle;

		if (isset($_POST["dialogstackpop"]) && ($_POST["dialogstackpop"] == count($_SESSION['dialogstack_currdlg'])))
		{
			// Pop !
			array_pop($_SESSION['dialogstack_currdlg']);
		} 
	}

	/**
	 * True if the current page has been loaded from an "dialog startup button"
	 */
	static private function GetRetArgName()
	{
		foreach($_REQUEST as $sArgName=>$sArgValue)
		{
			if (strstr($sArgName, "dlgstack_go,"))
			{
				$aTokens = explode(",", $sArgName);
				return self::ArgNameDecode($aTokens[1]);
			}
		}
		return "";
	}

	/**
	 * Protect against weird effects of PHP interpreting brackets...
	 */
	static private function ArgNameEncode($sArgName)
	{
		return str_replace(array('[', ']'), array('_bracket_open_', '_bracket_close_'), $sArgName);
	}
	static private function ArgNameDecode($sCodedArgName)
	{
		return str_replace(array('_bracket_open_', '_bracket_close_'), array('[', ']'), $sCodedArgName);
	}

	/**
	 * True if the current page has been loaded from an "dialog startup button"
	 */
	static public function IsDialogStartup()
	{
		return (strlen(self::GetRetArgName()) > 0);
	}


	/**
	 * Helper to  
	 */
	static private function RemoveArg(&$aValues, $sKey, &$retval = null)
	{
		if (isset($aValues[$sKey]))
		{
			if (empty($retval))
			{
				$retval = $aValues[$sKey];
			}
			unset($aValues[$sKey]);
		}
	}
	

	/**
	 * Record current page args, and returns the initial value for the dialog 
	 */
	static public function StartDialog()
	{
		if (!isset($_SESSION['dialogstack_currdlg']))
		{
			// Init stack
			$_SESSION['dialogstack_currdlg'] = array();
		}

		$sRetArgName = self::GetRetArgName();
		$sCodedArgName = self::ArgNameEncode($sRetArgName);

		$sArgForRetArgName = "dlgstack_init_".$sCodedArgName;
		$sButtonName = "dlgstack_go,".$sCodedArgName;

		// Do not record utility arguments, neither the current value (stored separately)
		//
		$initValue = null;
		$aPost = $_POST;
		self::RemoveArg($aPost, $sArgForRetArgName, $initValue);
		self::RemoveArg($aPost, $sButtonName);
		self::RemoveArg($aPost, 'dlgstack_onok_page', $sOnOKPage);
		self::RemoveArg($aPost, 'dlgstack_onok_args', $aOnOKArgs);
		$aGet = $_GET;
		self::RemoveArg($aGet, $sArgForRetArgName, $initValue);
		self::RemoveArg($aGet, $sButtonName);
		self::RemoveArg($aGet, 'dlgstack_onok_page', $sOnOKPage);
		self::RemoveArg($aGet, 'dlgstack_onok_args', $aOnOKArgs);

		if (self::$m_bCurrPageDeclared)
		{
			throw new Exception("DeclareCaller() must not be called before StartDialog()");
		}

		$aCall = array(
				"title"=>$_SESSION['dialogstack_callertitle'],
				"uri"=>$_SESSION['dialogstack_calleruri'],
				"post"=>$aPost,
				"get"=>$aGet,
				"retarg"=>$sRetArgName,
				"initval"=>$initValue,
		);
		if (isset($sOnOKPage)) $aCall["onok_page"] = $sOnOKPage;
		if (isset($aOnOKArgs)) $aCall["onok_args"] = $aOnOKArgs;

		array_push($_SESSION['dialogstack_currdlg'], $aCall);
		return $initValue;
	}
	/**
	 * Render a button to launch a new dialog
	 */
	static public function RenderEditableField($sTitle, $sArgName, $sCurrValue, $bAddFieldValue, $sOnOKPage = "", $aOnOKArgs = array())
	{
		$sRet = "";
		$sCodedArgName = self::ArgNameEncode($sArgName);
		if ($bAddFieldValue)
		{
			$sRet .= "<input type=\"hidden\" name=\"$sArgName\" value=\"$sCurrValue\">\n";
		}
		$sRet .= "<input type=\"hidden\" name=\"dlgstack_init_$sCodedArgName\" value=\"$sCurrValue\">\n";
		$sRet .= "<input type=\"submit\" name=\"dlgstack_go,$sCodedArgName\" value=\"$sTitle\">\n";
		if (!empty($sOnOKPage))
		{
			$sRet .= "<input type=\"hidden\" name=\"dlgstack_onok_page\" value=\"$sCurrValue\">\n";
		}
		foreach($aOnOKArgs as $sArgName=>$value)
		{
			$sRet .= "<input type=\"hidden\" name=\"dlgstack_onok_args[$sArgName]\" value=\"$value\">\n";
		}
		return $sRet;
	}
	/**
	 * Render a [set of] hidden field, from a value that may be an array
	 */
	static private function RenderHiddenField($sName, $value)
	{
		$sRet = "";
		if (is_array($value))
		{
			foreach($value as $sKey=>$subvalue)
			{
				$sRet .= self::RenderHiddenField($sName.'['.$sKey.']', $subvalue);
			}
		}
		else
		{
			$sRet .= "<input type=\"hidden\" name=\"$sName\" value=\"$value\">\n";
		}
		return $sRet;
	}
	/**
	 * Render a form to end the current dialog and return to the caller
	 */
	static public function RenderEndDialogForm($iButtonStyle, $sTitle, $sRetValue = null)
	{
		$aCall = end($_SESSION['dialogstack_currdlg']);
		if (!$aCall) return;
		return self::privRenderEndDialogForm($aCall, $iButtonStyle, $sTitle, $sRetValue);
	}

	/**
	 * Returns an array of buttons to get back to upper dialog levels
	 */
	static public function GetCurrentStack()
	{
		$aRet = array();
		if (isset($_SESSION['dialogstack_currdlg']))
		{
			foreach ($_SESSION['dialogstack_currdlg'] as $aCall)
			{
				$aRet[] = self::privRenderEndDialogForm($aCall, DLGSTACK_CANCEL, $aCall["title"]);
			}
		}
		return $aRet;
	}

	/**
	 * Render a form to end the current dialog and return to the caller
	 */
	static private function privRenderEndDialogForm($aCall, $iButtonStyle, $sTitle, $sRetValue = null)
	{
		if (($iButtonStyle == DLGSTACK_OK) && isset($aCall["onok_page"])) $sFormAction = $aCall["onok_page"];
		else                                                              $sFormAction = $aCall["uri"];  

		$sRet = "<form method=\"post\" action=\"$sFormAction\">\n";
		foreach ($aCall["post"] as $sName=>$value)
		{
			$sRet .= self::RenderHiddenField($sName, $value);
		}
		if ($iButtonStyle == DLGSTACK_OK)
		{
			if (isset($aCall["onok_args"]))
			{
				foreach($aCall["onok_args"] as $sArgName=>$value)
				{
					$sRet .= "<input type=\"hidden\" name=\"$sArgName\" value=\"$value\">\n";
				}
			}
			$sRet .= "<input type=\"hidden\" name=\"".$aCall["retarg"]."\" value=\"$sRetValue\">\n";
			$sRet .= "<input type=\"submit\" name=\"dlgstackOK\" value=\"$sTitle, (OK) Back to ".$aCall["title"]."\">\n";
		}
		elseif ($iButtonStyle == DLGSTACK_CANCEL)
		{
			if (!is_null($aCall["initval"]))
			{
				$sRet .= "<input type=\"hidden\" name=\"".$aCall["retarg"]."\" value=\"".$aCall["initval"]."\">\n";
			}
			$sRet .= "<input type=\"submit\" name=\"dlgstackCANCEL\" value=\"$sTitle\">\n";
		}
		else
		{
			throw new Exception("Wrong value for button style ($iButtonStyle)");		
		}
		$sRet .= "<input type=\"hidden\" name=\"dialogstackpop\" value=\"".count($_SESSION['dialogstack_currdlg'])."\">\n";
		$sRet .= "</form>\n";
		return $sRet;
	}
}
?>
