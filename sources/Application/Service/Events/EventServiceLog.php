<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Events;

use IssueLog;
use LogChannels;

class EventServiceLog extends IssueLog
{
	const CHANNEL_DEFAULT = LogChannels::EVENT_SERVICE;
}