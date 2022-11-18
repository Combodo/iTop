<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Cron;

use LogAPI;
use Page;

class CronLog extends LogAPI
{
	public static $iProcessNumber = 0;
	private static $bDebug = false;
	private static $oP = null;

	const CHANNEL_DEFAULT = 'Cron';
	/**
	 * @inheritDoc
	 *
	 * As this object is used during setup, without any conf file available, customizing the level can be done by changing this constant !
	 */
	const LEVEL_DEFAULT = self::LEVEL_INFO;

	protected static $m_oFileLog = null;

	public static function Log($sLevel, $sMessage, $sChannel = null, $aContext = array())
	{
		$sMessage = 'cron'.str_pad(static::$iProcessNumber, 3).$sMessage;
		parent::Log($sLevel, $sMessage, $sChannel, $aContext);
	}

	public static function Debug($sMessage, $sChannel = null, $aContext = array())
	{
		if (self::$bDebug && self::$oP) {
			self::$oP->p('cron'.str_pad(static::$iProcessNumber, 3).$sMessage);
		}
		parent::Debug($sMessage, $sChannel, $aContext);
	}

	public static function SetDebug(Page $oP, $bDebug)
	{
		self::$oP = $oP;
		self::$bDebug = $bDebug;
	}
}