<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Cas;

use IssueLog;
use LogAPI;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class CASLogger implements LoggerInterface
{
	public function __construct($sDebugFile)
	{
		CASLog::Enable($sDebugFile);
	}

	public const LEVEL_COMPAT = [
		LogLevel::EMERGENCY => LogAPI::LEVEL_ERROR,
		LogLevel::ALERT => LogAPI::LEVEL_ERROR,
		LogLevel::CRITICAL => LogAPI::LEVEL_ERROR,
		LogLevel::ERROR => LogAPI::LEVEL_ERROR,
		LogLevel::WARNING => LogAPI::LEVEL_WARNING,
		LogLevel::NOTICE => LogAPI::LEVEL_INFO,
		LogLevel::INFO => LogAPI::LEVEL_INFO,
		LogLevel::DEBUG => LogAPI::LEVEL_DEBUG,
	];

	public function emergency($message, array $context = array()):void
	{
		CASLog::Error('EMERGENCY: '.$message, CASLog::CHANNEL_DEFAULT, $context);
		IssueLog::Error('EMERGENCY: '.$message, CASLog::CHANNEL_DEFAULT, $context);
	}

	public function alert($message, array $context = array()):void
	{
		CASLog::Error('ALERT: '.$message, CASLog::CHANNEL_DEFAULT, $context);
		IssueLog::Error('ALERT: '.$message, CASLog::CHANNEL_DEFAULT, $context);
	}

	public function critical($message, array $context = array()):void
	{
		CASLog::Error('CRITICAL: '.$message, CASLog::CHANNEL_DEFAULT, $context);
		IssueLog::Error('CRITICAL: '.$message, CASLog::CHANNEL_DEFAULT, $context);
	}

	public function error($message, array $context = array()):void
	{
		CASLog::Error('ERROR: '.$message, CASLog::CHANNEL_DEFAULT, $context);
		IssueLog::Error('ERROR: '.$message, CASLog::CHANNEL_DEFAULT, $context);
	}

	public function warning($message, array $context = array()):void
	{
		CASLog::Warning('WARNING: '.$message, CASLog::CHANNEL_DEFAULT, $context);
	}

	public function notice($message, array $context = array()):void
	{
		CASLog::Info('NOTICE: '.$message, CASLog::CHANNEL_DEFAULT, $context);
	}

	public function info($message, array $context = array()):void
	{
		CASLog::Info('INFO: '.$message, CASLog::CHANNEL_DEFAULT, $context);
	}

	public function debug($message, array $context = array()):void
	{
		CASLog::Debug('DEBUG: '.$message, CASLog::CHANNEL_DEFAULT, $context);
	}

	public function log($level, $message, array $context = array()):void
	{
		$sLevel = self::LEVEL_COMPAT[$level] ?? LogAPI::LEVEL_ERROR;
		CASLog::Log($sLevel, strtoupper($level).": $message", CASLog::CHANNEL_DEFAULT, $context);
	}
}