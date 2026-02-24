<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Helper;

use LogAPI;

class DataFeatureRemovalLog extends LogAPI
{
	public const CHANNEL_DEFAULT = 'DataFeatureRemoval';

	protected static $m_oFileLog = null;

	public static function Enable($sTargetFile = null)
	{
		if (empty($sTargetFile)) {
			$sTargetFile = APPROOT.'log/error.log';
		}
		parent::Enable($sTargetFile);
	}
}
