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
 * File logging
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
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

class ToolsLog
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
