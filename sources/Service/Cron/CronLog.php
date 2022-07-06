<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Cron;

use LogAPI;

class CronLog extends LogAPI
{
	public static $iProcessNumber = 0;

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
}