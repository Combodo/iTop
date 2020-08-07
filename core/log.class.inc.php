<?php
// Copyright (C) 2010-2017 Combodo SARL
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
 * @since 2.7.0 N°2518 N°2793
 */
interface iLogFileNameBuilder
{
	/**
	 * @param string $sLogFileFullPath full path name for the log file
	 */
	public function __construct($sLogFileFullPath = null);

	/**
	 * @return string log file path we will write new log entry to
	 */
	public function GetLogFilePath();
}

class DefaultLogFileNameBuilder implements iLogFileNameBuilder
{
	private $sLogFileFullPath;

	/**
	 * @inheritDoc
	 */
	public function __construct($sLogFileFullPath = null)
	{
		$this->sLogFileFullPath = $sLogFileFullPath;
	}

	/**
	 * @inheritDoc
	 */
	public function GetLogFilePath()
	{
		return $this->sLogFileFullPath;
	}
}

/**
 * Adds a suffix to the filename
 *
 * @since 2.7.0 N°2518 N°2793
 */
abstract class RotatingLogFileNameBuilder implements iLogFileNameBuilder
{
	/**
	 * Test is done each time to cover edge case like session beginning at 23:59 and ending at 00:01
	 * We are caching the file mtime though
	 * @var array with full file path as key and DateTime (file last modification time) as value
	 */
	protected static $aLogFileLastModified = array();
	/** @var string */
	protected $sLogFileFullPath;
	/** @var string */
	protected $sFilePath;
	/** @var string */
	protected $sFileBaseName;
	/** @var string */
	protected $sFileExtension;

	/**
	 * @inheritDoc
	 */
	public function __construct($sLogFileFullPath = null)
	{
		$this->sLogFileFullPath = $sLogFileFullPath;
	}

	protected function GetLastModifiedDateForFile()
	{
		if (isset(static::$aLogFileLastModified[$this->sLogFileFullPath]))
		{
			return static::$aLogFileLastModified[$this->sLogFileFullPath];
		}

		return null;
	}

	protected function SetLastModifiedDateForFile($oDateTime)
	{
		static::$aLogFileLastModified[$this->sLogFileFullPath] = $oDateTime;
	}

	/**
	 * Need to be called when the file is rotated : actually the next call will need to check on the real date modified instead of using
	 * the previously  cached value !
	 */
	public function ResetLastModifiedDateForFile()
	{
		static::$aLogFileLastModified[$this->sLogFileFullPath] = null;
	}

	/**
	 * @inheritDoc
	 *
	 * Doing the check before opening and writing the log file. There is also a iProcess but cron can be disabled...
	 *
	 * @see \LogFileRotationProcess the iProcess impl
	 */
	public function GetLogFilePath()
	{
		$this->CheckAndRotateLogFile();
		return $this->sLogFileFullPath;
	}

	/**
	 * Check log last date modified. If too old then rotate the log file (move it to a new name with a suffix)
	 *
	 * @uses filemtime() to get log file date last modified
	 */
	public function CheckAndRotateLogFile()
	{
		$oConfig = utils::GetConfig();
		$sItopTimeZone = $oConfig->Get('timezone');
		$timezone = new DateTimeZone($sItopTimeZone);

		if ($this->GetLastModifiedDateForFile() === null)
		{
			if (!$this->IsLogFileExists())
			{
				return;
			}

			$iLogDateLastModifiedTimeStamp = filemtime($this->sLogFileFullPath);
			if ($iLogDateLastModifiedTimeStamp === false)
			{
				return;
			}
			$oDateTime = DateTime::createFromFormat('U', $iLogDateLastModifiedTimeStamp);
			$oDateTime->setTimezone($timezone);
			$this->SetLastModifiedDateForFile($oDateTime);
		}

		$oNow = new DateTime('now', $timezone);
		$bShouldRotate = $this->ShouldRotate($this->GetLastModifiedDateForFile(), $oNow);
		if (!$bShouldRotate)
		{
			return;
		}

		$this->RotateLogFile($this->GetLastModifiedDateForFile());
	}

	/**
	 * Rotate current log file
	 *
	 * @param DateTime $oLogFileLastModified date when the log file was last modified
	 *
	 * @uses \iTopMutex instead of flock as doing a rename on a file with a flock cause an error on PHP 5.6.40 Windows (ok on 7.3.15 though)
	 * @uses GetRotatedFileName to get rotated file name
	 */
	protected function RotateLogFile($oLogFileLastModified)
	{
		if (!$this->IsLogFileExists()) // extra check, but useful for cron also !
		{
			return;
		}

		$oLock = null;
		try
		{
			$oLock = new iTopMutex('log_rotation_'.$this->sLogFileFullPath);
			$oLock->Lock();
			if (!$this->IsLogFileExists()) // extra extra check if we were blocked and another process moved the file in the meantime
			{
				$oLock->Unlock();
				return;
			}
			$this->ResetLastModifiedDateForFile();
			$sNewLogFileName = $this->GetRotatedFileName($oLogFileLastModified);
			rename($this->sLogFileFullPath, $sNewLogFileName);
		}
		catch (Exception $e)
		{
			// nothing to do, cannot log... file will be renamed on the next call O:)
			return;
		}
		finally
		{
			if (!is_null($oLock)) { $oLock->Unlock();}
		}
	}

	/**
	 * @param DateTime $oLogFileLastModified date when the log file was last modified
	 *
	 * @return string the full path of the rotated log file
	 * @uses static::$oLogFileLastModified
	 * @uses GetFileSuffix
	 */
	public function GetRotatedFileName($oLogFileLastModified)
	{
		$aPathParts = pathinfo($this->sLogFileFullPath);
		$this->sFilePath = $aPathParts['dirname'];
		$this->sFileBaseName = $aPathParts['filename'];
		$this->sFileExtension = $aPathParts['extension'];

		$sFileSuffix = $this->GetFileSuffix($oLogFileLastModified);

		return $this->sFilePath.DIRECTORY_SEPARATOR
			.$this->sFileBaseName
			.'.'.$sFileSuffix
			.'.'.$this->sFileExtension;
	}

	/**
	 * @return bool true if file exists and is readable
	 */
	public function IsLogFileExists()
	{
		if (!file_exists($this->sLogFileFullPath))
		{
			return false;
		}

		if (!is_readable($this->sLogFileFullPath))
		{
			return false;
		}

		return true;
	}

	/**
	 * **Warning :** both DateTime params must have the same timezone set ! Should use the iTop timezone ('timezone' config parameter)
	 *
	 * @param DateTime $oLogFileLastModified date when the log file was last modified
	 * @param DateTime $oNow date/time of the log we want to write
	 *
	 * @return bool true if the file has older informations and we need to move it to an archive (rotate), false if we don't have to
	 */
	abstract public function ShouldRotate($oLogFileLastModified, $oNow);

	/**
	 * @param DateTime $oDate log file last modification date
	 *
	 * @return string suffix for the rotated log file
	 */
	abstract protected function GetFileSuffix($oDate);

	/**
	 * @see \LogFileRotationProcess
	 *
	 * @param \DateTime $oNow current date
	 *
	 * @return DateTime time when the cron process should run
	 */
	abstract public function GetCronProcessNextOccurrence(DateTime $oNow);
}

/**
 * @since 2.7.0 N°2518 N°2793
 */
class DailyRotatingLogFileNameBuilder extends RotatingLogFileNameBuilder
{
	/**
	 * @inheritDoc
	 */
	protected function GetFileSuffix($oDate)
	{
		return $oDate->format('Y-m-d');
	}

	/**
	 * @inheritDoc
	 */
	public function ShouldRotate($oLogFileLastModified, $oNow)
	{
		$iLogYear = $oLogFileLastModified->format('Y');
		$iLogDay = $oLogFileLastModified->format('z');
		$iNowYear = $oNow->format('Y');
		$iNowDay = $oNow->format('z');

		if ($iLogYear !== $iNowYear)
		{
			return true;
		}

		if ($iLogDay !== $iNowDay)
		{
			return true;
		}

		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function GetCronProcessNextOccurrence(DateTime $oNow)
	{
		$oOccurrence = clone $oNow;
		$oOccurrence->modify('tomorrow midnight');

		return $oOccurrence;
	}
}

/**
 * @since 2.7.0 N°2518 N°2793
 */
class WeeklyRotatingLogFileNameBuilder extends RotatingLogFileNameBuilder
{
	/**
	 * @inheritDoc
	 */
	protected function GetFileSuffix($oDate)
	{
		$sWeekYear = $oDate->format('o');
		$sWeekNumber = $oDate->format('W');

		return $sWeekYear.'-week'.$sWeekNumber;
	}

	/**
	 * @inheritDoc
	 */
	public function ShouldRotate($oLogFileLastModified, $oNow)
	{
		$iLogYear = $oLogFileLastModified->format('Y');
		$iLogWeek = $oLogFileLastModified->format('W');
		$iNowYear = $oNow->format('Y');
		$iNowWeek = $oNow->format('W');

		if ($iLogYear !== $iNowYear)
		{
			return true;
		}

		if ($iLogWeek !== $iNowWeek)
		{
			return true;
		}

		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function GetCronProcessNextOccurrence(DateTime $oNow)
	{
		$oOccurrence = clone $oNow;
		$oOccurrence->modify('Monday next week midnight');

		return $oOccurrence;
	}
}

/**
 * @since 2.7.0 N°2820
 */
class MonthlyRotatingLogFileNameBuilder extends RotatingLogFileNameBuilder
{
	/**
	 * @inheritDoc
	 */
	public function ShouldRotate($oLogFileLastModified, $oNow)
	{
		$iLogYear = $oLogFileLastModified->format('Y');
		$iLogMonth = $oLogFileLastModified->format('n');
		$iNowYear = $oNow->format('Y');
		$iNowMonth = $oNow->format('n');

		if ($iLogYear !== $iNowYear)
		{
			return true;
		}

		if ($iLogMonth !== $iNowMonth)
		{
			return true;
		}

		return false;
	}

	/**
	 * @inheritDoc
	 */
	protected function GetFileSuffix($oDate)
	{
		$sWeekYear = $oDate->format('o');
		$sWeekNumber = $oDate->format('m');

		return $sWeekYear.'-month'.$sWeekNumber;
	}

	/**
	 * @inheritDoc
	 */
	public function GetCronProcessNextOccurrence(DateTime $oNow)
	{
		$oOccurrence = clone $oNow;
		$oOccurrence->modify('first day of next month midnight');

		return $oOccurrence;
	}
}

/**
 * @since 2.7.0 N°2518
 */
class LogFileNameBuilderFactory
{
	/**
	 * Uses the 'log_filename_builder_impl' config parameter
	 *
	 * @param string $sFileFullPath
	 *
	 * @return \iLogFileNameBuilder
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public static function GetInstance($sFileFullPath)
	{
		$oConfig = utils::GetConfig();
		$sFileNameBuilderImpl = $oConfig->Get('log_filename_builder_impl');
		if (!is_a($sFileNameBuilderImpl, iLogFileNameBuilder::class, true))
		{
			$sFileNameBuilderImpl = 'DefaultLogFileNameBuilder';
		}

		return new $sFileNameBuilderImpl($sFileFullPath);
	}
}


/**
 * File logging
 *
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @since 2.7.0 N°2518 N°2793 file log rotation
 */
class FileLog
{
	protected $oFileNameBuilder;

	/**
	 * FileLog constructor.
	 *
	 * @param string $sFileName
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function __construct($sFileName = '')
	{
		$this->oFileNameBuilder = LogFileNameBuilderFactory::GetInstance($sFileName);
	}

	public function Error($sText, $sChannel = '', $aContext = array())
	{
		$this->Write($sText, __FUNCTION__, $sChannel, $aContext);
	}

	public function Warning($sText, $sChannel = '', $aContext = array())
	{
		$this->Write($sText, __FUNCTION__, $sChannel, $aContext);
	}

	public function Info($sText, $sChannel = '', $aContext = array())
	{
		$this->Write($sText, __FUNCTION__, $sChannel, $aContext);
	}

	public function Ok($sText, $sChannel = '', $aContext = array())
	{
		$this->Write($sText, __FUNCTION__, $sChannel, $aContext);
	}

	public function Debug($sText, $sChannel = '', $aContext = array())
	{
		$this->Write($sText, __FUNCTION__, $sChannel, $aContext);
	}

	public function Trace($sText, $sChannel = '', $aContext = array())
	{
		$this->Write($sText, __FUNCTION__, $sChannel, $aContext);
	}


	protected function Write($sText, $sLevel = '', $sChannel = '', $aContext = array())
	{
		$sTextPrefix = empty($sLevel) ? '' : (str_pad($sLevel, 7).' | ');
		$sTextSuffix = empty($sChannel) ? '' : " | $sChannel";
		$sText = "{$sTextPrefix}{$sText}{$sTextSuffix}";
		$sLogFilePath = $this->oFileNameBuilder->GetLogFilePath();

		if (empty($sLogFilePath))
		{
			return;
		}

		$hLogFile = @fopen($sLogFilePath, 'a');
		if ($hLogFile !== false)
		{
			flock($hLogFile, LOCK_EX);
			$sDate = date('Y-m-d H:i:s');
			if (empty($aContext))
			{
				fwrite($hLogFile, "$sDate | $sText\n");
			}
			else
			{
				$sContext = var_export($aContext, true);
				fwrite($hLogFile, "$sDate | $sText\n$sContext\n");
			}
			fflush($hLogFile);
			flock($hLogFile, LOCK_UN);
			fclose($hLogFile);
		}
	}
}

abstract class LogAPI
{
	const CHANNEL_DEFAULT   = '';

	const LEVEL_ERROR       = 'Error';
	const LEVEL_WARNING     = 'Warning';
	const LEVEL_INFO        = 'Info';
	const LEVEL_OK          = 'Ok';
	const LEVEL_DEBUG       = 'Debug';
	const LEVEL_TRACE       = 'Trace';
	/**
	 * @var string default log level, can be overrided
	 * @see GetMinLogLevel
	 * @since 2.7.1 N°2977
	 */
	const LEVEL_DEFAULT     = self::LEVEL_OK;

	protected static $aLevelsPriority = array(
		self::LEVEL_ERROR   => 400,
		self::LEVEL_WARNING => 300,
		self::LEVEL_INFO    => 200,
		self::LEVEL_OK      => 200,
		self::LEVEL_DEBUG   => 100,
		self::LEVEL_TRACE   =>  50,
	);

	protected static $m_oMockMetaModelConfig = null;

	public static function Enable($sTargetFile)
	{
		// m_oFileLog is not defined as a class attribute so that each impl will have its own
		static::$m_oFileLog = new FileLog($sTargetFile);
	}

	public static function MockStaticObjects($oFileLog, $oMetaModelConfig=null)
	{
		static::$m_oFileLog = $oFileLog;
		static::$m_oMockMetaModelConfig = $oMetaModelConfig;
	}

	public static function Error($sMessage, $sChannel = null, $aContext = array())
	{
		static::Log(self::LEVEL_ERROR, $sMessage, $sChannel, $aContext);
	}

	public static function Warning($sMessage, $sChannel = null, $aContext = array())
	{
		static::Log(self::LEVEL_WARNING, $sMessage, $sChannel, $aContext);
	}

	public static function Info($sMessage, $sChannel = null, $aContext = array())
	{
		static::Log(self::LEVEL_INFO, $sMessage, $sChannel, $aContext);
	}

	public static function Ok($sMessage, $sChannel = null, $aContext = array())
	{
		static::Log(self::LEVEL_OK, $sMessage, $sChannel, $aContext);
	}

	public static function Debug($sMessage, $sChannel = null, $aContext = array())
	{
		static::Log(self::LEVEL_DEBUG, $sMessage, $sChannel, $aContext);
	}

	public static function Trace($sMessage, $sChannel = null, $aContext = array())
	{
		static::Log(self::LEVEL_TRACE, $sMessage, $sChannel, $aContext);
	}

	public static function Log($sLevel, $sMessage, $sChannel = null, $aContext = array())
	{
		if (! static::$m_oFileLog)
		{
			return;
		}

		if (! isset(self::$aLevelsPriority[$sLevel]))
		{
			IssueLog::Error("invalid log level '{$sLevel}'");
			return;
		}

		if (is_null($sChannel))
		{
			$sChannel = static::CHANNEL_DEFAULT;
		}

		$sMinLogLevel = self::GetMinLogLevel($sChannel);

		if ($sMinLogLevel === false || $sMinLogLevel === 'false')
		{
			return;
		}
		if (is_string($sMinLogLevel))
		{
			if (! isset(self::$aLevelsPriority[$sMinLogLevel]))
			{
				throw new Exception("invalid configuration for log_level '{$sMinLogLevel}' is not within the list: ".implode(',', array_keys(self::$aLevelsPriority)));
			}
			elseif (self::$aLevelsPriority[$sLevel] < self::$aLevelsPriority[$sMinLogLevel])
			{
				//priority too low regarding the conf, do not log this
				return;
			}
		}

		static::$m_oFileLog->$sLevel($sMessage, $sChannel, $aContext);
	}

	/**
	 * @param $sChannel
	 *
	 * @return string one of the LEVEL_* const value
	 * @uses \LogAPI::LEVEL_DEFAULT
	 */
	private static function GetMinLogLevel($sChannel)
	{
		$oConfig = (static::$m_oMockMetaModelConfig !== null) ? static::$m_oMockMetaModelConfig :  \MetaModel::GetConfig();
		if (!$oConfig instanceof Config)
		{
			return static::LEVEL_DEFAULT;
		}

		$sLogLevelMin = $oConfig->Get('log_level_min');

		if (empty($sLogLevelMin))
		{
			return static::LEVEL_DEFAULT;
		}

		if (!is_array($sLogLevelMin))
		{
			return $sLogLevelMin;
		}

		if (isset($sLogLevelMin[$sChannel]))
		{
			return $sLogLevelMin[$sChannel];
		}

		if (isset($sLogLevelMin[static::CHANNEL_DEFAULT]))
		{
			return $sLogLevelMin[$sChannel];
		}

		return static::LEVEL_DEFAULT;
	}

}

class SetupLog extends LogAPI
{
	const CHANNEL_DEFAULT = 'SetupLog';
	/**
	 * @inheritDoc
	 *
	 * As this object is used during setup, without any conf file available, customizing the level can be done by changing this constant !
	 */
	const LEVEL_DEFAULT = self::LEVEL_INFO;

	protected static $m_oFileLog = null;
}

class IssueLog extends LogAPI
{
	const CHANNEL_DEFAULT = 'IssueLog';

	protected static $m_oFileLog = null;
}

class ToolsLog extends LogAPI
{
	const CHANNEL_DEFAULT = 'ToolsLog';

	protected static $m_oFileLog = null;
}

/**
 * @see \CMDBSource::LogDeadLock()
 * @since 2.7.1
 */
class DeadLockLog extends LogAPI
{
	const CHANNEL_WAIT_TIMEOUT = 'Deadlock-WaitTimeout';
	const CHANNEL_DEADLOCK_FOUND = 'Deadlock-Found';
	const CHANNEL_DEFAULT = self::CHANNEL_WAIT_TIMEOUT;

	/** @var \FileLog we want our own instance ! */
	protected static $m_oFileLog = null;

	public static function Enable($sTargetFile = null)
	{
		if (empty($sTargetFile))
		{
			$sTargetFile = APPROOT.'log/deadlocks.log';
		}
		parent::Enable($sTargetFile);
	}

	private static function GetChannelFromMysqlErrorNo($iMysqlErrorNo)
	{
		switch ($iMysqlErrorNo)
		{
			case 1205:
				return self::CHANNEL_WAIT_TIMEOUT;
				break;
			case 1213:
				return  self::CHANNEL_DEADLOCK_FOUND;
				break;
			default:
				return self::CHANNEL_DEFAULT;
				break;
		}
	}

	/**
	 * @param int $iMySQLErrNo will be converted to channel using {@link GetChannelFromMysqlErrorNo}
	 * @param string $sMessage
	 * @param null $iMysqlErroNo
	 * @param array $aContext
	 *
	 * @throws \Exception
	 */
	public static function Log($iMySQLErrNo, $sMessage, $iMysqlErroNo = null, $aContext = array())
	{
		$sChannel = self::GetChannelFromMysqlErrorNo($iMysqlErroNo);
		parent::Log($iMySQLErrNo, $sMessage, $sChannel, $aContext);
	}
}


class LogFileRotationProcess implements iScheduledProcess
{
	/**
	 * Cannot get this list from anywhere as log file name is provided by the caller using LogAPI::Enable
	 * @var string[]
	 */
	const LOGFILES_TO_ROTATE = array(
		'setup.log',
		'error.log',
		'tools.log',
		'itop-fence.log',
	);

	/**
	 * @inheritDoc
	 */
	public function Process($iUnixTimeLimit)
	{
		$sLogFileNameBuilder = $this->GetLogFileNameBuilderClassName();

		foreach (self::LOGFILES_TO_ROTATE as $sLogFileName)
		{
			$sLogFileFullPath = APPROOT
				.DIRECTORY_SEPARATOR.'log'
				.DIRECTORY_SEPARATOR.$sLogFileName;

			/** @var \RotatingLogFileNameBuilder $oLogFileNameBuilder */
			$oLogFileNameBuilder = new $sLogFileNameBuilder($sLogFileFullPath);
			$oLogFileNameBuilder->ResetLastModifiedDateForFile();
			$oLogFileNameBuilder->CheckAndRotateLogFile();
		}
	}

	/**
	 * @inheritDoc
	 */
	public function GetNextOccurrence()
	{
		try
		{
			$sLogFileNameBuilder = $this->GetLogFileNameBuilderClassName();
		}
		catch (ProcessException $e)
		{
			return new DateTime('3000-01-01');
		}

		/** @var \RotatingLogFileNameBuilder $oLogFileNameBuilder */
		$oLogFileNameBuilder = new $sLogFileNameBuilder();
		return $oLogFileNameBuilder->GetCronProcessNextOccurrence(new DateTime());
	}

	/**
	 * @return string RotatingLogFileNameBuilder implementation configured
	 * @throws \ProcessException if the class is invalid
	 */
	private function GetLogFileNameBuilderClassName()
	{
		$sLogFileNameBuilder = MetaModel::GetConfig()->Get('log_filename_builder_impl');
		if (is_a($sLogFileNameBuilder, RotatingLogFileNameBuilder::class, true))
		{
			return $sLogFileNameBuilder;
		}

		throw new ProcessException(self::class.' : The configured filename builder is invalid (log_filename_builder_impl="'.$sLogFileNameBuilder.'")');
	}
}