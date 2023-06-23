<?php
/**
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Cas;

use LogAPI;

class CASLog extends LogAPI
{
	const CHANNEL_DEFAULT = 'CASLog';

	protected static $m_oFileLog = null;
}

