<?php

namespace Combodo\iTop\Application\WebPage;

use Combodo\iTop\Application\Branding;
use Combodo\iTop\Application\UI\Base\Component\Title\Title;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;
use Dict;
use ExecutionKPI;
use IssueLog;
use utils;

/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * @since 2.7.1 N°2641 class creation
 */
class ErrorPage extends NiceWebPage
{
	public function __construct($sTitle)
	{
		$oKpi = new ExecutionKPI();
		parent::__construct($sTitle);
		$this->LinkScriptFromAppRoot("js/jquery.blockUI.js");
		$this->LinkScriptFromAppRoot("setup/setup.js");
		$this->LinkStylesheetFromAppRoot('css/font-awesome/css/all.min.css');
		$this->LinkStylesheetFromAppRoot('css/font-combodo/font-combodo.css');
		$this->add_saas("css/setup.scss");
		$oKpi->ComputeStats(get_class($this).' creation', 'ErrorPage');
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
		if(utils::IsEasterEggAllowed())
		{
			$this->add('<div class="message message-valid">'.Dict::S('UI:ErrorPage:UnstableVersion').'</div>');
			$this->add('<img src="' . utils::GetAbsoluteUrlAppRoot() . 'images/alpha-fatal-error.gif">');
			$this->add('<div class="message message-valid">'.nl2br(Dict::S('UI:ErrorPage:KittyDisclaimer')).'</div>');
		}
		$this->log_error($sText);
	}

	public function output()
	{
		$sLogo = Branding::GetLoginLogoAbsoluteUrl();
		$oSetupPage = UIContentBlockUIBlockFactory::MakeStandard('ibo_setup_container', ['ibo-setup']);
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

	public static function log_error($sText)
	{
		IssueLog::Error($sText);
	}

	public static function log_warning($sText)
	{
		IssueLog::Warning($sText);
	}

	public static function log_info($sText)
	{
		IssueLog::Info($sText);
	}

	public static function log_ok($sText)
	{
		IssueLog::Ok($sText);
	}

	public static function log($sText)
	{
		IssueLog::Ok($sText);
	}
}
