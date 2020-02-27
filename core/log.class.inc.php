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
 * @since 2.7.0 N°2518
 */
interface ILogFileNameBuilder
{
	/**
	 * @param string $sFileFullPath full path name for the log file
	 */
	public function __construct($sFileFullPath);

	/**
	 * @return string log file path we will write new log entry to
	 */
	public function GetLogFilePath();
}

class DefaultLogFileNameBuilder implements ILogFileNameBuilder
{
	private $sLogFileFullPath;

	/**
	 * @inheritDoc
	 */
	public function __construct($sFileFullPath)
	{
		$this->sLogFileFullPath = $sFileFullPath;
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
 * @since 2.7.0 N°2518
 */
abstract class RotatingLogFileNameBuilder implements ILogFileNameBuilder
{
	/** @var bool */
	protected static $bFileCheckDone = false;
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
	public function __construct($sFileFullPath)
	{
		$this->sLogFileFullPath = $sFileFullPath;

		$aPathParts = pathinfo($sFileFullPath);

		$this->sFilePath = $aPathParts['dirname'];
		$this->sFileBaseName = $aPathParts['filename'];
		$this->sFileExtension = $aPathParts['extension'];
	}

	/**
	 * @inheritDoc
	 */
	public function GetLogFilePath()
	{
		$this->CheckAndRotateLogFile();
		return $this->sLogFileFullPath;
	}

	/**
	 * Check log last date modified. If too old then rotate the log file (move it to a new name with a suffix)
	 *
	 * @uses \filemtime() to get log file date last modified
	 * @uses \iTopMutex during the whole check
	 * @uses ShouldRotate to check if we need to rotate
	 * @uses GetFileSuffix if we need to rotate, the suffix in the target rotated log filename
	 * @throws \Exception
	 */
	protected function CheckAndRotateLogFile()
	{
		if (static::$bFileCheckDone)
		{
			return;
		}

		$oMutex = new iTopMutex('log_rotate_'.$this->sFileBaseName);
		$oMutex->Lock();
		$iLogDateLastModifiedTimeStamp = filemtime($this->sLogFileFullPath);
		$oLogDateLastModified = DateTime::createFromFormat('U', $iLogDateLastModifiedTimeStamp);
		$oNow = new DateTime();
		$bShouldRotate = $this->ShouldRotate($oLogDateLastModified, $oNow);
		static::$bFileCheckDone = true;
		if (!$bShouldRotate)
		{
			$oMutex->Unlock();
			return;
		}

		$sFileSuffix = $this->GetFileSuffix();
		$sNewLogFileName =
					$this->sFilePath.DIRECTORY_SEPARATOR
					.$this->sFileBaseName
					.'.'.$sFileSuffix
					.'.'.$this->sFileExtension;

		rename($this->sLogFileFullPath, $sNewLogFileName);

		$oMutex->UnLock();
	}

	/**
	 * @param DateTime $oLogDateLastModified date when the log file was last modified
	 * @param DateTime $oNow date/time of the log we want to write
	 *
	 * @return bool true if the file has older informations and we need to move it to an archive (rotate), false if we don't have to
	 */
	abstract public function ShouldRotate($oLogDateLastModified, $oNow);

	/**
	 * @return string suffix for the rotated log file
	 */
	abstract protected function GetFileSuffix();
}

/**
 * @since 2.7.0 N°2518
 */
class DailyRotatingLogFileNameBuilder extends RotatingLogFileNameBuilder
{
	/**
	 * @inheritDoc
	 */
	protected function GetFileSuffix()
	{
		return date('Y-m-d');
	}

	/**
	 * @inheritDoc
	 */
	public function ShouldRotate($oLogDateLastModified, $oNow)
	{
		$oInterval = $oNow->diff($oLogDateLastModified);
		$iDaysDiff = $oInterval->d;

		return $iDaysDiff > 0;
	}
}

/**
 * @since 2.7.0 N°2518
 */
class WeeklyRotatingLogFileNameBuilder extends RotatingLogFileNameBuilder
{
	/**
	 * @inheritDoc
	 */
	protected function GetFileSuffix()
	{
		$sWeekYear = date('o');
		$sWeekNumber = date('W');

		return $sWeekYear.'-week'.$sWeekNumber;
	}

	/**
	 * @inheritDoc
	 */
	public function ShouldRotate($oLogDateLastModified, $oNow)
	{
		$iLogYear = $oLogDateLastModified->format('Y');
		$iLogWeek = $oLogDateLastModified->format('W');
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
	 * @return \ILogFileNameBuilder
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public static function GetInstance($sFileFullPath)
	{
		$oConfig = utils::GetConfig();
		$sFileNameBuilderImpl = $oConfig->Get('log_filename_builder_impl');
		if (empty($sFileNameBuilderImpl) || !class_exists($sFileNameBuilderImpl))
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
 * @since 2.7.0 allow to rotate file (N°2518)
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
	 * @return mixed|null
	 */
	private static function GetMinLogLevel($sChannel)
	{
		$oConfig = (static::$m_oMockMetaModelConfig !== null) ? static::$m_oMockMetaModelConfig :  \MetaModel::GetConfig();
		if (!$oConfig instanceof Config)
		{
			return self::LEVEL_OK;
		}

		$sLogLevelMin = $oConfig->Get('log_level_min');

		if (empty($sLogLevelMin))
		{
			return self::LEVEL_OK;
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

		return self::LEVEL_OK;
	}

}

class SetupLog extends LogAPI
{
	const CHANNEL_DEFAULT = 'SetupLog';

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
