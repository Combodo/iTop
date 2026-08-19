<?php

/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Cron;

use LogAPI;
use Page;
use utils;

/**
 * @since 3.1.0
 */
class CronLog extends LogAPI
{
	public static int $iProcessNumber = 0;
	private static int $iDebugLevel = 0;
	private static ?Page $oP = null;

	public const CHANNEL_DEFAULT = 'Cron';
	/**
	 * @inheritDoc
	 *
	 * As this object is used during setup, without any conf file available, customizing the level can be done by changing this constant !
	 */
	public const LEVEL_DEFAULT = self::LEVEL_INFO;

	protected static $m_oFileLog = null;

	public static function Log($sLevel, $sMessage, $sChannel = null, $aContext = []): void
	{
		if (static::$iProcessNumber !== 0) {
			$sMessage = 'cron'.str_pad(static::$iProcessNumber, 3).$sMessage;
		}
		parent::Log($sLevel, $sMessage, self::CHANNEL_DEFAULT, $aContext);
	}

	public static function SetDebug(Page $oP, int $iDebugLevel): void
	{
		self::$oP = $oP;
		self::$iDebugLevel = $iDebugLevel;
	}

	public static function GetDebugClassName($sTaskClass): string
	{
		if (utils::StartsWith($sTaskClass, 'Combodo\\iTop\\Service\\')) {
			return substr($sTaskClass, strlen('Combodo\\iTop\\Service\\'));
		}
		if (utils::StartsWith($sTaskClass, 'Combodo\\iTop\\')) {
			return substr($sTaskClass, strlen('Combodo\\iTop\\'));
		}
		return $sTaskClass;
	}
}
