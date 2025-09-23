<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Helper;


/**
 * Class UIHelper
 *
 * @internal
 * @author Eric Espie <eric.espie@combodo.com>
 * @package Combodo\iTop\Application\UI\Helper
 * @since 3.0.0
 */
class UIHelper
{
	/**
	 * @param string|null $sStateCode Code of the state value, can be null if allowed by the attribute definition
	 * @param bool $bAllowFallbackIfNoMatch If set to true, a fallback semantic color code will be returned in case of no matching mappping. Otherwise it will return null to indicate there was no match.
	 *
	 * @return string|null A semantic status color name (eg. success, pending, failure, neutral, ...) depending on the value's code. Usefull to try to find a semantic match when a class has no style defined on its state attribute.
	 */
	public static function GetColorNameFromStatusCode(?string $sStateCode, bool $bAllowFallbackIfNoMatch = true): ?string
	{
		$sStatusColor = null;

		switch ($sStateCode) {
			case 'active':
				$sStatusColor = 'active';
				break;

			case 'inactive':
				$sStatusColor = 'inactive';
				break;

			case 'new':
				$sStatusColor = 'new';
				break;

			case 'waiting_for_approval':
			case 'pending':
				$sStatusColor = 'waiting';
				break;

			case 'escalated_tto':
			case 'escalated_ttr':
			case 'rejected':
				$sStatusColor = 'failure';
				break;

			case 'resolved':
				$sStatusColor = 'success';
				break;

			case 'closed':
				$sStatusColor = 'frozen';
				break;

			case 'approved':
			case 'assigned':
			case 'dispatched':
			case 'redispatched':
				$sStatusColor = 'neutral';
				break;

			default:
				if ($bAllowFallbackIfNoMatch) {
					$sStatusColor = 'neutral';
				}
				break;
		}

		return $sStatusColor;
	}
}