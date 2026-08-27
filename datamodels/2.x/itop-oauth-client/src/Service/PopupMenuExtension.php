<?php

/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\OAuthClient\Service;

use Dict;
use iPopupMenuExtension;
use JSPopupMenuItem;
use OAuthClient;
use SeparatorPopupMenuItem;
use URLPopupMenuItem;

class PopupMenuExtension implements \iPopupMenuExtension
{
	public const MODULE_CODE = 'itop-oauth-client';

	/**
	 * @inheritDoc
	 */
	public static function EnumItems($iMenuId, $param)
	{
		$aResult = [];

		switch ($iMenuId) {
			case iPopupMenuExtension::MENU_OBJDETAILS_ACTIONS:
				$oItem = new JSPopupMenuItem(
					'OAuthConnectTopBar1',
					Dict::S('Unlock'),
					"console.log('OAuthConnectTopBar clicked');",
					[]
				);
				$oItem->SetIconClass('fas fa-unlock');
				$oItem->SetTooltip('Help please');
				$aResult[] = $oItem;

				$oUrlItem = new URLPopupMenuItem(
					'OAuthConnectTopBarUrl2',
					Dict::S('Access'),
					'https://www.example.com',
					'_blank'
				);
				$oUrlItem->SetIconClass('fas fa-universal-access');
				$oUrlItem->SetTooltip('Help please');
				$aResult[] = $oUrlItem;

				break;
			case iPopupMenuExtension::MENU_OBJDETAILS_FIELD_ACTIONS:
				if ($param['att_code'] === 'title' || $param['att_code'] === 'description' || $param['att_code'] === 'caller' ||
					$param['att_code'] === 'public_log' || $param['att_code'] === 'public_log2') {
					$oItem = new JSPopupMenuItem(
						'OAuthConnectTopBar'.uniqid(),
						Dict::S('Vote down'),
						"console.log('OAuthConnectTopBar clicked');",
						[]
					);
					$oItem->SetIconClass('fas fa-thumbs-down');
					$oItem->SetTooltip('Help please');
					$aResult[] = $oItem;
					$oSeparatorItem = new SeparatorPopupMenuItem();
					$aResult[] = $oSeparatorItem;

					$oItem2 = new JSPopupMenuItem(
						'OAuthConnectTopBar2'.uniqid(),
						Dict::S('Vote d2own'),
						"console.log('OAuthConnectTopBar clicked');",
						[]
					);
					$oItem2->SetIconClass('fas fa-thumbs-down');
					$oItem2->SetTooltip('Help please');
					$aResult[] = $oItem2;

					$oUrlItem = new URLPopupMenuItem(
						'OAuthConnectTopBarUrl'.uniqid(),
						Dict::S('Translate'),
						'https://www.example.com',
						'_blank'
					);
					$oUrlItem->SetIconClass('fas fa-language');
					$oUrlItem->SetTooltip('Help please');
					$aResult[] = $oUrlItem;

					$oSeparatorItem = new SeparatorPopupMenuItem();
					$aResult[] = $oSeparatorItem;

					$oUrlItem = new URLPopupMenuItem(
						'OAuthConnectTopBarUrl'.uniqid(),
						Dict::S('Translate'),
						'https://www.example.com',
						'_blank'
					);
					$oUrlItem->SetIconClass('fas fa-language');
					$oUrlItem->SetTooltip('Help please');
					$aResult[] = $oUrlItem;
				}
				break;
			case iPopupMenuExtension::MENU_OBJDETAILS_ACTIVITY_PANEL_ACTIONS:
				if ($param['caselog_att_code'] === 'public_log' || $param['caselog_att_code'] === 'public_log2' || $param['caselog_att_code'] === 'activity') {
					$oItem = new JSPopupMenuItem(
						'OAuthConnectTopBar',
						Dict::S('Trim content'),
						"console.log('OAuthConnectTopBar clicked');",
						[]
					);
					$oItem->SetIconClass('fas fa-cut');
					$oItem->SetTooltip('Help please');
					$aResult[] = $oItem;
					$oSeparatorItem = new SeparatorPopupMenuItem();
					$aResult[] = $oSeparatorItem;

					$oUrlItem = new URLPopupMenuItem(
						'OAuthConnectTopBarUrl',
						Dict::S('Empty content'),
						'https://www.example.com',
						'_blank'
					);
					$oUrlItem->SetIconClass('fas fa-trash');
					$oUrlItem->SetTooltip('Help please');
					$aResult[] = $oUrlItem;

					$oSeparatorItem = new SeparatorPopupMenuItem();
					$aResult[] = $oSeparatorItem;

					$oItem = new JSPopupMenuItem(
						'OAuthConnectTopBar2',
						Dict::S('Trim content'),
						"console.log('OAuthConnectTopBar clicked');",
						[]
					);
					$oItem->SetIconClass('fas fa-cut');
					$oItem->SetTooltip('Help please');
					$aResult[] = $oItem;
				}
				break;
			case iPopupMenuExtension::MENU_TOPBAR_ACTIONS:
				$oItem = new JSPopupMenuItem(
					'OAuthConnectTopBar'.uniqid(),
					Dict::S('Plug label'),
					"console.log('OAuthConnectTopBar clicked');",
					[]
				);
				$oItem->SetIconClass('fa fa-plug');
				$oItem->SetTooltip('Help please');
				$aResult[] = $oItem;

				$oSeparatorItem = new SeparatorPopupMenuItem();
				$aResult[] = $oSeparatorItem;

				$oUrlItem = new URLPopupMenuItem(
					'OAuthConnectTopBarUrl'.uniqid(),
					Dict::S('Link label'),
					'https://www.example.com',
					'_blank'
				);
				$oUrlItem->SetIconClass('fa fa-link');
				$oUrlItem->SetTooltip('Help please');
				$aResult[] = $oUrlItem;
				break;
			default:
				// Unknown type of menu, do nothing
				break;
		}

		return $aResult;
	}
}
