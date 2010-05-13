<?php
/**
 * Log
 * logging to files
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

class FileLog
{
	protected $m_sFile = ''; // log is disabled if this is empty

	public function __construct($sFileName = '')
	{
		$this->m_sFile = $sFileName;
	}

	public function Error($sText)
	{
		self::Write("Error | ".$sText);
	}

	public function Warning($sText)
	{
		self::Write("Warning | ".$sText);
	}

	public function Info($sText)
	{
		self::Write("Info | ".$sText);
	}

	public function Ok($sText)
	{
		self::Write("Ok | ".$sText);
	}

	protected function Write($sText)
	{
		if (strlen($this->m_sFile) == 0) return;

		$hLogFile = @fopen($this->m_sFile, 'a');
		if ($hLogFile !== false)
		{
			$sDate = date('Y-m-d H:i:s');
			fwrite($hLogFile, "$sDate | $sText\n");
			fclose($hLogFile);
		}
	}
}

class SetupLog
{
	protected static $m_oFileLog; 

	public static function Enable($sTargetFile)
	{
		self::$m_oFileLog = new FileLog($sTargetFile);
	}
	public static function Error($sText)
	{
		self::$m_oFileLog->Error($sText);
	}
	public static function Warning($sText)
	{
		self::$m_oFileLog->Warning($sText);
	}
	public static function Info($sText)
	{
		self::$m_oFileLog->Info($sText);
	}
	public static function Ok($sText)
	{
		self::$m_oFileLog->Ok($sText);
	}
}

class IssueLog
{
	protected static $m_oFileLog; 

	public static function Enable($sTargetFile)
	{
		self::$m_oFileLog = new FileLog($sTargetFile);
	}
	public static function Error($sText)
	{
		self::$m_oFileLog->Error($sText);
	}
	public static function Warning($sText)
	{
		self::$m_oFileLog->Warning($sText);
	}
	public static function Info($sText)
	{
		self::$m_oFileLog->Info($sText);
	}
	public static function Ok($sText)
	{
		self::$m_oFileLog->Ok($sText);
	}
}
?>
