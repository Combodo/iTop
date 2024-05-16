<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

use Combodo\iTop\Application\UI\Base\Component\Title\Title;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;
use Combodo\iTop\Application\WebPage\NiceWebPage;

require_once(APPROOT.'setup/modulediscovery.class.inc.php');
require_once(APPROOT.'setup/runtimeenv.class.inc.php');
require_once(APPROOT.'core/log.class.inc.php');

SetupLog::Enable(APPROOT.'/log/setup.log');


/**
 * @uses SetupLog
 */
class SetupPage extends NiceWebPage
{
	const DEFAULT_PAGE_TEMPLATE_REL_PATH = 'pages/backoffice/setuppage/layout';

	public function __construct($sTitle)
	{
		parent::__construct($sTitle);
		$this->LinkScriptFromAppRoot("js/jquery.blockUI.js");
		$this->LinkScriptFromAppRoot('node_modules/@popperjs/core/dist/umd/popper.js');
		$this->LinkScriptFromAppRoot('node_modules/tippy.js/dist/tippy-bundle.umd.js');
		$this->LinkScriptFromAppRoot("setup/setup.js");
		$this->LinkScriptFromAppRoot("setup/csp-detection.js?itop_version_wiki_syntax=" . utils::GetItopVersionWikiSyntax());
		$this->LinkStylesheetFromAppRoot('css/font-awesome/css/all.min.css');
		$this->LinkStylesheetFromAppRoot('css/font-combodo/font-combodo.css');
		$this->LinkStylesheetFromAppRoot('node_modules/tippy.js/dist/tippy.css');
		$this->LinkStylesheetFromAppRoot('node_modules/tippy.js/animations/shift-away-subtle.css');

		$this->LoadTheme();
		$this->add_saas("css/setup.scss");
	}

	/**
	 * Overriden because the application is not fully loaded when the setup is being run
	 */
	public function GetAbsoluteUrlAppRoot()
	{
		return '../';
	}

	/**
	 * Overriden because the application is not fully loaded when the setup is being run
	 */
	public function GetAbsoluteUrlModulesRoot()
	{
		return $this->GetAbsoluteUrlAppRoot().utils::GetCurrentEnvironment();
	}

	/**
	 * Overriden because the application is not fully loaded when the setup is being run
	 */
	function GetApplicationContext()
	{
		return '';
	}

	public function info($sText, $sTextForLog = null)
	{
		$this->add("<p class=\"info ibo-is-html-content\">$sText</p>\n");
		SetupLog::Info($sTextForLog ?? $sText);
	}

	public function ok($sText, $sTextForLog = null)
	{
		$this->add("<div class=\"message message-valid ibo-is-html-content\"><span class=\"message-title\">Success:</span>$sText</div>");
		SetupLog::Ok($sTextForLog ?? $sText);
	}

	public function warning($sText, $sTextForLog = null)
	{
		$this->add("<div class=\"message message-warning ibo-is-html-content\"><span class=\"message-title\">Warning:</span>$sText</div>");
		SetupLog::Warning($sTextForLog ?? $sText);
	}

	public function error($sText, $sTextForLog = null)
	{
		$this->add("<div class=\"message message-error ibo-is-html-content\">$sText</div>");
		SetupLog::Error($sTextForLog ?? $sText);
	}

	public function form($aData)
	{
		$this->add("<table class=\"formTable\">\n");
		foreach ($aData as $aRow)
		{
			$this->add("<tr>\n");
			if (isset($aRow['label']) && isset($aRow['input']) && isset($aRow['help']))
			{
				$this->add("<td class=\"wizlabel\">{$aRow['label']}</td>\n");
				$this->add("<td class=\"wizinput\">{$aRow['input']}</td>\n");
				$this->add("<td class=\"wizhelp\">{$aRow['help']}</td>\n");
			}
			else
			{
				if (isset($aRow['label']) && isset($aRow['help']))
				{
					$this->add("<td colspan=\"2\" class=\"wizlabel\">{$aRow['label']}</td>\n");
					$this->add("<td class=\"wizhelp\">{$aRow['help']}</td>\n");
				}
				else
				{
					if (isset($aRow['label']) && isset($aRow['input']))
					{
						$this->add("<td class=\"wizlabel\">{$aRow['label']}</td>\n");
						$this->add("<td colspan=\"2\" class=\"wizinput\">{$aRow['input']}</td>\n");
					}
					else
					{
						if (isset($aRow['label']))
						{
							$this->add("<td colspan=\"3\" class=\"wizlabel\">{$aRow['label']}</td>\n");
						}
					}
				}
			}
			$this->add("</tr>\n");
		}
		$this->add("</table>\n");
	}

	public function collapsible($sId, $sTitle, $aItems, $bOpen = true)
	{
		$this->add("<h3 class=\"clickable open\" id=\"{$sId}\">$sTitle</h3>");
		$this->p('<ul id="'.$sId.'_list">');
		foreach ($aItems as $sItem)
		{
			$this->p("<li>$sItem</li>\n");
		}
		$this->p('</ul>');
		$this->add_ready_script("$('#{$sId}').on('click', function() { $(this).toggleClass('open'); $('#{$sId}_list').toggle();} );\n");
		if (!$bOpen)
		{
			$this->add_ready_script("$('#{$sId}').toggleClass('open'); $('#{$sId}_list').toggle();\n");
		}
	}

	public function output()
	{
		$sLogo = utils::GetAbsoluteUrlAppRoot().'/images/logos/logo-itop-simple-dark.svg?t='.utils::GetCacheBusterTimestamp();
		$oSetupPage = UIContentBlockUIBlockFactory::MakeStandard();
		$oHeader = UIContentBlockUIBlockFactory::MakeStandard('header', ['ibo-setup--header']);
		$oSetupPage->AddSubBlock($oHeader);
		$oTitle = TitleUIBlockFactory::MakeForPageWithIcon($this->s_title, $sLogo, Title::DEFAULT_ICON_COVER_METHOD, false);
		$oHeader->AddSubBlock($oTitle);
		$oSetup = UIContentBlockUIBlockFactory::MakeStandard('setup', ['ibo-setup--body']);
		$oSetupPage->AddSubBlock($oSetup);
		$oSetup->AddSubBlock($this->oContentLayout);

		$this->oContentLayout = $oSetupPage;

		return parent::output();
	}

	/**
	 * @inheritDoc
	 */
	protected function LoadTheme()
	{
		// Do nothing
	}

	/**
	 * @inheritDoc
	 */
	protected function GetFaviconAbsoluteUrl()
	{
		return utils::GetAbsoluteUrlAppRoot().'setup/favicon.ico';
	}
}
