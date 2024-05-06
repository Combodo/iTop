<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\OAuthClient\Service;

use ApplicationContext;
use Dict;
use iPopupMenuExtension;
use JSPopupMenuItem;
use OAuthClient;
use SeparatorPopupMenuItem;
use URLPopupMenuItem;
use utils;

class PopupMenuExtension implements \iPopupMenuExtension
{
	const MODULE_CODE = 'itop-oauth-client';

	/**
	 * @inheritDoc
	 */
	public static function EnumItems($iMenuId, $param)
	{
		$aResult = [];

		switch ($iMenuId) {
			case iPopupMenuExtension::MENU_OBJDETAILS_ACTIONS:
				$oObj = $param;
				if ($oObj instanceof OAuthClient) {
					$bHasToken = !empty($oObj->Get('token'));
					$aResult[] = new SeparatorPopupMenuItem();

					$oAppContext = new ApplicationContext();
					$sMenu = $bHasToken ? 'Menu:RegenerateTokens' : 'Menu:GenerateTokens';
					$sObjClass = get_class($oObj);
					$sClass = $sObjClass;
					$sId = $oObj->GetKey();
					$sAjaxUri = utils::GetAbsoluteUrlModulePage(static::MODULE_CODE, 'ajax.php');
					// Add a new menu item that triggers a custom JS function defined in our own javascript file: js/sample.js
					$sJSFileUrl = 'env-'.utils::GetCurrentEnvironment().'/'.static::MODULE_CODE.'/assets/js/oauth_connect.js';
					$aResult[] = new JSPopupMenuItem(
						$sMenu.' from '.$sObjClass,
						Dict::S($sMenu),
						"OAuthConnect('$sClass', $sId, '$sAjaxUri')",
						[$sJSFileUrl]
					);

					if ($bHasToken) {
						$aScopes = $oObj->Get('scope')->GetValues();
						if (in_array('IMAP', $aScopes)) {
							$aParams = $oAppContext->GetAsHash();
							$sMenu = 'Menu:CreateMailbox';
							$sObjClass = get_class($oObj);
							$aParams['class'] = $sObjClass;
							$aParams['id'] = $oObj->GetKey();
							$aParams['operation'] = 'CreateMailbox';
							$aResult[] = new URLPopupMenuItem(
								$sMenu.' from '.$sObjClass,
								Dict::S($sMenu),
								utils::GetAbsoluteUrlModulePage(static::MODULE_CODE, 'index.php', $aParams)
							);
						}
					}
				}
				break;

			default:
				// Unknown type of menu, do nothing
				break;
		}

		return $aResult;
	}
}