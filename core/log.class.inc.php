<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * File logging
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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
