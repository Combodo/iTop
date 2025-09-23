<?php

namespace Combodo\iTop\Core\Trigger\Enum;

/**
 * Class SubscriptionPolicy
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Core\Trigger\Enum
 * @since 3.2.0
 */
enum SubscriptionPolicy: string {
	case AllowNoChannel = "allow_no_channel";
	case ForceAtLeastOneChannel = "force_at_least_one_channel";
	case ForceAllChannels = "force_all_channels";
}
