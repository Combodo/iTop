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
	public function __construct($sFileFullPath);

	public function GetLogFilePath();
}

class DefaultLogFileNameBuilder implements ILogFileNameBuilder
{
	private $sLogFileFullPath;

	public function __construct($sFileFullPath)
	{
		$this->sLogFileFullPath = $sFileFullPath;
	}

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
	protected $sFilePath;
	protected $sFileBaseName;
	protected $sFileExtension;

	public function __construct($sFileFullPath)
	{
		$aPathParts = pathinfo($sFileFullPath);

		$this->sFilePath = $aPathParts['dirname'];
		$this->sFileBaseName = $aPathParts['filename'];
		$this->sFileExtension = $aPathParts['extension'];
	}

	public function GetLogFilePath()
	{
		$sFileSuffix = $this->GetFileSuffix();

		return $this->sFilePath
			.'/'
			.$this->sFileBaseName
			.'.'.$sFileSuffix
			.'.'.$this->sFileExtension;
	}

	abstract protected function GetFileSuffix();
}

/**
 * @since 2.7.0 N°2518
 */
class DailyRotatingLogFileNameBuilder extends RotatingLogFileNameBuilder
{
	protected function GetFileSuffix()
	{
		return date('Y-m-d');
	}
}

/**
 * @since 2.7.0 N°2518
 */
class WeeklyRotatingLogFileNameBuilder extends RotatingLogFileNameBuilder
{
	protected function GetFileSuffix()
	{
		$sWeekYear = date('o');
		$sWeekNumber = date('W');

		return $sWeekYear.'-week'.$sWeekNumber;
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
		if (empty($sFileNameBuilderImpl))
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

	/**
	 * Since 2.7.0 with the 'log_filename_builder_impl' param the logs will output to different files name
	 * As now by default iTop will use {@link WeeklyRotatingLogFileNameBuilder} (rotation each week), to avoid confusion, we're renaming
	 * the legacy error.log / setup.log.
	 *
	 * @since 2.7.0 N°2518
	 * @uses utils::GetConfig() the config must be persisted !
	 */
	public static function RenameLegacyLogFiles()
	{
		$oConfig = utils::GetConfig();
		IssueLog::Enable(APPROOT.'log/error.log'); // refresh log file used
		$sLogFileNameParam = $oConfig->Get('log_filename_builder_impl');
		$aConfigValuesNoRotation = array('', 'DefaultLogFileNameBuilder');

		$bIsLogRotationActivated = (!in_array($sLogFileNameParam, $aConfigValuesNoRotation, true));
		if (!$bIsLogRotationActivated)
		{
			return;
		}

		IssueLog::Warning("Log name builder set to '$sLogFileNameParam', renaming legacy log files");
		$aLogFilesToRename = array(
			'log/setup.log' => 'log/setup.LEGACY.log',
			'log/error.log' => 'log/error.LEGACY.log',
		);
		foreach ($aLogFilesToRename as $sLogCurrentName => $sLogNewName)
		{
			$sSource = APPROOT.$sLogCurrentName;
			if (!file_exists($sSource))
			{
				IssueLog::Info("Log file '$sLogCurrentName' does not exists, skipping");
				continue;
			}

			$sDestination = APPROOT.$sLogNewName;
			$bResult = rename($sSource, $sDestination);
			if (!$bResult)
			{
				IssueLog::Error("Log file '$sLogCurrentName' cannot be renamed to '$sLogNewName'");
				continue;
			}
			IssueLog::Info("Log file '$sLogCurrentName' renamed to '$sLogNewName'");
		}
	}

	public function Error($sText)
	{
		$this->Write('Error | '.$sText);
	}

	public function Warning($sText)
	{
		$this->Write('Warning | '.$sText);
	}

	public function Info($sText)
	{
		$this->Write('Info | '.$sText);
	}

	public function Ok($sText)
	{
		$this->Write('Ok | '.$sText);
	}

	protected function Write($sText)
	{
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
			fwrite($hLogFile, "$sDate | $sText\n");
			fflush($hLogFile);
			flock($hLogFile, LOCK_UN);
			fclose($hLogFile);
		}
	}
}

abstract class LogAPI
{
	public static function Enable($sTargetFile)
	{
		// m_oFileLog is not defined as a class attribute so that each impl will have its own
		static::$m_oFileLog = new FileLog($sTargetFile);
	}

	public static function Error($sText)
	{
		if (static::$m_oFileLog)
		{
			static::$m_oFileLog->Error($sText);
		}
	}
	public static function Warning($sText)
	{
		if (static::$m_oFileLog)
		{
			static::$m_oFileLog->Warning($sText);
		}
	}
	public static function Info($sText)
	{
		if (static::$m_oFileLog)
		{
			static::$m_oFileLog->Info($sText);
		}
	}
	public static function Ok($sText)
	{
		if (static::$m_oFileLog)
		{
			static::$m_oFileLog->Ok($sText);
		}
	}
}

class SetupLog extends LogAPI
{
	protected static $m_oFileLog = null;
}

class IssueLog extends LogAPI
{
	protected static $m_oFileLog = null;
}

class ToolsLog extends LogAPI
{
	protected static $m_oFileLog = null;
}
