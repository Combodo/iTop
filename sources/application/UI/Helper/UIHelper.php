<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Helper;


use MetaModel;

// TODO 3.0.0: Delete this class as it is only a temporary helper while code is being reworked.

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
	public static function GetColorFromStatus(string $sClass, ?string $sStateCode): string
	{
		// Example on how to get the color for the current status of a class
//		$sStatusColor = 'neutral';
//		$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
//		if (strlen($sStateAttCode) == 0) {
//			return $sStatusColor;
//		}
//
//		$oStyle = MetaModel::GetEnumStyle($sClass, $sStateAttCode, $sStateCode);
//		if ($oStyle) {
//			$sStatusColor = $oStyle->GetMainColor();
//		}
//		return $sStatusColor;

		$sRootClass = MetaModel::GetRootClass($sClass);
		switch ($sRootClass) {
			case 'Ticket':
				// TODO 3.0.0 : Dehardcode this
				switch ($sStateCode) {
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
					default:
						$sStatusColor = 'neutral';
						break;
				}
				break;
			default:
				switch ($sStateCode) {
					case 'active':
						$sStatusColor = 'active';
						break;
					case 'inactive':
						$sStatusColor = 'inactive';
						break;
					default:
						$sStatusColor = 'neutral';
						break;
				}
		}
		return $sStatusColor;
	}
}