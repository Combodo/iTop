<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\OAuthClient\Service;

use AbstractApplicationUIExtension;
use utils;

class ApplicationUIExtension extends AbstractApplicationUIExtension
{

	public function GetHilightClass($oObject)
	{
		if ($oObject instanceof OAuthClient) {
			// Possible return values are:
			// HILIGHT_CLASS_CRITICAL, HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE
			$oConfig = utils::GetConfig();
			$aScopes = $oObject->Get('scope')->GetValues();
			if ($oObject->Get('status') == 'inactive') {
				return HILIGHT_CLASS_WARNING;
			} elseif (in_array('SMTP', $aScopes) && $oConfig->Get('email_transport_smtp.username') == $oObject->Get('name')) {
				return HILIGHT_CLASS_OK;
			}
		}

		return HILIGHT_CLASS_NONE;
	}
}