<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\TemporaryObjects;

/**
 * TemporaryObjectHelper.
 *
 * Helper with useful functions.
 *
 * @experimental do not use, this feature will be part of a future version
 *
 * @since 3.1
 */
class TemporaryObjectHelper
{
	// Global configuration
	public const CONFIG_FORCE             = 'temporary_object.force_creation';
	public const CONFIG_TEMP_LIFETIME     = 'temporary_object.lifetime';
	public const CONFIG_WATCHDOG_INTERVAL = 'temporary_object.watchdog_interval';
	public const CONFIG_GARBAGE_INTERVAL  = 'temporary_object.garbage_interval';

	// Temporary descriptor operation
	public const OPERATION_CREATE = 'create';
	public const OPERATION_DELETE = 'delete';

	/**
	 * GetWatchDogJS.
	 *
	 * @param string $sTempId
	 *
	 * @return string
	 */
	public static function GetWatchDogJS(string $sTempId): string
	{
		$iWatchdogInterval = TemporaryObjectConfig::GetInstance()->GetWatchdogInterval();

		return <<<JS
			window.setInterval(function() {
				$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php?route=temporary_object.watch_dog', {temp_id: '$sTempId'});
			}, $iWatchdogInterval * 1000)
JS;
	}
}
