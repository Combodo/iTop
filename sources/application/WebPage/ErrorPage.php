<?php

use Combodo\iTop\Application\UI\Base\Component\Title\Title;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;

/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * @since 2.7.1 N°2641 class creation
 */
class ErrorPage extends NiceWebPage
{
	public function __construct($sTitle)
	{
		parent::__construct($sTitle);
		$this->add_linked_script("../js/jquery.blockUI.js");
		$this->add_linked_script("../setup/setup.js");
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/font-awesome/css/all.min.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/font-combodo/font-combodo.css');
		$this->add_saas("css/setup.scss");
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
			$this->add('<img src="../images/alpha-fatal-error.gif">');
			$this->add('<div class="message message-valid">'.nl2br(Dict::S('UI:ErrorPage:KittyDisclaimer')).'</div>');
		}
		$this->log_error($sText);
	}

	public function output()
	{
		$sLogo = utils::GetAbsoluteUrlAppRoot(true).'/images/itop-logo.png?t='.utils::GetCacheBusterTimestamp();
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
