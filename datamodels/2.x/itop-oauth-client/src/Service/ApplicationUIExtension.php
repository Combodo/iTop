<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\OAuthClient\Service;

use AbstractApplicationUIExtension;
use OAuthClient;
use utils;

class ApplicationUIExtension extends AbstractApplicationUIExtension
{

	public function GetHilightClass($oObject)
	{
		if ($oObject instanceof OAuthClient) {
			// Possible return values are:
			// HILIGHT_CLASS_CRITICAL, HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE
			$oConfig = utils::GetConfig();
			if ($oObject->Get('status') == 'inactive') {
				return HILIGHT_CLASS_WARNING;
			} elseif ($oObject->Get('used_for_smtp') == 'yes' && $oConfig->Get('email_transport_smtp.username') == $oObject->Get('name')) {
				return HILIGHT_CLASS_OK;
			}
		}

		return HILIGHT_CLASS_NONE;
	}
}