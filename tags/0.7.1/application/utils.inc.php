<?php

define('CONFIGFILE', '../config.txt');

class utils
{
	private static $m_aConfig = null;

	public static function ReadParam($sName, $defaultValue = "")
	{
		return isset($_REQUEST[$sName]) ? $_REQUEST[$sName] : $defaultValue;
	}
	
	public static function ReadPostedParam($sName, $defaultValue = "")
	{
		return isset($_POST[$sName]) ? $_POST[$sName] : $defaultValue;
	}
	
	public static function GetNewTransactionId()
	{
		// TO DO implement the real mechanism here
		return sprintf("%08x", rand(0,2000000000));
	}
	
	public static function IsTransactionValid($sId)
	{
		// TO DO implement the real mechanism here
		return true;
	}
	
	public static function ReadFromFile($sFileName)
	{
		if (!file_exists($sFileName)) return false;
		return file_get_contents($sFileName);
	}

	public static function ReadConfig()
	{
		self::$m_aConfig = array();

		$sConfigContents = self::ReadFromFile(CONFIGFILE);
		if (!$sConfigContents) trigger_error("Could not load file ".CONFIGFILE);

		foreach (explode("\n", $sConfigContents) as $sLine)
		{
			$sLine = trim($sLine);
			if (($iPos = strpos($sLine, '#')) !== false)
			{
				// strip out the end of the line right after the #
				$sLine = substr($sLine, 0, $iPos);
			}

			$aMatches = array();
			if (preg_match("@(\\S+.*)=\s*(\S+.*)@", $sLine, $aMatches))
			{
				$sParamName = trim($aMatches[1]);
				$sParamValue = trim($aMatches[2]);
				self::$m_aConfig[$sParamName] = $sParamValue; 
			}
		}
	}

	public static function GetConfig($sParamName, $defaultValue = "")
	{
		if (is_null(self::$m_aConfig))
		{
			self::ReadConfig();
		}

		if (array_key_exists($sParamName, self::$m_aConfig))
		{
			return self::$m_aConfig[$sParamName];
		}
		else
		{
			return $defaultValue;
		}
	}
}
?>
