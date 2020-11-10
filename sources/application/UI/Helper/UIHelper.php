<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Helper;


use MetaModel;

// TODO 3.0.0: Delete this class as it is only a temporary helper while code is being reworked.
class UIHelper
{
	public static function GetColorFromStatus(string $sClass, ?string $sStateCode): string
	{
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