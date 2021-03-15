<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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
		$this->add_linked_script("../js/jquery.blockUI.js");
		$this->add_linked_script("../setup/setup.js");
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/font-awesome/css/all.min.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/font-combodo/font-combodo.css');
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

	public function info($sText)
	{
		$this->add("<p class=\"info\">$sText</p>\n");
		$this->log_info($sText);
	}

	public function ok($sText)
	{
		$this->add("<div class=\"message message-valid\"><span class=\"message-title\">Success:</span>$sText</div>");
		$this->log_ok($sText);
	}

	public function warning($sText)
	{
		$this->add("<div class=\"message message-warning\"><span class=\"message-title\">Warning:</span>$sText</div>");
		$this->log_warning($sText);
	}

	public function error($sText)
	{
		$this->add("<div class=\"message message-error\">$sText</div>");
		$this->log_error($sText);
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
		$sLogo = utils::GetAbsoluteUrlAppRoot().'/images/itop-logo.png?t='.utils::GetCacheBusterTimestamp();
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
	 * @deprecated 3.0.0 use SetupLog::Error
	 */
	public static function log_error($sText)
	{
		SetupLog::Error($sText);
	}

	/**
	 * @deprecated 3.0.0 use SetupLog::Warning
	 */
	public static function log_warning($sText)
	{
		SetupLog::Warning($sText);
	}

	/**
	 * @deprecated 3.0.0 use SetupLog::Info
	 */
	public static function log_info($sText)
	{
		SetupLog::Info($sText);
	}

	/**
	 * deprecated 3.0.0 use SetupLog::Ok
	 */
	public static function log_ok($sText)
	{
		SetupLog::Ok($sText);
	}

	/**
	 * @deprecated 3.0.0 use SetupLog::Ok
	 */
	public static function log($sText)
	{
		SetupLog::Ok($sText);
	}

	/**
	 * @inheritDoc
	 */
	protected function LoadTheme()
	{
		// Do nothing
	}
}
